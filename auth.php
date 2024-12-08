<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_abort();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $password_usuario = filter_var($_POST['login-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['login-email'], FILTER_SANITIZE_EMAIL);

    if (!isset($error)) {
        include 'config.php';

        try {

            $sql = "SELECT * FROM usuarios WHERE email_usuario = :email AND senha_usuario = :password_usuario";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':password_usuario' => $password_usuario
            ]);

            if ($stmt->rowCount() > 0) {

                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                $_SESSION['loggedin'] = true;
                $_SESSION['cd_usuario'] = $row['cd_usuario'];
                $_SESSION['gameName_usuario'] = $row['gameName_usuario'];
                $_SESSION['tagLine_usuario'] = $row['tagLine_usuario'];
                $_SESSION['puuid_usuario'] = $row['puuid_usuario'];
                $_SESSION['medalhas_usuario'] = $row['medalhas_usuario'];

                header("Location: index.php?success=1&success_message=" . urlencode("Login realizado com sucesso!"));
            } else {
                header("Location: login.php?error=" . urlencode("Email ou senha incorretos"));
            }
            exit();
        } catch (PDOException $e) {
            header("refresh:0;url=login.php"); ?>
            <script>
                alert("<?php $error = "Erro ao logar" . $e->getMessage() ?>")
            </script>
            <?php
            header("refresh:0;url=login.php");
        }
    }
} else {
    header("refresh:0;url=index.php");
}
