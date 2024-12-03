<?php
include "config.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

function calcularTierMedio($membros) {
    $tiers = [
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

    $soma = 0;
    $count = 0;

    
    if (is_string($membros)) {
        $membros = json_decode($membros, true);
    }

    foreach ($membros as $membro) {
        if (isset($membro['tier']) && isset($tiers[$membro['tier']])) {
            $soma += $tiers[$membro['tier']];
            $count++;
        }
    }

    if ($count === 0) return 'UNRANKED';

    $media = $soma / $count;
    
    
    $menor_diferenca = PHP_FLOAT_MAX;
    $tier_medio = 'UNRANKED';
    
    foreach ($tiers as $tier => $valor) {
        $diferenca = abs($valor - $media);
        if ($diferenca < $menor_diferenca) {
            $menor_diferenca = $diferenca;
            $tier_medio = $tier;
        }
    }

    return $tier_medio;
}

try {
    $cd_campeonato = filter_var($_POST['cd_campeonato'], FILTER_VALIDATE_INT);
    $cd_equipe = filter_var($_POST['cd_equipe'], FILTER_VALIDATE_INT);

    if (!$cd_campeonato || !$cd_equipe) {
        throw new Exception("Dados inválidos");
    }

    
    $stmt = $pdo->prepare("SELECT * FROM campeonatos WHERE cd_campeonato = ?");
    $stmt->execute([$cd_campeonato]);
    $campeonato = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campeonato) {
        throw new Exception("Campeonato não encontrado");
    }

    
    if ($campeonato['status'] !== 'ABERTO') {
        throw new Exception("Este campeonato não está aceitando inscrições");
    }

    
    $stmt = $pdo->prepare("SELECT * FROM equipe WHERE cd_equipe = ?");
    $stmt->execute([$cd_equipe]);
    $equipe = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$equipe) {
        throw new Exception("Equipe não encontrada");
    }

    
    $equipes_inscritas = json_decode($campeonato['equipes_inscritas'], true) ?: [];
    foreach ($equipes_inscritas as $equipe_inscrita) {
        if ($equipe_inscrita['cd_equipe'] == $cd_equipe) {
            throw new Exception("Esta equipe já está inscrita");
        }
    }

    
    if (count($equipes_inscritas) >= $campeonato['numero_equipes']) {
        throw new Exception("O campeonato já atingiu o número máximo de equipes");
    }

    
    if ($campeonato['balanceamento_elo'] && !empty($equipes_inscritas)) {
        $primeira_equipe = $equipes_inscritas[0];
        $tier_base = calcularTierMedio($primeira_equipe['membros']);
        $tier_nova_equipe = calcularTierMedio($equipe['membros']);

        $tiers = [
            'IRON' => 0, 'BRONZE' => 1, 'SILVER' => 2, 'GOLD' => 3,
            'PLATINUM' => 4, 'EMERALD' => 5, 'DIAMOND' => 6,
            'MASTER' => 7, 'GRANDMASTER' => 8, 'CHALLENGER' => 9
        ];

        if (abs($tiers[$tier_base] - $tiers[$tier_nova_equipe]) > 1) {
            throw new Exception("O tier da equipe não é compatível com o balanceamento do campeonato");
        }
    }

    
    $equipes_inscritas[] = [
        'cd_equipe' => $equipe['cd_equipe'],
        'nome' => $equipe['nome'],
        'membros' => json_decode($equipe['membros'], true)
    ];

    
    $stmt = $pdo->prepare("UPDATE campeonatos SET equipes_inscritas = ? WHERE cd_campeonato = ?");
    $stmt->execute([json_encode($equipes_inscritas), $cd_campeonato]);

    header("Location: gerenciarcampeonato.php?id=" . $cd_campeonato . "&success=1&success_message=" . urlencode("Equipe inscrita com sucesso!"));
    exit();

} catch (Exception $e) {
    header("Location: gerenciarcampeonato.php?id=" . $cd_campeonato . "&error=" . urlencode($e->getMessage()));
    exit();
} 