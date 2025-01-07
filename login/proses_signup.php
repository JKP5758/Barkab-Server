<?php
// Koneksi ke database
require '../koneksi.php';
$envVars = parse_ini_file('../.env');


$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mendapatkan data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $directory = trim($_POST['directory']);
    $password = trim($_POST['password']);

    // Validasi input
    if (empty($nis) || empty($nama)) {
        echo "Hanya Directory yang boleh Kosong!";
        exit;
    }

    if (empty($directory)){
        $directory = $nis;
    }

    if (empty($password)){
        $password = $nis;
    }

    // Cek apakah direktori sudah ada
    $userDir = $envVars["ROOT_DIR"] . $directory;
    if (file_exists($userDir)) {
        echo "Error: Direktori sudah ada. Silakan pilih nama direktori lain.";
        exit;
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Buat nama database
    $dbName = "db_barkab-server_" . $nis;

    // Siapkan pernyataan SQL untuk menyimpan data
    $sql = "INSERT INTO users (nis, nama, directory, password, db) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        // Bind parameter
        mysqli_stmt_bind_param($stmt, "sssss", $nis, $nama, $directory, $hashedPassword, $dbName);

        // Eksekusi pernyataan
        if (mysqli_stmt_execute($stmt)) {
            // Buat direktori untuk pengguna
            if (mkdir($userDir, 0777, true)) {
                // Buat database baru
                $createDbSql = "CREATE DATABASE `$dbName`";
                if (mysqli_query($koneksi, $createDbSql)) {
                    // Buat user MySQL baru
                    $createUserSql = "CREATE USER '$nis'@'%' IDENTIFIED BY '$password'";
                    if (mysqli_query($koneksi, $createUserSql)) {
                        // Berikan hak akses ke database baru
                        $grantSql = "GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, CREATE VIEW, EVENT, TRIGGER, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE, EXECUTE ON `$dbName`.* TO '$nis'@'%'";
                        if (mysqli_query($koneksi, $grantSql)) {
                            echo "Pendaftaran berhasil, direktori dan database telah dibuat!";
                            header("Location: ../login");
                        } else {
                            echo "Pendaftaran berhasil, tapi gagal memberikan hak akses database.";
                        }
                    } else {
                        echo "Pendaftaran berhasil, tapi gagal membuat user MySQL.";
                    }
                } else {
                    echo "Pendaftaran berhasil, tapi gagal membuat database.";
                }
            } else {
                echo "Pendaftaran berhasil, tapi gagal membuat direktori.";
            }
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }

        // Tutup pernyataan
        mysqli_stmt_close($stmt);
    } else {
        echo "Gagal menyiapkan pernyataan: " . mysqli_error($koneksi);
    }
}

// Tutup koneksi
mysqli_close($koneksi);
?>
