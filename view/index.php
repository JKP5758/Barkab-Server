<?php
session_start();

if (!isset($_SESSION['id'])) {
    echo "<script>location.href='../login';</script>";
} else {
    echo "<script>location.href='dashboard';</script>";
}