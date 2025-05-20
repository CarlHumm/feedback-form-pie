<?php
header("Content-Type: application/json");

if (!isset($_POST['email']) || !isset($_POST['message'])) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Missing email or message"
    ]);
    exit;
}

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$message = htmlspecialchars(trim($_POST['message']));

$filePath = __DIR__ . '/feedback.json';
$feedback = [];

if (file_exists($filePath)) {
    $json = file_get_contents($filePath);
    $feedback = json_decode($json, true) ?? [];
}

foreach ($feedback as $entry) {
    if (isset($entry['email']) && strtolower($entry['email']) === strtolower($email)) {
        echo json_encode([
            "success" => false,
            "status" => "duplicate",
            "message" => "Feedback already submitted."
        ]);
        exit;
    }
}

$feedback[] = [
    'email' => $email,
    'message' => $message,
    'timestamp' => date('c')
];

file_put_contents($filePath, json_encode($feedback, JSON_PRETTY_PRINT));

echo json_encode([
    "success" => true,
    "status" => "success",
    "message" => "Feedback received."
]);