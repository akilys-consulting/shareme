<?php
require __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
//ajouter pour éviter le message Cannot handle token prior to 2016-01-15T14:44:28+1100
\Firebase\JWT\JWT::$leeway = 10;

// used to get mysql database connection
class DatabaseService{

    private $db_host = "localhost";
    private $db_name = "appis";
    private $db_user = "root";
    private $db_password = "";
    private $connection;
    private $imageBase=null;

    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();

        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function getConnection(){

        $this->connection = null;

        try{

            $this->connection = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password,array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }catch(PDOException $exception){
            echo "Connection failed: " . $exception->getMessage();
        }

        return $this->connection;
    }

    public function getPlainTextKey() {
        return 'P1P3P.IHGTyyBgF567BGFDyhkbBiiàà86nb-çiçn]}<çè_è<<!ùjkKLè6Vj';
    }
    public function getIss() {
        return "APPIS";
    }
    public function getAud(){
        return  "ASSURANCE";
    }

    //
    // contrôle du token
    public function checkToken() {
        try {
            $token = $this-> getBearerToken();
            if ( $token) {
                // on decode le token
                $decoded = JWT::decode($token,  new Key($this->getPlainTextKey(), 'HS256'));
                // on contrôle le token
                if ( $decoded->exp >= time() && $decoded->iss ==$this->getIss() && $decoded->aud==$this->getAud()){
                    // Access is granted. Add code of the operation here
                    return array(
                        "status" => true,
                        "data"=>$token,
                        "message"=>"token ok"
                    );
                } else {
                    return array(
                        "status" => false,
                        "data" => array("time"=>time(),"exp"=>$decoded->exp,"iss"=>$decoded->iss,"aud"=>$decoded->aud),
                        "message"=>'token invalide'
                    );
                }
            } else {
                return array("status" => false,"message"=>"token absent");
            }

        }catch (Exception $e){
            return array(
                "status" => false,
                "message" => $e->getMessage());
        }
    }


    public function copieImage($img,$type,$oldImage){
      if ($img) {
        $filename = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(10/strlen($x)) )),1,10);
        //
        // orientation du path en focntion du type d'image
        switch (strtoupper($type)) {
          case 'EVENEMENT':
            $imagePath = "/images/evenements/";
          break;
          case 'USERS':
            $imagePath = "/images/avatar/";
          break;
          default:
            $imagePath = "/images/";
            break;
        }
        //
        // get informations about picture
        $info = pathinfo($img["image"]["name"]);
        //
        // define the picture path for database link and html displaying
        $this->imageBase = $imagePath . $filename . "." . $info['extension'];
        //
        // define the image path for store the event picture
        $fileFullPath = __DIR__."/../..". $imagePath. $filename. "." . $info['extension'];

        if ( move_uploaded_file($img["image"]["tmp_name"],$fileFullPath)) {
            //
            // on va supprimer l'ancienne image si elle existe
            if ($oldImage ) {
            $delImage = basename($oldImage);
            return unlink(__DIR__.'/../../'.$imagePath.$delImage);
          }
          return true;
        } else {
          return false;
        }
      } else {
        return true;
      }
    }

    public function getImagePath(){
        return $this->imageBase;
    }

  }
?>
