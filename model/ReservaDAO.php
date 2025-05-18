<?php
require_once 'Conexao.php';
require_once 'reserva.php';

class ReservaDAO{
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::getInstance();
        }
        private function logError($message) {
            error_log("[ReservaDAO] Erro: " . $message);
        }

    public function CreateReserva(Reserva $reserva) {
        $sql = "INSERT INTO reservas (id_imovel, nome_cliente,data_reserva) VALUES(?, ?, ?)";
        $query = $this->pdo->prepare($sql);
        return $query->execute([$reserva->getIdImovel(),
         $reserva->getNomeCliente(), 
         $reserva->getDataReserva()
         ]);
    }  

    public function ReadReserva() {
        $stmt = $this->pdo->query("SELECT * FROM reservas");
        $reservas = [];
    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reserva = new Reserva();
            $reserva->setId($row['id']);
            $reserva->setNomeCliente($row['nome_cliente']);
            $reserva->setDataReserva($row['data_reserva']);
            $reservas[] = $reserva;
        }
    
        return $reservas;
    }



    public function UpdateReserva(Reserva $reserva) {
    try {
        $sql = "UPDATE reservas SET nome_cliente = ?, data_reserva = ?, id_imovel = ? WHERE id = ?";
        $query = $this->pdo->prepare($sql);
        $success = $query->execute([
            $reserva->getNomeCliente(),
            $reserva->getDataReserva(),
            $reserva->getIdImovel(),
            $reserva->getId()
        ]);
        

        return $success && $query->rowCount() > 0;
    } catch (PDOException $e) {
        $this->logError("Erro ao atualizar reserva: " . $e->getMessage());
        return false;
    }
}

public function DeleteReserva($id) {
    try {
        if (!is_numeric($id)) {
            return false;
        }
        
        $query = $this->pdo->prepare("DELETE FROM reservas WHERE id = ?");
        $success = $query->execute([$id]);
        

        return $success && $query->rowCount() > 0;
    } catch (PDOException $e) {
        $this->logError("Erro ao deletar reserva: " . $e->getMessage());
        return false;
    }
}}




?>