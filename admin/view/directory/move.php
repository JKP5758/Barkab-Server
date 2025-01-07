<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../login/login.php");
    exit;
}

$rootDirectory = $envVars['ADMIN_DIR'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $relativePath = isset($_POST['directory']) ? $_POST['directory'] : $_SESSION['directory'];
    $directory = $rootDirectory . $relativePath;

    // Pastikan direktori valid
    if (strpos($directory, $rootDirectory) !== 0) {
        die("Akses tidak diizinkan.");
    }

    if ($action == 'cut' || $action == 'copy') {
        if (isset($_POST['selected'])) {
            $selectedItems = $_POST['selected'];
            $_SESSION['clipboard'] = [
                'action' => $action,
                'items' => $selectedItems,
                'source_directory' => $relativePath
            ];
            // Tidak perlu mengubah $_SESSION['selected_items'] di sini
            header("Location: list-admin.php?directory=" . urlencode($relativePath) . "&message=Items " . ($action == 'cut' ? 'cut' : 'copied'));
            exit;
        }
    } elseif ($action == 'paste') {
        if (isset($_SESSION['clipboard'])) {
            $clipboard = $_SESSION['clipboard'];
            $sourceDir = $rootDirectory . $clipboard['source_directory'];
            $destDir = $directory;

            foreach ($clipboard['items'] as $item) {
                $sourcePath = $rootDirectory . $item;
                $destPath = $destDir . '/' . basename($item);

                if ($clipboard['action'] == 'cut') {
                    rename($sourcePath, $destPath);
                } else { // copy
                    if (is_dir($sourcePath)) {
                        copyDir($sourcePath, $destPath);
                    } else {
                        copy($sourcePath, $destPath);
                    }
                }
            }

            unset($_SESSION['clipboard']);
            unset($_SESSION['selected_items']); // Hapus item yang dipilih setelah paste
            header("Location: list-admin.php?directory=" . urlencode($relativePath) . "&message=Items pasted");
            exit;
        }
    } elseif ($action == 'cancel') {
        unset($_SESSION['clipboard']);
        unset($_SESSION['selected_items']);
        header("Location: list-admin.php?directory=" . urlencode($relativePath) . "&message=Operation cancelled");
        exit;
    }
}

function copyDir($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            if (is_dir($src . '/' . $file)) {
                copyDir($src . '/' . $file, $dst . '/' . $file);
            } else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

// Jika tidak ada aksi valid, kembali ke halaman list
header("Location: list-admin.php?directory=" . urlencode($relativePath));
exit;
?>