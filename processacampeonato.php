<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: cadastracampeonato.php");
    exit();
}

try {
    $nome = filter_var($_POST['nomeCampeonato'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dataInicio = $_POST['dataInicio'];
    $numeroEquipes = filter_var($_POST['numeroEquipes'], FILTER_VALIDATE_INT);
    $balanceamento_elo = isset($_POST['balanceamento_elo']) ? 1 : 0;
    $organizador = $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario'];

    
    if (!$nome || strlen($nome) < 3 || strlen($nome) > 100) {
        throw new Exception("Nome do campeonato inválido");
    }

    if (!in_array($numeroEquipes, [4, 8])) {
        throw new Exception("Número de equipes inválido");
    }

    $dataInicioObj = new DateTime($dataInicio);
    $agora = new DateTime();
    
    if ($dataInicioObj < $agora) {
        throw new Exception("A data de início deve ser futura");
    }

    
    $sql = "INSERT INTO campeonatos (
                nome, 
                data_inicio, 
                numero_equipes,
                balanceamento_elo, 
                organizador,
                status,
                equipes_inscritas,
                chaveamento
            ) VALUES (
                :nome, 
                :data_inicio, 
                :numero_equipes,
                :balanceamento_elo,
                :organizador,
                'ABERTO',
                '[]',
                NULL
            )";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nome' => $nome,
        ':data_inicio' => $dataInicio,
        ':numero_equipes' => $numeroEquipes,
        ':balanceamento_elo' => $balanceamento_elo,
        ':organizador' => $organizador
    ]);

    if ($stmt->rowCount() > 0) {
        header("Location: campeonatos.php?success=1&success_message=" . urlencode("Campeonato criado com sucesso!"));
        exit();
    } else {
        throw new Exception("Erro ao criar campeonato");
    }

} catch (Exception $e) {
    header("Location: cadastracampeonato.php?error=" . urlencode($e->getMessage()));
    exit();
} 