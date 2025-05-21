<?php
require_once 'config/Conexao.php';

class Reserva {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getInstancia()->getConexao();
    }

    public function inserirReserva($dados) {
        $sql = "INSERT INTO reservas (nome, email, item) VALUES (:nome, :email, :item)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':item' => $dados['item']
        ]);
    }

    public function listarReservas() {
        $sql = "SELECT * FROM reservas ORDER BY id DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function editarReserva($id, $dados) {
        $sql = "UPDATE reservas SET nome = :nome, email = :email, item = :item WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':nome' => $dados['nome'],
            ':email' => $dados['email'],
            ':item' => $dados['item'],
            ':id' => $id
        ]);
    }

    public function deletarReserva($id) {
        $sql = "DELETE FROM reservas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function buscarReserva($id) {
        $sql = "SELECT * FROM reservas WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
}
}
?>