<?php
include "config.php";


$sql = "SELECT 
    c.*,
    JSON_LENGTH(c.equipes_inscritas) as total_equipes,
    (SELECT COUNT(*) FROM equipe) as total_equipes_cadastradas
FROM campeonatos c 
ORDER BY 
    CASE status 
        WHEN 'ABERTO' THEN 1 
        WHEN 'EM_ANDAMENTO' THEN 2 
        WHEN 'FINALIZADO' THEN 3 
    END, 
    data_inicio DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$campeonatos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Campeonatos - Protáthlima</title>
</head>
<body>
    <div class="background-image"></div>
    <header>
        <h1>Campeonatos</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="#">Menu</a>
                    <ul class="dropdown-content">
                        <li><a href="buscajogador.php">Busca de jogadores</a></li>
                        <li><a href="equipes.php">Equipes</a></li>
                        <li><a href="campeonatos.php">Campeonatos</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#">Campeonatos</a>
                    <ul class="dropdown-content">
                        <li><a href="campeonatos.php">Lista de Campeonatos</a></li>
                        <?php if (isset($_SESSION['loggedin'])): ?>
                            <li><a href="cadastracampeonato.php">Criar Campeonatos</a></li>
                            <li><a href="meustorneios.php">Meus Campeonatos</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <?php if (isset($_SESSION['loggedin'])): ?>
                        <a href="#"> <?php echo $_SESSION['gameName_usuario'] ?></a>
                        <ul class="dropdown-content">
                            <li><a href="#">Minhas moedas:</a></li>
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
        <section class="busca-content tournaments-list">
            <h2>CAMPEONATOS</h2>

            <?php if (isset($_SESSION['loggedin'])): ?>
                <div class="tournament-actions">
                    <a href="cadastracampeonato.php" class="primary-button">Criar Novo Campeonato</a>
                </div>
            <?php endif; ?>

            <div class="tournaments-grid">
                <?php if ($campeonatos): ?>
                    <?php foreach ($campeonatos as $campeonato): ?>
                        <div class="tournament-card <?= strtolower($campeonato['status']) ?>">
                            <h3><?= htmlspecialchars($campeonato['nome']) ?></h3>
                            
                            <div class="tournament-details">
                                <p class="status <?= strtolower($campeonato['status']) ?>">
                                    <?= $campeonato['status'] ?>
                                </p>
                                
                                <p class="date">
                                    <strong>Início:</strong> 
                                    <?= date('d/m/Y H:i', strtotime($campeonato['data_inicio'])) ?>
                                </p>
                                
                                <p class="teams">
                                    <strong>Equipes inscritas:</strong> 
                                    <?= $campeonato['total_equipes'] ?>/<?= $campeonato['numero_equipes'] ?>
                                </p>
                            </div>

                            <div class="tournament-actions">
                                <?php if ($campeonato['status'] === 'ABERTO'): ?>
                                    <?php if (isset($_SESSION['loggedin'])): ?>
                                        <?php 
                                        $usuario_atual = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];
                                        $is_organizador = ($campeonato['organizador'] === $usuario_atual);
                                        ?>
                                        <?php if ($is_organizador): ?>
                                            <a href="gerenciarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                               class="primary-button">
                                                Gerenciar Campeonato
                                            </a>
                                        <?php else: ?>
                                            <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                               class="primary-button">
                                                Visualizar Campeonato
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="login.php" class="secondary-button">
                                            Faça login para participar
                                        </a>
                                    <?php endif; ?>
                                <?php elseif ($campeonato['status'] === 'EM_ANDAMENTO'): ?>
                                    <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                       class="secondary-button">
                                        Ver Chaveamento
                                    </a>
                                <?php else: ?>
                                    <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                       class="secondary-button">
                                        Ver Resultados
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-tournaments">Nenhum campeonato encontrado.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html> 