<?php
$rootDirectory = 'C:\\xampp\\htdocs\\server-data\\';

// Memeriksa apakah parameter file ada
if (isset($_GET['file'])) {
    $relativePath = $_GET['file'];
    $file = $rootDirectory . $relativePath;

    // Pastikan file berada dalam direktori yang diizinkan
    if (strpos($file, $rootDirectory) !== 0) {
        die("Akses file tidak diizinkan.");
    }

    // Memeriksa apakah file ada
    if (file_exists($file)) {
        // Mengambil isi file
        $content = file_get_contents($file);
        $fileInfo = pathinfo($file);
        $fileName = $fileInfo['basename'];
        $fileExtension = strtolower($fileInfo['extension']);

        // Memeriksa apakah form telah disubmit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Menyimpan perubahan
            if (is_writable($file)) {
                file_put_contents($file, $_POST['content']);
                echo "<p>File telah disimpan!</p>";
                $content = $_POST['content']; // Update content to show the changes
            } else {
                echo "<p>File tidak dapat ditulis. Periksa izin file.</p>";
            }
        }

        // Menampilkan form untuk mengedit file
        echo "<h1>Edit File: $fileName</h1>";
        echo "<form method='post'>";
        
        // Gunakan textarea untuk file teks
        if (in_array($fileExtension, ['txt', 'css', 'js', 'html', 'php', 'xml', 'json', 'md', 'log'])) {
            echo "<textarea name='content' rows='20' cols='100'>" . htmlspecialchars($content) . "</textarea><br>";
        } else {
            echo "<p>Tipe file ini tidak dapat diedit secara langsung.</p>";
        }
        
        echo "<input type='submit' value='Simpan'>";
        echo "</form>";
        
        echo "<a href='list.php?directory=" . urlencode(dirname($relativePath)) . "'>Kembali ke Daftar File</a>";
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Parameter file tidak ada.";
}
?>
