<?php
require_once 'config/conexao.php';
require_once 'Model/Reserva.php';

class ReservaDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getInstance();
    }

    private function validarDatas($data_inicio, $data_termino) {
        if (strtotime($data_termino) < strtotime($data_inicio)) {
            throw new Exception("A data de término não pode ser anterior à data de início.");
        }
    }

    private function existeReserva($nome_cliente, $data_reserva, $id_imovel, $id = null) {
        try {
            $sql = "SELECT COUNT(*) FROM reserva 
                WHERE nome_cliente = :nome_cliente 
                AND data_reserva = :data_reserva";
            
            if ($id !== null) {
                $sql .= " AND id != :id";
            }
            
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':nome_cliente', $nome_cliente);
            $stmt->bindParam(':data_reserva', $data_reserva);
            
            if ($id !== null) {
                $stmt->bindParam(':id', $id);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo "Erro ao verificar reserva: " . $e->getMessage();
            return false;
        }
    }

    private function existeReservaImovel($id_imovel, $data_reserva, $id = null) {
        try {
            $sql = "SELECT COUNT(*) FROM reserva 
                WHERE id_imovel = :id_imovel 
                AND data_reserva = :data_reserva";
            
            if ($id !== null) {
                $sql .= " AND id != :id";
            }
            
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':id_imovel', $id_imovel);
            $stmt->bindParam(':data_reserva', $data_reserva);
            
            if ($id !== null) {
                $stmt->bindParam(':id', $id);
            }
            
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo "Erro ao verificar reserva do imóvel: " . $e->getMessage();
            return false;
        }
    }

    public function inserir(Reserva $reserva) {
        try {
            $nome_cliente = $reserva->getNomeCliente();
            $data_reserva = $reserva->getDataReserva();
            $data_termino = $reserva->getDataTermino();
            $id_imovel = $reserva->getIdImovel();

            // Valida as datas
            $this->validarDatas($data_reserva, $data_termino);

            // Verifica se o cliente já tem uma reserva na mesma data
            if ($this->existeReserva($nome_cliente, $data_reserva, $id_imovel)) {
                throw new Exception("Este cliente já possui uma reserva para esta data.");
            }

            // Verifica se o imóvel já está reservado para esta data
            if ($this->existeReservaImovel($id_imovel, $data_reserva)) {
                throw new Exception("Este imóvel já está reservado para esta data.");
            }

            $sql = "INSERT INTO reserva (nome_cliente, data_reserva, data_termino, id_imovel) 
                    VALUES (:nome_cliente, :data_reserva, :data_termino, :id_imovel)";
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':nome_cliente', $nome_cliente);
            $stmt->bindParam(':data_reserva', $data_reserva);
            $stmt->bindParam(':data_termino', $data_termino);
            $stmt->bindParam(':id_imovel', $id_imovel);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function atualizar(Reserva $reserva) {
        try {
            $nome_cliente = $reserva->getNomeCliente();
            $data_reserva = $reserva->getDataReserva();
            $data_termino = $reserva->getDataTermino();
            $id_imovel = $reserva->getIdImovel();
            $id = $reserva->getId();

            // Valida as datas
            $this->validarDatas($data_reserva, $data_termino);

            // Verifica se o cliente já tem uma reserva na mesma data (excluindo a reserva atual)
            if ($this->existeReserva($nome_cliente, $data_reserva, $id_imovel, $id)) {
                throw new Exception("Este cliente já possui uma reserva para esta data.");
            }

            // Verifica se o imóvel já está reservado para esta data (excluindo a reserva atual)
            if ($this->existeReservaImovel($id_imovel, $data_reserva, $id)) {
                throw new Exception("Este imóvel já está reservado para esta data.");
            }

            $sql = "UPDATE reserva 
                    SET nome_cliente = :nome_cliente, 
                        data_reserva = :data_reserva, 
                        data_termino = :data_termino, 
                        id_imovel = :id_imovel 
                    WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':nome_cliente', $nome_cliente);
            $stmt->bindParam(':data_reserva', $data_reserva);
            $stmt->bindParam(':data_termino', $data_termino);
            $stmt->bindParam(':id_imovel', $id_imovel);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function excluir($id) {
        try {
            $sql = "DELETE FROM reserva WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao excluir: " . $e->getMessage();
            return false;
        }
    }

    public function buscarPorId($id) {
        try {
            $sql = "SELECT * FROM reserva WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return new Reserva(
                    $resultado['id'], 
                    $resultado['nome_cliente'], 
                    $resultado['data_reserva'],
                    $resultado['data_termino'],
                    $resultado['id_imovel']
                );
            }
            return null;
        } catch (PDOException $e) {
            echo "Erro ao buscar: " . $e->getMessage();
            return null;
        }
    }

    public function listarTodos() {
        try {
            $sql = "SELECT r.*, i.descricao as descricao_imovel, i.valor as valor_imovel 
                    FROM reserva r 
                    JOIN imovel i ON r.id_imovel = i.id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar: " . $e->getMessage();
            return array();
        }
    }
}
?> 