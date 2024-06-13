<?php
require_once(__DIR__.'/../vendor/autoload.php');
session_start();

use \Vendor\Whatsappweb\auth;
use \Vendor\Whatsappweb\loadEnv;
use PhpOffice\PhpSpreadsheet\IOFactory;

loadEnv::cargar();

$url = $_ENV['URL_WHATSAPP'] ?? NULL;
$token = $_ENV['TOKEN_WHATSAPP'] ?? NULL;

class Excel{

    public static function uploadExcel($file){
        try {
            $fileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file);
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($fileType);
            $spreadsheet = $reader->load($file);
            
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            array_shift($data);
            
            $filteredData = array();
            foreach ($data as $row) {
                if (!empty($row[0]) && !empty($row[1])) { // Verificar que no estén vacíos
                    $nombre = $row[0]; // Suponiendo que la columna de nombre es la primera
                    $telefono = $row[1]; // Suponiendo que la columna de teléfono es la segunda
                    $filteredData[] = array('nombres' => $nombre, 'telefonos' => $telefono);
                }
            }
    
            if(empty($filteredData)) {
                throw new Exception('El archivo Excel no contiene datos válidos.');
            }
    
            return ['success' => true, 'data' => $filteredData, 'message' => 'Datos cargados correctamente'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }

    public function sendWhatsApp($message, $phone){
        global $url, $token;

        $VerifySession = auth::verify($_COOKIE['auth'] ?? NULL);

        if (!$VerifySession['success']) {
            return array('success' => false, 'message' => 'No tiene permisos para realizar esta acción');
        } else {

            $data = array(
                'from' => $phone . '@c.us',
                'text' => $message
            );

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/json\r\n" .
                                "Authorization: Bearer " . $token . "\r\n",
                    'method' => 'POST',
                    'content' => json_encode($data)
                )
            );

            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            return $result; 
        }
    }

    public function sendWhatsAppMedia($message, $phone, $path){
        global $token;

        $url = $_ENV['URL_WHATSAPP_MEDIA'] ?? NULL;

        $VerifySession = auth::verify($_COOKIE['auth'] ?? NULL);

        if (!$VerifySession['success']) {
            return array('success' => false, 'message' => 'No tiene permisos para realizar esta acción');
        } else {

            $data = array(
                'from' => $phone . '@c.us',
                'text' => $message,
                'file' => $path
            );

            $options = array(
                'http' => array(
                    'header' => "Content-type: application/json\r\n" .
                                "Authorization: Bearer " . $token . "\r\n",
                    'method' => 'POST',
                    'content' => json_encode($data)
                )
            );

            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);

            return $result; 
        }
    }

}

?>
