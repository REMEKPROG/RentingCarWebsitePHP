<?php

require '../php/polaczenie.php';

$sqlFreshestCars = "SELECT pojazdy.zdjecie, pojazdy.marka, pojazdy.model FROM wypozyczalnia.pojazdy
ORDER BY pojazdy.id DESC
LIMIT 2";

$sqlFreshCarsInstruction = $pdo->query($sqlFreshestCars);
$freshCars = $sqlFreshCarsInstruction->fetchAll();




?>




<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wypozyczalnia</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/fc8a4d7559.js" crossorigin="anonymous"></script>
</head>
<body>
    
    <nav>
        <ul>
            <li class="startweb"><a href="#"><img src="../img/logo.png" alt="logo wypozyczalni z widocznym samochodem i napisem"></a></li>
            <li><a href="wypozycz.php">Wypożycz</a></li>
            <li><a href="rezerwacja.php">Zarezerwuj</a></li>
            <li><a href="zwrot.php">zwróć pojazd</a></li>
        </ul>
    </nav>

    <header>
        <div class="header-hero-image">
            <div class="header-inside-text">
                <h1>Wypożyczalnia</h1>
                <p>wypożycz swój samochód marzeń!</p>
            </div>
            <div class="header-hero-image-background"></div>
        </div>
    </header>

    <main class="index-main-section">
        <h1>najświeższe oferty</h1>
        <div class="cars-list-display">
            <?php foreach($freshCars as $car) { ?>
            <div class="car-top-container">
                <img src="<?php echo $car["zdjecie"] ?>" alt="zdjęcie samochodu - druga oferta">
                <h3><?php echo $car["marka"] . " " . $car["model"] ?></h3>
            </div>
            <?php  } ?>
        </div>
    </main>

    
    
    <footer>
        <div class="text-footer">
            <h2>2025 &copy; WypozyczalniaRG</h2>
            <div class="footer-links-icons">
            <a href=""><i class="fa-brands fa-facebook"></i></a>
            <a href=""><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </footer>


    <script src="../scripts/webLoad.js"></script>
</body>
</html>