<?php
// Koneksi ke database
require '../koneksi.php';

$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mendapatkan data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($nis) || empty($nama) || empty($password)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan pernyataan SQL untuk menyimpan data
    $sql = "INSERT INTO users (nis, nama, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "sss", $nis, $nama, $hashedPassword);

        // Eksekusi pernyataan
        if (mysqli_stmt_execute($stmt)) {
            echo "Pendaftaran berhasil!";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }

        // Tutup pernyataan
        mysqli_stmt_close($stmt);
    } else {
        echo "Gagal menyiapkan pernyataan: " . mysqli_error($koneksi);
    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>
