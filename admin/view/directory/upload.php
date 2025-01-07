<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../login/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rootDirectory = $envVars['ADMIN_DIR'];
    $relativePath = isset($_POST['directory']) ? $_POST['directory'] : $_SESSION['directory'];
    $directory = $rootDirectory . $relativePath;

    // Pastikan direktori ada dan valid
    if (!is_dir($directory) || strpos($directory, $rootDirectory) !== 0) {
        die("Direktori tidak valid.");
    }

    // Upload file
    if (isset($_FILES['files'])) {
        foreach ($_FILES['files']['name'] as $i => $name) {
            if (empty($name)) continue;
            $tmpName = $_FILES['files']['tmp_name'][$i];
            $targetPath = $directory . '/' . $name;
            
            if (move_uploaded_file($tmpName, $targetPath)) {
                echo "File $name berhasil diunggah.<br>";
            } else {
                echo "Gagal mengunggah $name.<br>";
            }
        }
    }

    // Upload dan ekstrak ZIP
    if (isset($_FILES['zipfile']) && $_FILES['zipfile']['error'] == 0) {
        $zipName = $_FILES['zipfile']['name'];
        $zipTmp = $_FILES['zipfile']['tmp_name'];
        $zipPath = $directory . '/' . $zipName;

        if (move_uploaded_file($zipTmp, $zipPath)) {
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $zip->extractTo($directory);
                $zip->close();
                unlink($zipPath);
                echo "File ZIP berhasil diekstrak.<br>";
            } else {
                echo "Gagal membuka file ZIP.<br>";
            }
        } else {
            echo "Gagal mengunggah file ZIP.<br>";
        }
    }

    // Redirect kembali ke halaman list
    header("Location: list-admin.php?directory=" . urlencode($relativePath));
    exit;
} else {
    echo "Metode tidak diizinkan.";
}
?>
