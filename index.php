```php
<?php include 'koneksi.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAPIKOS - Selamat Datang</title>

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
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            color:#333;
            padding:20px;
        }

        .welcome-container{
            width:100%;
            max-width:950px;
            text-align:center;
        }

        .logo-area{
            margin-bottom:18px;
        }

        .logo-icon{
            font-size:52px;
            color:#00a65b;
            margin-bottom:12px;
        }

        .logo-text{
            font-size:38px;
            font-weight:800;
            color:#222;
            letter-spacing:1px;
        }

        .logo-text span{
            color:#00a65b;
        }

        .subtitle{
            font-size:16px;
            color:#666;
            margin-bottom:45px;
        }

        .role-grid{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:25px;
        }

        .role-card{
            background:#fff;
            border-radius:18px;
            padding:40px 25px;
            text-decoration:none;
            color:#333;
            border:1px solid #e2e8f0;
            box-shadow:0 4px 10px rgba(0,0,0,0.03);
            transition:0.3s;
            display:flex;
            flex-direction:column;
            align-items:center;
        }

        .role-card:hover{
            transform:translateY(-8px);
            border-color:#00a65b;
            box-shadow:0 14px 24px rgba(0,166,91,0.12);
        }

        .role-icon-box{
            width:85px;
            height:85px;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            font-size:34px;
            margin-bottom:22px;
            transition:0.3s;
        }

        .admin-box{
            background:#eef2f7;
            color:#4a5568;
        }

        .penjual-box{
            background:#e6f6ef;
            color:#00a65b;
        }

        .pelanggan-box{
            background:#e0f2fe;
            color:#0284c7;
        }

        .role-card:hover .admin-box{
            background:#4a5568;
            color:#fff;
        }

        .role-card:hover .penjual-box{
            background:#00a65b;
            color:#fff;
        }

        .role-card:hover .pelanggan-box{
            background:#0284c7;
            color:#fff;
        }

        .role-title{
            font-size:20px;
            font-weight:700;
            margin-bottom:10px;
            color:#1a202c;
        }

        .role-desc{
            font-size:14px;
            color:#718096;
            line-height:1.6;
        }

        .footer-note{
            margin-top:55px;
            font-size:12px;
            color:#94a3b8;
        }

        @media(max-width:768px){

            .role-grid{
                grid-template-columns:1fr;
            }

            .role-card{
                padding:30px 20px;
            }

            .logo-text{
                font-size:30px;
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

        <p class="subtitle">
            Selamat datang di sistem informasi pencarian dan pengelolaan kos berbasis web.
            Silakan pilih hak akses untuk melanjutkan ke dashboard.
        </p>

        <div class="role-grid">

            <a href="dashboard_admin.php" class="role-card">

                <div class="role-icon-box admin-box">
                    <i class="fa-solid fa-user-shield"></i>
                </div>

                <h2 class="role-title">Admin</h2>

                <p class="role-desc">
                    Mengelola data sistem, memverifikasi pengajuan kos,
                    menangani laporan pengguna, dan mengatur validasi platform.
                </p>

            </a>

            <a href="dashboard_penjual.php" class="role-card">

                <div class="role-icon-box penjual-box">
                    <i class="fa-solid fa-house-user"></i>
                </div>

                <h2 class="role-title">Penjual</h2>

                <p class="role-desc">
                    Menambahkan properti kos, mengelola fasilitas kamar,
                    memantau transaksi penyewa, dan mengatur data properti.
                </p>

            </a>

            <a href="pencari_kos.php" class="role-card">

                <div class="role-icon-box pelanggan-box">
                    <i class="fa-solid fa-user-astronaut"></i>
                </div>

                <h2 class="role-title">Pelanggan</h2>

                <p class="role-desc">
                    Mencari kos terbaik, melihat detail fasilitas,
                    melakukan booking kamar, dan memantau status pemesanan.
                </p>

            </a>

        </div>

        <div class="footer-note">
            &copy; 2026 PAPIKOS - Sistem Informasi Pencarian dan Pengelolaan Kos Berbasis Web
        </div>

    </div>

</body>
</html>
```
