<?php
// index.php

// 1. Header wajib buat API (Biar dianggap JSON sama browser/flutter)
header('Content-Type: application/json; charset=utf-8');

// 2. CORS (Izin akses lintas domain) - PENTING!
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// 3. Cache-Control (Performa & Keamanan)
// Simpan hasil di memori browser/aplikasi pengguna selama 1 jam (3600 detik)
// Ini mencegah VPS jebol jika diakses banyak orang bersamaan dengan pencarian yang sama.
header('Cache-Control: public, max-age=3600');

// 4. Baca File JSON
$json_file = 'data.json';

if (!file_exists($json_file)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "File database tidak ditemukan."]);
    exit;
}

// Load data ke memori
$json_string = file_get_contents($json_file);
$data_kampus = json_decode($json_string, true);

// 5. Ambil Parameter Pencarian & Filter dari URL
// q        = Mencari Nama Universitas ATAU Nama Prodi
// jenjang  = Mencari jenjang (contoh: "Sarjana", "Sarjana Terapan")
// kategori = Mencari kategori PTN (contoh: "Akademik", "Vokasi", "Kemenag")
$query    = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
$jenjang  = isset($_GET['jenjang']) ? strtolower(trim($_GET['jenjang'])) : '';
$kategori = isset($_GET['kategori']) ? strtolower(trim($_GET['kategori'])) : '';

// 6. Logika Pencarian
if ($query || $jenjang || $kategori) {
    $hasil_search = [];

    foreach ($data_kampus as $kampus) {
        // Filter 1: Cek Kategori Kampus
        $match_kategori = empty($kategori) || stripos($kampus['kategori'], $kategori) !== false;
        
        if (!$match_kategori) {
            continue; // Skip kampus ini jika kategorinya tidak sesuai
        }

        $valid_prodi = [];
        foreach ($kampus['data_prodi'] as $prodi) {
            // Filter 2: Cek Jenjang Prodi
            $jenjang_ok = empty($jenjang) || stripos($prodi['jenjang'], $jenjang) !== false;
            
            // Filter 3: Cek Query (bisa tembus di Nama Universitas ATAU Nama Prodi)
            $query_ok = empty($query) || 
                        stripos($kampus['universitas'], $query) !== false || 
                        stripos($prodi['nama_prodi'], $query) !== false;
            
            // Jika prodi ini lolos filter jenjang dan query, masukkan ke list valid
            if ($jenjang_ok && $query_ok) {
                $valid_prodi[] = $prodi;
            }
        }

        // Kalau ada minimal 1 prodi yang lolos filter, masukkan kampus ini ke hasil akhir
        if (count($valid_prodi) > 0) {
            $kampus['data_prodi'] = $valid_prodi;
            $kampus['total_prodi'] = count($valid_prodi); // Update jumlah prodi yang relevan saja
            $hasil_search[] = $kampus;
        }
    }

    // Response Hasil Pencarian
    echo json_encode([
        "meta" => [
            "name" => "API SNBT PTN Indonesia",
            "author" => "Romi Setiawan",
            "version" => "1.1.0",
            "github" => "https://github.com/miomidev/API-PTN"
        ],
        "status" => "success",
        "total_result" => count($hasil_search),
        "filters" => [
            "q" => $query,
            "jenjang" => $jenjang,
            "kategori" => $kategori
        ],
        "data" => $hasil_search
    ], JSON_UNESCAPED_UNICODE);
} else {
    // Kalau tidak ada parameter sama sekali
    echo json_encode([
        "status" => "success",
        "message" => "Gunakan parameter pencarian. Contoh: ?q=kedokteran, ?jenjang=sarjana, ?kategori=vokasi",
        "sample_data" => array_slice($data_kampus, 0, 3) // Kasih intip 3 data saja biar ringan
    ], JSON_UNESCAPED_UNICODE);
}
