<?php
session_start();

$host = 'localhost';
$dbname = 'addiction_tracking';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// === TELEGRAM BOT FONKSİYONU ===
function sendTelegram($message) {
    // Kendi bilgilerini entegre ettik
    $botToken = "8733092695:AAGx8CJNzlBx3bWL0Ygi2VyZ63ft6UDBK7Q"; 
    $chatId = "8563463695";

    $url = "https://api.telegram.org/bot$botToken/sendMessage?chat_id=$chatId&text=" . urlencode($message) . "&parse_mode=HTML";
    
    // PHP cURL ile arka planda hızlıca mesajı gönder
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Siteyi bekletmesin diye 3 saniye sınır
    curl_exec($ch);
    curl_close($ch);
}
?>