<body>
    <div class="background-image"></div>
    <header>
        <link rel="stylesheet" href="css/css.css">
        <h1>Login</h1>
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
                            <li><a href="logout.php"> Logout </a></li>
                        </ul>
                    <?php else: ?>
                        <a href="login.php"> Login </a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    </header>
    <div class="login-container">
        <section class="login">
            <form class="login-form" method="post" action="auth.php">
                <h2>Login</h2>
                <input type="text" name="login-email" placeholder="Email" >
                <input type="password" name="login-password" placeholder="Senha">
                <button type="submit" name="submit">Entrar</button>
            </form>
            <form class="signup-form" method="post" action="cadastro.php">
                <h2>Criar Conta</h2>
                <input type="text" name="signup-username" id="signup-username" placeholder="IDRiot#1234">
                <input type="email" name="signup-email" id="signup-email" placeholder="Email">
                <input type="password" name="signup-password" id="signup-password" placeholder="Senha">
                <input type="password" name="signup-confirm-password" id="signup-confirm-password"
                    placeholder="Confirmar senha">
                <button type="submit" name="submit">Cadastrar</button>
            </form>
        </section>
    </div>
    <?php include 'components/alert_modal.php'; ?>
    <script src="js/modal.js"></script>
</body>