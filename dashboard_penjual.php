<?php
include 'koneksi.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah_kos') {
    $stmt = $pdo->prepare("INSERT INTO master_kos (nama_kos, lokasi, tipe_kos, fasilitas, harga_per_bulan, status_kamar) VALUES (?, ?, ?, ?, ?, 'Pending Verifikasi')");
    $stmt->execute([$_POST['nama_kos'], $_POST['lokasi'], $_POST['tipe_kos'], $_POST['fasilitas'], $_POST['harga']]);
    $pesan = "Properti kos baru berhasil ditambahkan! Menunggu proses verifikasi (ACC) dari Admin.";
    $page = 'daftar_kos';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_kos') {
    $stmt = $pdo->prepare("UPDATE master_kos SET nama_kos = ?, lokasi = ?, tipe_kos = ?, fasilitas = ?, harga_per_bulan = ? WHERE id_kos = ?");
    $stmt->execute([$_POST['nama_kos'], $_POST['lokasi'], $_POST['tipe_kos'], $_POST['fasilitas'], $_POST['harga'], $_POST['id_kos']]);
    $pesan = "Data properti kos berhasil diperbarui!";
    $page = 'daftar_kos';
}

if (isset($_GET['hapus_kos'])) {
    $id = $_GET['hapus_kos'];
    $stmt = $pdo->prepare("DELETE FROM master_kos WHERE id_kos = ?");
    $stmt->execute([$id]);
    $pesan = "Data properti kos berhasil dihapus dari sistem.";
    $page = 'daftar_kos';
}
?>
