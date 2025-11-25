<?php
session_start();
$conn = include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirme_senha = $_POST['rsenha'];
    $gerente = isset($_POST['gerente']) ? 1 : 0;

    // Verifica se email já existe
    $stmt = $conn->prepare("SELECT ID_Adm FROM tbl_adm WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error = "Este CPF já está cadastrado.";
    } else {

        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
                INSERT INTO tbl_adm (Nome, Email, Senha, Gerente)
                VALUES (?, ?, ?, ?)
            ");

        $stmt->bind_param("sssi", $nome, $email, $hash, $gerente);

        if ($stmt->execute()) {
            $success = "Administrador cadastrado com sucesso!";
        } else {
            $error = "Erro ao cadastrar: " . $stmt->error;
        }
    }

    $stmt->close();
}

?>
<?php include 'topo.php'; ?>
<link rel="stylesheet" href="Estilologin.css">

<div class='container'>
    <h2>Cadastro</h2>

    <!-- EXIBE MENSAGENS AQUI -->
    <?php if (!empty($success)): ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($success) ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <p style="color: red; font-weight: bold;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
    <!-- FIM DAS MENSAGENS -->

    <div class="login-box">
        <form action="" method="post">

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Nome:</label>
            <input type="text" name="nome" required>

            <label>Senha:</label>
            <input type="password" name="senha" required>

            <label>Repetir Senha:</label>
            <input type="password" name="rsenha" required>
            <div class="checkbox-row">
                <label for="checkbox">Gerente:</label>
                <input type="checkbox" id="checkbox" name="gerente">
            </div>


            <button type="submit">Cadastrar</button>
        </form>
    </div>
</div>

<script src="script.js"></script>