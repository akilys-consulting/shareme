<?php
include_once '../config/database.php';

$conn = null;
//
// récupération des données paramètres
http_response_code(200);
//
// lecture des données d'entrées
    $data = json_decode(file_get_contents("php://input"));

try {
    $databaseService = new DatabaseService();


    //
    // check du token
    //$response = $databaseService->checkToken();
    //if ( $response["status"]  ) {

        $conn = $databaseService->getConnection();

        //
        // lecture des données d'entrées
        $data = json_decode(file_get_contents("php://input"));

        //
        // préparation de la requête
        $table_name = 'users';
        $query = "SELECT s.nom as societe, s.conf,u.password,s.avatar  from " . $table_name .
        " u " .
        " left join societes s on (u.societe_id = s.id)".
        "WHERE u.id = :user_id LIMIT 0,1";
        $stmt = $conn->prepare( $query );
        $stmt->bindParam(":user_id", $data["id"]);
        //
        $stmt->execute();
        $error = $stmt->errorInfo()[2];
        $num = $stmt->rowCount();

        if($num > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // envoi de la réponse
            echo json_encode(
                array(
                    "status"=>true,
                    "data"=>$results,
                    "message"=>'donnees trouvees')
                );
        }
        else {
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
        }

    //}else {
    //    //l'update password du user a échouée.
    //    http_response_code(200);
    //    echo json_encode($response);
    //   }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}



?>
