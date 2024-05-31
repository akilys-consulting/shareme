<?php
include_once '../config/database.php';
require __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

//
// lecture des données en entrée
$data = json_decode(file_get_contents("php://input"),true);
$email = $data['email'];
$password = $data['password'];
$erreurSql=null;
    http_response_code(200);
try {
    //
    // execution de l'ordre SQL
    $table_name = 'users';
    $query = "SELECT u.id, u.profil,u.email, u.nom, u.prenom,u.nomAffiche, societe_id, s.conf,u.password  from " . $table_name . 
    " u inner join societes s on (s.id = u.societe_id)".
    " WHERE email = ? and s.actif=1 LIMIT 0,1";
    $stmt = $conn->prepare( $query );
    $stmt->bindParam(1, $email);
    $stmt->execute();
    $error = $stmt->errorInfo()[2];
    $num = $stmt->rowCount();
    //
    // récupération des données lues
    if($num > 0){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $row['id'];
        $prenom = $row['prenom'];
        $nom = $row['nom'];
        $password2 = $row['password'];
        $nomAffiche=$row['nomAffiche'];
        $profil=$row['profil'];
        $email=$row['email']; 
        $societe_id=$row['societe_id'];
        $conf = json_decode($row['conf']);
        //
        // on vérifie que le password est conforme à la valeur encryptée : fonction native PHP
        if(password_verify($password, $password2))
        {
            //
            // génération du tokken
            $issuedat_claim = time(); // issued at
            $notbefore_claim = $issuedat_claim + 10; //not before in seconds
            $expire_claim = $issuedat_claim + 10800; // expire time in seconds
            $token = array(
                "iss" => $databaseService->getIss(),
                "aud" => $databaseService->getAud(),
                "iat" => $issuedat_claim,
                "nbf" => $notbefore_claim,
                "exp" => $expire_claim,
                "data" => array(
                    "id" => $id,
                    "nom" => $nom,
                    "prenom" => $prenom,
                    "email" => $email
            ));
            $key =JWT::encode($token, $databaseService->getPlainTextKey(),'HS256');
            //
            // envoi de la réponse
            echo json_encode(
                array(
                    "status"=>true,
                    "data"=>array("nom"=>$nom,"prenom"=>$prenom,"email"=>$email,"nomAffiche"=>$nomAffiche,"societe_id"=>$societe_id,"conf"=>$conf,"profil"=>json_decode($profil),"id"=>$id,"token"=>$key),
                    "message"=>'donnees trouvees')
                );
        }
        else{
            // l'identiifcation du user a échouée.
            echo json_encode(array("status" => false,"message"=>'Mot de passe incorrect'));

        }
    }        
    else{
        // l'identiifcation du user a échouée.
        echo json_encode(array("status" => false,"message"=>'email de connexion inconnu'));
    }
} catch (Exception $e){
    // l'identiifcation du user a échouée.
    //http_response_code(401);
     echo json_encode(array("status" => false,"message"=>$e->getMessage()));

}
?>