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
$status = $data['status'];
if (isset($data['societeId'] ) && $data['societeId'] != null ){
    $societeId = $data['societeId'];
}
$offset = intval($data['offset']);
$limit = intval($data['limit']);
if ( isset($data['datefilter']) ){
    $dtFilter = $data['datefilter'];
}
if ( isset($data['categorieFilter']) ){
    $categorieFilter = $data['categorieFilter'];
}
if ( isset($data['stringSearch']) ){
    $searchString = "%".$data['stringSearch']."%";
}
if ( isset($data['status']) && $data['status'] != null){
    $status = $data['status'];
}
http_response_code(200);

try {


    $databaseService = new DatabaseService();



        $conn = $databaseService->getConnection();

        //
        // préparation de la requête
        $table_name = 'evenement';
        $query = "SELECT evt.id, evt.titre, evt.adresse, options,organisateurid,description,prix,plan,lastEmplacement,status,image,site,categories ";
        $query .= "FROM evenement evt ";
        $query .= "LEFT JOIN PROGRAMMATION prog on ( prog.evenementid = evt.id) ";
        $query .= "LEFT JOIN USERS usr on ( usr.id = evt.organisateurid) ";

        // critère data
        if ( isset($dtFilter) ) {
            $query .= " where prog.datefin >= :dtfilter ";
        }
        if ( isset($societeId))
            $query .= " AND usr.societe_id = :societeId ";

        if ( isset($status)) {
            $query .= " AND evt.status = '".$status."' ";
        }
        if ( isset($searchString)){
            $query .= " AND evt.titre like :searchString ";
        }

        // critère catégories

        if ( count($categorieFilter)>0){
            $queryCat = "AND (";
            // on récupére le tableau des valeurs
            foreach($categorieFilter as &$cat) {
                $queryCat .= " POSITION('\"".$cat."\"' in evt.categories)>0 OR ";
            }
            $query.= substr($queryCat,0,-3).") ";
        }

        $stmt = $conn->prepare( $query );

        //
        // chargement des paramètres
        //$stmt->bindParam(':status', $status);
        if ( isset($societeId)) {
            $stmt->bindParam(':societeId', $societeId);
        }
        if ( isset($dtFilter) ) {
            $stmt->bindParam(':dtfilter', $dtFilter);
        }
        if ( isset($searchString)){
            $stmt->bindParam(':searchString', $searchString);
        }

        $stmt->execute();

        $error = $stmt->errorInfo()[2];
        $totalRow = $stmt->rowCount();

        $query .= "limit :limit offset :offset";
        $stmt = $conn->prepare( $query );

        if ( isset($societeId)) {
            $stmt->bindParam(':societeId', $societeId);
        }
        if ( isset($dtFilter) ) {
            $stmt->bindParam(':dtfilter', $dtFilter);
        }
        if ( isset($searchString)){
            $stmt->bindParam(':searchString',$searchString);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

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
                    "count"=>$totalRow,
                    "message"=>$query)
                );
        }
        else {
            echo json_encode(array("message" => utf8_encode( $query),"status"=>true));
        }

}catch(Exception $e){
    echo json_encode(array("message" => utf8_encode( $query).$e->getMessage(),"status"=>false));
}
?>
