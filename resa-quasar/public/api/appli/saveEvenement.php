<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$insert=true;
$conn = null;

http_response_code(200);
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
        if ($data['id'] >0)
        {
            $insert = false;
        }
      // préparation de la requête
      $table_name = 'Evenement';
      /*        organisateurid = :organisateurid,*/
      if ( !$insert) {
        $query = "UPDATE ".$table_name ."
        SET titre = :titre,
        adresse = :adresse,
        options = :options,
        description= :description,
        lastEmplacement = :lastEmplacement,
        prix= :prix,
        plan= :plan,
        status= :status,
        image=:image,
        site=:site,
        categories=:categories,
        organisateurid=:organisateurid
        where id= :id;" ;
        $message ="mise à jour réussie";
      } else {
        $query = "INSERT INTO " . $table_name . "
        (titre,adresse,options,description,prix,plan,lastEmplacement,status,image,site,categories,organisateurid)
        values (:titre,:adresse,:options,:description,:prix,:plan,:lastEmplacement,:status,:image,:site,:categories,:organisateurid)" ;
        $message ="creation réussie";
      }


      $stmt = $conn->prepare($query);
        //
      // affectation des paramètres
      if ( !$insert)
          $stmt->bindParam(':id', $data['id']);

      //
      $catgories = json_encode($data['categories']);
      $adresse = json_encode($data['adresse']);
      $stmt->bindParam(':titre', $data['titre']);
      $stmt->bindParam(':adresse', $adresse);
      $stmt->bindParam(':options', $data['options']);
      $stmt->bindParam(':organisateurid', $data['organisateurid']);
      $stmt->bindParam(':description', $data['description']);
      $stmt->bindParam(':lastEmplacement', $data['lastEmplacement']);
      $stmt->bindParam(':prix', $data['prix']);
      $stmt->bindParam(':plan', $data['plan']);
      $stmt->bindParam(':status', $data['status']);

      $stmt->bindParam(':site', $data['site']);
      $stmt->bindParam(':categories', $catgories);

      //
      // on prepare les données si il y a une image
      if ($databaseService->copieImage($_FILES,'EVENEMENT', $data['image'])) {
        $newImage = $databaseService->getImagePath();
        $stmt->bindParam(':image',$newImage);


        // execute du code SQL
        if ($stmt->execute()) {
          $lastId = $conn->lastInsertId();
            echo json_encode(array("message" => json_encode(array('id'=>$lastId,'image'=>$newImage)),"status"=>true));
        }
        else {
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
        }
      } else {
        echo json_encode(array("message" => "Impossible de copier l\'image","status"=>false));
      }
    } else {
      echo json_encode(array("message" => "token invalide","status"=>false));
    }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>
