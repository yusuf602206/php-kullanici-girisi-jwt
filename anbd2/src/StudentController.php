<?php 

class StudentController{

    private $gateway;
    private $jwt;


    public PDO $conn;
    public function __construct($gateway){
        $this->gateway = $gateway;
        $this->jwt = new jwt("secret_key");
        
    }
    public function processRequest($requestMethod){
        switch($requestMethod){
            case 'GET':
                $this->handleGetRequest();
                break;
            case 'POST':
                $this->handlePostRequest();
                break;
            case 'put':
                $this->handlePutRequest();
                break;
            case 'delete':
                $this->handleDeleteRequest();
                break;
            default:
                http_response_code(405);
                echo json_encode(["message"=>"metod gecersiz"]);
                break;            
        }
    }
    private function getTokenFromHeader() {
        $headers = apache_request_headers();
        $authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

        if ($authorizationHeader) {
            
            $parts = explode(" ", $authorizationHeader);
            if (count($parts) == 2) {
                return $parts[1]; 
            }
        }
        return null;
    }
    private function handleGetRequest(){
        $token = isset($_GET['token']) ? $_GET['token'] : null;
        $token = $this->getTokenFromHeader(); 
        if ($token) {
            try {
                $decoded = $this->jwt->decode($token); // Token'ı decode et
                echo "Decoded Payload: " . json_encode($decoded);
            } catch (Exception $e) {
                http_response_code(401); // Unauthorized
                echo json_encode(["message" => "Invalid or expired token"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Authorization token is required"]);
        }
        $studentId=$_GET['id']?? null;
        if($studentId){
            $student=$this->gateway->AddStudent($studentId);
            if($student){
                echo json_encode($student);
            }else{
                http_response_code(404);
                echo json_encode(['message'=> 'ogrenci bulunmadi']);
            } 

        }else{
            $students=$this->gateway->getAllStudents();
            echo json_encode($students);

        }
    }
    private function handlePostRequest(){
        $data =json_decode(file_get_contents("php://input"),true);
        if (isset($data["name"],$data["age"],$data["gender"])){
            $student=$this->gateway->AddStudent($data["name"], $data["age"], $data["gender"]);
            echo json_encode(["message" => "Öğrenci başarıyla eklendi", "student" => $student]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Eksik verileffr"]);
        
        }
    }
    private function handlePutRequest(){
        $data = json_decode(file_get_contents("php://input"), true);
        if (isset($data["id"], $data["name"], $data["age"], $data["gender"])) {
            $updated = $this->gateway->UpdateStudents($data["id"], $data["name"], $data["age"], $data["gender"]);
            if ($updated) {
                echo json_encode(["message" => "Öğrenci başarıyla güncellendi"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Öğrenci bulunamadı"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Eksik veriler"]);
        }
    }
    private function handleDeleteRequest(){
        $studentId = $_GET['id'] ?? null;
        if ($studentId) {
            $deleted = $this->gateway->deleteStudent($studentId);
            if ($deleted) {
                echo json_encode(["message" => "Öğrenci başarıyla silindi"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Öğrenci bulunamadı"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Öğrenci ID'si gereklidir"]);
        }
    }
    


}
?>