<?php
require_once(__DIR__.'/../vendor/autoload.php');
session_start();

use \Vendor\Whatsappweb\auth;
use \Vendor\Whatsappweb\loadEnv;

loadEnv::cargar();

$url = $_ENV['URL_WHATSAPP'] ?? NULL;
$token = $_ENV['TOKEN_WHATSAPP'] ?? NULL;

class sendWhatsApp{

    public function send($message, $phone){
        global $url, $token;

        $VerifySession = auth::verify($_COOKIE['auth'] ?? NULL);

        if(!$VerifySession['success']){
            return array('success' => false, 'message' => 'No tiene permisos para realizar esta acción');
        }else{
            $userId = $VerifySession['userId'];

            $data = array(
                'from' => $phone.'@c.us',
                'text' => $message
            );
    
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/json\r\n".
                                 "Authorization: Bearer ".$token."\r\n",
                    'method'  => 'POST',
                    'content' => json_encode($data)
                )
            );
    
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
    
            return $result;

        }
    
    }

}

