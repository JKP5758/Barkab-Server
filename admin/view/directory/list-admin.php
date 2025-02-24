<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../../login");
    exit;
}

// Tentukan direktori awal (root)
$rootDirectory = $envVars["ADMIN_DIR"];
// Menggunakan direktori dari sesi sebagai direktori default
$defaultDir = "Barkab-Server-Storage";

// Menggunakan direktori dari parameter GET jika tersedia, jika tidak gunakan default
$relativePath = isset($_GET['directory']) ? $_GET['directory'] : $defaultDir;
$directory = $rootDirectory . $relativePath;

// Pastikan direktori yang diminta masih dalam lingkup root directory dan direktori pengguna
if (strpos($directory, $rootDirectory . $defaultDir) !== 0) {
    $directory = $rootDirectory . $defaultDir;
    $relativePath = $defaultDir;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar File dan Direktori</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/logo-smk.png" type="image/png">
</head>
<body>


<?php
// Pastikan direktori ada
if (is_dir($directory)) {
    echo "<a class='logout' href='../dashboard'>Kembali</a>";

    echo "<h1>Daftar File dan Direktori Admin</h1>";
    
    echo "<p>Direktori saat ini: " . htmlspecialchars($relativePath) . "</p>";

    echo "<div class='upload-item'>";
    // Form untuk upload file
    echo "<form class='upload' action='upload.php' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            
            <label for='file-upload' class='custom-file-upload'>
                Pilih File
            </label>
            <input id='file-upload' type='file' name='files[]' multiple>
            
            <span id='file-name' class='file-name'>Tidak ada file yang dipilih</span>
            
            <input type='submit' value='Upload File' class='upload-button'>
        </form>";
    
    // Form untuk upload zip
    echo "<form class='upload' action='upload.php' method='post' enctype='multipart/form-data'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            
            <label for='zip-upload' class='custom-file-upload'>
                Pilih ZIP
            </label>
            <input id='zip-upload' type='file' name='zipfile' accept='.zip'>
            
            <span id='zip-file-name' class='file-name'>Tidak ada file ZIP yang dipilih</span>
            
            <input type='submit' value='Upload ZIP' class='upload-button'>
        </form>";
    echo "</div>";

    echo "<div class='tambah-item'>";
    // Tombol untuk membuat folder baru
    echo "<form action='create.php' method='post' title='Tambah Folder' style='display:inline; margin-right: 20px;'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='hidden' name='type' value='folder'>
            <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                <img class='tambah' src='./img/folder-tambah.png' alt='gambar tambah folder'>
            </button>
          </form>";
    
    // Tombol untuk membuat file baru
    echo "<form action='create.php' method='post' title='Tambah File' style='display:inline; margin-right: 20px;'>
            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
            <input type='hidden' name='type' value='file'>
            <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                <img class='tambah' src='./img/file-tambah.png' alt='gambar tambah folder'>
            </button>
          </form>";
    echo "</div>";

    // Periksa apakah ada file di direktori
    $hasFiles = false;
    $dirItems = scandir($directory);
    foreach ($dirItems as $item) {
        if ($item != "." && $item != ".." && is_file($directory . '/' . $item)) {
            $hasFiles = true;
            break;
        }
    }


    // Form untuk download, hapus, pindah, dan salin file yang dipilih
    echo "<form action='download_all.php' method='post' id='fileForm'>";
    echo "<input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>";
    if ($hasFiles) {
        echo "<input type='submit' id='downloadButton' class='item-action' value='Download Semua File'>";
    }
    echo "<input type='submit' id='cutButton' value='Pindahkan' formaction='move.php?action=cut' style='display:none;'>";
    echo "<input type='submit' id='copyButton' value='Salin' formaction='move.php?action=copy' style='display:none;'>";
    echo "<input type='submit' id='pasteButton' value='Tempel' formaction='move.php?action=paste' style='display:none;'>";
    echo "<input type='submit' id='cancelButton' value='Batal' formaction='move.php?action=cancel' style='display:none;'>";
    echo "<input type='submit' id='deleteButton' value='Hapus File Dipilih' formaction='delete.php' style='display:none;'>";
    echo "</form>";
    
    // Tambahkan ini sebelum menampilkan daftar file dan folder
    $selectedItems = isset($_SESSION['selected_items']) ? $_SESSION['selected_items'] : [];

    // Membuka direktori
    if ($handle = opendir($directory)) {
        echo "<div class='file-list'>";
        echo "<ul>";

        // Menambahkan link "../" untuk kembali ke direktori sebelumnya
        $parentDir = dirname($relativePath);
        if ($parentDir != $relativePath && $parentDir != '.') {
            echo "<li class='parent-dir lato'>
                    <div class='item-wrapper' style='margin: 0 ; max-width: 100%;'>
                        <a href='list-admin.php?directory=" . urlencode($parentDir) . "' class='item-link'>
                            <div class='name-file-folder'>
                                <img src='./img/folder-out.png' alt='gambar folder' srcset=''>
                                <strong>../</strong> 
                            </div>
                        </a>
                    </div>
                  </li>";
        }

        // Membaca semua file dan direktori di dalamnya
        while (false !== ($entry = readdir($handle))) {
            // Mengabaikan . dan ..
            if ($entry != "." && $entry != "..") {
                // Menampilkan setiap file/direktori
                $path = $directory . "/" . $entry; // Path lengkap ke file/direktori
                $entryRelativePath = $relativePath . '/' . $entry; // Jalur relatif menggunakan '/'
                if (is_dir($path)) {
                    // Menghitung total ukuran semua file dalam folder
                    $totalSize = 0;
                    $files = scandir($path);
                    foreach ($files as $file) {
                        if ($file != "." && $file != "..") {
                            $totalSize += filesize($path . '/' . $file);
                        }
                    }
                    // Menentukan ukuran folder
                    if ($totalSize < 1024) {
                        $ukuran = $totalSize . ' B'; // Menyimpan ukuran dalam byte jika kurang dari 1 KB
                    } elseif ($totalSize < 1024 * 1024) {
                        $ukuran = round($totalSize / 1024, 2) . ' KB'; // Menghitung ukuran folder dalam KB
                    } else {
                        $ukuran = round($totalSize / (1024 * 1024), 2) . ' MB'; // Mengonversi ke MB jika lebih dari 1 MB
                    }
                } else {
                    $fileSize = filesize($path);
                    if ($fileSize < 1024) {
                        $ukuran = $fileSize . ' B'; // Menyimpan ukuran dalam byte jika kurang dari 1 KB
                    } elseif ($fileSize < 1024 * 1024) {
                        $ukuran = round($fileSize / 1024, 2) . ' KB'; // Menghitung ukuran file dalam KB
                    } else {
                        $ukuran = round($fileSize / (1024 * 1024), 2) . ' MB'; // Mengonversi ke MB jika lebih dari 1 MB
                    }
                }
                if (is_dir($path)) {
                    echo "<li>
                            <div class='item-wrapper'>
                                <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm' class='item-checkbox'" . (in_array($entryRelativePath, $selectedItems) ? " checked" : "") . ">
                                <a href='list-admin.php?directory=" . urlencode($entryRelativePath) . "' class='item-link'>
                                    <div class='name-file-folder'>
                                        <img src='./img/folder-kosong.png' alt='gambar folder' srcset=''>
                                        <strong class='lato'>$entry</strong>
                                    </div>
                                </a>
                                $ukuran
                            </div>
                            <div class='action'>
                                <form action='rename.php' title='Ubah Nama' method='post' style='display:inline; margin-right: 10px;'>
                                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                    <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                    <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' src='./img/file-rename.png' alt='gambar rename'>
                                        </button>
                                </form>
                                <form action='delete.php' title='Hapus' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus folder ini?\");'>
                                    <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                    <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                    <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                        <img class='tambah' id='deleteButton' src='./img/file-sampah.png' alt='gambar sampah' style='border: 2px solid rgba(0, 0, 0, 1);'>
                                    </button>
                                </form>
                            </div>
                          </li>";
                } else {
                    $fileExtension = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
                    $editableExtensions = ['txt', 'css', 'js', 'html', 'php', 'xml', 'json', 'md', 'log', 'env', 'example']; // Tambahkan ekstensi lain sesuai kebutuhan
                    $mediaExtensions = ['png', 'jpg', 'jpeg', 'gif', 'mp4', 'mp3']; // Ekstensi media
        
                    // Check if the file is a media file
                    if (in_array($fileExtension, $mediaExtensions)) {
                        // Menentukan ikon berdasarkan ekstensi file
                        $icon = './img/file-media.png'; // Default icon
                        if (in_array($fileExtension, ['mp4', 'avi', 'mkv'])) {
                            $icon = './img/file-video.png'; // Ikon untuk video
                        } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                            $icon = './img/file-foto.png'; // Ikon untuk gambar
                        } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) {
                            $icon = './img/file-audio.png'; // Ikon untuk musik
                        }

                        echo "<li>
                                <div class='item-wrapper'>
                                    <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm' class='item-checkbox'" . (in_array($entryRelativePath, $selectedItems) ? " checked" : "") . ">
                                    <a href='media.php?file=" . urlencode($entryRelativePath) . "' class='item-link'>
                                        <div class='name-file-folder'>
                                            <img src='$icon' alt='gambar media' srcset=''>
                                            <span>$entry</span>
                                        </div>
                                    </a>
                                    $ukuran
                                </div>
                                <div class='action'>
                                    <form action='rename.php' title='Ubah Nama' method='post' style='display:inline; margin-right: 10px;'>
                                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                        <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                        <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' src='./img/file-rename.png' alt='gambar rename'>
                                        </button>
                                    </form>
                                    <form action='delete.php' title='Hapus' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus file ini?\");'>
                                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                        <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                        <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' id='deleteButton' src='./img/file-sampah.png' alt='gambar sampah' style='border: 2px solid rgba(0, 0, 0, 1);'>
                                        </button>
                                    </form>
                                </div>
                              </li>";
                    } elseif (in_array($fileExtension, $editableExtensions)) {
                        echo "<li>
                                <div class='item-wrapper'>
                                    <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm' class='item-checkbox'" . (in_array($entryRelativePath, $selectedItems) ? " checked" : "") . ">
                                    <a href='edit-admin.php?file=" . urlencode($entryRelativePath) . "' class='item-link'>
                                        <div class='name-file-folder'>
                                            <img src='./img/file.png' alt='gambar file' srcset=''>
                                            <span>$entry</span> 
                                        </div>
                                    </a>
                                    $ukuran
                                </div>
                                <div class='action'>
                                    <form action='rename.php' title='Ubah Nama' method='post' style='display:inline; margin-right: 10px;'>
                                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                        <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                        <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' src='./img/file-rename.png' alt='gambar rename'>
                                        </button>
                                    </form>
                                    <form action='delete.php' title='Hapus' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus file ini?\");'>
                                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                        <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                        <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' id='deleteButton' src='./img/file-sampah.png' alt='gambar sampah' style='border: 2px solid rgba(0, 0, 0, 1);'>
                                        </button>
                                    </form>
                                </div>
                              </li>";
                    } else {
                        echo "<li>
                                <div class='item-wrapper'>
                                    <input type='checkbox' name='selected[]' value='" . htmlspecialchars($entryRelativePath) . "' form='fileForm' class='item-checkbox'" . (in_array($entryRelativePath, $selectedItems) ? " checked" : "") . ">
                                    <a href='#' class='item-link'>
                                        <div class='name-file-folder'>
                                            $entry (File)
                                        </div>
                                    </a>
                                    $ukuran
                                </div>
                                <div class='action'>
                                    <form action='rename.php' title='Ubah Nama' method='post' style='display:inline; margin-right: 10px;'>
                                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                        <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                        <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' src='./img/file-rename.png' alt='gambar rename'>
                                        </button>
                                    </form>
                                    <form action='delete.php' title='Hapus' method='post' style='display:inline;' onsubmit='return confirm(\"Anda yakin ingin menghapus file ini?\");'>
                                        <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                                        <input type='hidden' name='item' value='" . htmlspecialchars($entryRelativePath) . "'>
                                        <button type='submit' style='border: none; background: none; cursor: pointer; padding: 0; display: flex;'>
                                            <img class='tambah' id='deleteButton' src='./img/file-sampah.png' alt='gambar sampah' style='border: 2px solid rgba(0, 0, 0, 1);'>
                                        </button>
                                    </form>
                                </div>
                              </li>";
                    }
                }
            }
        }
        

        echo "</ul>";
        echo "</div>";
        closedir($handle); // Menutup direktori
    } else {
        echo "Tidak bisa membuka direktori.";
    }
} else {
    echo "Direktori tidak ditemukan.";
    echo $defaultDir;
    echo $directory;
}

// JavaScript untuk mengubah teks tombol download dan menampilkan tombol hapus
$clipboardStatus = isset($_SESSION['clipboard']) ? json_encode($_SESSION['clipboard']) : 'null';
$selectedItemsJson = json_encode($selectedItems);
$hasFilesJson = json_encode($hasFiles);
?>

</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var checkboxes = document.querySelectorAll('.item-checkbox');
        var downloadButton = document.getElementById('downloadButton');
        var deleteButton = document.getElementById('deleteButton');
        var cutButton = document.getElementById('cutButton');
        var copyButton = document.getElementById('copyButton');
        var pasteButton = document.getElementById('pasteButton');
        var cancelButton = document.getElementById('cancelButton');
        
        var clipboardContent = <?php echo $clipboardStatus; ?>;
        var selectedItems = <?php echo $selectedItemsJson; ?>;
        var hasFiles = <?php echo $hasFilesJson; ?>;
        
        function updateButtonsVisibility() {
            var checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            if (clipboardContent) {
                pasteButton.style.display = 'inline-block';
                cancelButton.style.display = 'inline-block';
                cutButton.style.display = 'none';
                copyButton.style.display = 'none';
            } else {
                pasteButton.style.display = 'none';
                cancelButton.style.display = 'none';
                if (checkedBoxes.length > 0) {
                    cutButton.style.display = 'inline-block';
                    copyButton.style.display = 'inline-block';
                } else {
                    cutButton.style.display = 'none';
                    copyButton.style.display = 'none';
                }
            }
            
            if (checkedBoxes.length > 0) {
                if (downloadButton) downloadButton.value = 'Download File Dipilih';
                deleteButton.style.display = 'inline-block';
            } else {
                if (downloadButton) {
                    if (hasFiles) {
                        downloadButton.value = 'Download Semua File';
                        downloadButton.style.display = 'inline-block';
                    } else {
                        downloadButton.style.display = 'none';
                    }
                }
                deleteButton.style.display = 'none';
            }
        }
        
        function updateItemStatus() {
            checkboxes.forEach(function(checkbox) {
                var listItem = checkbox.closest('li');
                var link = listItem.querySelector('.item-link');
                var actions = listItem.querySelectorAll('.item-action');
                
                if (checkbox.checked) {
                    if (link) link.style.pointerEvents = 'none';
                    actions.forEach(function(action) {
                        action.disabled = true;
                    });
                } else {
                    if (link) link.style.pointerEvents = '';
                    actions.forEach(function(action) {
                        action.disabled = false;
                    });
                }
            });
        }
        
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    selectedItems.push(this.value);
                } else {
                    var index = selectedItems.indexOf(this.value);
                    if (index > -1) {
                        selectedItems.splice(index, 1);
                    }
                }
                
                updateButtonsVisibility();
                updateItemStatus();
                
                // Kirim status checkbox ke server
                fetch('update_selected.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({selected: selectedItems})
                });
            });
        });
        
        // Initial update
        updateButtonsVisibility();
        updateItemStatus();
    });

    // JavaScript untuk menampilkan nama file yang dipilih
    const fileInput = document.getElementById('file-upload');
    const fileName = document.getElementById('file-name');
    const uploadButton = document.querySelector('input[type="submit"][value="Upload File"]');
    const zipInput = document.getElementById('zip-upload');
    const zipFileName = document.getElementById('zip-file-name');
    const zipUploadButton = document.querySelector('input[type="submit"][value="Upload ZIP"]');

    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            const fileList = Array.from(this.files).map(file => file.name).join(', ');
            fileName.textContent = fileList;
            uploadButton.classList.add('active');
        } else {
            fileName.textContent = 'Tidak ada file yang dipilih';
            uploadButton.classList.remove('active');
        }
    });

    zipInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            zipFileName.textContent = this.files[0].name;
            zipUploadButton.classList.add('active');
        } else {
            zipFileName.textContent = 'Tidak ada file ZIP yang dipilih';
            zipUploadButton.classList.remove('active');
        }
    });
</script>