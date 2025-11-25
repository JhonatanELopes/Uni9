<?php
// OBRIGATÓRIO: Iniciar o buffer e a sessão no topo
ob_start();
session_start();

// 1. TENTA INCLUIR E OBTER A CONEXÃO
$conn = include_once 'connect.php';

// if (!($conn instanceof mysqli)) {
//     // Se a conexão falhar, exibe erro fatal (Verifique connect.php!)
//     //die("ERRO FATAL: Falha na conexão. Verifique se o MySQL está rodando e as credenciais.");
// }

$erro = "";

// 2. LÓGICA DE AUTENTICAÇÃO (SÓ EXECUTA APÓS O ENVIO DO FORMULÁRIO)
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Lê e sanitiza os dados do formulário
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '';
    $senha_digitada = trim($_POST['senha'] ?? ''); // Aplica trim()

    // Verifica se os campos não estão vazios
    if (empty($email) || empty($senha_digitada)) {
        $erro = "Preencha todos os campos.";
    } else {

        // 🎯 CORREÇÃO FINAL: Consulta SQL simples. Usamos o nome original da coluna 'Senha'.
        $stmt = $conn->prepare("SELECT ID_Adm, Nome, Senha, Gerente FROM tbl_adm WHERE Email = ?");

        if (!$stmt) {
            $erro = "ERRO SQL: Falha na preparação da consulta. Detalhe: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                
                // Agora, fetch_assoc() é usado, lendo a chave 'Senha'
                $usuario = $result->fetch_assoc(); 

                // 🚨 PONTO CRÍTICO: Se a verificação falhar, o código deve cair NO ELSE.
                if (password_verify($senha_digitada, $usuario['Senha'])) {

                    // --- INÍCIO DO BLOCO DE SUCESSO ---
                    $_SESSION['nome'] = $usuario['Nome'];
                    $_SESSION['id'] = $usuario['ID_Adm'];
                    $_SESSION['Gerente'] = $usuario['Gerente']; 

                    // Redirecionamento
                    if ($usuario['Gerente'] == '1') {
                        header("Location: pgAdms.php");
                    } else {
                        header("Location: pgUsuarios.php");
                    }

                    $stmt->close();
                    $conn->close();
                    exit; // ESSENCIAL
                    // --- FIM DO BLOCO DE SUCESSO ---

                } else {
                    $erro = "Email ou senha incorretos."; // FALHA NA SENHA
                }
            } else {
                $erro = "Email ou senha incorretos."; // USUÁRIO NÃO ENCONTRADO
            }

            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }
}

require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="EstiloLogin.css">
</head>

<body>
    <?php include 'topo.php'; ?>

    <div class="container">
        <h2>Login</h2><br>
        <div class="login-box">
            <!-- GARANTIR QUE action="login.php" -->
            <form method="POST" action="login.php">
                <?php if (!empty($erro)): ?>
                    <p
                        style="color: red; padding: 10px; border: 1px solid red; background-color: #ffeaea; border-radius: 5px;">
                        <?php echo $erro; ?>
                    </p>
                <?php endif; ?>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>

                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
    <script src="script.js"></script>


</body>

</html>
<?php
ob_end_flush();
?>