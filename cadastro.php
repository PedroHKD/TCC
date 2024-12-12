<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "api.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['signup-username'];
    $password_usuario = filter_var($_POST['signup-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirm_password = filter_var($_POST['signup-confirm-password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['signup-email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: login.php?error=" . urlencode("Email inválido"));
        exit();
    }

    if ($password_usuario !== $confirm_password) {
        header("Location: login.php?error=" . urlencode("As senhas não coincidem"));
        exit();
    }

    if (!validaRiotID($username)) {
        header("Location: login.php?error=" . urlencode("RIOT ID inválido"));
        exit();
    }

    list($gameName, $tagLine) = explode('#', $username);
    $resultado = consultarRiotID($gameName, $tagLine, $apiKey);

    if (isset($resultado['error'])) {
        header("Location: login.php?error=" . urlencode("Erro: " . $resultado['error']));
        exit();
    }

    if (!isset($resultado['gameName']) || !isset($resultado['tagLine']) || !isset($resultado['puuid'])) {
        header("Location: login.php?error=" . urlencode("Dados do jogador não encontrados"));
        exit();
    }

    $gameName = $resultado['gameName'];
    $tagLine = $resultado['tagLine'];
    $puuid = $resultado['puuid'];

    try {
        include 'config.php';

        $sql = "SELECT * FROM usuarios WHERE puuid_usuario = :puuid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':puuid' => $puuid]);

        if ($stmt->rowCount() > 0) {
            header("Location: login.php?error=" . urlencode("Já existe conta com esse RIOT ID cadastrada"));
            exit();
        }

        $sql = "INSERT INTO usuarios (gameName_usuario, tagLine_usuario, puuid_usuario, senha_usuario, email_usuario) 
                VALUES (:gameName, :tagLine, :puuid, :password, :email)";
        $stmt = $pdo->prepare($sql);
        
        $success = $stmt->execute([
            ':gameName' => $gameName,
            ':tagLine' => $tagLine,
            ':puuid' => $puuid,
            ':password' => $password_usuario,
            ':email' => $email
        ]);

        if ($success) {
            header("Location: login.php?success=1&success_message=" . urlencode("Conta criada com sucesso!"));
        } else {
            header("Location: login.php?error=" . urlencode("Não foi possível criar a conta"));
        }
        exit();

    } catch (PDOException $e) {
        header("Location: login.php?error=" . urlencode("Erro ao cadastrar usuário: " . $e->getMessage()));
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

error_log("Resultado API: " . print_r($resultado, true));
error_log("GameName: " . $gameName);
error_log("TagLine: " . $tagLine);
error_log("PUUID: " . $puuid);
