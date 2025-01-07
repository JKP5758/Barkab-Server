<?php
session_start();
// Koneksi ke database
require '../../koneksi.php';

$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mendapatkan data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($user) || empty($password)) {
        $_SESSION['error_message'] = "Semua kolom harus diisi!";
        header("Location: ../login");
        exit;
    }

    // Siapkan pernyataan SQL untuk mengambil data pengguna berdasarkan user
    $sql = "SELECT password, nama, user, status FROM admins WHERE user = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "s", $user);

        // Eksekusi pernyataan
        mysqli_stmt_execute($stmt);

        // Ambil hasil
        mysqli_stmt_bind_result($stmt, $hashedPassword, $name, $user, $status);
        mysqli_stmt_fetch($stmt);

        // Cek password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user'] = $user; 
            $_SESSION['name'] = $name; 
            $_SESSION['status'] = $status;
            // Redirect ke halaman list
            echo "<script>location.href='../view/dashboard';</script>";
        } else {
            $_SESSION['error_message'] = "Username atau password salah!";
            header("Location: ../login");
        }

        // Tutup pernyataan
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_message'] = "Gagal menyiapkan pernyataan: " . mysqli_error($koneksi);
        header("Location: ../login");
    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>
