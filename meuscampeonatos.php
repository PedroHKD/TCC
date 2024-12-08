<?php
include "config.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}


$usuario_riot_id = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];

$sql = "SELECT c.*, 
        JSON_LENGTH(equipes_inscritas) as total_equipes
        FROM campeonatos c 
        WHERE c.organizador = ? 
        ORDER BY 
        CASE status 
            WHEN 'ABERTO' THEN 1 
            WHEN 'EM_ANDAMENTO' THEN 2 
            WHEN 'FINALIZADO' THEN 3 
        END, 
        data_inicio DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario_riot_id]);
$campeonatos_organizador = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "SELECT c.*, 
        JSON_LENGTH(equipes_inscritas) as total_equipes
        FROM campeonatos c 
        WHERE JSON_CONTAINS(c.equipes_inscritas, 
                           JSON_OBJECT('membros', 
                                     JSON_ARRAY(
                                         JSON_OBJECT(
                                             'gameName', ?,
                                             'tagLine', ?
                                         )
                                     )
                                    ), 
                           '$.membros')
        AND c.organizador != ?
        ORDER BY 
        CASE status 
            WHEN 'ABERTO' THEN 1 
            WHEN 'EM_ANDAMENTO' THEN 2 
            WHEN 'FINALIZADO' THEN 3 
        END, 
        data_inicio DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['gameName_usuario'], $_SESSION['tagLine_usuario'], $usuario_riot_id]);
$campeonatos_participantes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Meus Campeonatos - Protáthlima</title>
</head>
<body>
    <div class="background-image"></div>
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
        <section class="busca-content my-tournaments">
            <h2>MEUS CAMPEONATOS</h2>

            <?php if (!empty($campeonatos_organizador)): ?>
                <div class="tournaments-section">
                    <h3>Campeonatos que Organizo</h3>
                    <div class="tournaments-grid">
                        <?php foreach ($campeonatos_organizador as $campeonato): ?>
                            <div class="tournament-card <?= strtolower($campeonato['status']) ?>">
                                <h4><?= htmlspecialchars($campeonato['nome']) ?></h4>
                                
                                <div class="tournament-details">
                                    <p class="status <?= strtolower($campeonato['status']) ?>">
                                        <?= $campeonato['status'] ?>
                                    </p>
                                    
                                    <p class="date">
                                        <strong>Início:</strong> 
                                        <?= date('d/m/Y H:i', strtotime($campeonato['data_inicio'])) ?>
                                    </p>
                                    
                                    <p class="teams">
                                        <strong>Equipes:</strong> 
                                        <?= $campeonato['total_equipes'] ?>/<?= $campeonato['numero_equipes'] ?>
                                    </p>
                                </div>

                                <div class="tournament-actions">
                                    <?php if ($campeonato['status'] === 'ABERTO'): ?>
                                        <a href="gerenciarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                           class="primary-button">Gerenciar Inscrições</a>
                                    <?php elseif ($campeonato['status'] === 'EM_ANDAMENTO'): ?>
                                        <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                           class="secondary-button">Ver Chaveamento</a>
                                    <?php else: ?>
                                        <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                           class="secondary-button">Ver Resultados</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($campeonatos_participantes)): ?>
                <div class="tournaments-section">
                    <h3>Campeonatos que Participo</h3>
                    <div class="tournaments-grid">
                        <?php foreach ($campeonatos_participantes as $campeonato): ?>
                            <div class="tournament-card <?= strtolower($campeonato['status']) ?>">
                                <h4><?= htmlspecialchars($campeonato['nome']) ?></h4>
                                
                                <div class="tournament-details">
                                    <p class="status <?= strtolower($campeonato['status']) ?>">
                                        <?= $campeonato['status'] ?>
                                    </p>
                                    
                                    <p class="date">
                                        <strong>Início:</strong> 
                                        <?= date('d/m/Y H:i', strtotime($campeonato['data_inicio'])) ?>
                                    </p>
                                    
                                    <p class="teams">
                                        <strong>Equipes:</strong> 
                                        <?= $campeonato['total_equipes'] ?>/<?= $campeonato['numero_equipes'] ?>
                                    </p>
                                </div>

                                <div class="tournament-actions">
                                    <?php if ($campeonato['status'] === 'EM_ANDAMENTO'): ?>
                                        <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                           class="secondary-button">Ver Chaveamento</a>
                                    <?php else: ?>
                                        <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                           class="secondary-button">Ver Resultados</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($campeonatos_organizador) && empty($campeonatos_participantes)): ?>
                <div class="no-tournaments">
                    <p>Você ainda não está participando de nenhum Campeonatos.</p>
                    <a href="campeonatos.php" class="primary-button">Ver Campeonatos Disponíveis</a>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html> 