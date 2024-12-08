<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include "api.php";
include "config.php";

if ($_SESSION['loggedin']) {
    $usuario = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];
} else {
    header("refresh:0;url=login.php"); ?>
    <script>
        alert("Necessário estar logado para criar/editar equipe")
    </script>
    <?php
}

$time = verificaResponsavel($usuario);

$membros = $time[0]['membros'] != null ? json_decode($time[0]['membros']) : [];

?>

<body>
    <div class="background-image"></div>
    <header>
        <link rel="stylesheet" href="css/css.css">
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
        <?php if ($time != false) { ?>
            <section class="busca-content team-form">
                <h2>EDIÇÃO DE EQUIPE</h2>
                <div style="color: white">Nome da equipe:</div>
                <form method="post" action="alteraequipe.php">
                    <div style="color: white"><?php echo $time[0]['nome'] ?>
                    </div>
                    <div style="color: white">Top laner:</div>
                    <div><input type="text" name="jogador1" id="jogador1" placeholder="" display="center"
                            value="<?php echo buscaRiotID($membros->jogador1->puuid, $apiKey) ?>">
                    </div>
                    <div style="color: white">Jungler:</div>
                    <div><input type="text" name="jogador2" id="jogador2" placeholder=""
                            value="<?php echo buscaRiotID($membros->jogador2->puuid, $apiKey) ?>">
                    </div>
                    <div style="color: white">Mid laner:</div>
                    <div><input type="text" name="jogador3" id="jogador3" placeholder=""
                            value="<?php echo buscaRiotID($membros->jogador3->puuid, $apiKey) ?>">
                    </div>
                    <div style="color: white">ADC:</div>
                    <div><input type="text" name="jogador4" id="jogador4" placeholder=""
                            value="<?php echo buscaRiotID($membros->jogador4->puuid, $apiKey) ?>"></div>
                    <div style="color: white">Suporte:</div>
                    <div><input type="text" name="jogador5" id="jogador5" placeholder=""
                            value="<?php echo buscaRiotID($membros->jogador5->puuid, $apiKey) ?>"></div>
                    <?php if (isset($_SESSION['puuid_usuario'])) { ?>
                        <div><button type="submit" name="submit">Alterar</button></div><?php } ?>
                        <div><a href="excluitime.php"><img src="images/excluir.png"></a></div>
                </form>
                <div><b style*="text-color: white">Observações: <br>O rank dos jogadores deve ter uma diferença de no máximo
                        1 elo.<br>Use o RiotID dos jogadores como por exemplo:
                        toplaner#BR1.</br></div>
            </section> <?php } else { ?>
            <section class="busca-content team-form">
                <h2>CRIAÇÃO DE EQUIPE</h2>
                <form method="post" action="criaequipe.php">
                    <div><input type="text" name="nomeEquipe" id="nomeEquipe" placeholder="Nome da equipe" value="">
                    </div>
                    <div><input type="text" name="jogador1" id="jogador1" placeholder="Top laner" display="center" value="">
                    </div>
                    <div><input type="text" name="jogador2" id="jogador2" placeholder="Jungler" value=""></div>
                    <div><input type="text" name="jogador3" id="jogador3" placeholder="Mid laner" value="">
                    </div>
                    <div><input type="text" name="jogador4" id="jogador4" placeholder="ADC" value=""></div>
                    <div><input type="text" name="jogador5" id="jogador5" placeholder="Support" value=""></div>
                    <?php if (isset($_SESSION['puuid_usuario'])) { ?>
                        <div><button type="submit" name="submit">Cadastrar</button></div><?php } ?>
                </form>
                <div><b style*="text-color: white">Observações: <br>O rank dos jogadores deve ter uma diferença de no máximo
                        1 elo.<br>Use o RiotID dos jogadores como por exemplo:
                        toplaner#BR1.</br></div>
            </section><?php } ?>
    </main>
</body>

<?php
function verificaResponsavel($usuario)
{
    include "config.php";
    $sql = "SELECT nome, membros FROM equipe WHERE responsavel = :usuario";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $usuario
    ]);

    if ($stmt->rowCount() > 0) {
        $time = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $time;
    } else
        return false;

}

function buscaRiotID($puuid, $apiKey)
{
    $link = "https://americas.api.riotgames.com/riot/account/v1/accounts/by-puuid/$puuid?api_key=$apiKey";

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

    $gameName = $data['gameName'];
    $tagLine = $data['tagLine'];
    $nome = $gameName . "#" . $tagLine;
    return $nome;
}
?>