<?php

class Conexao {
    private static $instance = null;
    private $conexao;

    private function __construct() {
        try {
            $this->conexao = new PDO(
                "pgsql:host=localhost;dbname=imobiliaria",
                "postgres",
                "4321",
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
        } catch (\PDOException $e) {
            throw new \Exception("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Conexao();
        }
        return self::$instance->conexao;
    }
} 