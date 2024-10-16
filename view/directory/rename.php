<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rename File</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <div class="header">
        <h1>File Management System</h1>
    </div>

    <div class="main-content">
        <?php
        $envVars = parse_ini_file('../../.env');
        $rootDirectory = $envVars['ROOT_DIR'];

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
                    $newPath = $itemDir . '/' . $newName;

                    if (rename($oldPath, $newPath)) {
                        header("Location: list.php?directory=" . urlencode($relativePath));
                        exit;
                    } else {
                        echo "<p class='error-message'>Gagal mengganti nama.</p>";
                    }
                } else {
                    // Tampilkan form untuk mengganti nama
                    echo "<h2>Rename: $itemName</h2>";
                    echo "<form method='post' class='rename-form'>
                            <input type='hidden' name='directory' value='" . htmlspecialchars($relativePath) . "'>
                            <input type='hidden' name='item' value='" . htmlspecialchars($item) . "'>
                            <input type='text' name='new_name' value='" . htmlspecialchars($itemName) . "' class='rename-input'>
                            <input type='submit' value='Rename' class='rename-submit'>
                          </form>";
                }
            } else {
                echo "<p class='error-message'>File atau folder tidak ditemukan.</p>";
            }
        } else {
            echo "<p class='error-message'>Metode tidak diizinkan.</p>";
        }
        ?>
    </div>

</div>

</body>
</html>
