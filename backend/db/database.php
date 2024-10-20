<?php

class Database {
    private $host = "localhost";
    private $db_name = "elitStyle";
    private $username = "root";  
    private $password = "";      
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Conexão falhou: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8");
        } catch(Exception $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }

        return $this->conn;
    }
}
