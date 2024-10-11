<?php
session_start();

if (!isset($_SESSION['nis'])) {
    echo "<script>location.href='../../login';</script>";
} else {
    echo "<script>location.href='list.php';</script>";
}