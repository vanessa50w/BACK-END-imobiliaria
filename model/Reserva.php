<?php
class Reserva {
    private $id;
    private $nome_cliente;
    private $data_reserva;
    private $data_termino;
    private $id_imovel;

    public function __construct($id = null, $nome_cliente = null, $data_reserva = null, $data_termino = null, $id_imovel = null) {
        $this->id = $id;
        $this->nome_cliente = $nome_cliente;
        $this->data_reserva = $data_reserva;
        $this->data_termino = $data_termino;
        $this->id_imovel = $id_imovel;
    }

    public function getId() {
        return $this->id;
    }

    public function getNomeCliente() {
        return $this->nome_cliente;
    }

    public function getDataReserva() {
        return $this->data_reserva;
    }

    public function getDataTermino() {
        return $this->data_termino;
    }

    public function getIdImovel() {
        return $this->id_imovel;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNomeCliente($nome_cliente) {
        $this->nome_cliente = $nome_cliente;
    }

    public function setDataReserva($data_reserva) {
        $this->data_reserva = $data_reserva;
    }

    public function setDataTermino($data_termino) {
        $this->data_termino = $data_termino;
    }

    public function setIdImovel($id_imovel) {
        $this->id_imovel = $id_imovel;
    }
}
?> 