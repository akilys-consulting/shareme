<?php
include_once  __DIR__ .'/../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
http_response_code(200);

$databaseService = new DatabaseService();




// si on a bien récupéré le token passé en paramètre

try{
        $response = $databaseService->checkToken();
            echo json_encode($response);


    }catch (Exception $e){
        echo json_encode(array(
            "status" => false,
            "message" => $e.message
        ));
    }

?>