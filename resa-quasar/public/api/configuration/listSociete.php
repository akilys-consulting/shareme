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
http_response_code(200);

try {
    $databaseService = new DatabaseService();

    //
    // check du token
    $response = $databaseService->checkToken();
    if ( $response["status"]  ) {
        $conn = $databaseService->getConnection();

        //
        // préparation de la requête
        $table_name = 'societes';
        $query = "SELECT societes.id,societes.nom, societes.adresse, societes.avatar, societes.email_contact,societes.telephone,societes.actif,societes.conf FROM " . $table_name;
        $stmt = $conn->prepare( $query );
        //
        $stmt->execute();
        $error = $stmt->errorInfo()[2];
        $num = $stmt->rowCount();

        if($num > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($results as &$ligne) {
                $ligne["conf"] = json_decode($ligne["conf"]);
                $ligne["adresse"]= json_decode($ligne["adresse"]);
                $ligne["actif"]= $ligne["actif"]==1?true:false;
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
    } else {
        //l'update password du user a échouée.
        http_response_code(200);
        echo json_encode($response);
    }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>
