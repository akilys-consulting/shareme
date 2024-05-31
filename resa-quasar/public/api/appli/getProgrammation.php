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
$data = json_decode(file_get_contents("php://input"),true);
$evtId = $data['evenementId'];

http_response_code(200);

try {

       
    $databaseService = new DatabaseService();
    //
    // check du token

 
        $conn = $databaseService->getConnection();

        //
        // préparation de la requête
        $query = "SELECT id,type, datedebut,datefin,jours,evenementid ";
        $query .= "FROM programmation ";
        $query .= "where evenementid = :eventid ";

        $stmt = $conn->prepare( $query );
        //
        // chargement des paramètres
        //$stmt->bindParam(':status', $status);
        if ( isset($evtId)) {
            $stmt->bindParam(':eventid', $evtId);
        }

        $stmt->execute();

        $error = $stmt->errorInfo()[2];
        $num = $stmt->rowCount();
        
        if($num > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // transformation du json jours de string à tableau
            foreach($results as &$ligne) {
                $ligne["jours"] = json_decode($ligne["jours"]);
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
            echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
        }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>