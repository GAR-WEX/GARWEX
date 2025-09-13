<?php
header('Content-Type: application/json');

$url = "https://spaceremit.com/api/v2/payment_info/";
$data = [
    "private_key" => "YOUR_PRIVATE_KEY", // ضع هنا المفتاح السري لاحقاً
    "payment_id" => $_POST['payment_id']
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data),
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) { die(json_encode(["error" => "فشل الاتصال بـ Spaceremit"])); }

$response = json_decode($result, true);
echo json_encode($response, JSON_PRETTY_PRINT);
