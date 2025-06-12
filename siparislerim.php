<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'musteri') {
    header("Location: login.php");
    exit;
}

$musteri_id = $_SESSION['musteri_id'];
$sql = "SELECT * FROM siparis WHERE musteri_id = ? ORDER BY siparis_id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $musteri_id);
$stmt->execute();
$result = $stmt->get_result();

include 'navbar.php';
renderNavbar();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Siparişlerim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Siparişlerim</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tarih</th>
                <th>Toplam Tutar</th>
                <th>Ödeme Durumu</th>
                <th>Teslim Durumu</th>
                <th>Detay</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['siparis_id']; ?></td>
                <td><?php echo $row['siparis_tarihi']; ?></td>
                <td><?php echo number_format($row['toplam_tutar'], 2); ?> TL</td>
                <td><?php echo $row['odeme_durumu']; ?></td>
                <td><?php echo $row['teslim_durumu']; ?></td>
                <td><a href="siparis_detay.php?id=<?php echo $row['siparis_id']; ?>" class="btn btn-sm btn-info">Detay</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
