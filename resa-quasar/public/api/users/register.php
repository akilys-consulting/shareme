<?php
include_once '../config/database.php';

header("Access-Control-Allow-Origin: http://localhost:8080");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$firstName = '';
$lastName = '';
$email = '';
$password = '';
$conn = null;
http_response_code(200);
try {
    $databaseService = new DatabaseService();
    $conn = $databaseService->getConnection();
    //
    // lecture des données d'entrées
    $data = json_decode(file_get_contents("php://input"));
    $nom = $data->nom;
    $prenom = $data->prenom;
    $nomAffiche = $data->nomAffiche;
    $email = $data->email;
    $profil = json_encode($data->profil);
    $password = $data->password;
    //
    // préparation de la requête
    $table_name = 'Users';
    $query = "INSERT INTO " . $table_name . "
                    SET nom = :nom,
                        prenom = :prenom,
                        nomAffiche = :nomAffiche,
                        email = :email,
                        profil = :profil,
                        password = :password";
    $stmt = $conn->prepare($query);
    //
    // encodage du password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    //
    // affectation des paramètres
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':nomAffiche', $nomAffiche);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':profil', $profil);
    //
    // execute du code SQL
    if ($stmt->execute()) {
        echo json_encode(array("message" => "creation réussie","status"=>true));
    }
    else{
        echo json_encode(array("message" => utf8_encode($stmt->errorInfo()[2]),"status"=>false));
    }
}catch(Exception $e){
    echo json_encode(array("message" => $e->getMessage(),"status"=>false));
}
?>