<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'function.php';
requireAdmin();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Conexão MySQLi
$conn = include 'connect.php';

// 1. PEGAR ID DO ADM PELA URL
$adm_id = filter_input(INPUT_GET, 'ID_Adm', FILTER_VALIDATE_INT);
if (!$adm_id) {
    $_SESSION['error'] = "ID inválido.";
    header("Location: pgAdms.php");
    exit;
}

// 2. BUSCAR DADOS DO ADMINISTRADOR
$stmt = $conn->prepare("SELECT ID_Adm, Nome, Email, Gerente FROM tbl_adm WHERE ID_Adm = ?");
$stmt->bind_param("i", $adm_id);
$stmt->execute();
$result = $stmt->get_result();
$adm = $result->fetch_assoc();

if (!$adm) {
    $_SESSION['error'] = "Administrador não encontrado.";
    header("Location: pgAdms.php");
    exit;
}



// 3. PROCESSAR FORMULÁRIO DE EDIÇÃO
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $gerente = isset($_POST['gerente']) ? 1 : 0;

    if (empty($nome) || empty($email)) {
        $error_msg = "Nome e Email são obrigatórios.";
    } else {

        // Se senha foi preenchida → atualizar senha
        if (!empty($senha)) {
            $hash = password_hash($senha, PASSWORD_BCRYPT);

            $update = $conn->prepare("
                UPDATE tbl_adm 
                SET Nome = ?, Email = ?, Senha = ?, Gerente = ?
                WHERE ID_Adm = ?
            ");
            $update->bind_param("sssii", $nome, $email, $hash, $gerente, $adm_id);

        } else {
            // Senha não alterada
            $update = $conn->prepare("
                UPDATE tbl_adm 
                SET Nome = ?, Email = ?, Gerente = ?
                WHERE ID_Adm = ?
            ");
            $update->bind_param("ssii", $nome, $email, $gerente, $adm_id);
        }

        if ($update->execute()) {
            $success_msg = "Administrador atualizado com sucesso!";
            // Atualiza dados na página
            $adm['Nome'] = $nome;
            $adm['Email'] = $email;
            $adm['Gerente'] = $gerente;
        } else {
            $error_msg = "Erro ao atualizar: " . $update->error;
        }

        $update->close();
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Administrador</title>
    <link rel="stylesheet" href="EstiloLogin.css">
</head>

<body>
<?php include 'Topo.php'; ?>

<div class="container">
    <h2>Editar Administrador</h2>

    <?php if (!empty($error_msg)): ?>
        <p style="color:red; font-weight:bold;"><?= htmlspecialchars($error_msg) ?></p>
    <?php endif; ?>

    <?php if (!empty($success_msg)): ?>
        <p style="color:green; font-weight:bold;"><?= htmlspecialchars($success_msg) ?></p>
    <?php endif; ?>

    <div class="login-box">
        <form method="POST">

            <label>Nome:</label>
            <input type="text" name="nome" value="<?= htmlspecialchars($adm['Nome']) ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($adm['Email']) ?>" required>

            <label>Senha (deixe vazio para não alterar):</label>
            <input type="password" name="senha">

            <label>
                <input type="checkbox" name="gerente" <?= $adm['Gerente'] ? 'checked' : '' ?>>  
                Gerente
            </label>

            <button type="submit">Salvar Alterações</button>
            <br><br>
            <a href="pgAdms.php">Voltar</a>

        </form>
    </div>
</div>

</body>
</html>
