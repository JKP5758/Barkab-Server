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

// Ambil NIS dari URL
$nis = isset($_GET['nis']) ? $_GET['nis'] : '';

// Ambil data pengguna berdasarkan NIS
$userQuery = mysqli_query($koneksi, "SELECT * FROM `users` WHERE nis = '$nis'");
$userData = mysqli_fetch_assoc($userQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" href="../directory/img/logo-smk.png" type="image/png">
</head>
<body>
    <div class="edit-container">
    <div class="form-container">
        <h2>Edit User</h2>
        <form method="POST" action="proses_edit.php">
            <input type="hidden" name="nis" value="<?php echo $userData['nis']; ?>">
            <div class="form-group">
                <label for="nis_change">NIS:</label>
                <input type="text" id="nis_change" name="nis_change" value="<?php echo $userData['nis']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $userData['nama']; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="(kosongkan jika tidak ingin mengubah)">
            </div>
            <div class="form-group">
                <input type="hidden" name="oldDir" value="<?php echo $userData['directory']; ?>">   
                <label for="directory">Directory:</label>
                <input type="text" id="directory" name="directory" value="<?php echo $userData['directory']; ?>">
            </div>
            <div class="form-group">
                <label for="db">Database:</label>
                <input type="text" id="db" name="db" value="<?php echo $userData['db']; ?>" readonly>
            </div>
            <div class="form-actions">
                <button type="submit" class="save-btn">Simpan</button>
                <button type="button" class="cancel-btn" onclick="window.history.back();">Batal</button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>
