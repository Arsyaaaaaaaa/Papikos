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

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAPIKOS - Admin Dashboard</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
            font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
        }

        body{
            background:#f4f7f6;
            color:#333;
            min-height:100vh;
            display:flex;
            flex-direction:column;
        }

        .header{
            background:#fff;
            padding:15px 40px;
            display:flex;
            justify-content:space-between;
            border-bottom:1px solid #eef2f1;
        }

        .logo-area{
            display:flex;
            align-items:center;
            gap:8px;
        }

        .logo-icon{
            color:#00a65b;
            font-size:22px;
        }

        .logo-text{
            font-size:20px;
            font-weight:700;
            color:#222;
        }

        .logo-badge{
            color:#00a65b;
            font-size:12px;
            margin-left:5px;
        }

        .container{
            max-width:1300px;
            width:100%;
            margin:30px auto;
            padding:0 20px;
            display:grid;
            grid-template-columns:2fr 1fr;
            gap:24px;
        }

        .left-column{
            display:flex;
            flex-direction:column;
            gap:24px;
        }

        .right-column{
            display:flex;
            flex-direction:column;
            gap:24px;
        }

        .dash-header-box{
            display:flex;
            justify-content:space-between;
            grid-column:span 2;
        }

        .dash-title{
            font-size:24px;
            font-weight:700;
            color:#111;
            margin-bottom:4px;
        }

        .dash-subtitle{
            font-size:14px;
            color:#777;
        }

        .btn-acc{
            background:#00a65b;
            color:#fff;
            padding:6px 12px;
            border:none;
            border-radius:6px;
            font-weight:600;
            text-decoration:none;
            font-size:13px;
            display:inline-flex;
            align-items:center;
            gap:5px;
        }

        .btn-acc:hover{
            background:#008f4e;
        }

        .btn-tolak{
            background:#ff3333;
            color:#fff;
            padding:6px 12px;
            border:none;
            border-radius:6px;
            font-weight:600;
            text-decoration:none;
            font-size:13px;
            display:inline-flex;
            align-items:center;
            gap:5px;
        }

        .btn-tolak:hover{
            background:#e02424;
        }

        .btn-detail{
            background:#0066cc;
            color:#fff;
            padding:6px 12px;
            border-radius:6px;
            text-decoration:none;
            font-size:13px;
            font-weight:600;
            display:inline-flex;
            align-items:center;
            gap:5px;
            margin-right:5px;
        }

        .btn-detail:hover{
            background:#0052a3;
        }

        .alert{
            background:#e6f6ef;
            color:#00a65b;
            padding:15px;
            border-radius:8px;
            border:1px solid #00a65b;
            margin-bottom:15px;
            font-size:14px;
            font-weight:600;
        }

        .stats-row{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:20px;
        }

        .stat-card{
            background:#fff;
            border-radius:12px;
            padding:24px;
            border:1px solid #eef2f1;
            box-shadow:0 2px 4px rgba(0,0,0,0.02);
        }

        .stat-title-area{
            display:flex;
            align-items:center;
            gap:10px;
            margin-bottom:15px;
            color:#555;
            font-size:14px;
            font-weight:600;
        }

        .stat-icon{
            padding:8px;
            border-radius:8px;
            font-size:16px;
        }

        .icon-verification{
            background:#fff3e0;
            color:#ff9800;
        }

        .icon-report{
            background:#e6f0fa;
            color:#0066cc;
        }

        .stat-value{
            font-size:36px;
            font-weight:700;
            color:#111;
        }

        .content-card{
            background:#fff;
            border-radius:12px;
            padding:24px;
            border:1px solid #eef2f1;
            min-height:200px;
            margin-bottom:20px;
        }

        .card-header-area{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:20px;
        }

        .card-title{
            font-size:18px;
            font-weight:700;
            color:#222;
        }

        .admin-table{
            width:100%;
            border-collapse:collapse;
            margin-top:15px;
        }

        .admin-table th,
        .admin-table td{
            padding:12px;
            border-bottom:1px solid #eee;
            text-align:left;
            font-size:14px;
        }

        .admin-table th{
            background:#f9fafb;
            color:#555;
            font-weight:600;
        }

        .badge{
            font-size:11px;
            font-weight:700;
            padding:4px 10px;
            border-radius:4px;
            text-transform:uppercase;
        }

        .badge-warning{
            background:#fff3e0;
            color:#ff9800;
        }

        .badge-danger{
            background:#fff0f0;
            color:#ff3333;
        }

        .badge-success{
            background:#e6f6ef;
            color:#00a65b;
        }

        .menu-card{
            background:#fff;
            border-radius:12px;
            padding:24px;
            border:1px solid #eef2f1;
        }

        .menu-label{
            font-size:11px;
            font-weight:700;
            color:#999;
            margin-bottom:15px;
            letter-spacing:1px;
        }

        .menu-list{
            display:flex;
            flex-direction:column;
            gap:8px;
        }

        .menu-link{
            display:flex;
            align-items:center;
            gap:12px;
            padding:14px 16px;
            text-decoration:none;
            color:#555;
            font-weight:600;
            border-radius:8px;
            font-size:14px;
        }

        .menu-link:hover,
        .menu-link.active{
            background:#e6f6ef;
            color:#00a65b;
        }

        .menu-link.logout{
            color:#ff3333;
            margin-top:20px;
            border-top:1px solid #eee;
            padding-top:20px;
            border-radius:0;
        }

        .modal{
            display:none;
            position:fixed;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:rgba(0,0,0,0.6);
            justify-content:center;
            align-items:center;
            z-index:1000;
            padding:20px;
        }

        .modal:target{
            display:flex;
        }

        .modal-content{
            background:#fff;
            padding:30px;
            border-radius:12px;
            max-width:600px;
            width:100%;
            position:relative;
            max-height:90vh;
            overflow-y:auto;
        }

        .close-modal{
            position:absolute;
            top:15px;
            right:20px;
            text-decoration:none;
            color:#aaa;
            font-size:24px;
            font-weight:bold;
        }

        .detail-img-placeholder{
            width:100%;
            height:180px;
            background:#eef2f1;
            border-radius:8px;
            margin:15px 0;
            display:flex;
            justify-content:center;
            align-items:center;
            color:#00a65b;
            font-weight:bold;
            flex-direction:column;
            gap:5px;
            border:2px dashed #00a65b;
        }

        .maps-placeholder{
            width:100%;
            background:#e0f2fe;
            padding:12px;
            border-radius:8px;
            margin-top:15px;
            border:1px solid #bae6fd;
            text-align:center;
        }

        .maps-link{
            color:#0284c7;
            font-weight:bold;
            text-decoration:none;
            font-size:13px;
        }

        .detail-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:10px;
            margin-bottom:15px;
            background:#f9fafb;
            padding:15px;
            border-radius:8px;
        }

        .detail-label{
            font-size:11px;
            color:#777;
            font-weight:600;
        }

        .detail-value{
            font-size:14px;
            color:#222;
            font-weight:700;
        }
    </style>
</head>

<body>

<header class="header">
    <div class="logo-area">
        <i class="fa-solid fa-house-laptop logo-icon"></i>
        <span class="logo-text">
            PAPIKOS
            <span class="logo-badge">Admin</span>
        </span>
    </div>
</header>

<div class="container">

    <div class="dash-header-box">
        <div>
            <h2 class="dash-title">
                <?= $page == 'dashboard' ? 'Ringkasan Dashboard Utama' : 'Panel Administrasi ' . ucfirst($page) ?>
            </h2>

            <p class="dash-subtitle">
                Memantau alur seluruh log data masuk terintegrasi secara real-time.
            </p>
        </div>
    </div>

    <div class="left-column">

        <?php if (!empty($pesan)): ?>
            <div class="alert">
                <?= $pesan ?>
            </div>
        <?php endif; ?>

        <?php if ($page == 'dashboard'): ?>

        <div class="stats-row" style="margin-bottom:10px;">

            <div class="stat-card">
                <div class="stat-title-area">
                    <i class="fa-solid fa-clock stat-icon icon-verification"></i>
                    <span>Menunggu Verifikasi</span>
                </div>

                <div class="stat-value">
                    <?php
                        $q = $pdo->query("SELECT COUNT(*) FROM master_kos");
                        echo $q->fetchColumn();
                    ?>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-title-area">
                    <i class="fa-solid fa-triangle-exclamation stat-icon icon-report"></i>
                    <span>Laporan Keluhan Aktif</span>
                </div>

                <div class="stat-value">1</div>
            </div>

        </div>

        <div class="content-card">

            <div class="card-header-area">
                <h3 class="card-title">
                    <i class="fa-solid fa-user-shield" style="color:#ff9800;"></i>
                    Data Pengajuan Verifikasi Kos
                </h3>

                <span class="badge badge-warning">
                    Butuh Tindakan
                </span>
            </div>

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Nama Properti</th>
                        <th>Lokasi</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php
                $stmt = $pdo->query("SELECT * FROM master_kos ORDER BY id_kos DESC");
                $kos_ada = false;

                while ($row = $stmt->fetch()):
                    $kos_ada = true;
                ?>

                <tr>

                    <td>
                        <b><?= htmlspecialchars($row['nama_kos']) ?></b>
                    </td>

                    <td>
                        <?= htmlspecialchars($row['lokasi']) ?>
                    </td>

                    <td>
                        Rp <?= number_format($row['harga_per_bulan'],0,',','.') ?>
                    </td>

                    <td>
                        <a href="#modal-dash-<?= $row['id_kos'] ?>" class="btn-detail">
                            <i class="fa-solid fa-circle-info"></i>
                            Detail
                        </a>

                        <a href="dashboard_admin.php?page=dashboard&aksi=acc&from=dashboard&id=<?= $row['id_kos'] ?>" class="btn-acc">
                            <i class="fa-solid fa-check"></i>
                            ACC
                        </a>
                    </td>

                </tr>

                <div id="modal-dash-<?= $row['id_kos'] ?>" class="modal">

                    <div class="modal-content">

                        <a href="#" class="close-modal">×</a>

                        <h3 style="margin-bottom:15px;">
                            <i class="fa-solid fa-house"></i>
                            Review Pengajuan Kos
                        </h3>

                        <div class="detail-grid">

                            <div>
                                <p class="detail-label">Nama Properti</p>
                                <p class="detail-value"><?= htmlspecialchars($row['nama_kos']) ?></p>
                            </div>

                            <div>
                                <p class="detail-label">Harga</p>
                                <p class="detail-value">
                                    Rp <?= number_format($row['harga_per_bulan'],0,',','.') ?>
                                </p>
                            </div>

                            <div>
                                <p class="detail-label">Lokasi</p>
                                <p class="detail-value"><?= htmlspecialchars($row['lokasi']) ?></p>
                            </div>

                            <div>
                                <p class="detail-label">Fasilitas</p>
                                <p class="detail-value"><?= htmlspecialchars($row['fasilitas']) ?></p>
                            </div>

                        </div>

                        <div class="detail-img-placeholder">
                            <i class="fa-solid fa-images" style="font-size:24px;"></i>
                            <span>[ FOTO KOS ]</span>
                        </div>

                        <div class="maps-placeholder">

                            <a href="https://maps.google.com/?q=<?= urlencode($row['nama_kos']) ?>" target="_blank" class="maps-link">

                                <i class="fa-solid fa-map-location-dot"></i>
                                Lihat Lokasi Maps

                            </a>

                        </div>

                        <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:8px;">

                            <a href="dashboard_admin.php?page=dashboard&aksi=acc&from=dashboard&id=<?= $row['id_kos'] ?>" class="btn-acc">
                                <i class="fa-solid fa-check"></i>
                                ACC
                            </a>

                            <a href="dashboard_admin.php?page=dashboard&aksi=tolak&from=dashboard&id=<?= $row['id_kos'] ?>" class="btn-tolak">
                                <i class="fa-solid fa-xmark"></i>
                                Tolak
                            </a>

                        </div>

                    </div>

                </div>

                <?php endwhile; ?>

                <?php if(!$kos_ada): ?>

                <tr>
                    <td colspan="4" style="text-align:center; color:#999; padding:20px 0;">
                        Tidak ada data pengajuan kos.
                    </td>
                </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

        <?php endif; ?>

    </div>

    <div class="right-column">

        <div class="menu-card">

            <p class="menu-label">MENU ADMIN</p>

            <div class="menu-list">

                <a href="?page=dashboard" class="menu-link <?= $page == 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-border-all"></i>
                    Dashboard
                </a>

                <a href="?page=verifikasi" class="menu-link <?= $page == 'verifikasi' ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-shield"></i>
                    Verifikasi Kos
                </a>

                <a href="?page=laporan" class="menu-link <?= $page == 'laporan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-invoice"></i>
                    Laporan
                </a>

                <a href="?page=pengaturan" class="menu-link <?= $page == 'pengaturan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-gear"></i>
                    Pengaturan
                </a>

                <a href="index.php" class="menu-link logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Keluar
                </a>

            </div>

        </div>

        <div style="background:#00a65b; border-radius:12px; padding:24px; color:#fff;">

            <h3 style="font-size:16px; font-weight:700; margin-bottom:10px;">
                Pusat Aktivitas
            </h3>

            <p style="font-size:13px; line-height:1.6; opacity:0.9;">
                Dashboard admin digunakan untuk memverifikasi data kos,
                melihat laporan pengguna, dan mengelola aktivitas sistem.
            </p>

        </div>

    </div>

</div>

</body>
</html>
