<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
include "config.php";

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/css.css">
    <title>Criar Torneio</title>
</head>
<body>
    <div class="background-image"></div>
    <header>
        <h1>Protáthlima</h1>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li class="dropdown">
                    <a href="#">Menu</a>
                    <ul class="dropdown-content">
                        <li><a href="buscajogador.php">Busca de jogadores</a></li>
                        <li><a href="equipes.php">Equipes</a></li>
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

    <main class="busca-container">
        <section class="busca-content tournament-form">
            <h2>CRIAR NOVO CAMPEONATO</h2>
            
            <form method="post" action="processacampeonato.php" class="tournament-creation-form">
                <div class="form-group">
                    <label for="nomeCampeonato">Nome do Torneio:</label>
                    <input type="text" 
                           name="nomeCampeonato" 
                           id="nomeCampeonato" 
                           required 
                           pattern="[A-Za-z0-9\s]{3,100}"
                           title="Nome deve ter entre 3 e 100 caracteres">
                </div>

                <div class="form-group">
                    <label for="dataInicio">Data de Início:</label>
                    <input type="datetime-local" 
                           name="dataInicio" 
                           id="dataInicio" 
                           required>
                </div>

                <div class="form-group">
                    <label for="numeroEquipes">Número de Equipes:</label>
                    <select name="numeroEquipes" id="numeroEquipes" required>
                        <option value="4">4 Equipes</option>
                        <option value="8">8 Equipes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="balanceamento">Balanceamento de ELO:</label>
                    <div class="toggle-switch">
                        <input type="checkbox" 
                               id="balanceamento" 
                               name="balanceamento_elo" 
                               checked>
                        <label for="balanceamento" class="toggle-label">
                            <span class="toggle-inner"></span>
                            <span class="toggle-switch"></span>
                        </label>
                    </div>
                    <small class="form-help">
                        Se ativado, a diferença máxima de elo entre as equipes será de 1 tier
                    </small>
                </div>

                <div class="form-actions">
                    <button type="submit" class="primary-button">Criar Torneio</button>
                </div>
            </form>
        </section>
    </main>

    <script>
        
        document.querySelector('form').addEventListener('submit', function(e) {
            const dataInicio = new Date(document.getElementById('dataInicio').value);
            const hoje = new Date();

            if (dataInicio < hoje) {
                e.preventDefault();
                alert('A data de início deve ser futura!');
            }
        });
    </script>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>
</html> 