<?php 

$type = 'mysql';
$server = 'localhost';
$database = 'wypozyczalnia';
$port = '3306';
$codingUTF = 'utf8mb4';


$dsn = "$type:host = $server;databaseName = $database;port = $port;charset = $codingUTF";

?>


<?php

$user = "root";
$password = "";
$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

$pdo = new PDO($dsn, $user, $password, $options);


?>