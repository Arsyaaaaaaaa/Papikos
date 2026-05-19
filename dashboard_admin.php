<?php
include 'koneksi.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pesan = '';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'acc' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("UPDATE master_kos SET status_kamar = 'Kosong' WHERE id_kos = ?");
    $stmt->execute([$id]);

    $pesan = "Pendaftaran kos berhasil DISETUJUI (ACC)! Properti sekarang aktif.";

    if(isset($_GET['from']) && $_GET['from'] == 'dashboard') {
        $page = 'dashboard';
    }
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'tolak' && isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM master_kos WHERE id_kos = ?");
    $stmt->execute([$id]);

    $pesan = "Pendaftaran kos telah DITOLAK (Tidak ACC) dan dihapus dari sistem.";

    if(isset($_GET['from']) && $_GET['from'] == 'dashboard') {
        $page = 'dashboard';
    }
}
?>
