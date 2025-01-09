<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
$envVars = parse_ini_file('../../../.env');
$adminDir = $envVars['ROOT_DIR'];

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
$oldDirectory = $_POST['oldDir']; // Ambil direktori lama

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

// Mengubah nama folder di server
$oldPath = $adminDir . $oldDirectory; // Path lama
$newPath = $adminDir . $directory; // Path baru

if (is_dir($oldPath)) {
    if (rename($oldPath, $newPath)) {
        // Folder berhasil diganti namanya
    } else {
        echo "Gagal mengganti nama folder.";
    }
} else {
    echo "Folder tidak ditemukan.";
    echo $oldPath;
}

// Jika password diubah
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $pwDbQuery = $koneksi->prepare("UPDATE `users` SET password=? WHERE nis=?");
    if ($pwDbQuery === false) {
        die("Error preparing password update statement: " . $koneksi->error);
    }
    $pwDbQuery->bind_param("ss", $hashedPassword, $nis);
    
    // Eksekusi query untuk mengubah password
    if (!$pwDbQuery->execute()) {
        die("Error executing password update: " . $pwDbQuery->error);
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
