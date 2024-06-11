<?php
namespace Vendor\Whatsappweb;

class userData{
        
        private $connection;
        
        public function __construct($connection){
            $this->connection = $connection;
        }
    
        public function GetCurrentUserData($userId){
            $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
    
            if($result->num_rows > 0){
                $row = $result->fetch_assoc();
                return array('success' => true, 'userName' => $row['user']);
            }else{
                return array('success' => false, 'message' => 'No se encontraron datos');
            }
        }

}