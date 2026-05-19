<?php
include 'koneksi.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'tambah_kos') {
    $stmt = $pdo->prepare("INSERT INTO master_kos (nama_kos, lokasi, tipe_kos, fasilitas, harga_per_bulan, status_kamar) VALUES (?, ?, ?, ?, ?, 'Pending Verifikasi')");
    $stmt->execute([
        $_POST['nama_kos'],
        $_POST['lokasi'],
        $_POST['tipe_kos'],
        $_POST['fasilitas'],
        $_POST['harga']
    ]);

    $pesan = "Properti kos baru berhasil ditambahkan!";
    $page = 'daftar_kos';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit_kos') {
    $stmt = $pdo->prepare("UPDATE master_kos SET nama_kos=?, lokasi=?, tipe_kos=?, fasilitas=?, harga_per_bulan=? WHERE id_kos=?");
    
    $stmt->execute([
        $_POST['nama_kos'],
        $_POST['lokasi'],
        $_POST['tipe_kos'],
        $_POST['fasilitas'],
        $_POST['harga'],
        $_POST['id_kos']
    ]);

    $pesan = "Data berhasil diperbarui!";
    $page = 'daftar_kos';
}

if (isset($_GET['hapus_kos'])) {
    $stmt = $pdo->prepare("DELETE FROM master_kos WHERE id_kos=?");
    $stmt->execute([$_GET['hapus_kos']]);

    $pesan = "Data berhasil dihapus!";
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

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f7f6;
    color:#333;
}

.top-navbar{
    width:100%;
    height:75px;
    background:#fff;
    border-bottom:1px solid #eee;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px;
    position:fixed;
    top:0;
    left:0;
    z-index:100;
}

.logo-area{
    display:flex;
    align-items:center;
    gap:10px;
}

.logo-icon{
    font-size:28px;
    color:#00a65b;
}

.logo-text{
    font-size:22px;
    font-weight:800;
}

.logo-text span{
    color:#00a65b;
    font-size:14px;
}

.top-right-menu{
    display:flex;
    align-items:center;
    gap:20px;
}

.notif-bell{
    position:relative;
    font-size:20px;
}

.notif-badge{
    width:8px;
    height:8px;
    background:red;
    border-radius:50%;
    position:absolute;
    top:-2px;
    right:-2px;
}

.profile-widget{
    display:flex;
    align-items:center;
    gap:10px;
}

.profile-img{
    width:35px;
    height:35px;
    border-radius:50%;
    background:#e6f6ef;
    color:#00a65b;
    display:flex;
    justify-content:center;
    align-items:center;
    font-weight:bold;
}

.main-container{
    display:flex;
    margin-top:75px;
    padding:30px;
    gap:30px;
}

.sidebar-card{
    width:250px;
    background:#fff;
    border-radius:12px;
    padding:20px;
    border:1px solid #eee;
    height:fit-content;
}

.menu-label{
    font-size:11px;
    color:#999;
    margin-bottom:15px;
    text-transform:uppercase;
}

.menu-list{
    display:flex;
    flex-direction:column;
    gap:5px;
}

.menu-link{
    text-decoration:none;
    color:#555;
    padding:12px;
    border-radius:8px;
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:600;
}

.menu-link:hover,
.menu-link.active{
    background:#e6f6ef;
    color:#00a65b;
}

.logout{
    color:red;
}

.content-area{
    flex:1;
    display:flex;
    flex-direction:column;
    gap:25px;
}

.content-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.dash-title{
    font-size:24px;
    font-weight:700;
}

.dash-subtitle{
    color:#777;
    margin-top:5px;
}

.btn-green{
    background:#00a65b;
    color:#fff;
    padding:10px 18px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-size:13px;
    font-weight:600;
}

.btn-green:hover{
    background:#008f4e;
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.stat-card{
    background:#fff;
    border-radius:12px;
    padding:20px;
    border:1px solid #eee;
}

.stat-header{
    display:flex;
    align-items:center;
    gap:10px;
    font-size:13px;
    color:#666;
    margin-bottom:15px;
}

.stat-icon-wrapper{
    width:32px;
    height:32px;
    border-radius:8px;
    display:flex;
    align-items:center;
    justify-content:center;
}

.stat-value{
    font-size:28px;
    font-weight:700;
}

.dashboard-row{
    display:flex;
    gap:25px;
}

.left-column{
    flex:2;
}

.right-column{
    flex:1;
}

.main-card{
    background:#fff;
    border-radius:12px;
    padding:25px;
    border:1px solid #eee;
}

.card-header-flex{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.card-title{
    font-size:18px;
    font-weight:700;
}

.see-all-link{
    text-decoration:none;
    color:#00a65b;
    font-weight:600;
}

.recent-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.recent-item{
    border:1px solid #eee;
    border-radius:10px;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.item-profile{
    display:flex;
    align-items:center;
    gap:12px;
}

.avatar-circle{
    width:40px;
    height:40px;
    border-radius:50%;
    background:#f1f5f9;
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

.item-details b{
    display:block;
    margin-bottom:3px;
}

.item-details span{
    font-size:12px;
    color:#777;
}

.item-price{
    color:#00a65b;
    font-weight:700;
}

.tips-box{
    background:#00a65b;
    color:#fff;
    padding:25px;
    border-radius:12px;
}

.tips-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:15px;
}

.tips-body{
    background:rgba(255,255,255,0.15);
    padding:15px;
    border-radius:8px;
    font-size:13px;
    line-height:1.5;
    margin-bottom:15px;
}

.btn-tips-link{
    display:block;
    background:#fff;
    color:#00a65b;
    text-align:center;
    padding:12px;
    border-radius:8px;
    text-decoration:none;
    font-weight:700;
}

.alert{
    background:#e6f6ef;
    color:#00a65b;
    border:1px solid #00a65b;
    padding:15px;
    border-radius:8px;
    font-weight:600;
}

.badge{
    padding:5px 10px;
    border-radius:5px;
    font-size:11px;
    font-weight:700;
}

.badge-warning{
    background:#fff3e0;
    color:#ff9800;
}

.badge-success{
    background:#e6f6ef;
    color:#00a65b;
}

.badge-info{
    background:#e0f2fe;
    color:#0284c7;
}

.properti-table{
    width:100%;
    border-collapse:collapse;
}

.properti-table th,
.properti-table td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:left;
    font-size:14px;
}

.properti-table th{
    background:#f9fafb;
}

.btn-edit{
    background:#ff9800;
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:12px;
    font-weight:bold;
}

.btn-danger{
    background:#ff3333;
    color:#fff;
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:12px;
    font-weight:bold;
}

.modal{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:999;
}

.modal:target{
    display:flex;
}

.modal-content{
    background:#fff;
    padding:30px;
    border-radius:12px;
    width:100%;
    max-width:500px;
    position:relative;
}

.close-modal{
    position:absolute;
    top:15px;
    right:20px;
    font-size:24px;
    text-decoration:none;
    color:#999;
}

.form-group{
    margin-bottom:15px;
}

.form-group label{
    display:block;
    margin-bottom:5px;
    font-size:12px;
    font-weight:600;
}

.form-group input,
.form-group textarea,
.form-group select{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
}

</style>
</head>

<body>

<header class="top-navbar">

<div class="logo-area">
    <i class="fa-solid fa-house-user logo-icon"></i>
    <div class="logo-text">
        PAPIKOS <span>Pemilik</span>
    </div>
</div>

<div class="top-right-menu">

<div class="notif-bell">
    <i class="fa-solid fa-bell"></i>
    <div class="notif-badge"></div>
</div>

<div class="profile-widget">
    <div class="profile-img">B</div>

    <div>
        <b>Budi Santoso</b><br>
        <small>Pemilik Kos</small>
    </div>
</div>

</div>
</header>

<div class="main-container">

<aside class="sidebar-card">

<p class="menu-label">Menu Penjual</p>

<nav class="menu-list">

<a href="?page=dashboard" class="menu-link <?= $page == 'dashboard' ? 'active' : '' ?>">
    <i class="fa-solid fa-chart-pie"></i>
    Dashboard
</a>

<a href="?page=daftar_kos" class="menu-link <?= $page == 'daftar_kos' ? 'active' : '' ?>">
    <i class="fa-solid fa-house"></i>
    Daftar Properti
</a>

<a href="?page=pesanan" class="menu-link <?= $page == 'pesanan' ? 'active' : '' ?>">
    <i class="fa-solid fa-receipt"></i>
    Pesanan
</a>

<a href="?page=pesan" class="menu-link <?= $page == 'pesan' ? 'active' : '' ?>">
    <i class="fa-solid fa-comment"></i>
    Chat
</a>

<a href="?page=pengaturan" class="menu-link <?= $page == 'pengaturan' ? 'active' : '' ?>">
    <i class="fa-solid fa-gear"></i>
    Pengaturan
</a>

<a href="index.php" class="menu-link logout">
    <i class="fa-solid fa-arrow-right-from-bracket"></i>
    Keluar
</a>

</nav>

</aside>

<main class="content-area">

<?php if (!empty($pesan)): ?>
<div class="alert">
    <?= $pesan ?>
</div>
<?php endif; ?>
