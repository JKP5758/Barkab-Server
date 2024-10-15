<?php
session_start();
$envVars = parse_ini_file('../../.env');

if (isset($_POST['directory'])) {
    // Ambil dan decode directory dari POST
    $directory = urldecode($_POST['directory']);
    
    // Tentukan root directory
    $rootDirectory = rtrim($envVars['ROOT_DIR'], '/');

    // Debugging: Cek nilai variabel
    error_log("Root Directory: $rootDirectory");
    error_log("Requested Directory: $directory");

    // Jika directory sudah ada di $_SESSION, kita cukup menggunakan $_POST['directory']
    // Pastikan kita tidak menggandakan nama folder
    $fullDirectoryPath = $rootDirectory . '/' . ltrim($directory, '/');

    // Cek apakah direktori ada
    if (!is_dir($fullDirectoryPath)) {
        echo "Direktori tidak ditemukan: $fullDirectoryPath";
        exit;
    }

    // Menyimpan nama direktori di $_SESSION
    $_SESSION['directory'] = $directory;

    // Nama file zip
    $zipFileName = tempnam(sys_get_temp_dir(), 'files_') . '.zip';

    // Membuat objek ZipArchive
    $zip = new ZipArchive();

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Jika ada file yang dipilih
        if (isset($_POST['selected']) && !empty($_POST['selected'])) {
            $selectedFiles = $_POST['selected'];
            foreach ($selectedFiles as $file) {
                $fullPath = $rootDirectory . '/' . ltrim($file, '/'); // Menghindari path duplikat
                if (file_exists($fullPath)) {
                    if (is_dir($fullPath)) {
                        // Jika direktori, tambahkan semua file di dalamnya
                        $files = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($fullPath),
                            RecursiveIteratorIterator::LEAVES_ONLY
                        );
                        foreach ($files as $fileInfo) {
                            if (!$fileInfo->isDir()) {
                                $filePath = $fileInfo->getRealPath();
                                // Menggunakan path relatif yang konsisten
                                // Mendapatkan path relatif dari fullPath
                                $relativePath = str_replace($rootDirectory . '/', '', $filePath);
                                $zip->addFile($filePath, $relativePath);
                            }
                        }
                    } else {
                        // Jika file, tambahkan langsung
                        $relativePath = str_replace($rootDirectory . '/', '', $fullPath);
                        $zip->addFile($fullPath, $relativePath);
                    }
                } else {
                    error_log("File tidak ditemukan: $fullPath");
                }
            }
        } else {
            // Jika tidak ada yang dipilih, download semua
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($fullDirectoryPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $fileInfo) {
                if (!$fileInfo->isDir()) {
                    $filePath = $fileInfo->getRealPath();
                    // Menggunakan path relatif yang konsisten
                    $relativePath = str_replace($rootDirectory . '/', '', $filePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }

        $zip->close();

        // Debug: Periksa apakah file ZIP ada
        if (file_exists($zipFileName)) {
            // Mengatur header untuk download file zip
            $zipFileDownloadName = "files_" . $_SESSION['directory'] . ".zip"; // Nama file sesuai dengan format yang diminta
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileDownloadName . '"');
            header('Content-Length: ' . filesize($zipFileName));

            // Mengeluarkan file zip untuk diunduh
            readfile($zipFileName);

            // Menghapus file zip setelah diunduh
            unlink($zipFileName);
            exit;
        } else {
            echo "File ZIP tidak dibuat.";
        }
    } else {
        echo "Tidak bisa membuat file zip.";
    }
} else {
    echo "Direktori tidak ditentukan.";
}
?>
