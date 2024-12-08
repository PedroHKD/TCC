<?php
include "config.php";
include "api.php";

$items_per_page = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$sql = "SELECT cd_equipe, nome, membros, responsavel, tier_medio FROM equipe LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':limit', $items_per_page, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total_sql = "SELECT COUNT(*) FROM equipe";
$total_stmt = $pdo->prepare($total_sql);
$total_stmt->execute();
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $items_per_page);

$pdo = null;

$role = [
    "jogador1" => "Top Laner",
    "jogador2" => "Jungler",
    "jogador3" => "Mid Laner",
    "jogador4" => "ADC",
    "jogador5" => "Suporte"
];

function calcularTierMedio($membros) {
    $tiers = [
        'IRON' => 0,
        'BRONZE' => 1,
        'SILVER' => 2,
        'GOLD' => 3,
        'PLATINUM' => 4,
        'EMERALD' => 5,
        'DIAMOND' => 6,
        'MASTER' => 7,
        'GRANDMASTER' => 8,
        'CHALLENGER' => 9
    ];

    $soma = 0;
    $count = 0;

    foreach ($membros as $membro) {
        if (isset($membro['tier']) && isset($tiers[$membro['tier']])) {
            $soma += $tiers[$membro['tier']];
            $count++;
        }
    }

    if ($count === 0) return 'UNRANKED';

    $media = $soma / $count;
    
    
    $menor_diferenca = PHP_FLOAT_MAX;
    $tier_medio = 'UNRANKED';
    
    foreach ($tiers as $tier => $valor) {
        $diferenca = abs($valor - $media);
        if ($diferenca < $menor_diferenca) {
            $menor_diferenca = $diferenca;
            $tier_medio = $tier;
        }
    }

    return $tier_medio;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Equipes</title>
</head>
<body>
    <div class="background-image"></div>
    <header>
        <h1>Equipes</h1>
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
        <section class="busca-content player-info-section">
            <h2>Equipes</h2>
            <div class="tournaments-grid">
                <?php if (count($teams) > 0): ?>
                    <?php foreach ($teams as $team): 
                        $membros = json_decode($team['membros'], true);
                    ?>
                        <div class="tournament-card">
                            <h3><?= htmlspecialchars($team['nome']) ?></h3>
                            
                            <div class="team-details">
                                <div class="team-info">
                                    <p><strong>Responsável:</strong> <?= htmlspecialchars($team['responsavel']) ?></p>
                                    <p><strong>Tier Médio:</strong> <span class="tier-badge <?= strtolower($team['tier_medio']) ?>"><?= $team['tier_medio'] ?></span></p>
                                </div>
                                
                                <div class="team-members">
                                    <h4>Membros da Equipe:</h4>
                                    <?php foreach ($membros as $key => $jogador): ?>
                                        <div class="member-info">
                                            <span class="role-badge"><?= htmlspecialchars($role[$key]) ?></span>
                                            <span class="player-name"><?= htmlspecialchars($jogador['gameName'] . "#" . $jogador['tagLine']) ?></span>
                                            <?php if (isset($jogador['tier'])): ?>
                                                <span class="tier-badge <?= strtolower($jogador['tier']) ?>"><?= $jogador['tier'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-teams">Nenhuma equipe encontrada.</p>
                <?php endif; ?>
            </div>
            <div class="pagination">
                <?php if ($page > 1) { ?>
                    <a href="?page=<?php echo $page - 1; ?>">Anterior</a>
                <?php } ?>

                <?php if ($page < $total_pages) { ?>
                    <a href="?page=<?php echo $page + 1; ?>">Próxima</a>
                <?php } ?>
            </div>
            <h2><a href="cadastraequipe.php" color="inherit">Criar/Editar equipe</a></h2>
        </section>
    </main>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html>