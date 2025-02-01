<?php

declare(strict_types=1);
require __DIR__ ."/bootstrap.php";

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);


$resource = $parts[3];

#$id = $parts[4] ?? null;

if ($resource != "getAllStudents") {

    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
    exit;
}

$user = new UserGateway($database);


$JwtCtrl = new Jwt($_ENV["SECRET_KEY"]);

$auth = new Auth($user, $JwtCtrl);

#if (!$auth->authenticateJWTToken()) {
#    exit;
#}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->processRequest($_SERVER['REQUEST_METHOD']);
} else {
    http_response_code(405);
    header('ALLOW: GET');
    echo json_encode(["message" => "Metod geçersiz"]);
    exit;
}


$gateway = new StudentGateway($database);

$controller = new StudentController($gateway);



$controller->processRequest($_SERVER['REQUEST_METHOD']);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->processRequest($_SERVER['REQUEST_METHOD']);
} else {
    http_response_code(405); // Geçersiz metod hatası
    header('ALLOW: GET');
    echo json_encode(["message" => "Metod geçersiz"]);
    exit;
}


if($_SERVER['REQUEST_METHOD']!=='POST'){
    http_response_code(405);
    header('ALLOW: POST');
    exit;
}

$contenttype=isset($_SERVER['CONTENT_TYPE'])?trim($_SERVER['CONTENT_TYPE']):"";

if($contenttype!=="application/json"){
    http_response_code(4001);
    echo json_encode(["message"=>"hatttaaaaa"]);
    exit;
}
$data=json_decode(file_get_contents("php://input"),true);

if($data==null){
    http_response_code(400);
    echo json_encode(["message"=> "hata2"]);
}

if(!array_key_exists("username",$data)||!array_key_exists("password",$data)){

    http_response_code(404);
    echo json_encode(["message"=>"hata3"]);
    exit;

}
$user_gateway=new Usergateway($database);

$user=$user_gateway->getByUsername($data["username"]);

if($user==null){

    http_response_code(400);
    echo json_encode(["message"=> "hata4"]);
    exit;
}
if(!password_verify($data["password"],$user["password_hash"])){

    http_response_code(400);
    echo json_encode(["message"=> "hata5"]);
    exit;
}
$payload=["id"=>$user["id"],"name"=>$user["name"]];

$JwtController=new jwt($_ENV["SECRET_KEY"]);
$token=$JwtController->encode($payload);
echo json_encode(["token"=>$token]);
$user=new Usergateway($database);
$gateway=new StudentGateWay($database);
$controller=new StudentController($gateway);
$controller->processRequest($_SERVER["REQUEST_METHOD"]);
?>



