# 🎓 API SNBT PTN Indonesia

![Status](https://img.shields.io/badge/Status-Active-success)
![Type](https://img.shields.io/badge/Type-Public_API-blue)
![Author](https://img.shields.io/badge/Author-Romi_Setiawan-orange)
![License](https://img.shields.io/badge/License-Non--Commercial-red)

Dokumentasi resmi untuk **API Data Perguruan Tinggi Negeri (PTN) Indonesia**.

API ini menyediakan data lengkap mengenai Universitas, Program Studi (Prodi), Daya Tampung (2025), dan Jumlah Peminat (2024) berdasarkan data seleksi masuk SNBT. Sangat cocok digunakan untuk prototyping aplikasi pendidikan, simulasi tryout, atau riset data akademik.



## 🚀 Cara Penggunaan

Anda tidak perlu menginstall apapun. Cukup lakukan *Request HTTP GET* ke endpoint yang tersedia di bawah ini. Bisa digunakan untuk project Web, Android (Kotlin/Java), Flutter, atau iOS.

### 1. Endpoint Pencarian
Mencari data universitas dan prodi berdasarkan kata kunci.

- **Method:** `GET`
- **URL:** `https://api-ptn.miomidev.com/`
- **Parameter:** 
  - `q` *(Opsional)*: Kata kunci (Nama Kampus atau Nama Prodi). Contoh: `q=kedokteran`
  - `jenjang` *(Opsional)*: Filter jenjang pendidikan. Contoh: `jenjang=sarjana`
  - `kategori` *(Opsional)*: Filter jenis kampus. Contoh: `kategori=vokasi`

#### Contoh Request Lanjutan
Mencari jurusan kedokteran untuk jenjang vokasi (D3/D4):
```bash
GET [https://api-ptn.miomidev.com/?q=kedokteran&jenjang=terapan&kategori=vokasi]
```

## 📦 Struktur Response
API akan mengembalikan data dalam format JSON. Setiap response memiliki header meta yang berisi informasi API dan Author, serta data yang berisi hasil pencarian.

**Contoh Response:**

```json
{
  "meta": {
    "name": "API SNBT PTN Indonesia",
    "author": "Romi Setiawan",
    "version": "1.0.0",
    "github": "https://github.com/romisetiawan/api-ptn"
  },
  "status": "success",
  "total_result": 1,
  "query": "bogor",
  "data": [
    {
      "universitas": "INSTITUT PERTANIAN BOGOR\n(https://registrasi.admisi.ipb.ac.id)",
      "kategori": "PTN Akademik (Sarjana)",
      "total_prodi": 65,
      "data_prodi": [
        {
          "nama_prodi": "AGRIBISNIS",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 54,
          "peminat_2024": 1034
        },
        {
          "nama_prodi": "AGRONOMI DAN HORTIKULTURA",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 68,
          "peminat_2024": 656
        },
        {
          "nama_prodi": "ARSITEKTUR LANSEKAP",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 30,
          "peminat_2024": 530
        },
        {
          "nama_prodi": "BIOKIMIA",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 33,
          "peminat_2024": 269
        },
        {
          "nama_prodi": "BIOLOGI",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 33,
          "peminat_2024": 353
        },
        {
          "nama_prodi": "EKONOMI SUMBERDAYA DAN LINGKUNGAN",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 39,
          "peminat_2024": 456
        },
        {
          "nama_prodi": "ILMU DAN TEKNOLOGI KELAUTAN",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 39,
          "peminat_2024": 531
        },
        {
          "nama_prodi": "ILMU KOMPUTER",
          "jenjang": "Sarjana",
          "daya_tampung_2025": 51,
          "peminat_2024": 1586
        },
    }
  ]
}
```

## 💻 Contoh Implementasi
Berikut adalah contoh cara menggunakan API ini di berbagai bahasa pemrograman.

![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)

JavaScript (Fetch API) :
``` bash
const keyword = 'ugm';

fetch(`https://api-ptn.miomidev.com/?q=${keyword}`)
  .then(response => response.json())
  .then(result => {
    console.log("Author:", result.meta.author);
    console.log("Data Kampus:", result.data);
  })
  .catch(error => console.error('Error:', error));

```

![Flutter](https://img.shields.io/badge/Flutter-%2302569B.svg?style=for-the-badge&logo=Flutter&logoColor=white)
![Dart](https://img.shields.io/badge/dart-%230175C2.svg?style=for-the-badge&logo=dart&logoColor=white)

Flutter (Dart)
``` bash
import 'package:http/http.dart' as http;
import 'dart:convert';

Future<void> searchKampus(String keyword) async {
  final url = Uri.parse('[https://api-ptn.miomidev.com/?q=$keyword]');
  
  try {
    final response = await http.get(url);
    if (response.statusCode == 200) {
      final data = json.decode(response.body);
      print("Ditemukan: ${data['total_result']} kampus");
    }
  } catch (e) {
    print("Error: $e");
  }
}

```

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)

PHP (cURL)

``` bash
<?php
$keyword = "itb";
$url = "[https://api-ptn.miomidev.com/?q=]" . urlencode($keyword);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);

$data = json_decode($output, true);
print_r($data);
?>
```

## ⚠️ Syarat & Ketentuan (Terms of Use)

1. Atribusi: API ini dibuat dan dikelola oleh Romi Setiawan (MiomiDev). Dilarang mengubah, menghapus, atau mengklaim kepemilikan data tanpa izin.

2. Penggunaan: Gratis untuk keperluan pendidikan, belajar, dan riset personal (Non-Komersial).

3. Larangan: Dilarang keras memperjualbelikan akses endpoint ini atau melakukan *spamming request* yang membebani server.