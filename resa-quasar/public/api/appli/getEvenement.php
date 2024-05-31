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
$id = $data['id'];

http_response_code(200);

try {

    
    $databaseService = new DatabaseService();



        $conn = $databaseService->getConnection();

        //
        // préparation de la requête
        $table_name = 'evenement';
        $query = "SELECT evt.id, evt.nom, evt.adresse, options,organisateurid,description,prix,plan,lastEmplacement,status,image,site,categories ";
        $query .= "FROM evenement evt ";
        $query .= "LEFT JOIN PROGRAMMATION prog on ( prog.evenementid = evt.id) ";
        $query .= "LEFT JOIN USERS usr on ( usr.id = evt.organisateurid) ";

        // critère data
        if ( isset($id) ) {
            $query .= " where evt.id = :id ";
        }


        $stmt = $conn->prepare( $query );
		if ( isset($id)) {
            $stmt->bindParam(':id', $id);
        }
        $stmt->execute();
        $num = $stmt->rowCount();
        
        if($num > 0){
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            foreach($results as &$ligne) {
                $ligne["categories"] = json_decode($ligne["categories"]);
                $ligne["adresse"]=json_decode($ligne["adresse"]);
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
            echo json_encode(array("message" => utf8_encode( $query),"status"=>true));
        }
    
}catch(Exception $e){
    echo json_encode(array("message" => utf8_encode( $query).$e->getMessage(),"status"=>false));
}
?>