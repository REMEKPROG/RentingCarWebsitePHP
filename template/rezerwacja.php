<?php

require '../php/polaczenie.php';
require '../php/rezerwacjaFunctions.php';

$errorMessage = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["clientName"]);
    $surname = trim($_POST["clientSurname"]);
    $mail = trim($_POST["clientMail"]);
    $phoneNumber = trim($_POST["clientPhone"]);
    $selectedCarId = $_POST["client-selected-car"];
    $startDate = $_POST["start-Date"];
    $endDate = $_POST["end-Date"];
        
        $formAccept = false;
        $errorMessage = Validate($name, $surname, $mail, $phoneNumber, $startDate, $endDate);
    
    }

    if ($formAccept) {
        $sqlClientData = "INSERT INTO wypozyczalnia.klienci(imie, nazwisko, email, telefon) VALUES (:imie, :nazwisko, :mail, :telefon)";

        $sqlClientInstruction = $pdo->prepare($sqlClientData);
        $sqlClientInstruction->execute([
                "imie" => $name,
                "nazwisko" => $surname,
                "mail" => $mail,
                "telefon" => $phoneNumber,
        ]);

        $lastClientId = $pdo->lastInsertId();
        $selectedCarId = (int)$selectedCarId;

        $sqlRentData = "INSERT INTO wypozyczalnia.wypozyczenia(id_pojazdu, id_klienta, data_wypozyczenia, data_zwrotu, status)
        VALUES (:idCar, :idClient, :startData, :endDate, 'Wypożyczony')";

        $rentDataInstruction = $pdo->prepare($sqlRentData);
        $rentDataInstruction->execute([
            "idCar" => $selectedCarId,
            "idClient" => $lastClientId,
            "startData" => $startDate,
            "endDate" => $endDate,
        ]);

        $sqlChangeAvaible = "UPDATE wypozyczalnia.pojazdy SET dostepnosc = false WHERE id = :id_pojazdu";

        $changeAvaibleInstruction = $pdo->prepare($sqlChangeAvaible);
        $changeAvaibleInstruction->execute([
            "id_pojazdu" => $selectedCarId,
        ]);

        header("location: dataReservation.php");
    }
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
            <li class="startweb"><a href="index.php"><img src="../img/logo.png" alt="logo wypozyczalni z widocznym samochodem i napisem"></a></li>
            <li><a href="wypozycz.php">Wypożycz</a></li>
            <li><a href="#">Zarezerwuj</a></li>
            <li><a href="zwrot.php">zwróć pojazd</a></li>
        </ul>
    </nav>

    <main>
        <section class="form-reservation-display">
            <section class="form-reservation-container">
                    <form action="rezerwacja.php" method="post">
                        <div class="client-section">
                            <div class="name-surname-labels">
                                <label for="">Imię:</label> <input type="text" name="clientName"> 
                                <label for="">Nazwisko:</label> <input type="text" name="clientSurname">
                            </div>
                            <br>
                            <div class="mail-phone-labels">
                                <label for="">E-mail:</label> <input type="email" name="clientMail">
                                <label for="">Telefon:</label> <input type="tel" name="clientPhone">
                            </div>
                        </div>
                        <div class="car-section">
                            <div class="car-img">
                                <img src="" alt="" id="car-image">
                            </div>
                            <div class="car-properties-form">
                                <label for="">Model samochodu:</label>
                                <select name="client-selected-car" id="selected-car">
                                    <?php foreach($allCars as $car) { ?>
                                    <option value="<?php echo $car["id"] ?>" data-img ="<?php echo $car["zdjecie"]?>"><?php echo $car["marka"] . " " . $car["model"] ?></option>
                                    <?php } ?>
                                </select>
                                <br>
                                <label for="">Data wypożyzenia:</label> <input type="date" name="start-Date"> <br>
                                <label for="">Data zwrotu:</label> <input type="date" name="end-Date">
                            </div>
                        </div>
                        <input type="submit" value="Zarezerwuj" class="reservation-button">
                        <h2><?php echo $errorMessage; ?></h2>
                    </form>
            </section>
        </section>
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
    <script src="../scripts/showCarImg.js"></script>
</body>
</html>