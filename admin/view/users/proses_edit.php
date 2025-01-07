<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../../login");
    exit;
}

// Koneksi ke database
require '../../../koneksi.php';

$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari form
$nis = $_POST['nis'];
$nis_change= $_POST['nis_change'];
$nama = $_POST['nama'];
$password = $_POST['password'];
$directory = $_POST['directory'];
$db = $_POST['db'];

// Siapkan query dengan prepared statement
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE `users` SET nis=?, nama=?, password=?, directory=?, db=? WHERE nis=?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssssss", $nis_change, $nama, $hashedPassword, $directory, $db, $nis);
} else {
    $query = "UPDATE `users` SET nis=?, nama=?, directory=?, db=? WHERE nis=?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("sssss", $nis_change, $nama, $directory, $db, $nis);
}

// Eksekusi query
if ($stmt->execute()) {
    header("Location: index.php?message=Data berhasil diperbarui");
} else {
    echo "Error: " . $stmt->error;
}

// Tutup koneksi
$stmt->close();
mysqli_close($koneksi);
?>
