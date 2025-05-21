<?php
class Imovel {
    private $id;
    private $descricao;
    private $valor;

    public function __construct($id = null, $descricao = null, $valor = null) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->valor = $valor;
    }

    public function getId() {
        return $this->id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }
}
?> 