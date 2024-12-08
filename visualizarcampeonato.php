<?php
include "config.php";
error_reporting(E_ALL ^ E_WARNING);

$cd_campeonato = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$cd_campeonato) {
    header("Location: campeonatos.php");
    exit();
}


$stmt = $pdo->prepare("SELECT * FROM campeonatos WHERE cd_campeonato = ?");
$stmt->execute([$cd_campeonato]);
$campeonato = $stmt->fetch(PDO::FETCH_ASSOC);


$chaveamento = !empty($campeonato['chaveamento']) ? 
    json_decode($campeonato['chaveamento'], true) : [];

$equipes_inscritas = !empty($campeonato['equipes_inscritas']) ? 
    json_decode($campeonato['equipes_inscritas'], true) : [];


$is_organizador = isset($_SESSION['loggedin']) && 
                 $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'] === $campeonato['organizador'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Chaveamento - <?= htmlspecialchars($campeonato['nome']) ?></title>
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
                <li class="dropdown"><?php if (isset($_SESSION['loggedin'])) { ?>
                        <a href="#"> <?php echo $_SESSION['gameName_usuario'] ?></a>
                        <ul class="dropdown-content">
                            <li><a href="#">Minhas moedas:</a></li>
                            <li><a href="feedback.php">Feedback</a></li>
                            <li><a href="logout.php"> Logout </a></li>
                        </ul><?php
                } else { ?>
                        <a href="login.php"> Login </a><?php
                } ?>
                </li>
            </ul>
        </nav>
    </header>

    <main class="busca-container">
        <section class="busca-content tournament-bracket">
            <h2><?= htmlspecialchars($campeonato['nome']) ?></h2>
            
            <div class="tournament-info">
                <p><strong>Status:</strong> <?= $campeonato['status'] ?></p>
                <p><strong>Data de Início:</strong> <?= date('d/m/Y H:i', strtotime($campeonato['data_inicio'])) ?></p>
            </div>

            <div class="bracket">
                <?php
                $rounds = [
                    8 => ['Quartas de Final', 'Semi Final', 'Final'],
                    4 => ['Semi Final', 'Final']
                ];
                $currentRounds = $rounds[$campeonato['numero_equipes']];
                
                foreach ($currentRounds as $roundIndex => $roundName):
                ?>
                    <div class="round">
                        <h3><?= $roundName ?></h3>
                        <?php
                        $matchesInRound = pow(2, count($currentRounds) - $roundIndex - 1);
                        for ($i = 0; $i < $matchesInRound; $i++):
                            $match = $chaveamento[$roundIndex][$i] ?? null;
                        ?>
                            <div class="match">
                                <?php if ($match): ?>
                                    <div class="team <?= isset($match['vencedor']) && $match['vencedor'] === $match['equipe1']['cd_equipe'] ? 'winner' : '' ?>">
                                        <?= htmlspecialchars($match['equipe1']['nome']) ?>
                                    </div>
                                    <div class="team <?= isset($match['vencedor']) && $match['vencedor'] === $match['equipe2']['cd_equipe'] ? 'winner' : '' ?>">
                                        <?= htmlspecialchars($match['equipe2']['nome']) ?>
                                    </div>
                                    <?php if ($is_organizador && !isset($match['vencedor'])): ?>
                                        <div class="match-actions">
                                            <form action="atualizarchave.php" method="POST" class="winner-form">
                                                <input type="hidden" name="cd_campeonato" value="<?= $cd_campeonato ?>">
                                                <input type="hidden" name="round" value="<?= $roundIndex ?>">
                                                <input type="hidden" name="match" value="<?= $i ?>">
                                                <button type="submit" name="vencedor" value="<?= $match['equipe1']['cd_equipe'] ?>" 
                                                        class="winner-button">
                                                        <?= htmlspecialchars($match['equipe1']['nome']) ?>
                                                        <br>Venceu
                                                </button>
                                                <button type="submit" name="vencedor" value="<?= $match['equipe2']['cd_equipe'] ?>" 
                                                        class="winner-button">
                                                        <?= htmlspecialchars($match['equipe2']['nome']) ?> 
                                                        <br>Venceu
                                                </button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="team">A definir</div>
                                    <div class="team">A definir</div>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($campeonato['status'] === 'FINALIZADO'): ?>
                <div class="tournament-winner">
                    <h3>Campeão do Campeonato</h3>
                    <?php
                    $ultimaRodada = end($chaveamento);
                    $ultimaPartida = end($ultimaRodada);
                    
                    if ($ultimaPartida && isset($ultimaPartida['vencedor'])):
                        $vencedor = $ultimaPartida['vencedor'] === $ultimaPartida['equipe1']['cd_equipe'] 
                            ? $ultimaPartida['equipe1'] 
                            : $ultimaPartida['equipe2'];
                    ?>
                        <div class="winner-display">
                            <h4><?= htmlspecialchars($vencedor['nome']) ?></h4>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>
