<?php
session_start();
$envVars = parse_ini_file('../../../.env');

require "../../../config.php";

if ($_SESSION['status'] == 'admin') {
    } else {
        echo "<script>location.href='../../login';</script>";
    }

// Ambil protokol (http atau https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Ambil host (domain dan port jika ada)
$host = $_SERVER['HTTP_HOST'];

// Pisahkan host dan port, ambil hanya host (tanpa port)
$hostWithoutPort = explode(':', $host)[0];

// Gabungkan protokol dan host tanpa port
$fullHostWithoutPort = $protocol . $hostWithoutPort;

// Cetak URL host tanpa port
// echo $fullHostWithoutPort;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="./img/logo-smk.png" type="image/png">
</head>
<body>
    <nav>
        <div class="logout">
            <?php echo "<a class='logout' href='../../login/logout.php' onclick='return confirm(\"Anda yakin ingin logout?\");'>Logout</a>"; ?>
        </div>
    </nav>
    
    <div class="crown">
        <img src="img/crown.png" alt="crown" >
    </div>
    
    <h1>Selamat datang, <?=htmlspecialchars($_SESSION['name'])?>!</h1>

    <div class="konten">
        <div class="dashboard-container">
            <div class="card">
                <img src="img/users.jpg" alt="Web Image">
                <div class="card-content">
                    <h3>Data Users</h3>
                    <p>Meliat Semua Data Users</p>
                    <a href="../users" class="btn">Lihat Users</a>
                </div>
            </div>
            <div class="card">
                <img src="img/directory.jpg" alt="Directory Image">
                <div class="card-content">
                    <h3>Directory</h3>
                    <p>Melihat Semua Directory Users.</p>
                    <a href="../directory" class="btn">Lihat Directory</a>
                </div>
            </div>
            <div class="card">
                <img src="img/database.jpg" alt="Database Image">
                <div class="card-content">
                    <h3>Database</h3>
                    <p>Tempat Mengelola Database Users.</p>
                    <a class="btn" onclick="showModal()">Lihat Database</a>
                </div>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer style="margin-top: 40px; color: rgb(0 0 0 / 0.5);">
        v<?= WEBSITE_VERSION; ?>
    </footer>

    <!-- Modal -->
    <div id="infoModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Username dan Password, Sama Seperti Sebelumnya!</p>
            <a href="<?=$fullHostWithoutPort.':'.$envVars['DB_PORT']?>" onclick="closeModal()">OK</a>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById('infoModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('infoModal').style.display = 'none';
        }
    </script>
</body>
</html>