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
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($nis) || empty($password)) {
        echo "Semua kolom harus diisi!";
        exit;
    }

    // Siapkan pernyataan SQL untuk mengambil data pengguna berdasarkan nis
    $sql = "SELECT password FROM users WHERE nis = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "s", $nis);

        // Eksekusi pernyataan
        mysqli_stmt_execute($stmt);

        // Ambil hasil
        mysqli_stmt_bind_result($stmt, $hashedPassword);
        mysqli_stmt_fetch($stmt);

        // Cek password
        if (password_verify($password, $hashedPassword)) {
            echo "Login berhasil! Selamat datang, " . htmlspecialchars($nis) . ".";
            // Di sini, kamu bisa melanjutkan ke halaman beranda atau yang lainnya
        } else {
            echo "nis atau password salah!";
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
