<?php
// --- INCLUDE KONEKSI DATABASE ---
include 'koneksi.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pesan = '';

// ==============================================
// PROSES CRUD PENJUAL: MANAJEMEN DAFTAR KOS
// ==============================================

// [CREATE] Penjual menambah properti kos baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah_kos') {
    // Menggunakan status 'Pending Verifikasi' sesuai kapasitas kolom DB Anda
    $stmt = $pdo->prepare("INSERT INTO master_kos (nama_kos, lokasi, tipe_kos, fasilitas, harga_per_bulan, status_kamar) VALUES (?, ?, ?, ?, ?, 'Pending Verifikasi')");
    $stmt->execute([$_POST['nama_kos'], $_POST['lokasi'], $_POST['tipe_kos'], $_POST['fasilitas'], $_POST['harga']]);
    $pesan = "Properti kos baru berhasil ditambahkan! Menunggu proses verifikasi (ACC) dari Admin.";
    $page = 'daftar_kos';
}

// [UPDATE] Penjual mengubah informasi data kos miliknya
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_kos') {
    $stmt = $pdo->prepare("UPDATE master_kos SET nama_kos = ?, lokasi = ?, tipe_kos = ?, fasilitas = ?, harga_per_bulan = ? WHERE id_kos = ?");
    $stmt->execute([$_POST['nama_kos'], $_POST['lokasi'], $_POST['tipe_kos'], $_POST['fasilitas'], $_POST['harga'], $_POST['id_kos']]);
    $pesan = "Data properti kos berhasil diperbarui!";
    $page = 'daftar_kos';
}

// [DELETE] Penjual menghapus properti kos miliknya
if (isset($_GET['hapus_kos'])) {
    $id = $_GET['hapus_kos'];
    $stmt = $pdo->prepare("DELETE FROM master_kos WHERE id_kos = ?");
    $stmt->execute([$id]);
    $pesan = "Data properti kos berhasil dihapus dari sistem.";
    $page = 'daftar_kos';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAPIKOS - Dashboard Pemilik</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS Utama - Sesuai Mockup UI Desain Papikos */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f4f7f6; color: #333; min-height: 100vh; display: flex; flex-direction: column; }

        /* TOP NAVBAR HEADER (FULL WIDTH DI ATAS) */
        .top-navbar { height: 75px; background-color: #ffffff; border-bottom: 1px solid #eef2f1; display: flex; justify-content: space-between; align-items: center; padding: 0 30px; position: fixed; top: 0; left: 0; right: 0; z-index: 100; }
        .logo-area { display: flex; align-items: center; gap: 8px; }
        .logo-icon { color: #00a65b; font-size: 28px; }
        .logo-text { font-size: 22px; font-weight: 800; color: #222; letter-spacing: -0.5px; }
        .logo-text span { color: #00a65b; font-weight: 500; font-size: 14px; margin-left: 5px; background: #e6f6ef; padding: 2px 6px; border-radius: 4px; }
        
        .top-right-menu { display: flex; align-items: center; gap: 25px; }
        .cs-info { font-size: 14px; color: #555; font-weight: 600; display: flex; align-items: center; gap: 6px; }
        .cs-info i { color: #00a65b; }
        .notif-bell { font-size: 20px; color: #666; position: relative; cursor: pointer; }
        .notif-badge { position: absolute; top: -2px; right: -2px; width: 8px; height: 8px; background-color: #ff3333; border-radius: 50%; }
        .profile-widget { display: flex; align-items: center; gap: 10px; border-left: 1px solid #eee; padding-left: 20px; }
        .profile-img { width: 35px; height: 35px; background-color: #e6f6ef; color: #00a65b; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .profile-info { font-size: 13px; }
        .profile-name { font-weight: 700; color: #222; }
        .profile-role { color: #888; font-size: 11px; }

        /* TATA LETAK UTAMA */
        .main-container { display: flex; margin-top: 75px; padding: 30px; gap: 30px; flex-grow: 1; }

        /* SIDEBAR KIRI (BENTUK KARTU MELAYANG SESUAI MOCKUP) */
        .sidebar-card { width: 260px; background: #ffffff; border-radius: 12px; padding: 25px 15px; border: 1px solid #eef2f1; height: fit-content; display: flex; flex-direction: column; }
        .menu-label { font-size: 11px; font-weight: 700; color: #999; margin-bottom: 15px; letter-spacing: 0.5px; padding-left: 15px; text-transform: uppercase; }
        .menu-list { display: flex; flex-direction: column; gap: 4px; }
        .menu-link { display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: #666; font-weight: 600; border-radius: 8px; font-size: 14px; transition: all 0.2s; }
        .menu-link i { font-size: 16px; width: 20px; }
        .menu-link:hover, .menu-link.active { background-color: #e6f6ef; color: #00a65b; }
        .divider { height: 1px; background: #eee; margin: 20px 0; }
        .menu-link.logout { color: #ff3333; }
        .menu-link.logout:hover { background-color: #ffe6e6; }

        /* KONTEN KANAN WAN */
        .content-area { flex-grow: 1; display: flex; flex-direction: column; gap: 25px; }
        .content-header { display: flex; justify-content: space-between; align-items: center; }
        .dash-title { font-size: 24px; font-weight: 700; color: #111; }
        .dash-subtitle { font-size: 14px; color: #777; margin-top: 2px; }

        /* STRUKTUR GRID 4 KARTU STATISTIK */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .stat-card { background: #ffffff; border-radius: 12px; padding: 20px; border: 1px solid #eef2f1; display: flex; flex-direction: column; gap: 15px; }
        .stat-header { display: flex; align-items: center; gap: 10px; font-size: 13px; color: #666; font-weight: 600; }
        .stat-icon-wrapper { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; }
        .stat-value { font-size: 28px; font-weight: 700; color: #111; }

        /* LAYOUT DUA KOLOM BAWAH */
        .dashboard-row { display: flex; gap: 25px; }
        .left-column { flex: 2; display: flex; flex-direction: column; }
        .right-column { flex: 1; }

        /* DYNAMIC RECENT LIST & CARDS */
        .main-card { background: #ffffff; border-radius: 12px; padding: 25px; border: 1px solid #eef2f1; }
        .card-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; color: #222; }
        .see-all-link { font-size: 13px; color: #00a65b; font-weight: 700; text-decoration: none; }

        /* List Rows Real-time */
        .recent-list { display: flex; flex-direction: column; gap: 12px; }
        .recent-item { display: flex; align-items: center; justify-content: space-between; padding: 15px; background: #ffffff; border: 1px solid #eef2f1; border-radius: 8px; }
        .item-profile { display: flex; align-items: center; gap: 15px; }
        .avatar-circle { width: 40px; height: 40px; background-color: #f1f5f9; color: #475569; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
        .item-details b { font-size: 14px; color: #222; display: block; margin-bottom: 2px; }
        .item-details span { font-size: 12px; color: #777; }
        .item-price { font-weight: 700; color: #00a65b; font-size: 14px; }

        /* TIPS BOX (HIJAU SESUAI MOCKUP) */
        .tips-box { background-color: #00a65b; border-radius: 12px; padding: 25px; color: white; display: flex; flex-direction: column; gap: 20px; }
        .tips-title { font-size: 18px; font-weight: 700; }
        .tips-body { background: rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 15px; font-size: 13px; line-height: 1.5; }
        .btn-tips-link { background: white; color: #00a65b; text-align: center; padding: 12px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 14px; display: block; }

        /* BUTTONS & BADGES */
        .btn-green { background-color: #00a65b; color: white; padding: 10px 18px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 13px; text-decoration: none; }
        .btn-green:hover { background-color: #008f4e; }
        .badge { font-size: 11px; font-weight: 700; padding: 5px 10px; border-radius: 4px; text-transform: uppercase; }
        .badge-warning { background-color: #fff3e0; color: #ff9800; }
        .badge-success { background-color: #e6f6ef; color: #00a65b; }
        .badge-info { background-color: #e0f2fe; color: #0369a1; }
        .alert { background-color: #e6f6ef; color: #00a65b; padding: 15px; border-radius: 8px; border: 1px solid #00a65b; margin-bottom: 10px; font-size: 14px; font-weight: 600; }

        /* TABEL DATA */
        .properti-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .properti-table th, .properti-table td { padding: 14px; border-bottom: 1px solid #eee; text-align: left; font-size: 14px; }
        .properti-table th { background-color: #f9fafb; color: #555; font-weight: 600; }
        .btn-edit { background-color: #ff9800; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; margin-right: 4px; }
        .btn-danger { background-color: #ff3333; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; }

        /* MODAL POP-UP STYLE */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 1000; }
        .modal:target { display: flex; }
        .modal-content { background: white; padding: 30px; border-radius: 12px; max-width: 500px; width: 100%; position: relative; }
        .close-modal { position: absolute; top: 15px; right: 20px; text-decoration: none; color: #aaa; font-size: 24px; font-weight: bold; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; margin-bottom: 5px; color: #555; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }

        /* Chat Layout */
        .chat-container { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; min-height: 380px; }
        .chat-sidebar { border-right: 1px solid #eee; padding-right: 15px; }
        .chat-user-item { padding: 12px; border-radius: 8px; background: #f9fafb; margin-bottom: 8px; cursor: pointer; font-size: 13px; font-weight: 600; }
        .chat-user-item.active { background: #e6f6ef; color: #00a65b; }
        .chat-body { display: flex; flex-direction: column; justify-content: space-between; padding-left: 15px; }
        .chat-bubble { background: #f1f5f9; padding: 12px 16px; border-radius: 12px; font-size: 14px; max-width: 80%; margin-bottom: 10px; }
        .chat-bubble.owner { background: #e6f6ef; color: #00a65b; align-self: flex-end; }
    </style>
</head>
<body>

    <header class="top-navbar">
        <div class="logo-area">
            <i class="fa-solid fa-house-user logo-icon"></i>
            <div class="logo-text">PAPIKOS <span>Pemilik</span></div>
        </div>
        <div class="top-right-menu">
            <div class="cs-info">
                <i class="fa-solid fa-phone"></i> CS: 0821-3825-9191
            </div>
            <div class="notif-bell">
                <i class="fa-solid fa-bell"></i>
                <div class="notif-badge"></div>
            </div>
            <div class="profile-widget">
                <div class="profile-img">B</div>
                <div class="profile-info">
                    <div class="profile-name">Budi Santoso</div>
                    <div class="profile-role">Pemilik Kos</div>
                </div>
            </div>
        </div>
    </header>

    <div class="main-container">

        <aside class="sidebar-card">
            <p class="menu-label">Menu Penjual</p>
            <nav class="menu-list">
                <a href="?page=dashboard" class="menu-link <?= $page == 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-pie"></i> Dashboard
                </a>
                <a href="?page=daftar_kos" class="menu-link <?= $page == 'daftar_kos' ? 'active' : '' ?>">
                    <i class="fa-solid fa-house-chimney-window"></i> Daftar Properti
                </a>
                <a href="?page=pesanan" class="menu-link <?= $page == 'pesanan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-receipt"></i> Pesanan Sewa
                </a>
                <a href="?page=pesan" class="menu-link <?= $page == 'pesan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-comment-dots"></i> Chat Penyewa
                </a>
                <a href="?page=pengaturan" class="menu-link <?= $page == 'pengaturan' ? 'active' : '' ?>">
                    <i class="fa-solid fa-user-gear"></i> Pengaturan Akun
                </a>
                <div class="divider"></div>
                <a href="index.php" class="menu-link logout" onclick="return confirm('Keluar dari Halaman Mitra Pemilik?')">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                </a>
            </nav>
        </aside>

        <main class="content-area">

            <?php if (!empty($pesan)): ?>
                <div class="alert"><i class="fa-solid fa-circle-check"></i> <?= $pesan ?></div>
            <?php endif; ?>

            <?php if ($page == 'dashboard'): ?>
                
                <div class="content-header">
                    <div>
                        <h2 class="dash-title">Ringkasan Dashboard</h2>
                        <p class="dash-subtitle">Pantau performa properti dan pendapatan Anda.</p>
                    </div>
                    <a href="?page=daftar_kos#modal-tambah-kos" class="btn-green"><i class="fa-solid fa-plus"></i> Tambah Properti Baru</a>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper" style="background:#e6f6ef; color:#00a65b;"><i class="fa-solid fa-house"></i></div>
                            Total Properti
                        </div>
                        <div class="stat-value">
                            <?php 
                                $q = $pdo->query("SELECT COUNT(*) FROM master_kos");
                                echo $q->fetchColumn(); // Menghitung total data riil dari database
                            ?>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper" style="background:#e0f2fe; color:#0284c7;"><i class="fa-solid fa-users"></i></div>
                            Total Penyewa
                        </div>
                        <div class="stat-value">48</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper" style="background:#fff3e0; color:#ff9800;"><i class="fa-solid fa-circle-check"></i></div>
                            Pesanan Baru
                        </div>
                        <div class="stat-value">5</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-icon-wrapper" style="background:#f3e8ff; color:#a855f7;"><i class="fa-solid fa-wallet"></i></div>
                            Pendapatan Bulan Ini
                        </div>
                        <div class="stat-value" style="font-size: 22px; padding-top: 5px;">Rp 15.4M</div>
                    </div>
                </div>

                <div class="dashboard-row">
                    <div class="left-column">
                        <div class="main-card">
                            <div class="card-header-flex">
                                <h3 class="card-title">Pesanan Sewa Terbaru</h3>
                                <a href="?page=pesanan" class="see-all-link">Lihat Semua <i class="fa-solid fa-angle-right"></i></a>
                            </div>

                            <div class="recent-list">
                                <?php
                                // Mengambil data 3 kos terbaru dari database agar tampil riil & dinamis
                                $stmt = $pdo->query("SELECT * FROM master_kos ORDER BY id_kos DESC LIMIT 3");
                                $ada_data = false;
                                while ($row = $stmt->fetch()):
                                    $ada_data = true;
                                ?>
                                <div class="recent-item">
                                    <div class="item-profile">
                                        <div class="avatar-circle">A</div>
                                        <div class="item-details">
                                            <b>Andi Saputra</b>
                                            <span><?= htmlspecialchars($row['nama_kos']) ?> (<?= htmlspecialchars($row['tipe_kos']) ?>)</span>
                                        </div>
                                    </div>
                                    <div class="item-price">Rp <?= number_format($row['harga_per_bulan'], 0, ',', '.') ?></div>
                                    <div>
                                        <?php if ($row['status_kamar'] == 'Pending Verifikasi'): ?>
                                            <span class="badge badge-warning">MENUNGGU VERIFIKASI</span>
                                        <?php else: ?>
                                            <span class="badge badge-success">AKTIF / DI ACC</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endwhile; if(!$ada_data): ?>
                                    <p style="color:#999; text-align:center; padding: 20px 0; font-size:14px;">Belum ada riwayat properti baru yang dimasukkan.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="right-column">
                        <div class="tips-box">
                            <h4 class="tips-title">Tips Papikos</h4>
                            <div class="tips-body">
                                Tingkatkan persentase respons chat Anda untuk mendapatkan badge <b>Super Host!</b> <br><br>Tambahkan foto ruangan dengan pencahayaan yang terang agar menarik lebih banyak penyewa.
                            </div>
                            <a href="#" class="btn-tips-link">Pelajari Lebih Lanjut</a>
                        </div>
                    </div>
                </div>

            <?php elseif ($page == 'daftar_kos'): ?>
                <div class="main-card">
                    <div class="card-header-flex">
                        <h3 class="card-title">Manajemen Kamar & Properti Kos</h3>
                        <a href="#modal-tambah-kos" class="btn-green"><i class="fa-solid fa-plus"></i> Tambah Properti Baru</a>
                    </div>

                    <table class="properti-table">
                        <thead>
                            <tr><th>Nama Kos</th><th>Alamat Hunian</th><th>Tipe & Harga</th><th>Status Validasi</th><th>Kontrol Aksi</th></tr>
                        </thead>
                        <tbody>
                            <?php 
                            $stmt = $pdo->query("SELECT * FROM master_kos ORDER BY id_kos DESC");
                            $ada_kos = false;
                            while ($row = $stmt->fetch()): 
                                $ada_kos = true;
                            ?>
                            <tr>
                              <td><b><?= htmlspecialchars($row['nama_kos']) ?></b></td>
                              <td><?= htmlspecialchars($row['lokasi']) ?></td>
                              <td><span class="badge badge-info"><?= $row['tipe_kos'] ?></span><br><small style="font-weight:bold; color:#555;">Rp <?= number_format($row['harga_per_bulan'],0,',','.') ?></small></td>
                              <td>
                                  <?php if ($row['status_kamar'] == 'Pending Verifikasi'): ?>
                                      <span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Pending Admin</span>
                                  <?php else: ?>
                                      <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Aktif (ACC)</span>
                                  <?php endif; ?>
                              </td>
                              <td>
                                  <a href="#modal-edit-kos-<?= $row['id_kos'] ?>" class="btn-edit"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                  <a href="dashboard_penjual.php?page=daftar_kos&hapus_kos=<?= $row['id_kos'] ?>" class="btn-danger" onclick="return confirm('Hapus pengajuan kos ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
                              </td>
                            </tr>

                            <div id="modal-edit-kos-<?= $row['id_kos'] ?>" class="modal">
                                <div class="modal-content">
                                    <a href="#" class="close-modal">×</a>
                                    <h3 style="margin-bottom: 20px;">Ubah Informasi Properti</h3>
                                    <form method="POST" action="dashboard_penjual.php">
                                        <input type="hidden" name="action" value="edit_kos">
                                        <input type="hidden" name="id_kos" value="<?= $row['id_kos'] ?>">
                                        <div class="form-group">
                                            <label>Nama Properti</label>
                                            <input type="text" name="nama_kos" value="<?= htmlspecialchars($row['nama_kos']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Lokasi / Alamat</label>
                                            <input type="text" name="lokasi" value="<?= htmlspecialchars($row['lokasi']) ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tipe Hunian</label>
                                            <select name="tipe_kos">
                                                <option value="Putra" <?= $row['tipe_kos']=='Putra'?'selected':'' ?>>Putra</option>
                                                <option value="Putri" <?= $row['tipe_kos']=='Putri'?'selected':'' ?>>Putri</option>
                                                <option value="Campur" <?= $row['tipe_kos']=='Campur'?'selected':'' ?>>Campur</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Harga per Bulan (Rp)</label>
                                            <input type="number" name="harga" value="<?= $row['harga_per_bulan'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Fasilitas Utama</label>
                                            <textarea name="fasilitas" rows="3" required><?= htmlspecialchars($row['fasilitas']) ?></textarea>
                                        </div>
                                        <button type="submit" class="btn-green" style="width:100%; justify-content:center;">Simpan Pembaruan</button>
                                    </form>
                                </div>
                            </div>

                            <?php endwhile; if(!$ada_kos): ?>
                                <tr><td colspan="5" style="text-align:center; color:#999; padding:30px 0;">Belum memasukkan data kos apa pun.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div id="modal-tambah-kos" class="modal">
                    <div class="modal-content">
                        <a href="#" class="close-modal">×</a>
                        <h3 style="margin-bottom: 20px;">Daftarkan Properti Kamar Baru</h3>
                        <form method="POST" action="dashboard_penjual.php">
                            <input type="hidden" name="action" value="tambah_kos">
                            <div class="form-group">
                                <label>Nama Properti Kos</label>
                                <input type="text" name="nama_kos" placeholder="Contoh: Kos Kenangan Indah" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat Lengkap</label>
                                <input type="text" name="lokasi" placeholder="Jl. Zainal Abidin Pagar Alam" required>
                            </div>
                            <div class="form-group">
                                <label>Tipe Kos</label>
                                <select name="tipe_kos">
                                    <option value="Putra">Putra</option>
                                    <option value="Putri">Putri</option>
                                    <option value="Campur">Campur</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Harga Sewa Bulanan (Rp)</label>
                                <input type="number" name="harga" placeholder="1500000" required>
                            </div>
                            <div class="form-group">
                                <label>Fasilitas Utama Kamar</label>
                                <textarea name="fasilitas" placeholder="Kamar mandi dalam, AC, WiFi gratis" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn-green" style="width:100%; justify-content:center;">Ajukan Pendaftaran Kamar</button>
                        </form>
                    </div>
                </div>

            <?php elseif ($page == 'pesanan'): ?>
                <div class="main-card">
                    <div class="card-header-flex"><h3 class="card-title">Daftar Log Booking Penyewa Masuk</h3></div>
                    <table class="properti-table">
                        <thead>
                            <tr><th>Nama Penyewa</th><th>Kamar Kos Pilihan</th><th>Tanggal Booking</th><th>Nominal Transfer</th><th>Status Transaksi</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><b>Andi Saputra</b></td>
                                <td>Kos Putra Kenangan (Kamar 02)</td>
                                <td>18 Mei 2026</td>
                                <td>Rp 1.500.000</td>
                                <td><span class="badge badge-warning"><i class="fa-solid fa-hourglass-half"></i> Menunggu Konfirmasi</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($page == 'pesan'): ?>
                <div class="main-card">
                    <div class="chat-container">
                        <div class="chat-sidebar">
                            <p style="font-weight:bold; margin-bottom:12px; font-size:11px; color:#999; text-transform:uppercase;">Pesan Masuk</p>
                            <div class="chat-user-item active">Andi Saputra <br><small style="color:#777; font-weight:400;">"Kak, kosan kamar 02 ready?"</small></div>
                            <div class="chat-user-item" style="opacity:0.6;">Budi Utomo <br><small style="color:#777; font-weight:400;">Pesan telah dibalas</small></div>
                        </div>
                        <div class="chat-body">
                            <div style="flex-grow:1; display:flex; flex-direction:column; justify-content:flex-end;">
                                <div class="chat-bubble">Halo Kak, permisi mau nanya apakah fasilitas kamar mandi dalam di Kos Putra Kenangan Kamar 02 pipanya bocor?</div>
                                <div class="chat-bubble owner">Halo! Tidak bocor kak, semua sudah ditinjau oleh sistem admin Papikos dan siap huni. Silakan dibooking.</div>
                                <div class="chat-bubble">Baik Kak, terima kasih infonya, saya lakukan transaksi sekarang.</div>
                            </div>
                            <div style="margin-top:15px; display:flex; gap:10px;">
                                <input type="text" placeholder="Tulis balasan pesan chat mitra..." style="flex-grow:1; padding:12px; border:1px solid #ddd; border-radius:8px; font-size:14px;">
                                <button class="btn-green" style="padding:12px 20px;"><i class="fa-solid fa-paper-plane"></i> Kirim</button>
                            </div>
                        </div>
                    </div>
                </div>

            <?php elseif ($page == 'pengaturan'): ?>
                <div class="main-card">
                    <div class="card-header-flex"><h3 class="card-title">Kredensial Akun Pemilik (Mitra)</h3></div>
                    <div style="max-width: 450px;">
                        <div class="form-group">
                            <label>Nama Pemilik / Nama Toko Mitra</label>
                            <input type="text" value="Budi Santoso" disabled style="background:#f5f5f5;">
                        </div>
                        <div class="form-group">
                            <label>Alamat E-mail Bisnis</label>
                            <input type="email" value="budi_papikos@gmail.com" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor WhatsApp Terintegrasi</label>
                            <input type="text" value="0821-3825-9191" required>
                        </div>
                        <button class="btn-green" style="padding:10px 24px; margin-top:10px;"><i class="fa-solid fa-floppy-disk"></i> Simpan Kredensial</button>
                    </div>
                </div>
            <?php endif; ?>

        </main>
    </div>

</body>
</html>