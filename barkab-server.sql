-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 21 Okt 2024 pada 05.17
-- Versi server: 8.0.39-1
-- Versi PHP: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `barkab-server`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `nis` int NOT NULL,
  `nama` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
  `directory` varchar(225) COLLATE utf8mb4_general_ci NOT NULL,
  `db` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`nis`, `nama`, `password`, `directory`, `db`) VALUES
(7000, 'Beta', '$2y$10$cFyvvm4SyNBBxzBn31a84uzmmb8y9HKsw.3SgkOSrfTDByvfytuV2', '7000', 'db_barkab-server_7000'),
(7001, 'Tes1', '$2y$10$mP6tHyNXAdbF1cAClbYGVuxMs3pxf3RTyK7WSQVzhLiUZBvYGH4ES', '7001', 'db_barkab-server_7001'),
(7341, 'Joko Purnomo', '$2y$10$zWYCjUrbn5SPIr.uLkg.AeU2EihOnmhapdv.gfd1WG0sX514swFzq', '7341', 'db_barkab-server_7341');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`nis`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
