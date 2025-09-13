<?php
header('Content-Type: application/json');

// ---- إعدادات ----
define('SPACEREMIT_PRIVATE_KEY', 'YOUR_PRIVATE_KEY'); // ضع المفتاح السري هنا
define('ALLOW_REQUEST_ORIGIN', 'https://yourdomain.com'); // ضع دومين موقعك إذا تحب حماية إضافية

// ---- تحقق من مصدر الطلب (اختياري لكنه جيد للأمان) ----
if (isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] !== ALLOW_REQUEST_ORIGIN) {
    echo json_encode(["error" => "مصدر الطلب غير مصرح به"]);
    exit;
}

// ---- تحقق من وجود payment_id ----
if (!isset($_POST['payment_id']) || empty($_POST['payment_id'])) {
    echo json_encode(["error" => "payment_id مفقود"]);
    exit;
}

// ---- تجهيز البيانات للطلب ----
$data = [
    "private_key" => SPACEREMIT_PRIVATE_KEY,
    "payment_id"  => $_POST['payment_id']
];

$url = "https://spaceremit.com/api/v2/payment_info/";

// ---- تنفيذ الطلب باستخدام cURL ----
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$result = curl_exec($ch);

if ($result === false) {
    echo json_encode(["error" => "فشل الاتصال بـ Spaceremit", "details" => curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// ---- تحليل النتيجة ----
$response = json_decode($result, true);

// ---- إعادة النتيجة بصيغة JSON منظمة ----
echo json_encode($response, JSON_PRETTY_PRINT);
