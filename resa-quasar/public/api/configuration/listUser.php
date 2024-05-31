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
    $response = $databaseService->checkToken();
    if ( $response["status"]  ) {

        $conn = $databaseService->getConnection();

        //
        // lecture des données d'entrées
        $data = json_decode(file_get_contents("php://input"));
        
        //
        // préparation de la requête
        $table_name = 'users';
        $query = "SELECT id,nom, prenom, email,nomAffiche,profil,societe_id FROM " . $table_name . " WHERE societe_id = :societeId";
        $stmt = $conn->prepare( $query );
        $stmt->bindParam(":societeId", $data->societeId);
        //
        $stmt->execute();
        $error = $stmt->errorInfo()[2];
        $num = $stmt->rowCount();
            
        if($num > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $index=0;
            foreach($results as $row){
                $results[$index++]["profil"] = json_decode($row["profil"]);
            }
            // envoi de la réponse
            echo json_encode(
                array(
                    "status"=>true,
                    "data"=>$results,
                    "message"=>'donnees trouvees')
                );
        }
        else {
            echo json_encode(array("message" => utf8_encode('pas d\'enregistrement'),"status"=>true));
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