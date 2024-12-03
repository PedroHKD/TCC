<?php
mb_internal_encoding('UTF-8');
mb_regex_encoding('UTF-8');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include "api.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $jogadores = array(
        'jogador1' => ($_POST['jogador1']),
        'jogador2' => ($_POST['jogador2']),
        'jogador3' => ($_POST['jogador3']),
        'jogador4' => ($_POST['jogador4']),
        'jogador5' => ($_POST['jogador5'])
    );

    $tiers = ["IRON", "BRONZE", "SILVER", "GOLD", "PLATINUM", "EMERALD", "DIAMOND", "MASTER", "GRANDMASTER", "CHALLENGER"];
    $elos = [];

    if (!isset($error)) {
        include 'config.php';

        try {

            foreach ($jogadores as $key => $jogador) {

                if (validaRiotID($jogador) == false) {
                    header("refresh:0;url=cadastraequipe.php"); ?>
                    <script>
                        alert("RIOT ID inválido <?= $jogador ?>")
                    </script>
                    <?php
                } else {
                    list($gameName, $tagLine) = explode('#', $jogador);
                }
                $resultado = consultarRiotID($gameName, $tagLine, $apiKey);

                if (!isset($resultado['puuid'])) {
                    header("refresh:0;url=cadastraequipe.php"); ?>
                    <script>
                        alert($jogador. " não encontrado")
                    </script>
                    <?php
                } else if (isset($resultado['error'])) {
                    header("refresh:0;url=cadastraequipe.php"); ?>
                        <script>
                            alert("<?= "Erro: " . $resultado['error'] ?>")
                        </script>
                        <?php
                        exit();
                } else {
                    $puuid = $resultado['puuid'];
                    $summonerID = consultarSummonerID($puuid, $apiKey);
                    $eloInfo = consultarElo($summonerID, $apiKey);

                    if (isset($eloInfo['rankedSolo'])) {
                        $elos[$key] = $eloInfo['rankedSolo']['tier'];
                    } else {
                        header("refresh:0;url=cadastraequipe.php"); ?>
                            <script>
                                alert("<?= "Erro ao obter o elo do jogador " . $jogador ?>")
                            </script>
                            <?php
                            exit();
                    }

                    $resultados[$key] = array(
                        'puuid' => $puuid,
                        'gameName' => $resultado['gameName'],
                        'tagLine' => $resultado['tagLine'],
                        'tier' => $elos[$key]
                    );

                }
            }

            $jsonResult = json_encode($resultados, JSON_PRETTY_PRINT);
        } catch (PDOException $e) {
            header("refresh:0;url=cadastraequipe.php"); ?>
            <script>
                alert("<?= $error = "Erro ao cadastrar equipe: " . $e->getMessage() ?>")
            </script>
            <?php
            header("refresh:1;url=cadastraequipe.php");
        }
    }

    foreach ($elos as $elo1) {
        foreach ($elos as $elo2) {
            if (diffTiers($elo1, $elo2, $tiers) > 1) {
                header("refresh:0;url=cadastraequipe.php"); ?>
                <script>
                    alert("A diferença de elo entre os jogadores é maior que um tier")
                </script>
                <?php
                exit();
            }
        }
    }

    if (isset($resultado['puuid'])) {
        $puuid = $resultado['puuid'];

        $sql = "SELECT nome FROM equipe WHERE JSON_CONTAINS(membros, :puuid, '$')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':puuid' => json_encode($puuid)
        ]);

        if ($stmt->rowCount() > 1) {
            $equipe = $stmt->fetchColumn();?>
            <?php header("refresh:0;url=cadastraequipe.php"); ?>
            <script>
                alert("O jogador <?= $jogador ?> já faz parte da equipe <?= $equipe ?>")
            </script>
            <?php
            exit();
        }
    }
}

if (!isset($error)) {
    include 'config.php';
    $responsavel = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];
    
    try {
        // Calcular o tier médio
        $tier_values = [
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
        
        $soma_tiers = 0;
        foreach ($elos as $elo) {
            $soma_tiers += $tier_values[$elo];
        }
        
        $media_numerica = $soma_tiers / count($elos);
        
        
        $menor_diferenca = PHP_FLOAT_MAX;
        $tier_medio = '';
        
        foreach ($tier_values as $tier => $valor) {
            $diferenca = abs($valor - $media_numerica);
            if ($diferenca < $menor_diferenca) {
                $menor_diferenca = $diferenca;
                $tier_medio = $tier;
            }
        }

        
        $sql = "UPDATE equipe 
                SET membros = :jsonResult,
                    tier_medio = :tier_medio 
                WHERE responsavel = :responsavel";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':jsonResult' => $jsonResult,
            ':responsavel' => $responsavel
        ]);

        if ($stmt->rowCount() > 0) {
            header("Location: equipes.php?success=1&success_message=" . urlencode("Equipe atualizada com sucesso!"));
        } else {
            header("Location: equipes.php?error=" . urlencode("Não foi possível atualizar a equipe"));
        }
        exit();

    } catch (Exception $e) {
        header("Location: equipes.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}


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

function consultarRiotID($gameName, $tagLine, $apiKey)
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

    return $data;
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

function diffTiers($tier1, $tier2, $tiers)
{
    $index1 = array_search($tier1, $tiers);
    $index2 = array_search($tier2, $tiers);

    if ($index1 === false || $index2 === false) {
        return PHP_INT_MAX;
    }

    return abs($index1 - $index2);
}