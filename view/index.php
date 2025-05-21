<?php
require_once 'Controller/ImobiliariaController.php';
$controller = new ImobiliariaController();

// Processar formulários
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        switch ($_POST['acao']) {
            case 'salvar_imovel':
                if (!empty($_POST['descricao']) && !empty($_POST['valor'])) {
                    $controller->salvarImovel(
                        trim($_POST['descricao']),
                        floatval($_POST['valor']),
                        !empty($_POST['id_imovel']) ? intval($_POST['id_imovel']) : null
                    );
                }
                break;
            case 'salvar_reserva':
                if (!empty($_POST['nome_cliente']) && !empty($_POST['data_reserva']) && !empty($_POST['id_imovel'])) {
                    $controller->salvarReserva(
                        trim($_POST['nome_cliente']),
                        $_POST['data_reserva'],
                        intval($_POST['id_imovel']),
                        !empty($_POST['id_reserva']) ? intval($_POST['id_reserva']) : null
                    );
                }
                break;
            case 'excluir_imovel':
                if (!empty($_POST['id'])) {
                    $controller->excluirImovel(intval($_POST['id']));
                }
                break;
            case 'excluir_reserva':
                if (!empty($_POST['id'])) {
                    $controller->excluirReserva(intval($_POST['id']));
                }
                break;
        }
    }
}

$imoveis = $controller->listarImoveis();
$reservas = $controller->listarReservas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Imobiliário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-tabs .nav-link.active {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .table th {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 1rem;
        }
        .btn {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #bb2d3b;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .btn-sm {
            padding: 0.25rem 1rem;
        }
    </style>
</head>
<body>
    <div class="container mt-3">
        <h2 class="mb-3">Sistema Imobiliário</h2>
        
        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="imoveis-tab" data-bs-toggle="tab" data-bs-target="#imoveis" type="button" role="tab">Imóveis</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reservas-tab" data-bs-toggle="tab" data-bs-target="#reservas" type="button" role="tab">Reservas</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- Aba de Imóveis -->
            <div class="tab-pane fade show active" id="imoveis" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Cadastro de Imóvel</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="acao" value="salvar_imovel">
                                    <input type="hidden" name="id_imovel" id="id_imovel">
                                    <div class="form-group">
                                        <label for="descricao">Descrição</label>
                                        <input type="text" class="form-control" id="descricao" name="descricao" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="valor">Valor</label>
                                        <input type="number" class="form-control" id="valor" name="valor" step="0.01" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-pill">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Imóveis Cadastrados</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Descrição</th>
                                                <th>Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($imoveis as $imovel): ?>
                                                <tr>
                                                    <td><?php echo $imovel->getId(); ?></td>
                                                    <td><?php echo $imovel->getDescricao(); ?></td>
                                                    <td>R$ <?php echo number_format($imovel->getValor(), 2, ',', '.'); ?></td>
                                                    <td>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="acao" value="excluir_imovel">
                                                            <input type="hidden" name="id" value="<?php echo $imovel->getId(); ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill">Excluir</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aba de Reservas -->
            <div class="tab-pane fade" id="reservas" role="tabpanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Nova Reserva</h5>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="acao" value="salvar_reserva">
                                    <input type="hidden" name="id_reserva" id="id_reserva">
                                    <div class="form-group">
                                        <label for="nome_cliente">Nome do Cliente</label>
                                        <input type="text" class="form-control" id="nome_cliente" name="nome_cliente" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="data_reserva">Data</label>
                                        <input type="date" class="form-control" id="data_reserva" name="data_reserva" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_imovel">Imóvel</label>
                                        <select class="form-control" id="id_imovel" name="id_imovel" required>
                                            <?php foreach ($imoveis as $imovel): ?>
                                                <option value="<?php echo $imovel->getId(); ?>">
                                                    <?php echo $imovel->getDescricao(); ?> - R$ <?php echo number_format($imovel->getValor(), 2, ',', '.'); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-pill">Salvar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Reservas Cadastradas</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Cliente</th>
                                                <th>Data</th>
                                                <th>Imóvel</th>
                                                <th>Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($reservas as $reserva): ?>
                                                <tr>
                                                    <td><?php echo $reserva['id']; ?></td>
                                                    <td><?php echo $reserva['nome_cliente']; ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($reserva['data_reserva'])); ?></td>
                                                    <td><?php echo $reserva['descricao_imovel']; ?></td>
                                                    <td>R$ <?php echo number_format($reserva['valor_imovel'], 2, ',', '.'); ?></td>
                                                    <td>
                                                        <form method="POST" style="display: inline;">
                                                            <input type="hidden" name="acao" value="excluir_reserva">
                                                            <input type="hidden" name="id" value="<?php echo $reserva['id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-pill">Excluir</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 