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
    $directory = trim($_POST['directory']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($nis) || empty($nama) || empty($directory) || empty($password)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Cek apakah direktori sudah ada
    $userDir = 'C:\\xampp\\htdocs\\server-data\\' . $directory;
    if (file_exists($userDir)) {
        echo "Error: Direktori sudah ada. Silakan pilih nama direktori lain.";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan pernyataan SQL untuk menyimpan data
    $sql = "INSERT INTO users (nis, nama, directory, password) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "ssss", $nis, $nama, $directory, $hashedPassword);

        // Eksekusi pernyataan
        if (mysqli_stmt_execute($stmt)) {
            // Buat direktori untuk pengguna
            if (mkdir($userDir, 0777, true)) {
                echo "Pendaftaran berhasil dan direktori telah dibuat!";
                header("Location: index.php");
            } else {
                echo "Pendaftaran berhasil, tapi gagal membuat direktori.";
            }
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
