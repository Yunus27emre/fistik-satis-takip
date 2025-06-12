<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM urun";
$result = $conn->query($sql);

include 'navbar.php';
renderNavbar();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Listesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Ürün Listesi</h3>
    <a href="urun_ekle.php" class="btn btn-success mb-3">Yeni Ürün Ekle</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Ad</th>
                <th>Detay</th>
                <th>Fiyat</th>
                <th>Birim</th>
                <th>Stok</th>
                <th>Aktif Mi?</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['urun_id']; ?></td>
                    <td><?php echo $row['ad']; ?></td>
                    <td><?php echo $row['detay']; ?></td>
                    <td><?php echo $row['fiyat']; ?> TL</td>
                    <td><?php echo $row['birim']; ?></td>
                    <td><?php echo $row['stok']; ?></td>
                    <td><?php echo ($row['durum'] === 'aktif') ? 'Evet' : 'Hayır'; ?></td>
                    <td>
                        <a href="urun_guncelle.php?id=<?php echo $row['urun_id']; ?>" class="btn btn-warning btn-sm">Güncelle</a>
                        <a href="urun_sil.php?id=<?php echo $row['urun_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Silmek istediğine emin misin?');">Sil</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a href="dashboard.php" class="btn btn-secondary">Admin Paneline Dön</a>
</div>
</body>
</html>
