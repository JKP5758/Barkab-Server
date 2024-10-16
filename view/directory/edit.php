<?php

$envVars = parse_ini_file('../../.env');
$rootDirectory = $envVars['ROOT_DIR'];

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text Editor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Text Editor</h1>
        <a class='logout' href='list.php?directory=<?php echo urlencode(dirname($relativePath)); ?>'>Kembali ke Daftar File</a>
    </header>

    <div class="editor-wrapper">
        <?php

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
                        echo "<p class='success-message' id='successMessage'>File telah disimpan!</p>";
                        $content = $_POST['content']; // Update content to show the changes
                    } else {
                        echo "<p class='error-message'>File tidak dapat ditulis. Periksa izin file.</p>";
                    }
                }

                // Menampilkan form untuk mengedit file
                echo "<h2>Edit File: $fileName</h2>";
                echo "<form method='post'>";
                
                // Gunakan Ace Editor untuk file teks
                if (in_array($fileExtension, ['txt', 'css', 'js', 'html', 'php', 'xml', 'json', 'md', 'log'])) {
                    echo "<div id='editor' style='height: 500px; width: 100%;'>" . htmlspecialchars($content) . "</div>";
                    echo "<textarea name='content' id='content' style='display:none;'>" . htmlspecialchars($content) . "</textarea>";
                } else {
                    echo "<p>Tipe file ini tidak dapat diedit secara langsung.</p>";
                }

                echo "<input style='margin-top:20px;' class='submit-btn' type='submit' value='Simpan'>";
                echo "</form>";
                
                // Tambahkan Ace Editor JavaScript
                echo "<script src='https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js'></script>";
                echo "<script>
                        var editor = ace.edit('editor');
                        editor.setTheme('ace/theme/monokai');
                        editor.session.setMode('ace/mode/" . getAceMode($fileExtension) . "');
                        editor.session.on('change', function() {
                            document.getElementById('content').value = editor.getValue();
                        });
                      </script>";
            } else {
                echo "<p class='error-message'>File tidak ditemukan.</p>";
            }
        } else {
            echo "<p class='error-message'>Parameter file tidak ada.</p>";
        }

        // Fungsi untuk menentukan mode Ace Editor berdasarkan ekstensi file
        function getAceMode($extension) {
            $modes = [
                'txt' => 'plain_text',
                'css' => 'css',
                'js' => 'javascript',
                'html' => 'html',
                'php' => 'php',
                'xml' => 'xml',
                'json' => 'json',
                'md' => 'markdown',
                'log' => 'log'
            ];
            return isset($modes[$extension]) ? $modes[$extension] : 'plain_text';
        }
        ?>
    </div>

</div>

<script>
    // Menghilangkan pesan sukses setelah 5 detik
    setTimeout(function() {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.classList.add('fade-out');
        }
    }, 3000);
</script>

</body>
</html>
