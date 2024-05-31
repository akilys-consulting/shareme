<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


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

        //
        // préparation de la requête
        $table_name = 'users';
        $query = "DELETE FROM " . $table_name . "
                        where id = :id";
        $stmt = $conn->prepare($query);

        //
        // affectation des paramètres
        $stmt->bindParam(':id', $data->id);

        //
        // execute du code SQL
        if ($stmt->execute()) {
            echo json_encode(array("message" => "suppression réussie","status"=>true));
        }
        else{
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
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