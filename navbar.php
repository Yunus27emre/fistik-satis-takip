<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
function renderNavbar() {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Fıstık Dükkanı</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">';

    if (isset($_SESSION["kullanici_turu"]) && $_SESSION["kullanici_turu"] == "musteri") {
        echo '<li class="nav-item"><a class="nav-link" href="sepet.php">Sepetim</a></li>
              <li class="nav-item"><a class="nav-link" href="siparislerim.php">Siparişlerim</a></li>
              <li class="nav-item"><a class="nav-link" href="profil.php">Profilim</a></li>
              <li class="nav-item"><a class="nav-link" href="logout.php">Çıkış</a></li>';
    } elseif (isset($_SESSION["kullanici_turu"]) && $_SESSION["kullanici_turu"] == "admin") {
        echo '<li class="nav-item"><a class="nav-link" href="dashboard.php">Admin Paneli</a></li>
              <li class="nav-item"><a class="nav-link" href="admin_siparisler.php">Siparişler</a></li>
              <li class="nav-item"><a class="nav-link" href="profil.php">Profilim</a></li>
              <li class="nav-item"><a class="nav-link" href="logout.php">Çıkış</a></li>';
    } else {
        echo '<li class="nav-item"><a class="nav-link" href="login.php">Giriş Yap</a></li>
              <li class="nav-item"><a class="nav-link" href="register.php">Kayıt Ol</a></li>';
    }

    echo '</ul></div></div></nav>';
}
?>
