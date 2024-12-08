<?php
include "config.php";
error_reporting(E_ALL ^ E_WARNING);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$sql = "SELECT c.*, 
        JSON_LENGTH(equipes_inscritas) as total_equipes
        FROM campeonatos c 
        WHERE status IN ('ABERTO', 'EM_ANDAMENTO')
        ORDER BY 
        CASE status 
            WHEN 'ABERTO' THEN 1 
            WHEN 'EM_ANDAMENTO' THEN 2 
        END,
        data_inicio ASC
        LIMIT 6";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$campeonatos_ativos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Protáthlima - Início</title>
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

    <main class="home-container">
        <section class="active-tournaments">
            <h2>CAMPEONATOS ATIVOS</h2>
            
            <?php if ($campeonatos_ativos): ?>
                <div class="tournaments-grid">
                    <?php foreach ($campeonatos_ativos as $campeonato): ?>
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
                                    <strong>Equipes:</strong> 
                                    <?= $campeonato['total_equipes'] ?>/<?= $campeonato['numero_equipes'] ?>
                                </p>
                            </div>

                            <div class="tournament-actions">
                                <?php if ($campeonato['status'] === 'ABERTO'): ?>
                                    <?php if (isset($_SESSION['loggedin'])): ?>
                                        <a href="gerenciarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                           class="primary-button">Participar</a>
                                    <?php else: ?>
                                        <a href="login.php" class="secondary-button">
                                            Faça login para participar
                                        </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <a href="visualizarcampeonato.php?id=<?= $campeonato['cd_campeonato'] ?>" 
                                       class="secondary-button">Ver Chaveamento</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="view-all">
                    <a href="campeonatos.php" class="primary-button">Ver Todos os Campeonatos</a>
                </div>
            <?php else: ?>
                <div class="no-tournaments">
                    <p>Não há campeonatos ativos no momento.</p>
                    <?php if (isset($_SESSION['loggedin'])): ?>
                        <a href="cadastracampeonato.php" class="primary-button">Criar Novo Torneio</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html>