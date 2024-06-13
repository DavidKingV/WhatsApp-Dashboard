<?php
require_once(__DIR__.'/../vendor/autoload.php');
include __DIR__.'/index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (isset($data['action']) && $data['action'] === 'sendExcelMessageMedia') {
        $message = $data['message'] ?? '';
        $phoneNumbers = $data['phoneNumbers'] ?? [];
        $countryCode = $data['countryCode'] ?? '';
        $path = $data['path'] ?? '';

        $control = new Excel();
        $responses = [];
        foreach ($phoneNumbers as $phone) {
            $fullPhone = $countryCode . $phone;
            $send = $control->sendWhatsAppMedia($message, $fullPhone, $path);
            $responses[] = array('phone' => $fullPhone, 'response' => $send);
        }

        echo json_encode(array('success' => true, 'message' => 'Mensajes enviados', 'responses' => $responses));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Acción no especificada o incorrecta.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Método no permitido.'));
}