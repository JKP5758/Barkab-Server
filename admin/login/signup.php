<?php
    session_start();
    require "../../koneksi.php";

    if ($_SESSION['status']== 'admin') {
    } else {
        echo "<script>location.href='../login';</script>";
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
                    <label for="user">Username</label>
                    <input type="text" id="user" name="user" required placeholder="">
                </div>
                <div class="input-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" required placeholder="">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input id="password-field" type="password" name="password" placeholder="Default Username">
                    </div>
                </div>
                
                <button type="submit" class="btn">Sign Up</button>

                <div class="create-account">
                    <p class="text-center">Sudah Punya Akun? <a href="../login/">Login</a></p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

