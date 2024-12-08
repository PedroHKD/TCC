<?php error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
include "api.php";
if (!isset($_SESSION['loggedin'])) {
    session_start();
} ?>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Busca de jogadores</title>
</head>

<body>
    <div class="background-image"></div>
    <header>
        <h1>Busca de jogadores</h1>
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
        <div class="search-bar-container">
            <form class="" method="post" action="buscajogador.php">
                <input type="riotID" name="riotID" placeholder="Exemplo#BR1">
                <button type="submit" name="submit"><img src="images/search.png"></img></button>
            </form>
        </div>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $riotID = filter_var($_POST['riotID'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (validaRiotID($riotID) == false) {
                header("Location: buscajogador.php?error=" . urlencode("RIOT ID inválido"));
                exit();
            }

            list($gameName, $tagLine) = explode('#', $riotID);
            $puuid = consultarPuuid($gameName, $tagLine, $apiKey);
            
            if (!isset($puuid)) {
                header("Location: buscajogador.php?error=" . urlencode("Jogador não encontrado"));
                exit();
            }

            $summonerID = consultarSummonerID($puuid, $apiKey);
            $consultaElo = consultarElo($summonerID, $apiKey); 
            $campeoes = consultarCampeoes($puuid, $apiKey);

            $rankedSolo = $consultaElo['rankedSolo'];
            ?>
            <section class="busca-content player-info-section">
                <div class="tournament-card">
                    <h3><?= $riotID ?></h3>
                    
                    <div class="team-details">
                        <div class="rank-container">
                            <div class="rank-info">
                                <h4>Ranked Solo/Duo</h4>
                                <div class="rank-details">
                                    <div class="tier-info">
                                        <span class="tier-badge <?= strtolower($consultaElo['rankedSolo']['tier'] ?? 'unranked') ?>">
                                            <?= $consultaElo['rankedSolo']['tier'] ? $consultaElo['rankedSolo']['tier'] : "Sem Rank" ?>
                                        </span>
                                    </div>
                                    <?php if (isset($consultaElo['rankedSolo']) && isset($consultaElo['rankedSolo']['wins']) && isset($consultaElo['rankedSolo']['losses'])): 
                                        $totalGamesSolo = $consultaElo['rankedSolo']['wins'] + $consultaElo['rankedSolo']['losses'];
                                        $winrateSolo = $totalGamesSolo > 0 ? round(($consultaElo['rankedSolo']['wins'] / $totalGamesSolo) * 100, 1) : 0;
                                    ?>
                                    <div class="stats-info">
                                        <p class="pdl">PDL: <?= $consultaElo['rankedSolo']['leaguePoints'] ?></p>
                                        <p class="winrate">Winrate: <?= $winrateSolo ?>%</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="rank-info">
                                <h4>Ranked Flex</h4>
                                <div class="rank-details">
                                    <div class="tier-info">
                                        <span class="tier-badge <?= strtolower($consultaElo['rankedFlex']['tier'] ?? 'unranked') ?>">
                                            <?= $consultaElo['rankedFlex']['tier'] ? $consultaElo['rankedFlex']['tier'] : "Sem Rank" ?>
                                        </span>
                                    </div>
                                    <?php if (isset($consultaElo['rankedFlex']) && isset($consultaElo['rankedFlex']['wins']) && isset($consultaElo['rankedFlex']['losses'])): 
                                        $totalGamesFlex = $consultaElo['rankedFlex']['wins'] + $consultaElo['rankedFlex']['losses'];
                                        $winrateFlex = $totalGamesFlex > 0 ? round(($consultaElo['rankedFlex']['wins'] / $totalGamesFlex) * 100, 1) : 0;
                                    ?>
                                    <div class="stats-info">
                                        <p class="pdl">PDL: <?= $consultaElo['rankedFlex']['leaguePoints'] ?></p>
                                        <p class="winrate">Winrate: <?= $winrateFlex ?>%</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="champion-mastery">
                            <h4>Campeões Mais Jogados</h4>
                            <div class="champion-list">
                                <?php
                                include "config.php";
                                foreach ($campeoes as $campeao):
                                    $sql = "SELECT nome FROM campeoes WHERE id = :id";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam('id', $campeao['championId'], PDO::PARAM_INT);
                                    $stmt->execute();
                                    $nomeCampeoes = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                    <div class="champion-item">
                                        <img src="https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/champion-icons/<?= $campeao['championId'] ?>.png" 
                                             alt="<?= $nomeCampeoes['nome'] ?>" 
                                             title="<?= $nomeCampeoes['nome'] ?>">
                                        <p class="champion-name"><?= $nomeCampeoes['nome'] ?></p>
                                        <p class="mastery-points"><?= number_format($campeao['championPoints'], 0, ',', '.') ?> pts</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section><?php
        }
        ?>


    </main>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>

</html>
<?php
function validaRiotID($riotID)
{
    $parts = explode('#', $riotID);

    if (count($parts) !== 2) {
        return false;
    }

    list($gameName, $tagLine) = $parts;

    if (preg_match('/^[\w\s]{3,16}$/u', $gameName) && preg_match('/^[a-zA-Z0-9]{3,16}$/', $tagLine)) {
        return true;
    } else {
        return false;
    }
}

function consultarPuuid($gameName, $tagLine, $apiKey)
{
    $encodedGameName = urlencode($gameName);
    $link = "https://americas.api.riotgames.com/riot/account/v1/accounts/by-riot-id/$encodedGameName/$tagLine?api_key=$apiKey";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => "cURL Error: $error $link"];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "JSON Decode Error: " . json_last_error_msg()];
    }

    $puuid = $data['puuid'];

    return $puuid;
}

function consultarSummonerID($puuid, $apiKey)
{
    $link = "https://br1.api.riotgames.com/lol/summoner/v4/summoners/by-puuid/$puuid?api_key=$apiKey";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => "cURL Error: $error $link"];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "JSON Decode Error: " . json_last_error_msg()];
    }

    $summonerID = $data['id'];

    return $summonerID;
}

function consultarElo($summonerID, $apiKey)
{
    $link = "https://br1.api.riotgames.com/lol/league/v4/entries/by-summoner/$summonerID?api_key=$apiKey";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => "cURL Error: $error $link"];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    foreach ($data as $entry) {
        if ($entry['queueType'] == 'RANKED_SOLO_5x5') {
            $rankedSolo = [
                'tier' => $entry['tier'],
                'rank' => $entry['rank'],
                'wins' => $entry['wins'],
                'losses' => $entry['losses'],
                'leaguePoints' => $entry['leaguePoints']
            ];
        } elseif ($entry['queueType'] == 'RANKED_FLEX_SR') {
            $rankedFlex = [
                'tier' => $entry['tier'],
                'rank' => $entry['rank'],
                'wins' => $entry['wins'],
                'losses' => $entry['losses'],
                'leaguePoints' => $entry['leaguePoints']
            ];
        }
    }

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "JSON Decode Error: " . json_last_error_msg()];
    }

    return ['rankedSolo' => $rankedSolo, 'rankedFlex' => $rankedFlex];
}

function consultarCampeoes($puuid, $apiKey)
{
    $link = "https://br1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-puuid/$puuid/top?count=5&api_key=$apiKey";

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36 Edg/125.0.0.0'
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return ["error" => "cURL Error: $error $link"];
    }

    curl_close($ch);

    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        return ["error" => "JSON Decode Error: " . json_last_error_msg()];
    }
    $championData = [];

    foreach ($data as $entry) {
        $championData[] = [
            'championId' => $entry['championId'],
            'championPoints' => $entry['championPoints']
        ];
    }

    return $championData;
}
?>