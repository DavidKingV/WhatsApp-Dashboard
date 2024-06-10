<?php
namespace Vendor\Whatsappweb;

class DBConnection{
    
    private $connection;
    
    public function __construct(){
        loadEnv::cargar();

        $host=$_ENV['DB_HOST'];
        $user=$_ENV['DB_USER'];
        $password=$_ENV['DB_PASSWORD'];
        $db=$_ENV['DB_NAME'];

        $this->connection = new \mysqli($host,$user,$password,$db);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        if (!$this->connection->set_charset("utf8")) {
            die("Error al cargar el conjunto de caracteres utf8: " . $this->connection->error);
        }
    }


    public function getConnection(){
        return $this->connection;
    }

}
?>

