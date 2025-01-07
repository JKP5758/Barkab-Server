<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../login/login.php");
    exit;
}

$rootDirectory = $envVars['ADMIN_DIR'];
$defaultDir = $_SESSION['directory'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
    <link rel="icon" href="./img/logo-smk.png" type="image/png">
    <title>Buat Folder atau File</title>
</head>
<body>
    <div class="container"> <!-- Added a container for styling -->
        <?php
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
                echo "<form method='post' class='rename-form'> <!-- Added class for form styling -->
                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                        <input type='hidden' name='type' value='folder'>
                        <input type='text' name='folder_name' class='rename-input' placeholder='Nama Folder' required>
                        <input type='submit' name='create' class='rename-submit' value='Buat Folder'>
                        <a href='list-admin.php?directory=" . urlencode($relativePath) . "'><button type='button' class='cancel-button'>Batal</button></a>
                      </form>";

                if (isset($_POST['create'])) {
                    $folderName = $_POST['folder_name'];
                    $newFolder = $directory . '/' . $folderName;
                    if (!file_exists($newFolder)) {
                        if (mkdir($newFolder)) {
                            header("Location: list-admin.php?directory=" . urlencode($relativePath));
                            exit;
                        } else {
                            echo "<div class='error-message'>Gagal membuat folder.</div>";
                        }
                    } else {
                        echo "<div class='error-message'>Folder sudah ada.</div>";
                    }
                }
            } elseif ($type == 'file') {
                // Form untuk membuat file baru
                echo "<h2>Buat File Baru</h2>";
                echo "<form method='post' class='rename-form'> <!-- Added class for form styling -->
                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                        <input type='hidden' name='type' value='file'>
                        <input type='text' name='file_name' class='rename-input' placeholder='Nama File' required>
                        <input type='submit' name='create' class='rename-submit' value='Buat File'>
                        <a href='list-admin.php?directory=" . urlencode($relativePath) . "'><button type='button' class='cancel-button'>Batal</button></a>
                      </form>";

                if (isset($_POST['create'])) {
                    $fileName = $_POST['file_name'];
                    if (pathinfo($fileName, PATHINFO_EXTENSION) == '') {
                        $fileName .= '.txt';
                    }
                    $newFile = $directory . '/' . $fileName;
                    if (!file_exists($newFile)) {
                        if (file_put_contents($newFile, '') !== false) {
                            header("Location: list-admin.php?directory=" . urlencode($relativePath));
                            exit;
                        } else {
                            echo "<div class='error-message'>Gagal membuat file.</div>";
                        }
                    } else {
                        echo "<div class='error-message'>File sudah ada.</div>";
                    }
                }
            }
        } else {
            echo "<div class='error-message'>Metode tidak diizinkan.</div>";
        }
        ?>
    </div>
</body>
</html>
