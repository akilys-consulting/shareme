<?php
include_once '../config/database.php';

$conn = null;
//
// récupération des données paramètres
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
        $data = json_decode(file_get_contents("php://input"),true);

        //
        // préparation de la requête
        switch ($data['type']) {
          case 'usr':
            $query = "SELECT u.avatar,u.nomaffiche  from users u ".
            "WHERE u.id = :user_id LIMIT 0,1";
            break;
          case 'soc':
            $query = "SELECT s.avatar,s.nomaffiche  from societes s ".
            " left join users u on (u.societe_id = s.id) ".
            "WHERE u.id = :user_id LIMIT 0,1";
            break;
          default:
            $query = "SELECT u.avatar,u.nomaffiche  from users u ".
            "WHERE u.id = :user_id LIMIT 0,1";
            break;
        }

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
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"data"=>null,"status"=>false));
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
