<?php
include 'db.php';
session_start();

if (!isset($_SESSION['kullanici_turu']) || $_SESSION['kullanici_turu'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: urun_listele.php");
    exit;
}

$urun_id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM urun WHERE urun_id = ?");
$stmt->bind_param("i", $urun_id);

if ($stmt->execute()) {
    header("Location: urun_listele.php");
    exit;
} else {
    echo "Hata oluştu: " . $conn->error;
}
?>
