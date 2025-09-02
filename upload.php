<?php
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input["image"])) {
    die("Tidak ada data gambar.");
}

// Ambil base64 image
$img = $input["image"];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);

// Simpan ke file
if (!is_dir("uploads")) {
    mkdir("uploads");
}
$filename = "uploads/" . uniqid() . ".png";
file_put_contents($filename, $data);

// Simpan ke database (contoh MySQL)
$conn = new mysqli("localhost", "root", "", "kamera_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
$stmt = $conn->prepare("INSERT INTO foto (file_path) VALUES (?)");
$stmt->bind_param("s", $filename);
$stmt->execute();

echo "✅ Foto berhasil disimpan: $filename";
?>


