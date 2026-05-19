<?php
include 'koneksi.php';

$tipe_filter = isset($_GET['tipe']) ? $_GET['tipe'] : '';
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql = "SELECT * FROM master_kos WHERE status_kamar = 'Kosong'";
$params = [];

if (!empty($search_query)) {
    $sql .= " AND (nama_kos LIKE ? OR lokasi LIKE ?)";
    $params[] = "%" . $search_query . "%";
    $params[] = "%" . $search_query . "%";
    $judul_section = "Hasil Pencarian untuk: '" . htmlspecialchars($search_query) . "'";
} 
elseif ($tipe_filter == 'Putra') {
    $sql .= " AND tipe_kos = 'Putra'";
    $judul_section = "Hasil Pencarian: Kos Putra";
} 
elseif ($tipe_filter == 'Putri') {
    $sql .= " AND tipe_kos = 'Putri'";
    $judul_section = "Hasil Pencarian: Kos Putri";
} 
elseif ($tipe_filter == 'Campur') {
    $judul_section = "Hasil Pencarian: Kos Campur";
} 
else {
    $judul_section = "Semua Rekomendasi Kos Pilihan";
}

$sql .= " ORDER BY id_kos DESC";

$stmt_pilihan = $pdo->prepare($sql);
$stmt_pilihan->execute($params);

$stmt_baru = $pdo->query("SELECT * FROM master_kos WHERE status_kamar = 'Kosong' ORDER BY id_kos DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAPIKOS - Cari Kos-Kosan Impianmu</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
            font-family:'Segoe UI',sans-serif;
        }

        body{
            background-color:#ffffff;
            color:#333;
            overflow-x:hidden;
        }

        .navbar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:20px 8%;
            background:#fff;
            position:sticky;
            top:0;
            z-index:1000;
            box-shadow:0 2px 10px rgba(0,0,0,0.05);
        }

        .logo{
            font-size:24px;
            font-weight:800;
            color:#222;
            text-decoration:none;
        }

        .logo span{
            color:#00a65b;
        }

        .nav-links{
            display:flex;
            gap:25px;
            list-style:none;
        }

        .nav-links a{
            text-decoration:none;
            color:#555;
            font-size:14px;
            font-weight:600;
            padding:5px 10px;
            border-radius:4px;
            transition:0.2s;
        }

        .nav-links a:hover,
        .nav-links a.active{
            color:#00a65b;
            background-color:#e6f6ef;
        }

        .nav-auth{
            display:flex;
            gap:15px;
            align-items:center;
        }

        .btn-masuk{
            color:#00a65b;
            text-decoration:none;
            font-weight:700;
            font-size:14px;
        }

        .btn-daftar{
            background:#00a65b;
            color:#fff;
            padding:8px 20px;
            border-radius:6px;
            text-decoration:none;
            font-weight:700;
            font-size:14px;
        }

        .hero{
            height:420px;
            background:
            linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
            url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size:cover;
            background-position:center;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:center;
            color:#fff;
            text-align:center;
        }

        .hero h1{
            font-size:42px;
            font-weight:800;
            margin-bottom:15px;
        }

        .search-container{
            background:#fff;
            padding:8px;
            border-radius:50px;
            display:flex;
            width:600px;
            box-shadow:0 10px 25px rgba(0,0,0,0.2);
            margin-top:15px;
        }

        .search-container input{
            flex:1;
            border:none;
            padding:12px 20px;
            border-radius:50px;
            outline:none;
            font-size:14px;
            color:#333;
        }

        .btn-cari{
            background:#00a65b;
            border:none;
            color:#fff;
            padding:0 25px;
            border-radius:50px;
            font-weight:700;
            cursor:pointer;
            transition:0.2s;
        }

        .btn-cari:hover{
            background:#008f4e;
        }

        .section-container{
            padding:50px 8%;
        }

        .section-header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
        }

        .section-header h3{
            font-size:24px;
            font-weight:800;
        }

        .section-header a{
            color:#00a65b;
            text-decoration:none;
            font-weight:700;
            font-size:14px;
        }

        .kos-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:25px;
        }

        .kos-card{
            background:#fff;
            border-radius:12px;
            overflow:hidden;
            border:1px solid #eef2f1;
            transition:0.3s;
            cursor:pointer;
            position:relative;
        }

        .kos-card:hover{
            transform:translateY(-8px);
            box-shadow:0 15px 30px rgba(0,0,0,0.08);
        }

        .kos-img{
            width:100%;
            height:180px;
            background:#f3f4f6;
            background-size:cover;
            background-position:center;
        }

        .kos-info{
            padding:15px;
        }

        .tag-tipe{
            position:absolute;
            top:12px;
            left:12px;
            color:#fff;
            font-size:11px;
            font-weight:800;
            padding:5px 10px;
            border-radius:6px;
            text-transform:uppercase;
            letter-spacing:0.5px;
        }

        .tag-putra{
            background-color:#0284c7;
        }

        .tag-putri{
            background-color:#ec4899;
        }

        .tag-campur{
            background-color:#a855f7;
        }

        .kos-nama{
            font-size:16px;
            font-weight:700;
            margin-bottom:4px;
            color:#111;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .kos-lokasi{
            font-size:13px;
            color:#666;
            margin-bottom:12px;
            display:flex;
            align-items:center;
            gap:4px;
        }

        .kos-harga{
            color:#00a65b;
            font-weight:800;
            font-size:16px;
            border-top:1px solid #f3f4f6;
            padding-top:10px;
        }

        .kos-harga span{
            color:#888;
            font-size:12px;
            font-weight:400;
        }

        .area-grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
        }

        .area-card{
            height:140px;
            border-radius:12px;
            background-size:cover;
            background-position:center;
            display:flex;
            align-items:flex-end;
            padding:20px;
            text-decoration:none;
            color:#fff;
            font-weight:700;
            font-size:18px;
            position:relative;
            overflow:hidden;
        }

        .area-card::before{
            content:'';
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:linear-gradient(transparent, rgba(0,0,0,0.7));
        }

        .area-card span{
            z-index:1;
        }

        .kos-baru-grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
        }

        .kos-baru-card{
            height:220px;
            border-radius:12px;
            background-size:cover;
            background-position:center;
            display:flex;
            flex-direction:column;
            justify-content:flex-end;
            padding:25px;
            color:#fff;
            position:relative;
            overflow:hidden;
            text-decoration:none;
        }

        .kos-baru-card::before{
            content:'';
            position:absolute;
            top:0;
            left:0;
            width:100%;
            height:100%;
            background:linear-gradient(transparent, rgba(0,0,0,0.8));
        }

        .kos-baru-card h4{
            z-index:1;
            font-size:18px;
            font-weight:800;
            margin-bottom:2px;
        }

        .kos-baru-card p{
            z-index:1;
            font-size:13px;
            opacity:0.9;
            color:#e6f6ef;
            font-weight:600;
        }

        .footer{
            background:#1a1a1a;
            color:#fff;
            padding:60px 8%;
            display:grid;
            grid-template-columns:2fr 1fr 1fr 1fr;
            gap:40px;
        }

        .footer-col h4{
            margin-bottom:20px;
            font-size:16px;
            color:#fff;
        }

        .footer-col ul{
            list-style:none;
        }

        .footer-col ul li{
            margin-bottom:10px;
        }

        .footer-col ul li a{
            color:#aaa;
            text-decoration:none;
            font-size:14px;
        }

        .footer-bottom{
            background:#111;
            padding:20px 8%;
            text-align:center;
            color:#555;
            font-size:12px;
        }

        .empty-state{
            text-align:center;
            padding:50px;
            color:#999;
            grid-column:span 4;
            font-size:15px;
            font-weight:500;
        }
    </style>
</head>

<body>

<nav class="navbar">
    <a href="pencari_kos.php" class="logo">PAPI<span>KOS</span></a>

    <ul class="nav-links">
        <li>
            <a href="pencari_kos.php" class="<?= ($tipe_filter == '' && empty($search_query)) ? 'active' : '' ?>">
                Semua Beranda
            </a>
        </li>

        <li>
            <a href="pencari_kos.php?tipe=Putra" class="<?= $tipe_filter == 'Putra' ? 'active' : '' ?>">
                <i class="fa-solid fa-mars"></i> Kost Putra
            </a>
        </li>

        <li>
            <a href="pencari_kos.php?tipe=Putri" class="<?= $tipe_filter == 'Putri' ? 'active' : '' ?>">
                <i class="fa-solid fa-venus"></i> Kost Putri
            </a>
        </li>

        <li>
            <a href="pencari_kos.php?tipe=Campur" class="<?= $tipe_filter == 'Campur' ? 'active' : '' ?>">
                <i class="fa-solid fa-person-half-dress"></i> Kost Campur
            </a>
        </li>
    </ul>

    <div class="nav-auth">
        <a href="#" class="btn-masuk">Masuk Mitra</a>
        <a href="#" class="btn-daftar">Mulai Cari</a>
    </div>
</nav>

<section class="hero">
    <h1>Menghubungkan Setiap Cerita Anak Kos</h1>

    <p style="opacity:0.9; font-size:15px;">
        Temukan hunian kos real-time mahasiswa terintegrasi sistem database Universitas Lampung.
    </p>

    <form action="pencari_kos.php" method="GET" class="search-container">
        <input
            type="text"
            name="search"
            placeholder="Cari nama kos atau lokasi..."
            value="<?= htmlspecialchars($search_query) ?>"
            required
        >

        <button type="submit" class="btn-cari">
            Cari Rumah
        </button>
    </form>
</section>

<section class="section-container" id="konten-kos">

    <div class="section-header">
        <h3><?= $judul_section ?></h3>

        <?php if (!empty($tipe_filter) || !empty($search_query)): ?>
            <a href="pencari_kos.php" style="color:#ff3333;">
                <i class="fa-solid fa-rotate-left"></i>
                Reset Pencarian
            </a>
        <?php else: ?>
            <a href="#">Lihat Semua ></a>
        <?php endif; ?>
    </div>

    <div class="kos-grid">

        <?php 
        $ada_data = false;

        while($row = $stmt_pilihan->fetch()):
            $ada_data = true;

            $badge_class = 'tag-campur';

            if ($row['tipe_kos'] == 'Putra') {
                $badge_class = 'tag-putra';
            }

            if ($row['tipe_kos'] == 'Putri') {
                $badge_class = 'tag-putri';
            }
        ?>

        <div class="kos-card">

            <div class="tag-tipe <?= $badge_class ?>">
                <?= htmlspecialchars($row['tipe_kos']) ?>
            </div>

            <div class="kos-img"
                style="background-image:url('https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=400&q=80');">
            </div>

            <div class="kos-info">

                <div class="kos-nama">
                    <?= htmlspecialchars($row['nama_kos']) ?>
                </div>

                <div class="kos-lokasi">
                    <i class="fa-solid fa-location-dot" style="color:#ffaa00;"></i>
                    <?= htmlspecialchars($row['lokasi']) ?>
                </div>

                <div class="kos-harga">
                    Rp <?= number_format($row['harga_per_bulan'], 0, ',', '.') ?>
                    <span>/ bulan</span>
                </div>

            </div>
        </div>

        <?php endwhile; ?>

        <?php if(!$ada_data): ?>

        <div class="empty-state">
            <i class="fa-solid fa-magnifying-glass-blur"
               style="font-size:38px; color:#ccc; margin-bottom:15px; display:block;">
            </i>

            Tidak ditemukan data kos yang cocok dengan pencarian Anda.
        </div>

        <?php endif; ?>

    </div>
</section>

<section class="section-container"
         style="background:#f9fafb; border-top:1px solid #f3f4f6; border-bottom:1px solid #f3f4f6;">

    <div class="section-header">
        <h3>Area Kampus Terpopuler</h3>
    </div>

    <div class="area-grid">

        <a href="#"
           class="area-card"
           style="background-image:url('https://images.unsplash.com/photo-1555881400-74d7acaacd8b?auto=format&fit=crop&w=500&q=80');">
            <span>Kawasan Universitas Lampung</span>
        </a>

        <a href="#"
           class="area-card"
           style="background-image:url('https://images.unsplash.com/photo-1596422846543-75c6fc18a5cf?auto=format&fit=crop&w=500&q=80');">
            <span>Kawasan ITERA</span>
        </a>

        <a href="#"
           class="area-card"
           style="background-image:url('https://images.unsplash.com/photo-1571504216939-693f293d78ea?auto=format&fit=crop&w=500&q=80');">
            <span>Kawasan Polinela</span>
        </a>

    </div>
</section>

<section class="section-container">

    <div class="section-header">
        <h3>Kos Tersedia Pekan Ini</h3>
    </div>

    <div class="kos-baru-grid">

        <?php
        $ada_baru = false;

        while($row_baru = $stmt_baru->fetch()):
            $ada_baru = true;
        ?>

        <a href="#"
           class="kos-baru-card"
           style="background-image:url('https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=500&q=80');">

            <h4><?= htmlspecialchars($row_baru['nama_kos']) ?></h4>

            <p>
                Mulai Rp <?= number_format($row_baru['harga_per_bulan'],0,',','.') ?> / Bln
            </p>

        </a>

        <?php endwhile; ?>

        <?php if(!$ada_baru): ?>

        <p style="color:#aaa; font-size:14px; text-align:center; grid-column:span 3;">
            Belum ada penambahan kos baru pekan ini.
        </p>

        <?php endif; ?>

    </div>
</section>

<footer class="footer">

    <div class="footer-col">
        <h4 style="font-size:24px; font-weight:800; color:#00a65b;">
            PAPIKOS
        </h4>

        <p style="color:#aaa; font-size:14px; line-height:1.6;">
            Solusi digital terbaik pencarian tempat tinggal dan kos-kosan mahasiswa.
        </p>
    </div>

    <div class="footer-col">
        <h4>Navigasi</h4>

        <ul>
            <li><a href="#">Tentang Kami</a></li>
            <li><a href="#">Pusat Bantuan</a></li>
        </ul>
    </div>

    <div class="footer-col">
        <h4>Kebijakan Hukum</h4>

        <ul>
            <li><a href="#">Syarat & Ketentuan</a></li>
            <li><a href="#">Kebijakan Privasi</a></li>
        </ul>
    </div>

    <div class="footer-col">
        <h4>Kontak</h4>

        <p style="color:#aaa; font-size:14px; margin-bottom:5px;">
            CS: 0821-3825-9191
        </p>

        <p style="color:#aaa; font-size:14px;">
            support@papikos.com
        </p>
    </div>

</footer>

<div class="footer-bottom">
    &copy; 2026 PAPIKOS - All Rights Reserved.
</div>

</body>
</html>
