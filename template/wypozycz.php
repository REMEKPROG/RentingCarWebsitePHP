<?php
require '../php/polaczenie.php';
require '../php/wypozyczenie.php';

function checkMinDate($pdo) {
    $checkMinDateSQL = "SELECT MIN(rok_produkcji) FROM wypozyczalnia.pojazdy";
    $minDateInstruction = $pdo->query($checkMinDateSQL);
    return $date = $minDateInstruction->fetch();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $carCategory = $_POST["car-category"];
    $brands = $_POST["brands"] ?? [];
    $year = $_POST["year-of-car"] ?? "";
}

$uknownDateMessage = "";

if(!($carCategory == false)) {

    if ($year != "") {

        $checkMinDateSQL = "SELECT MIN(rok_produkcji) AS rok FROM wypozyczalnia.pojazdy";
        $minDateInstruction = $pdo->query($checkMinDateSQL);
        $minDate = $minDateInstruction->fetch();

        if ($year < $minDate["rok"]) {
            $uknownDateMessage = "Podaj poźniejszą datę";

            $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy";
    
            $pojazdyInstruction = $pdo->query($sqlCars);
            $pojazdy = $pojazdyInstruction->fetchAll();
        } else {
        $year = (int)$year;

        $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy WHERE rok_produkcji >= :year 
        ORDER BY rok_produkcji ASC";

        $pojazdyInstruction = $pdo->prepare($sqlCars);
        $pojazdyInstruction->execute(["year" => $year]);
        
        $pojazdy = $pojazdyInstruction->fetchAll();
        }


    } else if (count($brands) > 0) {


        $brandsInSQL = implode(",", array_fill(0, count($brands), "?"));
        
        if ($carCategory != "All") {
        
        $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy WHERE typ = ? AND marka IN ($brandsInSQL)";

        $pojazdyInstruction = $pdo->prepare($sqlCars);
        $variablesInSQLStatement = array_merge([$carCategory], $brands);

        $pojazdyInstruction->execute($variablesInSQLStatement);
        $pojazdy = $pojazdyInstruction->fetchAll();
        } else {
            $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy WHERE marka IN ($brandsInSQL)";

            $pojazdyInstruction = $pdo->prepare($sqlCars);
    
            $pojazdyInstruction->execute($brands);
            $pojazdy = $pojazdyInstruction->fetchAll();
        }



    } else if($carCategory == "All") {


            $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy";
    
            $pojazdyInstruction = $pdo->query($sqlCars);
            $pojazdy = $pojazdyInstruction->fetchAll();
        }else {

        $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy WHERE typ = ?";

        $pojazdyInstruction = $pdo->prepare($sqlCars);
        $pojazdyInstruction->execute([$carCategory]);

        $pojazdy = $pojazdyInstruction->fetchAll();
        }



    } else {
    $sqlCars = "SELECT * FROM wypozyczalnia.pojazdy";
    
    $pojazdyInstruction = $pdo->query($sqlCars);
    $pojazdy = $pojazdyInstruction->fetchAll();
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
            <li><a href="#">Wypożycz</a></li>
            <li><a href="rezerwacja.php">Zarezerwuj</a></li>
            <li><a href="zwrot.php">zwróć pojazd</a></li>
        </ul>
    </nav>

    <main>
        <div class="display-section">
            <section class="form-choice-section">
                <form action="wypozycz.php" method="post">
                    <div class="form-category-chooser">
                    <label for="car-category">Wybierz kategorie Samochodu</label>
                    <select name="car-category" id="car-category">
                        <option value="All">Wszystkie</option>
                        <option value="osobowy">Osobowe</option>
                        <option value="dostawczy">Dostawcze</option>
                        <option value="skuter">Skutery</option>
                    </select>
                    </div>
                     <br>
                    <div class="form-requirements">
                        <div class="brands-form-container">
                            <label class="brand-label" for="<?php $i . "m" ?>">marka:</label>
                            <?php
                            $i = 0;
                            foreach($allBrands as $brand) { $i++ ?>
                            <br>
                            <input type="checkbox" id="<?php echo $i ?>" name="brands[]" value = "<?php echo $brand["marka"] ?>"> <label for="<?php echo $i ?>"><?php echo $brand["marka"];?></label>
                            <?php } ?>
                        </div>
                        <div class="years-price-form-container">
                            <label class="year-label" for="">Rocznik(Od tego rocznika w górę)</label>
                            <input type="number" name="year-of-car">
                            <h3><?php echo $uknownDateMessage ?></h3>
                        </div>    
                    </div>
                    <input type="submit" value="Wyszukaj" class="submit-button">
                </form>
            </section>
            <section class="center-section">
                <?php foreach($pojazdy as $pojazd) { ?>
                <div class="car-offers-container">
                    <img src="<?php echo $pojazd["zdjecie"] ?>" alt="zdjecie samochodu">
                    <div class="car-offers-text">
                        <h3><?php echo $pojazd["marka"] . " ". $pojazd["model"] ?></h3>
                        <p>rok produkcji: <b><?php echo $pojazd["rok_produkcji"] ?></b></p>
                        <p>Cena za dzień: <b><?php echo $pojazd["cena_za_dzien"] ?>zł</b></p>
                        <p>dostępność: <b> 
                            <?php if ($pojazd["dostepnosc"] == 1) {
                                echo "pojazd jest dostępny";
                            } else {
                                echo "pojazd niedostępny";
                            } ?>
                            </b>
                        </p>
                        <button class="description-button">Opis</button>
                        <div class="description-cars">
                            <p><?php echo $pojazd["opis"] ?></p>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </section>
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
    <script src="../scripts/showDescriptions.js"></script>
</body>
</html>