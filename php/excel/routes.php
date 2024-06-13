<?php
require_once(__DIR__.'/../vendor/autoload.php');
include __DIR__.'/index.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    $excel = new Excel();
    $data = $excel->uploadExcel($file);

    header('Content-Type: application/json');
    echo json_encode($data);
    
}else{
    echo json_encode(array("error" => "No se ha subido ningún archivo."));
}
