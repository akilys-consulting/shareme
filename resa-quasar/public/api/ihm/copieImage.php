<?php
include_once '../config/database.php';
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$response = array("status" => true,"message"=>'OK');
$dataImage = json_decode($_POST["image"]);
$dossier = $_POST["repertoireImg"];
$action = $_POST["action"];
// formatage du fichier destination
$fichierDestination = $_SERVER['DOCUMENT_ROOT'].'/../'.$dossier.$dataImage->fichier.'.'.$dataImage->type;
$dataImage->data = str_replace('data:image/jpeg;base64,', '', $dataImage->data);
$data = base64_decode($dataImage->data);

// si le fichier existe on le supprime
switch ( $action) {
  case 'add':
    $response = array("status" => false,"message"=>'add ok');
    if (!file_put_contents($fichierDestination, $data)) {
      $response = array("status" => false,"message"=>'IMEC');
    }
    break;
    case 'del':
      $response = array("status" => false,"message"=>'del ok');
      if (file_exists($fichierDestination)) {
        if ( !unlink($fichierDestination)) {
          $response = array("status" => false,"message"=>'IMED');
        }
      } else {
        $response = array("status" => false,"message"=>'IMNI');
      }
    break;
    default:
      $response = array("status" => false,"message"=>'action inconnue');
    break;
}

echo json_encode($response);

?>