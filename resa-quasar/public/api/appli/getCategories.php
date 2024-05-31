<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$conn = null;
//
// récupération des données paramètres
$data = json_decode(file_get_contents("php://input"));

$allCategories = array();


http_response_code(200);

try {
    $databaseService = new DatabaseService();


        $conn = $databaseService->getConnection();

        //
        // préparation de la requête
        $query = "SELECT nom ";
        $query .= "FROM categories where actif=1 ";
        
        $stmt = $conn->prepare( $query );
        $stmt->execute();
        
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // envoi de la réponse
            echo json_encode(
                array(
                    "status"=>true,
                    "data"=>$results,
                    "message"=>$query)
            );
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>