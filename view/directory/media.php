<?php

$envVars = parse_ini_file('../../.env');
$rootDirectory = $envVars['ROOT_DIR'];

// Ambil protokol (http atau https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Ambil host (domain dan port jika ada)
$host = $_SERVER['HTTP_HOST'];

// Pisahkan host dan port, ambil hanya host (tanpa port)
$hostWithoutPort = explode(':', $host)[0];

// Gabungkan protokol dan host tanpa port
$fullHostWithoutPort = $protocol . $hostWithoutPort;

$relativePath = $_GET['file'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Viewer</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/logo-smk.png" type="image/png">
</head>
<body>

<div class="container">
    <header>
        <h1>Media Viewer</h1>
        <a class='logout' href='list.php?directory=<?php echo urlencode(dirname($relativePath)); ?>'>Kembali ke Daftar File</a>
    </header>

    <div class="media-wrapper">
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
                $fileInfo = pathinfo($file);
                $fileExtension = strtolower($fileInfo['extension']);

                // Menampilkan media berdasarkan jenis file
                switch ($fileExtension) {
                    case 'png':
                    case 'jpg':
                    case 'jpeg':
                    case 'gif':
                    case 'bmp':
                        // Menampilkan gambar
                        echo "<h2>Gambar: {$fileInfo['basename']}</h2>";
                        echo "<img src='" .$fullHostWithoutPort.':'.$envVars['HOST_PORT'].'/'. htmlspecialchars($relativePath) . "' alt='" . htmlspecialchars($fileInfo['basename']) . "' style='max-width: 100%; height: auto;'>";
                        break;

                    case 'mp4':
                    case 'webm':
                    case 'ogg':
                        // Menampilkan video
                        echo "<h2>Video: {$fileInfo['basename']}</h2>";
                        echo "<video controls style='max-width: 100%;'>
                                <source src='" .$fullHostWithoutPort.':'.$envVars['HOST_PORT'].'/'. htmlspecialchars($relativePath) . "' type='video/" . $fileExtension . "'>
                                Your browser does not support the video tag.
                              </video>";
                        break;

                    case 'mp3':
                    case 'wav':
                    case 'ogg':
                        // Menampilkan audio
                        echo "<h2>Audio: {$fileInfo['basename']}</h2>";
                        echo "<audio controls>
                                <source src='" .$fullHostWithoutPort.':'.$envVars['HOST_PORT'].'/'. htmlspecialchars($relativePath) . "' type='audio/" . $fileExtension . "'>
                                Your browser does not support the audio tag.
                              </audio>";
                        break;

                    default:
                        echo "<p>Tipe file ini tidak dapat ditampilkan.</p>";
                        break;
                }
            } else {
                echo "<p class='error-message'>File tidak ditemukan.</p>";
            }
        } else {
            echo "<p class='error-message'>Parameter file tidak ada.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
