<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fıstık Satış</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php
include 'db.php';
include 'navbar.php';
renderNavbar();

$sql = "CALL urun_listele_aktif()";
$result = $conn->query($sql);

echo '<div class="container mt-4"><div class="row">';
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4">
                <div class="card mb-4">
                    <img src="' . $row["resim"] . '" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">' . $row["ad"] . '</h5>
                        <p class="card-text">' . $row["detay"] . '</p>
                        <p class="card-text"><strong>' . $row["fiyat"] . ' TL / ' . $row["birim"] . '</strong></p>
                        <form method="POST" action="sepet.php">
                            <input type="hidden" name="urun_id" value="' . $row["urun_id"] . '">
                            <input type="number" name="miktar" value="1" min="0.1" step="0.1" class="form-control mb-2">
                            <button type="submit" class="btn btn-success">Sepete Ekle</button>
                        </form>
                    </div>
                </div>
              </div>';
    }
} else {
    echo '<p class="text-center">Hiç ürün bulunamadı.</p>';
}
echo '</div></div>';
$conn->close();
?>

</body>
</html>