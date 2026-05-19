<?php
// --- INCLUDE KONEKSI DATABASE ---
include 'koneksi.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pesan = '';

// ==============================================
// PROSES AKSI ADMIN (ACC / TIDAK ACC)
// ==============================================

// Jika Admin menyetujui pendaftaran kos (ACC)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'acc' && isset($_GET['id'])) {
    $id = $_GET['id'];
    // Mengubah status kamar menjadi 'Kosong' (artinya disetujui & tersedia di publik)
    $stmt = $pdo->prepare("UPDATE master_kos SET status_kamar = 'Kosong' WHERE id_kos = ?");
    $stmt->execute([$id]);
    $pesan = "Pendaftaran kos berhasil DISETUJUI (ACC)! Properti sekarang aktif.";
    
    // Jika aksi dikerjakan di dashboard, tetap kunci halaman di dashboard setelah refresh
    if(isset($_GET['from']) && $_GET['from'] == 'dashboard') { $page = 'dashboard'; }
}

// Jika Admin menolak pendaftaran kos (Tidak ACC)
if (isset($_GET['aksi']) && $_GET['aksi'] == 'tolak' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM master_kos WHERE id_kos = ?");
    $stmt->execute([$id]);
    $pesan = "Pendaftaran kos telah DITOLAK (Tidak ACC) dan dihapus dari sistem.";
    
    if(isset($_GET['from']) && $_GET['from'] == 'dashboard') { $page = 'dashboard'; }
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
        /* CSS Murni - Desain Dashboard Admin Papikos */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; min-height: 100vh; display: flex; flex-direction: column; }
        
        /* Header Navbar */
        .header { background-color: #ffffff; padding: 15px 40px; display: flex; justify-content: space-between; items-center; border-bottom: 1px solid #eef2f1; }
        .logo-area { display: flex; items-center; gap: 8px; }
        .logo-icon { color: #00a65b; font-size: 22px; }
        .logo-text { font-size: 20px; font-weight: 700; color: #222; }
        .logo-badge { color: #00a65b; font-size: 12px; font-weight: 400; margin-left: 5px; }

        /* Main Container Layout */
        .container { max-width: 1300px; width: 100%; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
        .left-column { display: flex; flex-direction: column; gap: 24px; }
        .right-column { display: flex; flex-direction: column; gap: 24px; }

        /* Dashboard Header */
        .dash-header-box { display: flex; justify-content: space-between; items-center; grid-column: span 2; }
        .dash-title { font-size: 24px; font-weight: 700; color: #111; margin-bottom: 4px; }
        .dash-subtitle { font-size: 14px; color: #777; }
        
        /* Buttons Kontrol Admin */
        .btn-acc { background-color: #00a65b; color: white; padding: 6px 12px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: 13px; display: inline-flex; items-center; gap: 5px; }
        .btn-acc:hover { background-color: #008f4e; }
        
        .btn-tolak { background-color: #ff3333; color: white; padding: 6px 12px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: 13px; display: inline-flex; items-center; gap: 5px; }
        .btn-tolak:hover { background-color: #e02424; }

        .btn-detail { background-color: #0066cc; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 600; display: inline-flex; items-center; gap: 5px; margin-right: 5px;}
        .btn-detail:hover { background-color: #0052a3; }
        
        /* Alert/Notifikasi */
        .alert { background-color: #e6f6ef; color: #00a65b; padding: 15px; border-radius: 8px; border: 1px solid #00a65b; margin-bottom: 15px; font-size: 14px; font-weight: 600; }

        /* Stats Cards */
        .stats-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .stat-card { background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #eef2f1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .stat-title-area { display: flex; items-center; gap: 10px; margin-bottom: 15px; color: #555; font-size: 14px; font-weight: 600; }
        .stat-icon { padding: 8px; border-radius: 8px; font-size: 16px; }
        .icon-verification { background-color: #fff3e0; color: #ff9800; }
        .icon-report { background-color: #e6f0fa; color: #0066cc; }
        .stat-value { font-size: 36px; font-weight: 700; color: #111; }

        /* Cards Content */
        .content-card { background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #eef2f1; min-height: 200px; margin-bottom: 20px; }
        .card-header-area { display: flex; justify-content: space-between; items-center; margin-bottom: 20px; }
        .card-title { font-size: 18px; font-weight: 700; color: #222; }

        /* Table Layout */
        .admin-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .admin-table th, .admin-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
        .admin-table th { background-color: #f9fafb; color: #555; font-weight: 600; }
        
        /* Badges */
        .badge { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase; }
        .badge-warning { background-color: #fff3e0; color: #ff9800; }
        .badge-danger { background-color: #fff0f0; color: #ff3333; }
        .badge-success { background-color: #e6f6ef; color: #00a65b; }

        /* Sidebar / Right Menu */
        .menu-card { background: #ffffff; border-radius: 12px; padding: 24px; border: 1px solid #eef2f1; }
        .menu-label { font-size: 11px; font-weight: 700; color: #999; margin-bottom: 15px; letter-spacing: 1px; }
        .menu-list { display: flex; flex-direction: column; gap: 8px; }
        .menu-link { display: flex; items-center; gap: 12px; padding: 14px 16px; text-decoration: none; color: #555; font-weight: 600; border-radius: 8px; font-size: 14px; }
        .menu-link:hover, .menu-link.active { background-color: #e6f6ef; color: #00a65b; }
        .menu-link.logout { color: #ff3333; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px; border-radius: 0; }

        /* CSS MODAL DETAIL MURNI */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); justify-content: center; align-items: center; z-index: 1000; padding: 20px; }
        .modal:target { display: flex; } 
        .modal-content { background: white; padding: 30px; border-radius: 12px; max-width: 600px; width: 100%; position: relative; max-height: 90vh; overflow-y: auto; }
        .close-modal { position: absolute; top: 15px; right: 20px; text-decoration: none; color: #aaa; font-size: 24px; font-weight: bold; }
        
        /* Detail Pop-up Sub-elements */
        .detail-img-placeholder { width: 100%; height: 180px; background-color: #eef2f1; border-radius: 8px; margin: 15px 0; display: flex; justify-content: center; align-items: center; color: #00a65b; font-weight: bold; flex-direction: column; gap: 5px; border: 2px dashed #00a65b; }
        .maps-placeholder { width: 100%; background-color: #e0f2fe; padding: 12px; border-radius: 8px; margin-top: 15px; border: 1px solid #bae6fd; text-align: center; }
        .maps-link { color: #0284c7; font-weight: bold; text-decoration: none; font-size: 13px; }
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 15px; background: #f9fafb; padding: 15px; border-radius: 8px; }
        .detail-label { font-size: 11px; color: #777; font-weight: 600; }
        .detail-value { font-size: 14px; color: #222; font-weight: 700; }
    </style>
</head>
<body>

    <header class="header">
        <div class="logo-area">
            <i class="fa-solid fa-house-laptop logo-icon"></i>
            <span class="logo-text">PAPIKOS <span class="logo-badge">Admin</span></span>
        </div>
    </header>

    <div class="container">
        
        <div class="dash-header-box">
            <div>
                <h2 class="dash-title">
                    <?= $page == 'dashboard' ? 'Ringkasan Dashboard Utama' : 'Panel Administrasi ' . ucfirst($page) ?>
                </h2>
                <p class="dash-subtitle">Memantau alur seluruh log data masuk terintegrasi secara real-time.</p>
            </div>
        </div>

        <div class="left-column">
            
            <?php if (!empty($pesan)): ?>
                <div class="alert"><?= $pesan ?></div>
            <?php endif; ?>

            <?php if ($page == 'dashboard'): ?>
                
                <div class="stats-row" style="margin-bottom: 10px;">
                    <div class="stat-card">
                        <div class="stat-title-area">
                            <i class="fa-solid fa-clock-solid fa-clock stat-icon icon-verification"></i>
                            <span>Menunggu Verifikasi (Pendaftaran Baru)</span>
                        </div>
                        <div class="stat-value">
                            <?php 
                                // Menghitung kos yang belum di-ACC (asumsi status_kamar bawaan form daftar/selain 'Kosong' atau data baru)
                                // Di sini kita hitung semua data kos yang ada di antrean master_kos
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
                        <h3 class="card-title"><i class="fa-solid fa-user-shield" style="color: #ff9800;"></i> Data Masuk: Pengajuan Verifikasi Kos</h3>
                        <span class="badge badge-warning">Butuh Tindakan</span>
                    </div>

                    <table class="admin-table">
                        <thead>
                            <tr><th>Nama Properti</th><th>Lokasi Alamat</th><th>Harga</th><th>Aksi Cepat Dashboard</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stmt = $pdo->query("SELECT * FROM master_kos ORDER BY id_kos DESC");
                            $kos_ada = false;
                            while ($row = $stmt->fetch()): 
                                $kos_ada = true;
                            ?>
                            <tr>
                                <td><b><?= htmlspecialchars($row['nama_kos']) ?></b></td>
                                <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                <td><small>Rp <?= number_format($row['harga_per_bulan'],0,',','.') ?></small></td>
                                <td>
                                    <a href="#modal-dash-<?= $row['id_kos'] ?>" class="btn-detail"><i class="fa-solid fa-circle-info"></i> Detail</a>
                                    <a href="dashboard_admin.php?page=dashboard&aksi=acc&from=dashboard&id=<?= $row['id_kos'] ?>" class="btn-acc" onclick="return confirm('ACC pendaftaran kos ini?')"><i class="fa-solid fa-check"></i> ACC</a>
                                </td>
                            </tr>

                            <div id="modal-dash-<?= $row['id_kos'] ?>" class="modal">
                                <div class="modal-content">
                                    <a href="#" class="close-modal">×</a>
                                    <h3 style="margin-bottom: 15px;"><i class="fa-solid fa-house"></i> Review Pengajuan Kos</h3>
                                    
                                    <div class="detail-grid">
                                        <div><p class="detail-label">Nama Properti</p><p class="detail-value"><?= htmlspecialchars($row['nama_kos']) ?></p></div>
                                        <div><p class="detail-label">Harga Sewa</p><p class="detail-value">Rp <?= number_format($row['harga_per_bulan'],0,',','.') ?></p></div>
                                        <div><p class="detail-label">Alamat Lokasi</p><p class="detail-value"><?= htmlspecialchars($row['lokasi']) ?></p></div>
                                        <div><p class="detail-label">Fasilitas</p><p class="detail-value"><?= htmlspecialchars($row['fasilitas']) ?></p></div>
                                    </div>

                                    <div class="detail-img-placeholder">
                                        <i class="fa-solid fa-images" style="font-size: 24px;"></i>
                                        <span>[ FOTO BUKTI FASILITAS KOS ]</span>
                                    </div>

                                    <div class="maps-placeholder">
                                        <a href="http://googleusercontent.com/maps.google.com/?q=<?= urlencode($row['nama_kos']) ?>" target="_blank" class="maps-link">
                                            <i class="fa-solid fa-map-location-dot"></i> Lihat Titik Koordinat Maps Properti →
                                        </a>
                                    </div>

                                    <div style="margin-top: 20px; display:flex; justify-content: flex-end; gap: 8px;">
                                        <a href="dashboard_admin.php?page=dashboard&aksi=acc&from=dashboard&id=<?= $row['id_kos'] ?>" class="btn-acc"><i class="fa-solid fa-check"></i> Setujui & ACC</a>
                                        <a href="dashboard_admin.php?page=dashboard&aksi=tolak&from=dashboard&id=<?= $row['id_kos'] ?>" class="btn-tolak"><i class="fa-solid fa-xmark"></i> Tolak</a>
                                    </div>
                                </div>
                            </div>

                            <?php 
                            endwhile; 
                            if(!$kos_ada):
                            ?>
                            <tr><td colspan="4" style="text-align: center; color: #999; padding: 20px 0;">Tidak ada pendaftaran kos baru yang masuk.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="content-card">
                    <div class="card-header-area">
                        <h3 class="card-title"><i class="fa-solid fa-file-invoice" style="color: #ff3333;"></i> Data Masuk: Laporan Keluhan Pengguna</h3>
                        <span class="badge badge-danger">Kritis</span>
                    </div>

                    <table class="admin-table">
                        <thead>
                            <tr><th>Pelapor</th><th>Objek Kos Terlapor</th><th>Keterangan Masalah Keluhan</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Andi Saputra</b></td>
                                <td>Kos Putra Kenangan (Kamar 02)</td>
                                <td>Saluran WiFi induk terputus dan kamar mandi mengalami kebocoran air.</td>
                                <td><span class="badge badge-warning">Menunggu Tindakan</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($page == 'verifikasi'): ?>
                <div class="content-card">
                    <div class="card-header-area"><h3 class="card-title">Manajemen Antrean Verifikasi Properti</h3></div>
                    <table class="admin-table">
                        <thead>
                            <tr><th>Nama Kos</th><th>Alamat</th><th>Harga</th><th>Kontrol Tindakan</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stmt = $pdo->query("SELECT * FROM master_kos ORDER BY id_kos DESC");
                            while ($row = $stmt->fetch()): 
                            ?>
                            <tr>
                                <td><b><?= htmlspecialchars($row['nama_kos']) ?></b></td>
                                <td><?= htmlspecialchars($row['lokasi']) ?></td>
                                <td>Rp <?= number_format($row['harga_per_bulan'],0,',','.') ?></td>
                                <td>
                                    <a href="#modal-dash-<?= $row['id_kos'] ?>" class="btn-detail"><i class="fa-solid fa-circle-info"></i> Detail</a>
                                    <a href="dashboard_admin.php?page=verifikasi&aksi=acc&id=<?= $row['id_kos'] ?>" class="btn-acc"><i class="fa-solid fa-check"></i> ACC</a>
                                    <a href="dashboard_admin.php?page=verifikasi&aksi=tolak&id=<?= $row['id_kos'] ?>" class="btn-tolak"><i class="fa-solid fa-xmark"></i> Tolak</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($page == 'laporan'): ?>
                <div class="content-card">
                    <div class="card-header-area"><h3 class="card-title">Daftar Log Laporan Keluhan Masuk</h3></div>
                    <table class="admin-table">
                        <thead>
                            <tr><th>Nama Pelapor</th><th>Objek Masalah</th><th>Isi Keluhan</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Andi Saputra</b></td>
                                <td>Kos Putra Kenangan (Kamar 02)</td>
                                <td>Saluran WiFi induk terputus dan kamar mandi mengalami kebocoran air.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($page == 'pengaturan'): ?>
                <div class="content-card">
                    <h3 class="card-title">Pengaturan Akun Admin</h3>
                    <div style="max-width: 400px; margin-top: 15px;">
                        <label style="font-size: 12px; font-weight:600; color:#555;">Username Kredensial</label>
                        <input type="text" value="admin_papikos" disabled style="width:100%; padding:10px; margin-top:5px; border:1px solid #ddd; background:#f5f5f5; border-radius:6px;">
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="right-column">
            
            <div class="menu-card">
                <p class="menu-label">MENU UTAMA ADMIN</p>
                <div class="menu-list">
                    <a href="?page=dashboard" class="menu-link <?= $page == 'dashboard' ? 'active' : '' ?>">
                        <i class="fa-solid fa-border-all"></i> Dashboard
                    </a>
                    <a href="?page=verifikasi" class="menu-link <?= $page == 'verifikasi' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-shield"></i> Verifikasi Kos
                    </a>
                    <a href="?page=laporan" class="menu-link <?= $page == 'laporan' ? 'active' : '' ?>">
                        <i class="fa-solid fa-file-invoice"></i> Laporan
                    </a>
                    <a href="?page=pengaturan" class="menu-link <?= $page == 'pengaturan' ? 'active' : '' ?>">
                        <i class="fa-solid fa-gear"></i> Pengaturan Akun
                    </a>
                    <a href="index.php" class="menu-link logout" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </a>
                </div>
            </div>

            <div style="background-color: #00a65b; border-radius: 12px; padding: 24px; color: #ffffff;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 10px;">Pusat Aktivitas Terpadu</h3>
                <p style="font-size: 13px; line-height: 1.6; opacity: 0.9;">Kini halaman Dashboard utama merangkum seluruh berkas pendaftaran dan laporan keluhan yang masuk agar Admin tidak melewatkan pembaruan data sistem.</p>
            </div>

        </div>

    </div>

</body>
</html>
