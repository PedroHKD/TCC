<?php
include "config.php";

if (!isset($_SESSION['loggedin'])) {
    session_start();
} 

$responsavel = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];

$sql = "DELETE FROM equipe WHERE responsavel = :responsavel";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':responsavel' => $responsavel
]);

header("refresh:0;url=equipes.php");