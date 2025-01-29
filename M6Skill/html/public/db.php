<?php
$host = "mariadb"; // Database host (bijvoorbeeld de naam van je container als je Docker gebruikt)
$dbname = "phplogin"; // Naam van je database
$username = "m6skill"; // Gebruikersnaam van de database
$password = "tek2005"; // Wachtwoord van de database

try {
    // Maak verbinding met de database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Fout bij verbinding: " . $e->getMessage());
}
