<?php
session_start();
require "../../koneksi.php";

if (!isset($_SESSION['status'])) {
    echo "<script>location.href='../login';</script>";
} else {
    echo "<script>location.href='view/dashboardk';</script>";
}