<?php
include "config.php";

if (!isset($_SESSION['loggedin'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Não autorizado']);
    exit();
}

try {
    // Ler o corpo da requisição JSON
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $cd_campeonato = filter_var($data['cd_campeonato'], FILTER_VALIDATE_INT);
    $cd_equipe = filter_var($data['cd_equipe'], FILTER_VALIDATE_INT);

    if (!$cd_campeonato || !$cd_equipe) {
        throw new Exception("Dados inválidos");
    }

    // Buscar o campeonato
    $sql = "SELECT organizador, equipes_inscritas, status FROM campeonatos WHERE cd_campeonato = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cd_campeonato]);
    $campeonato = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campeonato) {
        throw new Exception("Campeonato não encontrado");
    }

    if ($campeonato['organizador'] !== $_SESSION['gameName_usuario'] . "#" . $_SESSION['tagLine_usuario']) {
        throw new Exception("Você não tem permissão para remover equipes deste campeonato");
    }

    if ($campeonato['status'] !== 'ABERTO') {
        throw new Exception("Não é possível remover equipes de um campeonato em andamento ou finalizado");
    }

    // Decodificar o array de equipes
    $equipes_inscritas = json_decode($campeonato['equipes_inscritas'], true) ?: [];
    
    // Remover a equipe do array
    $equipes_inscritas = array_filter($equipes_inscritas, function($equipe) use ($cd_equipe) {
        return $equipe['cd_equipe'] != $cd_equipe;
    });
    
    
    $equipes_inscritas = array_values($equipes_inscritas);

    
    $sql = "UPDATE campeonatos 
            SET equipes_inscritas = ? 
            WHERE cd_campeonato = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([json_encode($equipes_inscritas), $cd_campeonato]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Equipe removida com sucesso!'
        ]);
    } else {
        throw new Exception("Erro ao remover equipe");
    }

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
} 