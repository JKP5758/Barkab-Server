<?php
session_start();
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
        $_SESSION['error_message'] = "Semua kolom harus diisi!";
        header("Location: ../login/index.php");
        exit;
    }

    // Siapkan pernyataan SQL untuk mengambil data pengguna berdasarkan nis
    $sql = "SELECT password, directory, db, nama FROM users WHERE nis = ?";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "s", $nis);

        // Eksekusi pernyataan
        mysqli_stmt_execute($stmt);

        // Ambil hasil
        mysqli_stmt_bind_result($stmt, $hashedPassword, $userDirectory, $userDb, $userName);
        mysqli_stmt_fetch($stmt);

        // Cek password
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['nis'] = $nis; // Simpan NIS dalam sesi
            $_SESSION['directory'] = $userDirectory; // Simpan direktori dalam sesi
            $_SESSION['db'] = $userDb; // Simpan nama database dalam sesi
            $_SESSION['nama'] = $userName; // Simpan nama pengguna dalam sesi
            // Redirect ke halaman list
            echo "<script>location.href='../view/dashboard';</script>";
        } else {
            $_SESSION['error_message'] = "NIS atau password salah!";
            header("Location: ../login/index.php");
        }

        // Tutup pernyataan
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_message'] = "Gagal menyiapkan pernyataan: " . mysqli_error($koneksi);
        header("Location: ../login/index.php");
    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>
