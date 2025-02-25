<?php

class Database {
    private $host = 'localhost'; // Host do banco de dados
    private $db_name = 'academia'; // Nome do banco de dados
    private $username = 'root'; // Usuário do banco de dados
    private $password = ''; // Senha do banco de dados (vazia neste caso)
    private $conn;

    // Método para estabelecer a conexão com o banco de dados
    public function getConnection() {
        $this->conn = null;

        try {
            // Configura a conexão PDO
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Define o modo de erro para exceções
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $this->conn;
        } catch (PDOException $e) {
            // Em caso de erro, exibe a mensagem
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
            exit();
        }
    }
}