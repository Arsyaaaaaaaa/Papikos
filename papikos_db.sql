SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

CREATE TABLE `master_kos` (
  `id_kos` int NOT NULL,
  `nama_kos` varchar(100) NOT NULL,
  `lokasi` varchar(150) NOT NULL,
  `tipe_kos` enum('Putra','Putri','Campur') NOT NULL,
  `fasilitas` text NOT NULL,
  `harga_per_bulan` int NOT NULL,
  `status_kamar` enum('Kosong','Terisi','Pending Verifikasi') NOT NULL DEFAULT 'Pending Verifikasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `master_kos` (`id_kos`, `nama_kos`, `lokasi`, `tipe_kos`, `fasilitas`, `harga_per_bulan`, `status_kamar`) VALUES
(2, 'Kos Gantek', 'Jl. Gantenk', 'Putra', 'kamar mandi', 20000000, 'Kosong');

CREATE TABLE `master_pengguna` (
  `id_pengguna` int NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `master_pengguna` (`id_pengguna`, `nama_lengkap`, `no_hp`) VALUES
(1, 'Dhemas Ravinagata', '081234567890');

CREATE TABLE `transaksi_booking` (
  `id_booking` int NOT NULL,
  `id_kos` int NOT NULL,
  `id_pengguna` int NOT NULL,
  `tanggal_booking` date NOT NULL,
  `status_booking` enum('Pending','Dikonfirmasi','Selesai') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `master_kos`
  ADD PRIMARY KEY (`id_kos`);

ALTER TABLE `master_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

ALTER TABLE `transaksi_booking`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `id_kos` (`id_kos`),
  ADD KEY `id_pengguna` (`id_pengguna`);

ALTER TABLE `master_kos`
  MODIFY `id_kos` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `master_pengguna`
  MODIFY `id_pengguna` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `transaksi_booking`
  MODIFY `id_booking` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `transaksi_booking`
  ADD CONSTRAINT `transaksi_booking_ibfk_1` FOREIGN KEY (`id_kos`) REFERENCES `master_kos` (`id_kos`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_booking_ibfk_2` FOREIGN KEY (`id_pengguna`) REFERENCES `master_pengguna` (`id_pengguna`) ON DELETE CASCADE;

COMMIT;
