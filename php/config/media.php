<?php
require_once __DIR__ . '/../vendor/autoload.php'; 
use Vendor\Whatsappweb\loadEnv;

loadEnv::cargar();

// Directorio donde se guardará el archivo temporalmente
$tempDir = __DIR__ . '/../../public/temp/';

// Asegúrate de que el directorio exista
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
}

// Verifica si se ha enviado un archivo
if (isset($_FILES['whatsappFile'])) {
    $file = $_FILES['whatsappFile'];

    // Verifica si no hubo errores durante la carga
    if ($file['error'] === UPLOAD_ERR_OK) {
        $tmpName = $file['tmp_name'];
        $fileName = basename($file['name']);

        // Construye la ruta completa del archivo
        $filePath = $tempDir . $fileName;

        // Mueve el archivo al directorio temporal
        if (move_uploaded_file($tmpName, $filePath)) {
            $baseUrl = $_ENV['BASE_URL'] ?? 'http://localhost';
            $relativePath = str_replace(__DIR__ . '/../../public/', '', $filePath);
            $webUrl = $baseUrl . '/' . $relativePath;
            
            // Devuelve la ruta del archivo
            echo json_encode(["message" => "Archivo cargado correctamente.", "path" => $webUrl ]);
        } else {
            echo json_encode(["message" => "Error al mover el archivo."]);
        }
    } else {
        echo json_encode(["message" => "Error al cargar el archivo."]);
    }
} else {
    echo json_encode(["message" => "No se ha enviado ningún archivo."]);
}
?>
