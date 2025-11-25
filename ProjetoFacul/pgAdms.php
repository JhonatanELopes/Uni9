<?php
$conn = include 'connect.php';

$searchTerm = isset($_POST['search']) ? trim($_POST['search']) : "";

// Base SQL
$sql = "SELECT ID_Adm AS id, Nome, Email FROM tbl_adm";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="EstiloLogin.css">
    <title>Document</title>
</head>
<body>
    <?php include 'topo.php'; ?>
    <?php include 'function.php'?>
    <!-- Busca de Usuários -->
    <div class="products-container">
        <form action="pgAdms.php" method="POST" id="form" class="search-form">
            <input type="text" name="search" placeholder="Buscar Administradores..."
                   value=<?php echo htmlspecialchars($searchTerm)?>>
            <button type="submit">Buscar</button>
        </form>
        
        <!--Contador de Usuários-->
        <div class="product-count">
            Administradores Encontrados:<?php echo $productCount; ?>
        </div>
        <!--Tabela de Usuários --> 
       <table class="products-table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($productCount > 0): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['Nome']) ?></td>
                    <td><?= htmlspecialchars($usuario['Email']) ?></td>

                    <?php if (isLoggedIn() && isAdmin()): ?>
                       <td class="actions">
    <a href="edit.php?ID_Adm=<?= $usuario['id'] ?>">Editar</a>
    <a href="delete.php?ID_Adm=<?= $usuario['id'] ?>"
       onclick="return confirm('Tem certeza que deseja excluir este Administrador?');">
       Excluir
    </a>


</td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Nenhum Administrador encontrado.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>



    </div>
    <div class="botao">
        <form action="Cadastro.php">
            <button>Cadastrar Administradores</button>
        </form>
    </div>
</body>
</html>