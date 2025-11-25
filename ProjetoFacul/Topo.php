<?php
// Lógica para garantir que a sessão esteja ativa é crucial aqui:
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>
<link rel="stylesheet" href="Estilologin.css">
<header class="header">
    <div class="header-left-top">
        <a href="index.php" class="btn-back">← Voltar</a>
    </div>

    <div class="header-center">
        <h1>UNINOVE</h1>
        <div class="dots">
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <div class="cadastrologin">
        <?php if (isset($_SESSION['id'])): ?>
            <a href="logout.php">Sair (<?php echo htmlspecialchars($_SESSION['nome']); ?>)</a>
            <a href="pgUsuarios.php">Gerenciar Usuários</a>
            <?php if (isset($_SESSION['Gerente']) && $_SESSION['Gerente'] === 1): ?>
                <a href="pgAdms.php">Gerenciar Administradores</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">Login</a></li>
        <?php endif; ?>

    </div>
</header>