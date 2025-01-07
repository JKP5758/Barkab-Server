<?php
    session_start();
    require "../../koneksi.php";
    require "../../config.php";

    if ($_SESSION['status'] == 'admin') {
        echo "<script>location.href='../view/dashboard';</script>";
    }

    
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="./images/logo-smk.png" type="image/png">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="image-container">
                <img src="../login/images/hd4.png" alt="Classroom">
            </div>
            <form class="login-form" action="proses_login.php" method="post">
                <h2>Login</h2>
                <div>
                    <?php
                        if (isset($_SESSION['error_message'])) {
                            echo "<p class='error-message'>" . $_SESSION['error_message'] . "</p>";
                            unset($_SESSION['error_message']); // Hapus pesan kesalahan setelah ditampilkan
                        }
                    ?>
                </div>
                
                <div class="input-group">
                    <label for="user">User</label>
                    <input type="text" id="user" name="user" required placeholder="">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" required placeholder="">
                    </div>
                </div>
                
                <button type="submit" class="btn">Login</button>

                <div class="options">
                    <a href="#">Lupa Password</a>
                </div>
                <div class="create-account">
                    <p>Tidak Punya Akun? <a href="#">Buat Akun</a></p>
                </div>
            </form>
        </div>
    </div>
    <footer style="margin-top: 40px; color: rgb(0 0 0 / 0.5);">
        v<?= WEBSITE_VERSION; ?>
    </footer>
</body>
</html>
