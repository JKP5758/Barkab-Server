<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$envVars = parse_ini_file('../../../.env');
$adminDir = $envVars['ADMIN_DIR'];

// Koneksi ke database
require '../../../koneksi.php';

$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil data dari form
$nis = $_POST['nis'];
$nama = $_POST['nama'];
$password = $_POST['password'];
$directory = $_POST['directory'];

// Siapkan query untuk mengupdate data pengguna
$query = "UPDATE `users` SET nama=?, directory=? WHERE nis=?";
$stmt = $koneksi->prepare($query);
if ($stmt === false) {
    die("Error preparing statement: " . $koneksi->error);
}
$stmt->bind_param("sss", $nama, $directory, $nis);

// Eksekusi query
if (!$stmt->execute()) {
    die("Error executing update: " . $stmt->error);
}

// Jika password diubah
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE `users` SET nama=?, password=?, directory=? WHERE nis=?";
    $stmt = $koneksi->prepare($query);
    if ($stmt === false) {
        die("Error preparing statement: " . $koneksi->error);
    }
    $stmt->bind_param("ssss", $nama, $hashedPassword, $directory, $nis);
    
    // Eksekusi query untuk mengupdate data pengguna
    if (!$stmt->execute()) {
        die("Error executing update: " . $stmt->error);
    }

    // Mengubah password di MySQL user
    $alterUserQuery = "ALTER USER '$nis'@'%' IDENTIFIED BY '$password'";
    if (!$koneksi->query($alterUserQuery)) {
        die("Error executing ALTER USER: " . $koneksi->error);
    }
}

// Redirect atau tampilkan pesan sukses
header("Location: index.php?message=Data berhasil diperbarui");
exit;

// Tutup koneksi
$stmt->close();
$koneksi->close();
?>
