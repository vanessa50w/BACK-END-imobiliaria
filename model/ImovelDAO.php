<?php
require_once 'config/conexao.php';
require_once 'Model/Imovel.php';

class ImovelDAO {
    private $conexao;

    public function __construct() {
        $this->conexao = Conexao::getInstance();
    }

    private function existeImovel($descricao) {
        try {
            $sql = "SELECT COUNT(*) FROM imovel WHERE descricao = :descricao";
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':descricao', $descricao);
            
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo "Erro ao verificar imóvel: " . $e->getMessage();
            return false;
        }
    }

    private function existeImovelNoDia($descricao) {
        try {
            $hoje = date('Y-m-d');
            $sql = "SELECT COUNT(*) FROM imovel WHERE descricao = :descricao AND DATE(data_cadastro) = :hoje";
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':hoje', $hoje);
            
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            echo "Erro ao verificar imóvel no dia: " . $e->getMessage();
            return false;
        }
    }

    public function inserir(Imovel $imovel) {
        try {
            $descricao = $imovel->getDescricao();
            $valor = $imovel->getValor();

            // Verifica se já existe um imóvel com a mesma descrição
            if ($this->existeImovel($descricao)) {
                throw new Exception("Já existe um imóvel cadastrado com esta descrição.");
            }

            // Verifica se já existe um imóvel com a mesma descrição no mesmo dia
            if ($this->existeImovelNoDia($descricao)) {
                throw new Exception("Não é possível cadastrar o mesmo imóvel mais de uma vez no mesmo dia.");
            }

            $sql = "INSERT INTO imovel (descricao, valor, data_cadastro) VALUES (:descricao, :valor, CURRENT_TIMESTAMP)";
            $stmt = $this->conexao->prepare($sql);
            
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':valor', $valor);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao inserir: " . $e->getMessage();
            return false;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function atualizar(Imovel $imovel) {
        try {
            $sql = "UPDATE imovel SET descricao = :descricao, valor = :valor WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            
            $descricao = $imovel->getDescricao();
            $valor = $imovel->getValor();
            $id = $imovel->getId();
            
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':valor', $valor);
            $stmt->bindParam(':id', $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erro ao atualizar: " . $e->getMessage();
            return false;
        }
    }

    public function excluir($id) {
        try {
            $sql = "DELETE FROM imovel WHERE id = :id";
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
            $sql = "SELECT * FROM imovel WHERE id = :id";
            $stmt = $this->conexao->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return new Imovel($resultado['id'], $resultado['descricao'], $resultado['valor']);
            }
            return null;
        } catch (PDOException $e) {
            echo "Erro ao buscar: " . $e->getMessage();
            return null;
        }
    }

    public function listarTodos() {
        try {
            $sql = "SELECT * FROM imovel";
            $stmt = $this->conexao->prepare($sql);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $imoveis = array();
            foreach ($resultados as $resultado) {
                $imoveis[] = new Imovel($resultado['id'], $resultado['descricao'], $resultado['valor']);
            }
            return $imoveis;
        } catch (PDOException $e) {
            echo "Erro ao listar: " . $e->getMessage();
            return array();
        }
    }
}
?> 