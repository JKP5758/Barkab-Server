<?php
if (isset($_POST['directory'])) {
    $rootDirectory = 'C:\\xampp\\htdocs\\server-data\\7341';
    $directory = urldecode($_POST['directory']);
    
    // Nama file zip
    $zipFileName = 'files.zip';
    
    // Membuat objek ZipArchive
    $zip = new ZipArchive();
    
    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Jika ada file yang dipilih
        if (isset($_POST['selected']) && !empty($_POST['selected'])) {
            $selectedFiles = $_POST['selected'];
            foreach ($selectedFiles as $file) {
                $fullPath = $rootDirectory . $file;
                if (file_exists($fullPath)) {
                    if (is_dir($fullPath)) {
                        // Jika direktori, tambahkan semua file di dalamnya
                        $files = new RecursiveIteratorIterator(
                            new RecursiveDirectoryIterator($fullPath),
                            RecursiveIteratorIterator::LEAVES_ONLY
                        );
                        foreach ($files as $name => $fileInfo) {
                            if (!$fileInfo->isDir()) {
                                $filePath = $fileInfo->getRealPath();
                                $relativePath = substr($filePath, strlen($rootDirectory) + 1);
                                $zip->addFile($filePath, $relativePath);
                            }
                        }
                    } else {
                        // Jika file, tambahkan langsung
                        $relativePath = ltrim($file, '\\');
                        $zip->addFile($fullPath, $relativePath);
                    }
                }
            }
        } else {
            // Jika tidak ada yang dipilih, download semua
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $fileInfo) {
                if (!$fileInfo->isDir()) {
                    $filePath = $fileInfo->getRealPath();
                    $relativePath = substr($filePath, strlen($directory) + 1);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        
        $zip->close();
        
        // Mengatur header untuk download file zip
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=' . $zipFileName);
        header('Content-Length: ' . filesize($zipFileName));
        
        // Mengeluarkan file zip untuk diunduh
        readfile($zipFileName);
        
        // Menghapus file zip setelah diunduh
        unlink($zipFileName);
        exit;
    } else {
        echo "Tidak bisa membuat file zip.";
    }
} else {
    echo "Direktori tidak ditentukan.";
}
?>
