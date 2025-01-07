<?php
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
    $nama = trim($_POST['nama']);
    $password = trim($_POST['password']);
    $status = trim('admin');

    // Validasi input
    if (empty($user) || empty($nama)) {
        echo "Hanya Password yang boleh Kosong!";
        exit;
    }

    if (empty($password)){
        $password = $user;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan pernyataan SQL untuk menyimpan data
    $sql = "INSERT INTO admins (user, nama, password, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "ssss", $user, $nama, $hashedPassword, $status);
        
        // Eksekusi pernyataan
        if (mysqli_stmt_execute($stmt)) {
            echo "Pendaftaran berhasil!";
        } else {
            echo "Gagal mendaftar: " . mysqli_error($koneksi);
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
