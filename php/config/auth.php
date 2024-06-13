<?php
namespace Vendor\Whatsappweb;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class auth {

    public static function verify($jwt){
        loadEnv::cargar();
        
        $secretKey = $_ENV['SECRET_KEY'] ?? NULL;

        if(isset($_SESSION['userId'])&&isset($_COOKIE['auth'])){
            try {
                $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));     
                return array('success' => true, 'userId' => $decoded->userId);
            } catch (\Exception $e) {
                return array('success' => false, 'message' => $e->getMessage());
            }
        }else{
            return array('success' => false, 'message' => 'No tiene permisos para realizar esta acción');
        }
    }

}