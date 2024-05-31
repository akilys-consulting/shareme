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
$evtId = $data->evenementId;

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
    $table_name = 'evenement';
    $query = "SELECT evt.id, nom, adresse, options,organisateurid,description,prix,plan,lastEmplacement,status ";
    $query .= "FROM evenement evt ";
    $query .= "LEFT JOIN PROGRAMMATION prog on ( prog.evenementid = evt.id) ";
    if ( isset($dtFilter) ) {
        $query .= "where prog.dtdebut >= :dtfilter and prog.dtfin <= :dtfilter ";
    }
    if ( isset($organisateurId))
        $query .= "AND organisateurid like :organisateurid ";

    $stmt = $conn->prepare( $query );
    //
    // chargement des paramètres
    //$stmt->bindParam(':status', $status);
    if ( isset($organisateurId)) {
        $stmt->bindParam(':organisateurid', $organisateurId);
    }
    if ( isset($dtFilter) ) {
        $stmt->bindParam(':dtfilter', $dtFilter);
    }

    $stmt->execute();


    $error = $stmt->errorInfo()[2];
    $totalRow = $stmt->rowCount();

    $query .= "limit :limit offset :offset";
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $num = $stmt->rowCount();

    if($num > 0){
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // envoi de la réponse
        echo json_encode(
            array(
                "status"=>true,
                "data"=>$results,
                "count"=>$totalRow,
                "message"=>'donnees trouvees')
        );
    }
     else {
        echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
     }
    } else {
      echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
    }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>
