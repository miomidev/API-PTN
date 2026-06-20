<?php
// index.php

// 1. Header wajib buat API (Biar dianggap JSON sama browser/flutter)
header('Content-Type: application/json; charset=utf-8');

// 2. CORS (Izin akses lintas domain) - PENTING!
// Tanda * berarti semua web boleh akses. Kalau mau aman ubah jadi domainmu.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// 3. Baca File JSON
$json_file = 'data.json';

if (!file_exists($json_file)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "File database tidak ditemukan."]);
    exit;
}

// Load data ke memori
$json_string = file_get_contents($json_file);
$data_kampus = json_decode($json_string, true);

// 4. Ambil Parameter Pencarian dari URL
// Contoh: domain.com/api-snbt/?q=bogor
$query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

// 5. Logika Pencarian
if ($query) {
    $hasil_search = [];

    foreach ($data_kampus as $kampus) {
        // Cek apakah Nama Universitas mengandung kata kunci pencarian
        // strpos = cari posisi text, stripos = cari tanpa peduli huruf besar/kecil
        if (stripos($kampus['universitas'], $query) !== false) {
            $hasil_search[] = $kampus;
        }
    }

    // Response Hasil Pencarian
    echo json_encode([
        // Bagian Header/Info Author
        "meta" => [
            "name" => "API SNBT PTN Indonesia",
            "author" => "Romi Setiawan",
            "version" => "1.0.0",
            "github" => "https://github.com"
        ],
        // Bagian Data Utama
        "status" => "success",
        "total_result" => count($hasil_search),
        "query" => $query,
        "data" => $hasil_search
    ]);
} else {
    // Kalau tidak ada query pencarian, tampilkan pesan atau semua data (hati-hati berat)
    // Disini kita batasi tampilkan 10 saja biar ringan
    echo json_encode([
        "status" => "success",
        "message" => "Silakan tambahkan parameter '?q=nama_kampus' di URL untuk mencari.",
        "sample_data" => array_slice($data_kampus, 0, 5) // Cuma kasih intip 5 data
    ]);
}
