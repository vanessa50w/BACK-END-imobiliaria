<?php
require_once 'config/conexao.php';
require_once 'model/Reserva.php';
require_once 'model/ReservaDAO.php';

$dao = new ReservaDAO();

// --- Criar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'cadastrar') {
    $reserva = new Reserva();
    $reserva->setIdImovel($_POST['id_imovel']);
    $reserva->setNomeCliente($_POST['nome_cliente']);
    $reserva->setDataReserva($_POST['data_reserva']);
    $dao->createReserva($reserva);
    header("Location: index.php");
    exit;
}

// --- Atualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'editar') {
    $reserva = new Reserva();
    $reserva->setId($_POST['id']);
    $reserva->setIdImovel($_POST['id_imovel']);
    $reserva->setNomeCliente($_POST['nome_cliente']);
    $reserva->setDataReserva($_POST['data_reserva']);
    $dao->updateReserva($reserva);
    header("Location: index.php");
    exit;
}

// --- Excluir
if (isset($_GET['excluir'])) {
    $dao->deleteReserva($_GET['excluir']);
    header("Location: index.php");
    exit;
}

// --- Buscar para editar
$reservaParaEditar = null;
if (isset($_GET['editar'])) {
    $reservas = $dao->readReserva();
    foreach ($reservas as $r) {
        if ($r->getId() == $_GET['editar']) {
            $reservaParaEditar = $r;
            break;
        }
    }
}

// --- Listar todas
$reservas = $dao->readReserva();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>CRUD Reservas - Imobiliária</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: auto; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        form { margin-top: 20px; }
        input, select, button { display: block; margin-top: 10px; padding: 8px; width: 100%; }
        h1, h2 { text-align: center; }
        a { text-decoration: none; margin-right: 10px; }
    </style>
</head>
<body>

    <h1><?= $reservaParaEditar ? 'Editar Reserva' : 'Nova Reserva' ?></h1>

    <form method="POST" action="index.php">
        <input type="hidden" name="acao" value="<?= $reservaParaEditar ? 'editar' : 'cadastrar' ?>">
        <?php if ($reservaParaEditar): ?>
            <input type="hidden" name="id" value="<?= $reservaParaEditar->getId() ?>">
        <?php endif; ?>

        <label>Imóvel:</label>
        <select name="id_imovel" required>
            <option value="">Selecione</option>
            <?php
            $imoveis = [
                1 => "Rua A, 123 (Locação)",
                2 => "Rua B, 456 (Compra)",
                3 => "Rua C, 789 (Locação)",
                4 => "Rua D, 321 (Compra)"
            ];
            foreach ($imoveis as $id => $desc):
                $selected = $reservaParaEditar && $reservaParaEditar->getIdImovel() == $id ? 'selected' : '';
                echo "<option value=\"$id\" $selected>$desc</option>";
            endforeach;
            ?>
        </select>

        <label>Nome do Cliente:</label>
        <input type="text" name="nome_cliente" required value="<?= $reservaParaEditar ? $reservaParaEditar->getNomeCliente() : '' ?>">

        <label>Data da Reserva:</label>
        <input type="date" name="data_reserva" required value="<?= $reservaParaEditar ? $reservaParaEditar->getDataReserva() : '' ?>">

        <button type="submit"><?= $reservaParaEditar ? 'Atualizar Reserva' : 'Fazer Reserva' ?></button>
    </form>

    <hr>

    <h2>Reservas Realizadas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imóvel</th>
                <th>Cliente</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($reservas) === 0): ?>
            <tr><td colspan="5">Nenhuma reserva cadastrada.</td></tr>
        <?php else: ?>
            <?php foreach ($reservas as $res): ?>
                <tr>
                    <td><?= $res->getId() ?></td>
                    <td><?= $imoveis[$res->getIdImovel()] ?? "ID " . $res->getIdImovel() ?></td>
                    <td><?= $res->getNomeCliente() ?></td>
                    <td><?= $res->getDataReserva() ?></td>
                    <td>
                        <a href="?editar=<?= $res->getId() ?>">Editar</a>
                        <a href="?excluir=<?= $res->getId() ?>" onclick="return confirm('Excluir reserva?')">Excluir</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
