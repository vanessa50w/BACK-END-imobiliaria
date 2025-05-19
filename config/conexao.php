<?php

class Conexao {
    private static $instance;

    public static function getConxao(){

        if (!isset(self::$instance)){
            self::$instance = new \PDO('pgsql:host=localhost;dbname=imobiliaria', 'root', '');
        }
        return self::$instance;
    }
}