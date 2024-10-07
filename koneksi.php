<?php
// Baca isi file .env
$envVars = parse_ini_file('.env');

// Setel variabel-variabel dari file .env
$dbhost = $envVars['DB_HOST'];
$dbuser = $envVars['DB_USER'];
$dbpass = $envVars['DB_PASS'];
$dbname = $envVars['DB_NAME'];


$koneksi = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

?>