<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


http_response_code(200);
$message = "";
try {
    $databaseService = new DatabaseService();

        //
    // check du token
    $response = $databaseService->checkToken();
    if ( $response["status"]  ) {

        $conn = $databaseService->getConnection();
        //
        // lecture des données d'entrées
        // lecture des données d'entrées
        $data = json_decode($_POST['data'],true);
        //
        //
        // préparation de la requête
        $table_name = 'users';
        if ( $data['id']) {
            $query = "UPDATE ".$table_name ."
                    SET nom = :nom,
                    prenom = :prenom,
                    nomAffiche = :nomAffiche,
                    email = :email,
                    profil= :profil,
                    societe_id = :societe_id,
                    avatar= :avatar
                    where id= :id;" ;
            $message ="mise à jour réussie";
        } else {
            $query = "INSERT INTO " . $table_name . "
                            (id,nom,prenom,nomAffiche,email,password,profil,societe_id,avatar)
                            values (:id,:nom,:prenom,:nomAffiche,:email,:password,:profil,:societe_id,:avatar)" ;
            $message ="creation réussie";
            echo "insert";
        }
        $stmt = $conn->prepare($query);

        //
        // encodage du password
        if ( isset($data['password'])) {
            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        //
        // affectation des paramètres
        $encodedValue = json_encode($data['profil']);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':nom', $data['nom']);
        $stmt->bindParam(':prenom', $data['prenom']);
        $stmt->bindParam(':nomAffiche', $data['nomAffiche']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':profil', $encodedValue);
        $stmt->bindParam(':societe_id', $data['societe_id']);

      //
      // on prepare les données si il y a une image
      if ($databaseService->copieImage($_FILES,'USERS', $data['avatar'])) {
        $newImage = $databaseService->getImagePath();
        $stmt->bindParam(':avatar',$newImage);

        //
        // execute du code SQL
        if ($stmt->execute()) {
          $lastId = $data['id']?$data['id']:$conn->lastInsertId();
          echo json_encode(array("message" => json_encode(array('id'=>$lastId,'image'=>$newImage)),"status"=>true));
        }
        else{
            //echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
            echo json_encode(array("message" => utf8_encode($query),"status"=>false));
        }
      } else {
        echo json_encode(array("message" => "Impossible de copier l\'image","status"=>false));
      }
    }else {
        //l'update password du user a échouée.
        http_response_code(200);
        echo json_encode($response);
       }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>
