<?php
    session_start();
    require "../koneksi.php";

    if (isset($_SESSION['id'])) {
        echo "<script>alert('Anda Telah Login');window.location='../view/dashboard/';</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="icon" href="../aset/img/favicon.png" type="image/png">
    <title>login</title>
</head>
<body>
    <div class="container">
        <form action="proses_login.php" method="POST">
            <h2>Login</h2>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
            <a class="button" href="https://wa.me/+6282313921367?text=Halo%2C%20aku%20mau%20daftar%0A%0ANama%20%3A%20%0Ausername%20%3A%20%0Apassword%20%3A%20%0A%0AFoto%20Profil%20%3A%20%28Silakan%20langsung%20kirim%20gambarnya%29">Register</a>
        </form>
    </div>
</body>
</html>