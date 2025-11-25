<?php
$conn = include 'connect.php';

$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : "";

// Base SQL
$sql = "SELECT ID_Usuario AS id, Nome, Email, Telefone, CPF, DataNasc, Modalidade, Curso FROM tbl_Usuario";
$params = [];
$types = "";

// Filtro de busca
if (!empty($searchTerm)) {
    $sql .= " WHERE Nome LIKE ? OR Email LIKE ?";
    $params[] = "%$searchTerm%";
    $params[] = "%$searchTerm%";
    $types = "ss";
}

// Executa consulta
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$usuarios = $result->fetch_all(MYSQLI_ASSOC);
$productCount = count($usuarios);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="EstiloLogin.css">
    <title>Usuários</title>
</head>

<body>

    <?php include 'topo.php'; ?>
    <?php include 'function.php'; ?>

    <div class="products-container">

        <!-- Formulário de busca -->
        <form action="pgUsuarios.php" method="POST" class="search-form">
            <input type="text" name="search" placeholder="Buscar usuários"
                value="<?php echo htmlspecialchars($searchTerm); ?>">
            <button type="submit">Buscar</button>
        </form>

        <div class="product-count">
            Usuários encontrados: <?php echo $productCount; ?>
        </div>

        <!-- Tabela -->
        <div class="table-responsive">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>CPF</th>
                        <th>Data de Nascimento</th>
                        <th>Curso</th>
                        <th>Modalidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($productCount > 0): ?>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($usuario['Nome']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['Email']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['Telefone']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['CPF']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['DataNasc']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['Modalidade']); ?></td>
                                <td><?php echo htmlspecialchars($usuario['Curso']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">Nenhum usuário encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
        <?php if (isset($_SESSION['Gerente']) && $_SESSION['Gerente'] === 1): ?>
            <div class="botao">
                <form action="pgAdms.php">
                    <button>Gerenciar Administradores</button>
                </form>
            </div>
        <?php endif; ?>

</body>

</html>