<?php
session_start();
$envVars = parse_ini_file('../../../.env');
$adminDir = $envVars['ROOT_DIR'];

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
$oldDir = $_POST['oldDir'];
$directory = $_POST['directory'];


// Tambahkan logika untuk mengganti nama directory
$oldDirectoryPath = $adminDir . $oldDir; // Path lama
$newDirectoryPath = $adminDir . $directory; // Path baru (misalnya menambahkan _new)

// Cek apakah directory lama ada
if (is_dir($oldDirectoryPath)) {
    // Ganti nama directory
    if (rename($oldDirectoryPath, $newDirectoryPath)) {
        // Directory berhasil diganti namanya
    } else {
        echo "Gagal mengganti nama directory.";
    }
} else {
    echo "Directory tidak ditemukan.";
    echo $oldDirectoryPath;
}

// Siapkan query dengan prepared statement
if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE `users` SET nis=?, nama=?, password=?, directory=? WHERE nis=?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("sssss", $nis_change, $nama, $hashedPassword, $directory,  $nis);
} else {
    $query = "UPDATE `users` SET nis=?, nama=?, directory=? WHERE nis=?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssss", $nis_change, $nama, $directory,  $nis);
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
