<?php
include "config.php";

if (!isset($_SESSION['loggedin']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: campeonatos.php");
    exit();
}

try {
    $cd_campeonato = filter_var($_POST['cd_campeonato'], FILTER_VALIDATE_INT);
    $round = filter_var($_POST['round'], FILTER_VALIDATE_INT);
    $match = filter_var($_POST['match'], FILTER_VALIDATE_INT);
    $vencedor = filter_var($_POST['vencedor'], FILTER_VALIDATE_INT);

    
    $stmt = $pdo->prepare("SELECT *, JSON_LENGTH(equipes_inscritas) as num_equipes FROM campeonatos WHERE cd_campeonato = ?");
    $stmt->execute([$cd_campeonato]);
    $campeonato = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$campeonato) {
        throw new Exception("Campeonato não encontrado");
    }

    $chaveamento = json_decode($campeonato['chaveamento'], true);
    
    
    $numEquipes = $campeonato['num_equipes'];
    $totalRounds = $numEquipes == 8 ? 3 : 2; 
    
    
    $chaveamento[$round][$match]['vencedor'] = $vencedor;

    
    if ($round < ($totalRounds - 1)) {
        $nextRound = $round + 1;
        $nextMatch = floor($match / 2);
        
        
        $stmt = $pdo->prepare("SELECT cd_equipe, nome FROM equipe WHERE cd_equipe = ?");
        $stmt->execute([$vencedor]);
        $vencedorInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$vencedorInfo) {
            throw new Exception("Equipe vencedora não encontrada");
        }

        
        $isFirstTeam = $match % 2 === 0;
        
        
        if (!isset($chaveamento[$nextRound][$nextMatch])) {
            $chaveamento[$nextRound][$nextMatch] = [
                'equipe1' => $isFirstTeam ? $vencedorInfo : ['nome' => 'A definir', 'cd_equipe' => null],
                'equipe2' => $isFirstTeam ? ['nome' => 'A definir', 'cd_equipe' => null] : $vencedorInfo
            ];
        } else {
            if ($isFirstTeam) {
                $chaveamento[$nextRound][$nextMatch]['equipe1'] = $vencedorInfo;
            } else {
                $chaveamento[$nextRound][$nextMatch]['equipe2'] = $vencedorInfo;
            }
        }
    }

    
    $stmt = $pdo->prepare("UPDATE campeonatos SET chaveamento = ? WHERE cd_campeonato = ?");
    $result = $stmt->execute([json_encode($chaveamento), $cd_campeonato]);

    if ($result) {
        
        $ehUltimoRound = ($round === ($totalRounds - 1));
        $todasPartidasFinalizadas = true;
        
        
        if ($ehUltimoRound) {
            foreach ($chaveamento[$round] as $partida) {
                if (!isset($partida['vencedor'])) {
                    $todasPartidasFinalizadas = false;
                    break;
                }
            }
            
            
            if ($todasPartidasFinalizadas) {
                
                $stmt = $pdo->prepare("UPDATE campeonatos SET status = 'FINALIZADO' WHERE cd_campeonato = ?");
                $stmt->execute([$cd_campeonato]);

                
                $stmt = $pdo->prepare("SELECT jogadores FROM equipe WHERE cd_equipe = ?");
                $stmt->execute([$vencedor]);
                $equipe = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($equipe) {
                    $jogadores = json_decode($equipe['jogadores'], true);
                    
                    
                    $puuids = array_map(function($jogador) {
                        return $jogador['puuid'];
                    }, $jogadores);
                    
                    
                    if (!empty($puuids)) {
                        $placeholders = str_repeat('?,', count($puuids) - 1) . '?';
                        $stmt = $pdo->prepare("
                            UPDATE usuarios 
                            SET medalhas_usuario = medalhas_usuario + 1
                            WHERE puuid IN ($placeholders)
                        ");
                        $stmt->execute($puuids);
                    }
                }
            }
        }
        
        header("Location: visualizarcampeonato.php?id=" . $cd_campeonato . "&success=1");
        exit();
    } else {
        throw new Exception("Erro ao atualizar o chaveamento");
    }

} catch (Exception $e) {
    header("Location: visualizarcampeonato.php?id=" . $cd_campeonato . "&error=" . urlencode($e->getMessage()));
    exit();
} 