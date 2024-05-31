<?php
include_once '../config/database.php';
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();
// base d'un nouveau mot de passe
$newPassword = "nouveauMdp" . date("m.d.y");

//
// lecture des données en entrée
$data = json_decode(file_get_contents("php://input"),true);
$email = $data['email'];
$erreurSql=null;
http_response_code(200);
try {
    //
    // encodage du password

    $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);


    //
    // execution de l'ordre SQL
    $table_name = 'users';
    $query = "UPDATE " . $table_name . " SET password = :newpassword where email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':newpassword',$password_hash);
    $stmt->bindParam(':email', $email);

    if($stmt->execute()){
      // envoi d'un message
      mail('jerome.vidaillac@enac.fr','Mise à jour mot de passe','Votre nouveau mot de passe est : '.$newPassword);

      http_response_code(200);
      echo json_encode(array("status"=>true,"message"=>'maj MdP ok'));
    } else {
      http_response_code(200);
      echo json_encode(array("status"=>false,"message"=>utf8_encode($stmt->errorInfo()[2])));
    }
} catch (Exception $e){
    // l'identiifcation du user a échouée.
    //http_response_code(401);
     echo json_encode(array("status" => false,"message"=>$e->getMessage()));

}
?>
