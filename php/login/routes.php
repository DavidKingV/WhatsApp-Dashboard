<?php
require_once(__DIR__.'/../vendor/autoload.php');
include __DIR__.'/index.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use \Vendor\Whatsappweb\loadEnv;

loadEnv::cargar();

$secretkey = $_ENV['SECRET_KEY'] ?? NULL;
$lifeTime = $_ENV['LIFE_TIME'] ?? NULL;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])){

    $action = $_POST['action'];

//casos de login
    switch($action){
        case 'login':
            $user = $data['user'] ?? '';
            $pass = $data['password'] ?? '';

            $dbConnection = new \Vendor\Whatsappweb\DBConnection();
            $control = new LoginControl($dbConnection);
            $login = $control->indexLogin($user, $pass);

            if($login['success']){
                //se crea una sesion
                session_set_cookie_params($lifeTime);
                session_start();
                
                $_SESSION['userId'] = $login['userId'];

                //se crea un token utilizando jwt
                $payload = [
                    "userId" => $login['userId'],
                    "userName" => $user
                ];

                $jwt = JWT::encode($payload, $secretkey, 'HS256');

                //se genera un cookie con el token
                setcookie("auth", $jwt, time() + ($lifeTime), "/", "", 1, 1);

                header('Content-Type: application/json');
                echo json_encode($login);

            }elseif(!$login['success']){
                header('Content-Type: application/json');
                echo json_encode($login);
            }else{
                header('Content-Type: application/json');
                echo json_encode(array("success" => false, "message" => "Error en la consulta de la base de datos"));
            }
        break;

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        case 'logout':
            $control = new LoginControl($con);
            $control -> logout();
            header('Content-Type: application/json');
            echo json_encode(array("status" => "success", "message" => "Sesión cerrada"));
        break;
        default:
        // code...
        break;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {

    switch ($_GET['action']) {

        case 'get_name_clients':
            $control = new ClientsControl($con);
            $clients = $control -> GetNameCostumers();

            header('Content-Type: application/json');
            echo json_encode($clients);
        break;

    }
    
}