<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'musteri') {
    header("Location: login.php");
    exit;
}

$musteri_id = $_SESSION['musteri_id'];

$sepet_id = null;
$res = $conn->query("SELECT sepet_id FROM sepet WHERE musteri_id = $musteri_id");
if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $sepet_id = $row['sepet_id'];
} else {
    $conn->query("INSERT INTO sepet (musteri_id) VALUES ($musteri_id)");
    $sepet_id = $conn->insert_id;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['urun_id'], $_POST['miktar'])) {
    $urun_id = intval($_POST['urun_id']);
    $miktar = floatval($_POST['miktar']);
    $conn->query("CALL sepete_urun_ekle($sepet_id, $urun_id, $miktar)");
    header("Location: sepet.php");
    exit;
}

if (isset($_GET['sil'])) {
    $urun_id = intval($_GET['sil']);
    $conn->query("DELETE FROM sepet_detay WHERE sepet_id = $sepet_id AND urun_id = $urun_id");
    header("Location: sepet.php");
    exit;
}

$sql = "SELECT sd.urun_id, u.ad, u.fiyat, sd.miktar_kg, (u.fiyat * sd.miktar_kg) as toplam
        FROM sepet_detay sd JOIN urun u ON sd.urun_id = u.urun_id
        WHERE sd.sepet_id = $sepet_id";
$sonuc = $conn->query($sql);

include 'navbar.php';
renderNavbar();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Sepetim</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3>Sepetim</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Ürün</th>
        <th>Fiyat</th>
        <th>Miktar (kg)</th>
        <th>Toplam</th>
        <th>İşlem</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $genel_toplam = 0;
      if ($sonuc->num_rows > 0) {
        while($row = $sonuc->fetch_assoc()) {
          echo '<tr>
                  <td>' . $row['ad'] . '</td>
                  <td>' . $row['fiyat'] . ' TL</td>
                  <td>' . $row['miktar_kg'] . '</td>
                  <td>' . $row['toplam'] . ' TL</td>
                  <td><a href="sepet.php?sil=' . $row['urun_id'] . '" class="btn btn-danger btn-sm">Kaldır</a></td>
                </tr>';
          $genel_toplam += $row['toplam'];
        }
      } else {
        echo '<tr><td colspan="5" class="text-center">Sepetiniz boş</td></tr>';
      }
      ?>
    </tbody>
  </table>
  <div class="text-end">
    <h5>Genel Toplam: <?php echo number_format($genel_toplam, 2); ?> TL</h5>
  </div>
  <?php if ($genel_toplam > 0): ?>
    <form action="siparis_ver.php" method="POST">
      <input type="hidden" name="sepet_id" value="<?php echo $sepet_id; ?>">
      <button type="submit" class="btn btn-success">Siparişi Onayla</button>
    </form>
  <?php endif; ?>
  <a href="index.php" class="btn btn-secondary mt-3">Ana Sayfaya Dön</a>
</div>
</body>
</html>
