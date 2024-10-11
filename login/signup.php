<?php
    session_start();
    require "../koneksi.php";

    if (isset($_SESSION['nis'])) {
        echo "<script>alert('Anda Telah Login');window.location='../view/dashboard/';</script>";
    }
?>

<!doctype html>
<html lang="en">
<head>
    <title>SignUp</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="style.css"> <!-- Menggunakan style.css yang sama -->

</head>
<body>
    <div class="login-container"> <!-- Menggunakan kelas yang sama -->
        <div class="login-box">
            <div class="image-container">
                <img src="../login/images/hd4.jpg" alt="Classroom">
            </div>
            <form class="login-form" action="proses_signup.php" method="post"> <!-- Menggunakan kelas yang sama -->
                <h2>Sign Up</h2> <!-- Mengubah judul untuk konsistensi -->
                <div class="input-group">
                    <label for="nis">NIS</label>
                    <input type="text" id="nis" name="nis" required placeholder="">
                </div>
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required placeholder="">
                </div>
                <div class="input-group">
                    <label for="directory">Nama Direktori</label>
                    <input type="text" id="directory" name="directory" required placeholder="">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input id="password-field" type="password" name="password" required placeholder="">
                    </div>
                </div>
                
                <button type="submit" class="btn">Sign Up</button>

                <div class="create-account">
                    <p class="text-center">Sudah Punya Akun? <a href="index.php">Login</a></p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

