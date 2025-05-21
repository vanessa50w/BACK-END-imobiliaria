<?php
class Conexao {
    private static $instancia;
    private $conexao;

    private function __construct() {
        try {
            $this->conexao = new PDO("mysql:host=localhost;dbname=imobiliaria", "usuario", "senha");
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexao->exec("SET NAMES utf8");
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public static function getInstancia() {
        if (!isset(self::$instancia)) {
            self::$instancia = new Conexao();
        }
        return self::$instancia;
    }

    public function getConexao() {
        return $this->conexao;
}
}
?>