<?php
// 1. Inicia ou resume a sessão existente
// É importante iniciar a sessão para poder destruí-la corretamente.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 2. Remove todas as variáveis da sessão
// Isso limpa todos os dados armazenados na superglobal $_SESSION.
$_SESSION = array();

// 3. Destruir o cookie da sessão (opcional, mas recomendado)
// Se você estiver usando cookies para propagar o ID da sessão (o que é o padrão),
// é uma boa prática também excluir o cookie.
// Nota: Isso destruirá a sessão, e não apenas os dados da sessão!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, // Define o tempo de expiração para o passado
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destrói a sessão no servidor
session_destroy();

// 5. Redireciona o usuário para a página de login
// Você pode alterar "login.php" para qualquer página que desejar após o logout (ex: "index.php").
header("Location: login.php?status=loggedout");
exit(); // Garante que nenhum código adicional seja executado após o redirecionamento
?>