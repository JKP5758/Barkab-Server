<?php
if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    echo "<h1>Unggah File yang Telah Diedit</h1>";
    echo "<form action='upload.php' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='original_file' value='" . htmlspecialchars($file) . "'>
            <label for='file'>Pilih file untuk diunggah (ganti nama jika diperlukan):</label>
            <input type='file' name='file' id='file' required>
            <input type='submit' value='Unggah'>
          </form>";
} else {
    echo "File tidak ditentukan.";
}
?>
