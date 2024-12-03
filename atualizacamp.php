<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'iniciar_campeonato') {
    try {
        $cd_campeonato = filter_var($_POST['cd_campeonato'], FILTER_VALIDATE_INT);
        
        if (!$cd_campeonato) {
            throw new Exception("ID do campeonato inválido");
        }

        
        $stmt = $pdo->prepare("SELECT * FROM campeonatos WHERE cd_campeonato = ?");
        $stmt->execute([$cd_campeonato]);
        $campeonato = $stmt->fetch(PDO::FETCH_ASSOC);
        
        
        $equipes = json_decode($campeonato['equipes_inscritas'], true);
        
        
        shuffle($equipes);
        
        
        $chaveamento = [];
        $totalEquipes = count($equipes);
        $primeiraRodada = [];
        
        
        for ($i = 0; $i < $totalEquipes; $i += 2) {
            
            $stmt = $pdo->prepare("SELECT nome FROM equipe WHERE cd_equipe = ?");
            
            
            $stmt->execute([$equipes[$i]['cd_equipe']]);
            $nomeEquipe1 = $stmt->fetchColumn();
            
            
            $stmt->execute([$equipes[$i + 1]['cd_equipe']]);
            $nomeEquipe2 = $stmt->fetchColumn();
            
            $primeiraRodada[] = [
                'equipe1' => [
                    'cd_equipe' => $equipes[$i]['cd_equipe'],
                    'nome' => $nomeEquipe1
                ],
                'equipe2' => [
                    'cd_equipe' => $equipes[$i + 1]['cd_equipe'],
                    'nome' => $nomeEquipe2
                ]
            ];
        }
        
        $chaveamento[0] = $primeiraRodada;
        
        
        $stmt = $pdo->prepare("
            UPDATE campeonatos 
            SET status = 'EM_ANDAMENTO',
                chaveamento = ?
            WHERE cd_campeonato = ?
        ");
        
        $result = $stmt->execute([
            json_encode($chaveamento),
            $cd_campeonato
        ]);

        if ($result) {
            header("Location: visualizarcampeonato.php?id=" . $cd_campeonato . "&success=1&success_message=" . urlencode("Campeonato iniciado com sucesso!"));
        } else {
            throw new Exception("Não foi possível iniciar o campeonato");
        }
        
    } catch (Exception $e) {
        header("Location: gerenciarcampeonato.php?id=" . $cd_campeonato . "&error=" . urlencode($e->getMessage()));
    }
    exit();
} else {
    header("Location: campeonatos.php");
    exit();
}