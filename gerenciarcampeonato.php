<?php
include "config.php";
error_reporting(E_ALL ^ E_WARNING);
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

$cd_campeonato = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$cd_campeonato) {
    header("Location: campeonatos.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM campeonatos WHERE cd_campeonato = ?");
$stmt->execute([$cd_campeonato]);
$campeonato = $stmt->fetch(PDO::FETCH_ASSOC);


$is_organizador = false;
if (isset($_SESSION['loggedin'])) {
    $usuario_atual = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];
    $is_organizador = ($campeonato['organizador'] === $usuario_atual);
}


$equipes_inscritas = json_decode($campeonato['equipes_inscritas'], true) ?? [];
$total_equipes = count($equipes_inscritas);

$sql = "SELECT e.cd_equipe, e.nome, e.tier_medio, e.responsavel 
        FROM equipe e";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$todas_equipes = $stmt->fetchAll(PDO::FETCH_ASSOC);


$equipes_inscritas = json_decode($campeonato['equipes_inscritas'], true) ?? [];
$ids_inscritos = array_column($equipes_inscritas, 'cd_equipe');

$equipes_disponiveis = array_filter($todas_equipes, function($equipe) use ($ids_inscritos) {
    return !in_array($equipe['cd_equipe'], $ids_inscritos);
});


if ($campeonato['balanceamento_elo'] && !empty($equipes_inscritas)) {
    
    $sql = "SELECT tier_medio 
            FROM equipe 
            WHERE cd_equipe = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$equipes_inscritas[0]]);
    $tier_base = $stmt->fetchColumn();

    if ($tier_base) {
        $tiers = [
            'IRON' => 0, 'BRONZE' => 1, 'SILVER' => 2, 'GOLD' => 3,
            'PLATINUM' => 4, 'EMERALD' => 5, 'DIAMOND' => 6,
            'MASTER' => 7, 'GRANDMASTER' => 8, 'CHALLENGER' => 9
        ];

        $equipes_disponiveis = array_filter($equipes_disponiveis, function($equipe) use ($tier_base, $tiers) {
            if (empty($equipe['tier_medio']) || !isset($tiers[$equipe['tier_medio']])) {
                return false;
            }
            
            $diff = abs($tiers[$equipe['tier_medio']] - $tiers[$tier_base]);
            return $diff <= 1;
        });
    }
}


$equipes_inscritas_detalhes = [];
if (!empty($equipes_inscritas)) {
    
    $ids_equipes = array_map(function($equipe) {
        return $equipe['cd_equipe'];
    }, $equipes_inscritas);
    
    if (!empty($ids_equipes)) {
        $placeholders = str_repeat('?,', count($ids_equipes) - 1) . '?';
        $sql = "SELECT cd_equipe, nome FROM equipe WHERE cd_equipe IN ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($ids_equipes);
        $equipes_inscritas_detalhes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Gerenciar Campeonato - <?= htmlspecialchars($campeonato['nome']) ?></title>
</head>
<body>
    <div class="background-image"></div>

    <?php if (isset($_GET['error']) || isset($_GET['success'])): ?>
        <div class="modal" id="alertModal">
            <div class="modal-content <?= isset($_GET['error']) ? 'error' : 'success' ?>">
                <div class="modal-header">
                    <h3><?= isset($_GET['error']) ? 'Erro' : 'Sucesso' ?></h3>
                    <span class="close-modal">&times;</span>
                </div>
                <div class="modal-body">
                    <?php if (isset($_GET['error'])): ?>
                        <span class="modal-icon">⚠️</span>
                        <p><?= htmlspecialchars(urldecode($_GET['error'])) ?></p>
                    <?php else: ?>
                        <span class="modal-icon">✅</span>
                        <p>Operação realizada com sucesso!</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button onclick="closeModal()" class="modal-button">Fechar</button>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <header>
        <h1>Protáthlima</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="#">Menu</a>
                    <ul class="dropdown-content">
                        <li><a href="buscajogador.php">Busca de jogadores</a></li>
                        <li><a href="equipes.php">Equipes</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Campeonatos</a>
                    <ul class="dropdown-content">
                        <li><a href="campeonatos.php">Lista de Campeonatos</a></li>
                        <?php if (isset($_SESSION['loggedin'])): ?>
                            <li><a href="cadastracampeonato.php">Criar Campeonatos</a></li>
                            <li><a href="meuscampeonatos.php">Meus Campeonatos</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <?php if (isset($_SESSION['loggedin'])): ?>
                        <a href="#"> <?php echo $_SESSION['gameName_usuario'] ?></a>
                        <ul class="dropdown-content">
                            <li><a href="#">Minhas medalhas: <?php echo $_SESSION['medalhas_usuario'] ?></a></li>
                            <li><a href="feedback.php">Feedback</a></li>
                            <li><a href="logout.php"> Logout </a></li>
                        </ul>
                    <?php else: ?>
                        <a href="login.php"> Login </a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </header>

    <main class="busca-container">
        <section class="busca-content tournament-management">
            <h2><?= htmlspecialchars($campeonato['nome']) ?></h2>
            
            <div class="tournament-info">
                <p>Data de Início: <?= date('d/m/Y H:i', strtotime($campeonato['data_inicio'])) ?></p>
                <p>Inscritos: <?= count($equipes_inscritas) ?>/<?= $campeonato['numero_equipes'] ?></p>
                <p>Status: <?= $campeonato['status'] ?></p>
            </div>

            <div class="teams-section">
                <h3>Equipes Inscritas</h3>
                <div class="teams-grid">
                    <?php foreach ($equipes_inscritas_detalhes as $equipe): ?>
                        <div class="team-card">
                            <h4><?= htmlspecialchars($equipe['nome']) ?></h4>
                            <?php if ($campeonato['status'] === 'ABERTO'): ?>
                                <button class="remove-team" data-team-id="<?= $equipe['cd_equipe'] ?>">
                                    Remover Equipe
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($is_organizador && $campeonato['status'] === 'ABERTO' && $total_equipes < $campeonato['numero_equipes']): ?>
                    <div class="tournament-actions">
                        <h3>Adicionar Equipe</h3>
                        <form method="post" action="processainscricao.php">
                            <input type="hidden" name="cd_campeonato" value="<?= $cd_campeonato ?>">
                            <div class="filter-section">
                                <input type="text" id="filterNome" placeholder="Filtrar por nome..." class="filter-input">
                                <select id="filterTier" class="filter-select">
                                    <option value="">Todos os ranks</option>
                                    <option value="IRON">Iron</option>
                                    <option value="BRONZE">Bronze</option>
                                    <option value="SILVER">Silver</option>
                                    <option value="GOLD">Gold</option>
                                    <option value="PLATINUM">Platinum</option>
                                    <option value="EMERALD">Emerald</option>
                                    <option value="DIAMOND">Diamond</option>
                                    <option value="MASTER">Master</option>
                                    <option value="GRANDMASTER">Grandmaster</option>
                                    <option value="CHALLENGER">Challenger</option>
                                </select>
                            </div>
                            <select name="cd_equipe" id="equipeSelect" required>
                                <option value="">Selecione uma equipe</option>
                                <?php foreach ($equipes_disponiveis as $equipe): ?>
                                    <option value="<?= $equipe['cd_equipe'] ?>" 
                                            data-tier="<?= htmlspecialchars($equipe['tier_medio']) ?>"
                                            data-responsavel="<?= htmlspecialchars($equipe['responsavel']) ?>">
                                        <?= htmlspecialchars($equipe['nome']) ?> 
                                        (<?= $equipe['tier_medio'] ?>) - 
                                        <?= $equipe['responsavel'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="primary-button">Adicionar Equipe</button>
                        </form>
                    </div>
                <?php endif; ?>

                <?php if ($campeonato['status'] === 'ABERTO' && 
                         count($equipes_inscritas) === (int)$campeonato['numero_equipes']): ?>
                    <div class="start-tournament">
                        <form action="atualizacamp.php" method="POST">
                            <input type="hidden" name="action" value="iniciar_campeonato">
                            <input type="hidden" name="cd_campeonato" value="<?= htmlspecialchars($cd_campeonato) ?>">
                            <button type="submit" class="primary-button" onclick="return confirm('Tem certeza que deseja iniciar o campeonato? Esta ação não pode ser desfeita.');">
                                Iniciar Campeonato
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
    document.querySelectorAll('.remove-team').forEach(button => {
        button.addEventListener('click', async function() {
            if (confirm('Tem certeza que deseja remover esta equipe?')) {
                const teamId = this.dataset.teamId;
                try {
                    const response = await fetch('removeequipe.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            cd_campeonato: <?= $cd_campeonato ?>,
                            cd_equipe: teamId
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.error || 'Erro ao remover equipe');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro ao remover equipe');
                }
            }
        });
    });
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterNome = document.getElementById('filterNome');
        const filterTier = document.getElementById('filterTier');
        const equipeSelect = document.getElementById('equipeSelect');
        const originalOptions = Array.from(equipeSelect.options);

        function filterEquipes() {
            const nome = filterNome.value.toLowerCase();
            const tier = filterTier.value;

            
            while (equipeSelect.options.length > 1) {
                equipeSelect.remove(1);
            }

            
            originalOptions.forEach(option => {
                if (option.value === "") return;

                const matchesNome = option.text.toLowerCase().includes(nome);
                const matchesTier = !tier || option.getAttribute('data-tier') === tier;

                if (matchesNome && matchesTier) {
                    equipeSelect.add(option.cloneNode(true));
                }
            });
        }

        filterNome.addEventListener('input', filterEquipes);
        filterTier.addEventListener('change', filterEquipes);
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        document.querySelectorAll('.alert-close').forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });

        
        if (window.history.replaceState) {
            const cleanURL = window.location.href.split('?')[0];
            window.history.replaceState({}, document.title, cleanURL);
        }
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('alertModal');
        if (modal) {
            modal.style.display = 'block';
            
            
            document.querySelector('.close-modal').addEventListener('click', closeModal);
            
            
            window.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
            
            
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeModal();
                }
            });
        }
    });

    function closeModal() {
        const modal = document.getElementById('alertModal');
        if (modal) {
            modal.style.display = 'none';
            
            
            if (window.history.replaceState) {
                const cleanURL = window.location.href.split('?')[0];
                window.history.replaceState({}, document.title, cleanURL);
            }
        }
    }
    </script>
</body>
</html> 