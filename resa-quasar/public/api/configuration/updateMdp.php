<?php
include_once '../config/database.php';

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

// check du token
$response = $databaseService->checkToken();
if ( $response["status"]  ) {

    $data = json_decode(file_get_contents("php://input"));
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

    if ( $data->id){
        try{
            $table_name = 'Users';
            $query = "UPDATE " . $table_name . " SET password = :newpassword where id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':newpassword',$password_hash);
            $stmt->bindParam(':id', $data->id);

            if($stmt->execute()){

                http_response_code(200);
                echo json_encode(array("status"=>true,"message"=>'maj MdP ok'));
            }
            else{
                http_response_code(200);
                echo json_encode(array("status"=>false,"message"=>utf8_encode($stmt->errorInfo()[2])));
            }
        } catch (Exception $e){
        //l'update password du user a échouée.
            http_response_code(200);
            echo json_encode(array("status" => false,"message"=>$e->getMessage()));
        }
    }else {
        http_response_code(400);
        echo json_encode(array("status"=>false,"message"=>'paramètre inconnu'));
    }
} else {
    //l'update password du user a échouée.
    http_response_code(200);
    echo json_encode($response);
}
?>