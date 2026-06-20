<?php
// =============================================
// GitHub Webhook - Auto Deploy (Git Pull)
// =============================================
// Setiap kali kamu push ke GitHub, file ini akan
// dipanggil oleh GitHub dan otomatis git pull.
// =============================================

header('Content-Type: application/json; charset=utf-8');

// ========== KONFIGURASI ==========
// Ganti dengan secret yang kamu set di GitHub Webhook Settings
$webhook_secret = getenv('WEBHOOK_SECRET') ?: 'api-ptn-secret-2025';

// ========== VERIFIKASI SIGNATURE ==========
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if (empty($signature)) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "No signature provided."]);
    exit;
}

$expected = 'sha256=' . hash_hmac('sha256', $payload, $webhook_secret);

if (!hash_equals($expected, $signature)) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Invalid signature."]);
    exit;
}

// ========== VERIFIKASI EVENT ==========
$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';

if ($event !== 'push') {
    echo json_encode(["status" => "skipped", "message" => "Event '$event' ignored. Only 'push' is processed."]);
    exit;
}

// ========== CEK BRANCH (Hanya main) ==========
$data = json_decode($payload, true);
$branch = $data['ref'] ?? '';

if ($branch !== 'refs/heads/main') {
    echo json_encode(["status" => "skipped", "message" => "Branch '$branch' ignored. Only 'main' is deployed."]);
    exit;
}

// ========== DEPLOY: GIT PULL ==========
$output = [];
$return_code = 0;

// Set git safe directory (fix ownership issue dalam Docker)
exec('git config --global --add safe.directory /var/www/html 2>&1', $output, $return_code);

// Jalankan git pull
exec('cd /var/www/html && git pull origin main 2>&1', $output, $return_code);

// ========== RESPONSE ==========
if ($return_code === 0) {
    echo json_encode([
        "status" => "success",
        "message" => "Deploy berhasil! Git pull completed.",
        "output" => implode("\n", $output),
        "timestamp" => date('Y-m-d H:i:s')
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Deploy gagal.",
        "output" => implode("\n", $output),
        "return_code" => $return_code,
        "timestamp" => date('Y-m-d H:i:s')
    ]);
}
