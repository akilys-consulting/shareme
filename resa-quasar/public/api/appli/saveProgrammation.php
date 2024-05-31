<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$insert=true;
$conn = null;
$erreur=false;
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
        $data = json_decode(file_get_contents("php://input"));


        if ( $data->id) $insert=false;

        // préparation de la requête
        $table_name = 'programmation';

        if (!$insert) {
            $query = "UPDATE ".$table_name ." 
            SET type = :type,
            datedebut = :datedebut,
            datefin = :datefin,
            evenementid = :evenementid,
            jours= :jours
            where id= :id;" ;       
            $message ="mise à jour réussie";
        } else {
            $query = "INSERT INTO " . $table_name . "
            (type,evenementid,datedebut,datefin,jours)
            values (:type,:evenementid,:datedebut,:datefin,:jours)" ;     
            $message ="creation réussie"; 
        }
        $stmt = $conn->prepare($query);
        //
        // affectation des paramètres
        $encodedJours = json_encode($data->jours);
        $stmt->bindParam(':evenementid', $data->evenementid);
        $stmt->bindParam(':type', $data->type);
        $stmt->bindParam(':datedebut', $data->datedebut);
        $stmt->bindParam(':datefin', $data->datefin);
        $stmt->bindParam(':jours', $encodedJours);
        
        if (!$insert) {
            $stmt->bindParam(':id', $data->id);
        }

        // execute du code SQL
        if ($stmt->execute()) {
            $lastId = $conn->lastInsertId();
            echo json_encode(array("message" => $lastId,"status"=>true));
        }
        else{
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
        }
    }else {
        //token en erreur.
        http_response_code(200);
        echo json_encode($response);
    }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>