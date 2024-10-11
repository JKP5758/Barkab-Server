<?php
$rootDirectory = 'C:\\xampp\\htdocs\\server-data\\';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $relativePath = isset($_POST['directory']) ? $_POST['directory'] : '';
    $item = isset($_POST['item']) ? $_POST['item'] : '';
    $oldPath = $rootDirectory . $item;

    // Pastikan path valid
    if (strpos($oldPath, $rootDirectory) !== 0) {
        die("Akses tidak diizinkan.");
    }

    if (file_exists($oldPath)) {
        $itemName = basename($oldPath);
        $itemDir = dirname($oldPath);

        if (isset($_POST['new_name'])) {
            $newName = $_POST['new_name'];
            $newPath = $itemDir . '\\' . $newName;

            if (rename($oldPath, $newPath)) {
                header("Location: list.php?directory=" . urlencode($relativePath));
                exit;
            } else {
                echo "Gagal mengganti nama.";
            }
        } else {
            // Tampilkan form untuk mengganti nama
            echo "<h2>Rename: $itemName</h2>";
            echo "<form method='post'>
                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                    <input type='hidden' name='item' value='" . htmlspecialchars($item) . "'>
                    <input type='text' name='new_name' value='" . htmlspecialchars($itemName) . "'>
                    <input type='submit' value='Rename'>
                  </form>";
        }
    } else {
        echo "File atau folder tidak ditemukan.";
    }
} else {
    echo "Metode tidak diizinkan.";
}
?>