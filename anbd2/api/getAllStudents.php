<?php

declare(strict_types=1);

// Gerekli dosyaları dahil edin
require __DIR__ . "/bootstrap.php"; // Veritabanı bağlantısı ve gerekli bağımlılıkları içeren dosya

// URL'nin doğru şekilde geldiğinden emin ol
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$parts = explode("/", $path);

// Burada doğru URL'yi kontrol edin, getAllStudents doğru gelmeli
$resource = $parts[3]; // 3. parça getAllStudents olmalı

// Eğer URL getAllStudents değilse 404 döndür
if ($resource != "getAllStudents") {
    http_response_code(404);
    echo json_encode(["message" => "Not Found"]);
    exit;
}

// JWT doğrulama işlemi
$user = new UserGateway($database); // Veritabanı bağlantısı ve kullanıcı işlemleri
$JwtCtrl = new Jwt($_ENV["SECRET_KEY"]); // JWT kontrol sınıfı
#$auth = new Auth($user, $JwtCtrl); // JWT doğrulaması için Auth sınıfı

// Token doğrulama işlemi
#if (!$auth->authenticateJWTToken()) {
    #http_response_code(401); // Unauthorized
    #echo json_encode(["message" => "Unauthorized"]);
    #exit;}

// Öğrencileri almak için StudentGateway'i kullan
$gateway = new StudentGateway($database); // Öğrencilerle ilgili işlemleri yöneten sınıf
$controller = new StudentController($gateway); // Controller sınıfını başlat

// GET isteği yapılmışsa öğrenci verilerini al
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller->processRequest($_SERVER['REQUEST_METHOD']); // Öğrencileri getiren metodu çağır
} else {
    http_response_code(405); // Geçersiz metod hatası
    header('ALLOW: GET');
    echo json_encode(["message" => "Method Not Allowed"]);
    exit;
}
