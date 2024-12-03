<?php

$host = 'localhost';
$dbname = 'tcc';
$username = 'roottcc';
$password_usuario = 'jMo1ex2]Q[ecHoW4';

$session_timeout = 3600;
ini_set('session.gc_maxlifetime', $session_timeout);

session_start();

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password_usuario);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8");
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}