<?php

require 'polaczenie.php';

$sqlCars = "SELECT marka, model, id, zdjecie FROM wypozyczalnia.pojazdy WHERE dostepnosc = 1";

$sqlCarsInstruction = $pdo->query($sqlCars);
$allCars = $sqlCarsInstruction->fetchAll();

function Validate($name, $surname, $mail, $phoneNumber, $startDate, $endDate) {
    global $formAccept;
    $currentDate = date("Y-m-d", time());
    if(empty($name) || empty($surname) || empty($mail) || (strlen($phoneNumber) != 9) || (empty($startDate) || $startDate > $endDate || $startDate < $currentDate) || (empty($endDate) || $endDate < $startDate)) {
        $errorMessage = "Prosze wypełnić formularz dobrze!";
        return $errorMessage;
    } else {
        $errorMessage = "";
        $formAccept = true;
        return $errorMessage;
    }
}

?>