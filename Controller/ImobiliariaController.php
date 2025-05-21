<?php
require_once 'Model/Imovel.php';
require_once 'Model/ImovelDAO.php';
require_once 'Model/Reserva.php';
require_once 'Model/ReservaDAO.php';

class ImobiliariaController {
    private $imovelDAO;
    private $reservaDAO;

    public function __construct() {
        $this->imovelDAO = new ImovelDAO();
        $this->reservaDAO = new ReservaDAO();
    }

    public function listarImoveis() {
        return $this->imovelDAO->listarTodos();
    }

    public function listarReservas() {
        return $this->reservaDAO->listarTodos();
    }

    public function salvarImovel($descricao, $valor, $id = null) {
        $imovel = new Imovel($id, $descricao, $valor);
        if ($id) {
            return $this->imovelDAO->atualizar($imovel);
        } else {
            return $this->imovelDAO->inserir($imovel);
        }
    }

    public function salvarReserva($nome_cliente, $data_reserva, $id_imovel, $id = null) {
        $reserva = new Reserva($id, $nome_cliente, $data_reserva, $id_imovel);
        if ($id) {
            return $this->reservaDAO->atualizar($reserva);
        } else {
            return $this->reservaDAO->inserir($reserva);
        }
    }

    public function excluirImovel($id) {
        return $this->imovelDAO->excluir($id);
    }

    public function excluirReserva($id) {
        return $this->reservaDAO->excluir($id);
    }

    public function buscarImovel($id) {
        return $this->imovelDAO->buscarPorId($id);
    }

    public function buscarReserva($id) {
        return $this->reservaDAO->buscarPorId($id);
    }
}
?> 