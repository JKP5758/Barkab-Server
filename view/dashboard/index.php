<?php
session_start();

if (!isset($_SESSION['nis'])) {
    echo "<script>location.href='../../login';</script>";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav><a href="../../login/logout.php">Logout</a></nav>
    <h1>Selamat datang, <?=htmlspecialchars($_SESSION['nama'])?>!</h1>

    <div class="konten">
        <div class="dashboard-container">
            <div class="card">
                <img src="img/website.jpg" alt="Web Image">
                <div class="card-content">
                    <h3>Lihat Web</h3>
                    <p>Kamu dapat Melihat Web buatan mu di sini.</p>
                    <a href="#" class="btn">Kunjungi Web</a>
                </div>
            </div>
            <div class="card">
                <img src="img/directory.jpg" alt="Directory Image">
                <div class="card-content">
                    <h3>Lihat Source Code</h3>
                    <p>Kamu dapat Mengelola, Mengupload, Mendownload, dan Mengedit source code web mu di sini.</p>
                    <a href="../directory/" class="btn">Lihat Source Code</a>
                </div>
            </div>
            <div class="card">
                <img src="img/database.jpg" alt="Database Image">
                <div class="card-content">
                    <h3>Lihat Database</h3>
                    <p>Kamu dapat mengelola Databasemu di sini.</p>
                    <a href="#" class="btn">Lihat Database</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>