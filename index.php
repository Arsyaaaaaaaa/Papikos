<?php include 'koneksi.php'; ?>

<?php
// index.php - Halaman Pemilihan Akses (Role Selector)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAPIKOS - Selamat Datang</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* CSS MURNI - Gaya Estetik & Minimalis Papikos */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f4f7f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        .welcome-container {
            text-align: center;
            max-width: 900px;
            width: 100%;
            padding: 20px;
        }

        .logo-area {
            margin-bottom: 15px;
        }

        .logo-icon {
            color: #00a65b;
            font-size: 48px;
            margin-bottom: 10px;
        }

        .logo-text {
            font-size: 32px;
            font-weight: 800;
            color: #222;
            letter-spacing: 0.5px;
        }

        .logo-text span {
            color: #00a65b;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 40px;
        }

        /* Grid Pilihan Role */
        .role-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        /* Card untuk Setiap Role */
        .role-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 20px;
            text-decoration: none;
            color: #333;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }

        /* Efek Hover Card */
        .role-card:hover {
            transform: translateY(-8px);
            border-color: #00a65b;
            box-shadow: 0 12px 20px rgba(0, 166, 91, 0.1);
        }

        .role-icon-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 32px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        /* Variasi Warna Icon Per Role */
        .admin-box { background-color: #eef2f7; color: #4a5568; }
        .penjual-box { background-color: #e6f6ef; color: #00a65b; }
        .pelanggan-box { background-color: #e0f2fe; color: #0284c7; }

        .role-card:hover .admin-box { background-color: #4a5568; color: #fff; }
        .role-card:hover .penjual-box { background-color: #00a65b; color: #fff; }
        .role-card:hover .pelanggan-box { background-color: #0284c7; color: #fff; }

        .role-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .role-desc {
            font-size: 13px;
            color: #718096;
            line-height: 1.5;
            text-align: center;
        }

        /* Footer Keterangan Sistem */
        .footer-note {
            margin-top: 50px;
            font-size: 12px;
            color: #a0aec0;
        }

        /* Responsif untuk Layar HP */
        @media (max-width: 768px) {
            .role-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            .role-card {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="welcome-container">
        <div class="logo-area">
            <i class="fa-solid fa-house-chimney-window logo-icon"></i>
            <h1 class="logo-text">PAPI<span>KOS</span></h1>
        </div>
        <p class="subtitle">Selamat datang! Silakan pilih hak akses Anda untuk masuk ke sistem.</p>

        <div class="role-grid">
            
            <a href="dashboard_admin.php" class="role-card">
                <div class="role-icon-box admin-box">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h2 class="role-title">Admin</h2>
                <p class="role-desc">Mengelola verifikasi berkas kos, meninjau laporan keluhan, dan validasi sistem.</p>
            </a>

            <a href="dashboard_penjual.php" class="role-card">
                <div class="role-icon-box penjual-box">
                    <i class="fa-solid fa-house-user"></i>
                </div>
                <h2 class="role-title">Penjual</h2>
                <p class="role-desc">Mendaftarkan properti kamar, mengatur harga, fasilitas, dan mengelola pesanan sewa.</p>
            </a>

            <a href="pencari_kos.php" class="role-card">
                <div class="role-icon-box pelanggan-box">
                    <i class="fa-solid fa-user-astronaut"></i>
                </div>
                <h2 class="role-title">Pelangan</h2>
                <p class="role-desc">Mencari kos-kosan impian, memantau ketersediaan kamar, dan melakukan transaksi booking.</p>
            </a>

        </div>

        <div class="footer-note">
            &copy; 2026 PAPIKOS - Sistem Informasi Pencarian dan Pengelolaan Kos Berbasi Web.
        </div>
    </div>

</body>
</html>