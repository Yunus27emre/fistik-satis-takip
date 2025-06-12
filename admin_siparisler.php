<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT s.*, m.ad AS musteri_ad, m.soyad AS musteri_soyad
        FROM siparis s
        JOIN musteri m ON s.musteri_id = m.musteri_id
        ORDER BY s.siparis_id DESC";
$result = $conn->query($sql);

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Tüm Siparişler</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Tüm Siparişler</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Müşteri</th>
                <th>Tarih</th>
                <th>Toplam Tutar</th>
                <th>Ödeme</th>
                <th>Teslim</th>
                <th>Detay</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['siparis_id']; ?></td>
                <td><?php echo $row['musteri_ad'] . ' ' . $row['musteri_soyad']; ?></td>
                <td><?php echo $row['siparis_tarihi']; ?></td>
                <td><?php echo number_format($row['toplam_tutar'], 2); ?> TL</td>
                <td><?php echo $row['odeme_durumu']; ?></td>
                <td><?php echo $row['teslim_durumu']; ?></td>
                <td>
                  <a href="siparis_detay.php?id=<?php echo $row['siparis_id']; ?>" class="btn btn-sm btn-info">Detay</a>
                  <a href="admin_siparis_guncelle.php?id=<?php echo $row['siparis_id']; ?>" class="btn btn-sm btn-warning">Güncelle</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Admin Paneline Dön</a>
</div>
</body>
</html>
