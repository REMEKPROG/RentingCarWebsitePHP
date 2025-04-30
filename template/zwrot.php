<?php

require '../php/polaczenie.php';

function validateExistsPhone($phoneNumber, $pdo) {
    $sqlFindPhoneNumber = "SELECT * FROM wypozyczalnia.klienci
    WHERE EXISTS(select klienci.telefon FROM wypozyczalnia.klienci where klienci.telefon = :phoneNumber)";

    $sqlFindPhoneNumberInstruction = $pdo->prepare($sqlFindPhoneNumber);
    $sqlFindPhoneNumberInstruction->execute(["phoneNumber" => $phoneNumber]);

    $sqlPhoneBool = $sqlFindPhoneNumberInstruction->fetchAll();


    if (count($sqlPhoneBool) == 0) {
        return true;
    } else {
        $flag = true;
        return false;
    };
}


$missingPhoneNumberMessage = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["telKey"])){
        if(validateExistsPhone(trim($_POST["telKey"]), $pdo)) {
            $missingPhoneNumberMessage = "ten numer telefonu nie istnieje, wprowadź poprawne dane!";
        } else {
            $missingPhoneNumberMessage = "";
            $key = trim($_POST["telKey"]);
            $flag = true;
        }
    }
    if(isset($_POST["rentId"])) {

        $rentId = $_POST["rentId"];
        $vechicleId = $_POST["vechicleId"];

        $currentDate = date("Y-m-d");

        $sqlUpdateRentTable = "UPDATE wypozyczalnia.wypozyczenia SET status = 'zwrócony', rzeczywista_data_zwrotu = :currentDate
        WHERE wypozyczenia.id = :rentId";
        
        $sqlUpdateRentTableInstruction = $pdo->prepare($sqlUpdateRentTable);
        $sqlUpdateRentTableInstruction->execute([
            "currentDate" => $currentDate,
            "rentId" => $rentId,
        ]);

        $sqlUpdateVechicleTable = "UPDATE wypozyczalnia.pojazdy SET dostepnosc = true WHERE pojazdy.id = :vechicleId";

        $sqlUpdateVechicleTableInstruction = $pdo->prepare($sqlUpdateVechicleTable);
        $sqlUpdateVechicleTableInstruction->execute(["vechicleId" => $vechicleId]);
    }
}

if ($flag) {
    $sqlGetClientData = "SELECT
    wypozyczenia.id,
    klienci.imie, 
    klienci.nazwisko, 
    klienci.email,
    wypozyczenia.id_pojazdu, 
    pojazdy.marka, 
    pojazdy.model, 
    wypozyczenia.data_wypozyczenia, 
    wypozyczenia.data_zwrotu
FROM wypozyczalnia.wypozyczenia
INNER JOIN wypozyczalnia.klienci ON wypozyczenia.id_klienta = klienci.id
INNER JOIN wypozyczalnia.pojazdy ON wypozyczenia.id_pojazdu = pojazdy.id
WHERE klienci.telefon = :phoneNumber AND wypozyczenia.status = 'wypożyczony'";

    $sqlClientDataInstruction = $pdo->prepare($sqlGetClientData);
    $sqlClientDataInstruction->execute(["phoneNumber" => $key]);

    $sqlClientData = $sqlClientDataInstruction->fetchAll();
} else {
    $sqlClientData = [];
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
            <li><a href="rezerwacja.php">Zarezerwuj</a></li>
            <li><a href="#">zwróć pojazd</a></li>
        </ul>
    </nav>

   <main class="zwrot-main">
        <section class="rents-Container-display">
            <div class="client-rent-form">
                <form action="zwrot.php" method="post">
                    <label for="">Numer telefonu:</label>
                    <input type="tel" name="telKey"> <br>
                    <input type="submit" class="submit-button" value="wyświetl rezerwację">
                </form>
                <h3><?php echo $missingPhoneNumberMessage ?></h3>
            </div>
            <div class="rents-description-container">
                <?php foreach($sqlClientData as $clientData) { ?>
                <div class="client-rent-data" value="<?php echo $clientData["id"] ?>">
                    <b><p>Imie:</b><?php echo $clientData["imie"] ?></p>
                    <b><p>Nazwisko:</b><?php echo $clientData["nazwisko"] ?></p>
                    <b><p>Email:</b><?php echo $clientData["email"] ?></p>
                    <b><p>Pojazd:</b><?php echo $clientData["marka"] . " " . $clientData["model"] ?></p>
                    <b><p>Data wypożyczenia:</b><?php echo $clientData["data_wypozyczenia"] ?></p>
                    <b><p>Data zwrócenia:</b><?php echo $clientData["data_zwrotu"] ?></p>
                    <form action="zwrot.php" method="post">
                        <input type="hidden" name="rentId" value="<?php echo $clientData["id"] ?>">
                        <input type="hidden" name="telKey" value="<?php echo $key ?>">
                        <input type="hidden" name="vechicleId" value="<?php echo $clientData["id_pojazdu"] ?>">
                        <input type="submit" class="submit-button" value="Zwróć pojazd">
                    </form>
                </div>
                <?php } ?>
            </div>
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
</body>
</html>