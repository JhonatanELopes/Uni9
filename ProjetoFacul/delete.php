<?php
require_once 'function.php';
requireAdmin();

$conn = include 'connect.php'; 

$page_title = "Excluir Administrador";
$errors = [];
$adm = null;
$adm_id_to_delete = null;

// Se o formulário de confirmação foi enviado
if (isset($_POST['confirm_delete'])) {

    $adm_id_to_delete = filter_input(INPUT_POST, 'ID_Adm', FILTER_VALIDATE_INT);

    if ($adm_id_to_delete) {

        // Buscar o nome do Admin antes de excluir
        $stmt = $conn->prepare("SELECT Nome FROM tbl_adm WHERE ID_Adm = ?");
        $stmt->bind_param("i", $adm_id_to_delete);
        $stmt->execute();
        $result = $stmt->get_result();
        $adm_info = $result->fetch_assoc();
        $stmt->close();

        $deleted_adm_name = $adm_info ? $adm_info['Nome'] : "ID $adm_id_to_delete";

        // Excluir o Admin
        $delete = $conn->prepare("DELETE FROM tbl_adm WHERE ID_Adm = ?");
        $delete->bind_param("i", $adm_id_to_delete);
        $delete->execute();

        if ($delete->affected_rows > 0) {
            $_SESSION['flash_message'] = "Administrador '" . htmlspecialchars($deleted_adm_name) . "' excluído com sucesso!";
            header("Location: pgAdms.php");
            exit;
        } else {
            $errors[] = "Administrador não encontrado ou já excluído.";
        }

        $delete->close();

    } else {
        $errors[] = "ID de Administrador inválido.";
    }

} else {

    // ID recebido pela URL
    $adm_id_to_delete = filter_input(INPUT_GET, 'ID_Adm', FILTER_VALIDATE_INT);

    if ($adm_id_to_delete) {

        $stmt = $conn->prepare("SELECT ID_Adm, Nome FROM tbl_adm WHERE ID_Adm = ?");
        $stmt->bind_param("i", $adm_id_to_delete);
        $stmt->execute();
        $result = $stmt->get_result();
        $adm = $result->fetch_assoc();
        $stmt->close();

        if (!$adm) {
            $errors[] = "Administrador não encontrado.";
            $adm_id_to_delete = null;
        }

    } else {
        $errors[] = "Nenhum Administrador selecionado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - THE STOCK</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

<?php include 'topo.php'; ?>

<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($adm_id_to_delete && $adm): ?>
        <p>Tem certeza que deseja excluir o Administrador:
            <strong><?php echo htmlspecialchars($adm['Nome']); ?></strong>?</p>

        <form action="delete.php" method="POST">
            <input type="hidden" name="ID_Adm" value="<?php echo $adm['ID_Adm']; ?>">
            <button type="submit" name="confirm_delete">Sim, excluir</button>
            <a href="pgAdms.php" class="button-like-link">Cancelar</a>
        </form>

    <?php endif; ?>

</div>

</body>
</html>
