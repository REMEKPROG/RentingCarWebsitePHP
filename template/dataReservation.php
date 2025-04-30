<?php

require '../php/polaczenie.php';


    $sqlDataReservation = "SELECT
    klienci.imie, 
    klienci.nazwisko, 
    klienci.email, 
    klienci.telefon, 
    pojazdy.marka, 
    pojazdy.model, 
    wypozyczenia.data_wypozyczenia, 
    wypozyczenia.data_zwrotu
FROM wypozyczalnia.wypozyczenia
INNER JOIN wypozyczalnia.klienci ON wypozyczenia.id_klienta = klienci.id
INNER JOIN wypozyczalnia.pojazdy ON wypozyczenia.id_pojazdu = pojazdy.id
WHERE wypozyczenia.id = (
	SELECT MAX(id) FROM wypozyczalnia.wypozyczenia
);";

    $sqlDataReservationInstruction = $pdo->query($sqlDataReservation);
    $data = $sqlDataReservationInstruction->fetch();


    header("Refresh:10; url=index.php");


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
    



            <main class="reservation-main">
                <section class="data-client-container">
                    <h1>Zarezerwowano pomyślnie!</h1>
                    <h2>Dane rezerwacji:</h2>
                    <p>Imię: <b><?php echo $data["imie"]; ?></b></p>
                    <p>Nazwisko: <b><?php echo $data["nazwisko"]; ?></b></p>
                    <p>E-mail: <b><?php echo $data["email"]; ?></b></p>
                    <p>Numer telefon: <b><?php echo $data["telefon"]; ?></b></p>
                    <p>Samochód: <b><?php echo $data["marka"] . " " . $data["model"]; ?></b></p>
                    <p>Data wypożyczenia: <b><?php echo $data["data_wypozyczenia"]; ?></b></p>
                    <p>Data zwrotu: <b><?php echo $data["data_zwrotu"]; ?></b></p>
                </section>
                <section class="counting-switch">
                    <h2>Zostaniesz przeniesiony na strone główną za 10 sekund</h2>
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