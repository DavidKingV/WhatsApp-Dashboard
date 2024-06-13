<?php
require_once(__DIR__.'/../vendor/autoload.php');
include __DIR__.'/index.php';

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])){

    $action = $_POST['action'];

//casos de login
    switch($action){

        case 'sendWhatsApp':
            $message = $_POST['message'] ?? '';
            $phone = $_POST['phoneNumber'] ?? '';
            $countryCode = $_POST['countryCode'] ?? '';

            $phone = $countryCode.$phone;

            $control = new sendWhatsApp();
            $send = $control->send($message, $phone);

            echo ($send);

        break;
        
    }

}