    <?php 
    require 'polaczenie.php';


    $sqlBrands = "SELECT DISTINCT marka FROM wypozyczalnia.pojazdy";

    $brandsInstruction = $pdo->query($sqlBrands);
    $allBrands = $brandsInstruction->FetchAll();


    ?>