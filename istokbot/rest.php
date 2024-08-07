<?php

header('Content-Type: text/html; charset=UTF-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$token = getenv("TOKEN"); 
$apiUrl = "https://api.telegram.org/bot$token/";
$base_url = getenv("BASE_URL");
if (!$base_url) {
	$base_url = "https://nzhlv.fun/istokbot";
}

$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
    
    exit;
}

$message = isset($update['message']) ? $update['message'] : "";
$chatId = isset($message['chat']['id']) ? $message['chat']['id'] : "";
$firstName = isset($message['chat']['first_name']) ? $message['chat']['first_name'] : "";

if (isset($message['text'])) {
    $text = $message['text'];

    if ($text === '/start') {
        sendGreeting($chatId, $firstName);
    }
}

function sendGreeting($chatId, $firstName) {
    global $token;
    $message = "Привет, $firstName!\nЧего изволите";
    $keyboard = [
        'inline_keyboard' => [
            [
                ['text' => "Меню", 'web_app' => ['url' => $base_url . '/menu.html']],
                ['text' => "Прием заказов", 'web_app' => ['url' => $base_url . '/order10.html']]
            ],
            [
                ['text' => "Акции", 'web_app' => ['url' => $base_url . '/promo.html']],
                ['text' => "FAQ", 'web_app' => ['url' => $base_url . '/faq.html']]
            ],
            [
                ['text' => "Обратная связь", 'web_app' => ['url' => $base_url . '/feedback.html']]
            ]
        ]
    ];
    $encodedKeyboard = json_encode($keyboard);
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($message) . "&reply_markup=$encodedKeyboard";
    file_get_contents($url);
}

function sendMessage($chatId, $message) {
    global $token;
    $url = "https://api.telegram.org/bot$token/sendMessage?chat_id=$chatId&text=" . urlencode($message);
    file_get_contents($url);
}

?>