<?php
include "config.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $mensagem = filter_var($_POST['mensagem'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cd_usuario = $_SESSION['cd_usuario'];

        if (empty($mensagem)) {
            throw new Exception("A mensagem não pode estar vazia");
        }

        $sql = "INSERT INTO feedback (cd_usuario, mensagem) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([$cd_usuario, $mensagem]);

        if ($result) {
            header("Location: feedback.php?success=1&success_message=" . urlencode("Feedback enviado com sucesso!"));
        } else {
            throw new Exception("Erro ao enviar feedback");
        }

    } catch (Exception $e) {
        header("Location: feedback.php?error=" . urlencode($e->getMessage()));
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Feedback - Protáthlima</title>
</head>
<body>
    <div class="background-image"></div>
    <header>
        <h1>Feedback</h1>
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
                            <li><a href="meuscampeonatos.php">Meus Campeonatos</a></li>
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

    <main class="feedback-container">
        <section class="feedback-content">
            <form method="post" class="feedback-form">
                <h2>Envie seu feedback</h2>
                <textarea 
                    name="mensagem" 
                    placeholder="Digite sua mensagem aqui..." 
                    rows="5" 
                    required
                ></textarea>
                <button type="submit" class="primary-button">Enviar</button>
            </form>
        </section>
    </main>

    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html> 