<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../login/login.php");
    exit;
}

$rootDirectory = $envVars['ADMIN_DIR'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $relativePath = isset($_POST['directory']) ? $_POST['directory'] : '';
    $itemToDelete = isset($_POST['item']) ? $_POST['item'] : '';

    // Validasi path
    $fullPath = $rootDirectory . $itemToDelete;
    if (strpos($fullPath, $rootDirectory) !== 0) {
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
            // Hapus file
            unlink($fullPath);
        }
        echo "Item berhasil dihapus.";
    } else {
        echo "Item tidak ditemukan.";
    }

    // Redirect kembali ke halaman list
    header("Location: list-admin.php?directory=" . urlencode($relativePath));
    exit;
} else {
    echo "Metode tidak diizinkan.";
}
?>