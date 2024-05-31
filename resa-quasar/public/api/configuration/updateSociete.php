<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


http_response_code(200);
$insert=true;

try {
    $databaseService = new DatabaseService();

        //
    // check du token
    $response = $databaseService->checkToken();
    if ( $response["status"]  ) {
        $conn = $databaseService->getConnection();
        //
        // lecture des données d'entrées
        $data = json_decode($_POST['data'],true);


        //
        // préparation de la requête
        $query = 'INSERT INTO societes (id, nom, adresse, email_contact,actif,avatar) VALUES(:id, :nom, :adresse, :email_contact, :actif, :avatar) ON DUPLICATE KEY UPDATE nom = :nom, adresse= :adresse ,email_contact = :email_contact, actif= :actif, avatar= :avatar';
        $stmt = $conn->prepare($query);
        $memoResult = true;

        $adresse = json_encode($data['adresse']);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':adresse',  $adresse);
        $stmt->bindParam(':email_contact', $data['email_contact']);
            // gestion du boolean actif
        $value = $data['actif']?1:0;
        $stmt->bindParam(':actif', $value);
        $stmt->bindParam(':avatar', $data['avatar']);

        if ($_FILES){
          $fileName = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
          //
          // get informations about picture
          $info = pathinfo($_FILES["image"]["name"]);

          //
          // define the image path for store the event picture
          $fileFullPath = __DIR__."/../../images/avatar/$fileName". "." . $info['extension'];
          //
          // define the picture path for database link and html displaying
          $imageBase = "/images/avatar/".$fileName. "." . $info['extension'];
          $stmt->bindParam(':avatar', $imageBase);
        }


        // execute du code SQL
        if ($stmt->execute()) {
          $lastId = $conn->lastInsertId();
          if ( $_FILES){
            if ( move_uploaded_file($_FILES["image"]["tmp_name"],$fileFullPath)) {
              //
              // on va supprimer l'ancienne image si elle existe
              $oldImage = basename($data['avatar']);
              unlink(__DIR__.'/../../images/avatar/'.$oldImage);
              echo json_encode(array("message" => $imageBase,"status"=>true));
            } else {
              echo json_encode(array("message" => "impossible de copier l'image","status"=>false));
            }
          } else {
            echo json_encode(array("message" => $lastId,"status"=>true));
          }
        }
        else{
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
        }

    } else {
      echo json_encode(array("message" => "token invalide","status"=>false));
    }
}catch(Exception $e){
    echo json_encode(array("message" =>$e->getMessage(),"status"=>false));
}
?>
