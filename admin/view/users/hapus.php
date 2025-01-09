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

$nis = htmlspecialchars($_GET['nis']);

$hapusQuery = mysqli_query($koneksi,"SELECT directory, db FROM users WHERE nis = '$nis'");
$delet = mysqli_fetch_assoc($hapusQuery);

// Tambahkan logika untuk menghapus directory
if ($delet) {
    $fullPath = $adminDir . $delet['directory']; // Path lengkap ke directory yang akan dihapus

    // Validasi path
    if (strpos($fullPath, $adminDir) !== 0) {
        die("Akses ditolak");
    }

    if (file_exists($fullPath)) {
        if (is_dir($fullPath)) {
            // Hapus direktori dan isinya
            $it = new RecursiveDirectoryIterator($fullPath, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach($files as $file) {
                if ($file->isDir()){
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
            rmdir($fullPath);
        } else {
            // Hapus file jika bukan directory
            unlink($fullPath);
        }
        echo "Directory berhasil dihapus.";
    } else {
        echo "Directory tidak ditemukan.";
    }
} else {
    echo "Tidak ada directory yang ditentukan untuk dihapus.";
}

// Hapus Databasenya
$deletDbQuery = mysqli_query($koneksi, "DROP DATABASE `{$delet['db']}`");
if (!$deletDbQuery) {
    die("Error dropping database: " . mysqli_error($koneksi));
}

// Hapus Akun Database
$deletUserDB = mysqli_query($koneksi, "DROP USER '$nis'@'%'");
if (!$deletUserDB) {
    die("Error dropping user: " . mysqli_error($koneksi));
}

// Hapus Akun
$deletUser = mysqli_query($koneksi, "DELETE FROM users WHERE `users`.`nis` = '$nis'");
if (!$deletUser) {
    die("Error deleting user: " . mysqli_error($koneksi));
}

header("Location: index.php?message=Data berhasil dihapus");
?>