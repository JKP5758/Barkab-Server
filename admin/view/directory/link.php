<?php
// Path folder target (yang ingin di-link)
$target = '/path/to/your/storage/directory';

// Path folder link (tempat symbolic link dibuat)
$link = '/path/to/public/storage';

// Membuat symbolic link
if (symlink($target, $link)) {
    echo "Symbolic link berhasil dibuat!";
} else {
    echo "Gagal membuat symbolic link.";
}
?>
