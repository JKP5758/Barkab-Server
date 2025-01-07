<?php
session_start();
$envVars = parse_ini_file('../../../.env');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['status'])) {
    header("Location: ../../login");
    exit;
}

// Koneksi ke database
require '../../../koneksi.php';

$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Ambil nilai pencarian dari input
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Modifikasi query untuk mempertimbangkan pencarian
$usersQuery = mysqli_query($koneksi, "SELECT nis, nama, directory, db FROM `users` WHERE 
    nis LIKE '%$search%' OR 
    nama LIKE '%$search%' OR 
    directory LIKE '%$search%' OR 
    db LIKE '%$search%'");

// Pastikan untuk memeriksa apakah ada hasil

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Pengguna</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <a class='logout' href='../dashboard'>Kembali</a>
    <div class="container">
    <?php if (isset($_GET['message'])): ?>
        <div class="notification" id="notification">
            <?= htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const notification = document.getElementById('notification');
            if (notification) {
                notification.style.display = 'block'; // Tampilkan notifikasi
                notification.style.transition = 'opacity 1s ease'; // Tambahkan transisi

                // Hilangkan notifikasi secara perlahan setelah 3 detik
                setTimeout(() => {
                    notification.style.opacity = '0'; // Kurangi opacity ke 0
                    setTimeout(() => {
                        notification.style.display = 'none'; // Sembunyikan elemen setelah transisi selesai
                    }, 1000); // Waktu yang sama dengan durasi transisi
                }, 3000);

                // Hapus parameter "message" dari URL
                const url = new URL(window.location.href);
                url.searchParams.delete('message');
                window.history.replaceState({}, document.title, url);
            }
        });
    </script>

        <h1>Daftar Pengguna</h1>
        
        <!-- Form pencarian -->
        <form class="serch" method="POST">
        <div class="search-container">
            <input type="text" name="search" placeholder="Cari pengguna...">
            <button type="submit">Cari</button>
        </div>
        </form>

        <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Password</th>
                <th>Directory</th>
                <th>Database</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Menampilkan hasil pencarian
            if (mysqli_num_rows($usersQuery) > 0) {
                while ($row = mysqli_fetch_assoc($usersQuery)) {
                    echo "<tr>
                        <td>{$row['nis']}</td>
                        <td>{$row['nama']}</td>
                        <td>********</td>
                        <td>{$row['directory']}</td>
                        <td>{$row['db']}</td>
                        <td>
                            <a href='edit.php?nis={$row['nis']}' class='edit-btn'>Edit</a>
                            <button class='delete-btn'>Hapus</button>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada hasil ditemukan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    </div>
</body>
</html>
