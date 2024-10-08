<?php
// Tentukan direktori awal (root)
$rootDirectory = 'C:\\xampp\\htdocs\\server-data\\';

// Menggunakan direktori dari parameter GET jika tersedia, jika tidak gunakan default
$relativePath = isset($_GET['directory']) ? $_GET['directory'] : '7341';
$directory = $rootDirectory . $relativePath;

// Pastikan direktori yang diminta masih dalam lingkup root directory
if (strpos($directory, $rootDirectory) !== 0) {
    $directory = $rootDirectory . '7341';
    $relativePath = '7341';
}

// Pastikan direktori ada
if (is_dir($directory)) {
    echo "<h1>Daftar File dan Direktori</h1>";
    
    echo "<p>Direktori saat ini: " . htmlspecialchars($relativePath) . "</p>";
    
    // Tombol untuk membuat folder baru
    echo "<form action='create.php' method='post' style='display:inline;'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='hidden' name='type' value='folder'>
            <input type='submit' value='+ /folder'>
          </form>";
    
    // Tombol untuk membuat file baru
    echo "<form action='create.php' method='post' style='display:inline;'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='hidden' name='type' value='file'>
            <input type='submit' value='+ .txt'>
          </form>";
    
    // Form untuk upload file
    echo "<form action='upload.php' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='file' name='files[]' multiple>
            <input type='submit' value='Upload File'>
          </form>";
    
    // Form untuk upload zip
    echo "<form action='upload.php' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='file' name='zipfile' accept='.zip'>
            <input type='submit' value='Upload ZIP'>
          </form>";
    
    // Form untuk download dan hapus file yang dipilih
    echo "<form action='download_all.php' method='post' id='fileForm'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='submit' id='downloadButton' value='Download Semua File'>
            <input type='submit' id='deleteButton' value='Hapus File Dipilih' formaction='delete.php' style='display:none;'>
          </form>";

    // Membuka direktori
    if ($handle = opendir($directory)) {
        echo "<ul>";

        // Menambahkan link "../" untuk kembali ke direktori sebelumnya
        $parentDir = dirname($relativePath);
        if ($parentDir != $relativePath && $parentDir != '.') {
            echo "<li><a href='list.php?directory=" . urlencode($parentDir) . "'><strong>../</strong></a> (Direktori Sebelumnya)</li>";
        }

        // Membaca semua file dan direktori di dalamnya
        while (false !== ($entry = readdir($handle))) {
            // Mengabaikan . dan ..
            if ($entry != "." && $entry != "..") {
                // Menampilkan setiap file/direktori
                $path = $directory . "\\" . $entry; // Path lengkap ke file/direktori
                $entryRelativePath = $relativePath . '\\' . $entry;
                if (is_dir($path)) {
                    echo "<li>
                            <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm'>
                            <a href='list.php?directory=" . urlencode($entryRelativePath) . "'><strong>$entry</strong></a> (Direktori)
                            <form action='rename.php' method='post' style='display:inline;'>
                                <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                <input type='submit' value='Rename'>
                            </form>
                            <form action='delete.php' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus folder ini?\");'>
                                <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                <input type='submit' value='Hapus'>
                            </form>
                          </li>";
                } else {
                    $fileExtension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    $editableExtensions = ['txt', 'css', 'js', 'html', 'php', 'xml', 'json', 'md', 'log']; // Tambahkan ekstensi lain sesuai kebutuhan
                    
                    if (in_array($fileExtension, $editableExtensions)) {
                        echo "<li>
                                <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm'>
                                <a href='edit.php?file=" . urlencode($entryRelativePath) . "'>$entry</a> (File)
                                <form action='rename.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                    <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                    <input type='submit' value='Rename'>
                                </form>
                                <form action='delete.php' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus file ini?\");'>
                                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                    <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                    <input type='submit' value='Hapus'>
                                </form>
                              </li>";
                    } else {
                        echo "<li>
                                <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm'>
                                $entry (File)
                                <form action='rename.php' method='post' style='display:inline;'>
                                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                    <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                    <input type='submit' value='Rename'>
                                </form>
                                <form action='delete.php' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus file ini?\");'>
                                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                    <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                    <input type='submit' value='Hapus'>
                                </form>
                              </li>";
                    }
                }
            }
        }

        echo "</ul>";
        closedir($handle); // Menutup direktori
    } else {
        echo "Tidak bisa membuka direktori.";
    }
} else {
    echo "Direktori tidak ditemukan.";
}

// JavaScript untuk mengubah teks tombol download dan menampilkan tombol hapus
echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkboxes = document.querySelectorAll('input[type=checkbox][name=\"selected[]\"]');
        var downloadButton = document.getElementById('downloadButton');
        var deleteButton = document.getElementById('deleteButton');
        
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                var checkedBoxes = document.querySelectorAll('input[type=checkbox][name=\"selected[]\"]:checked');
                if (checkedBoxes.length > 0) {
                    downloadButton.value = 'Download File Dipilih';
                    deleteButton.style.display = 'inline-block';
                } else {
                    downloadButton.value = 'Download Semua File';
                    deleteButton.style.display = 'none';
                }
            });
        });
    });
</script>";

?>