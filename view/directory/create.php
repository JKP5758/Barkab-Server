<?php
session_start();
$envVars = parse_ini_file('../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['nis'])) {
    header("Location: ../login/login.php");
    exit;
}

$rootDirectory = $envVars['ROOT_DIR'];
$defaultDir = $_SESSION['directory'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $relativePath = isset($_POST['directory']) ? $_POST['directory'] : '';
    $directory = $rootDirectory . $relativePath;
    $type = isset($_POST['type']) ? $_POST['type'] : '';

    // Pastikan direktori valid
    if (strpos($directory, $rootDirectory) !== 0) {
        die("Akses tidak diizinkan.");
    }

    if ($type == 'folder') {
        // Form untuk membuat folder baru
        echo "<h2>Buat Folder Baru</h2>";
        echo "<form method='post'>
                <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                <input type='hidden' name='type' value='folder'>
                <input type='text' name='folder_name' placeholder='Nama Folder' required>
                <input type='submit' name='create' value='Buat Folder'>
                <a href='list.php?directory=" . urlencode($relativePath) . "'><button type='button'>Batal</button></a>
              </form>";

        if (isset($_POST['create'])) {
            $folderName = $_POST['folder_name'];
            $newFolder = $directory . '\\' . $folderName;
            if (!file_exists($newFolder)) {
                if (mkdir($newFolder)) {
                    header("Location: list.php?directory=" . urlencode($relativePath));
                    exit;
                } else {
                    echo "Gagal membuat folder.";
                }
            } else {
                echo "Folder sudah ada.";
            }
        }
    } elseif ($type == 'file') {
        // Form untuk membuat file baru
        echo "<h2>Buat File Baru</h2>";
        echo "<form method='post'>
                <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                <input type='hidden' name='type' value='file'>
                <input type='text' name='file_name' placeholder='Nama File' required>
                <input type='submit' name='create' value='Buat File'>
                <a href='list.php?directory=" . urlencode($relativePath) . "'><button type='button'>Batal</button></a>
              </form>";

        if (isset($_POST['create'])) {
            $fileName = $_POST['file_name'];
            if (pathinfo($fileName, PATHINFO_EXTENSION) == '') {
                $fileName .= '.txt';
            }
            $newFile = $directory . '\\' . $fileName;
            if (!file_exists($newFile)) {
                if (file_put_contents($newFile, '') !== false) {
                    header("Location: list.php?directory=" . urlencode($relativePath));
                    exit;
                } else {
                    echo "Gagal membuat file.";
                }
            } else {
                echo "File sudah ada.";
            }
        }
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>