<?php
class Reserva{
private $id;
private $id_imovel;
private $nome_cliente;
private $data_reserva;

    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }


    public function getIdImovel() {
        return $this->id_imovel;
    }
    public function setIdImovel($id_imovel) {
        $this->id_imovel = $id_imovel;
    }


    public function getNomeCliente() {
        return $this->nome_cliente;
    }
    public function setNomeCliente($nome_cliente) {
        $this->nome_cliente = $nome_cliente;
    }


    public function getDataReserva() {
        return $this->data_reserva;
    }
    public function setDataReserva($data_reserva) {
        $this->data_reserva = $data_reserva;
    }
}


?>