<?php
namespace Vendor\Whatsappweb;

class loadEnv{

    public static function cargar(){
        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    }

}

?>