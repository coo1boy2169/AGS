<?php
session_start();
include_once('database.php');

if ($_SESSION['user_type'] != 'student') {
    header('Location: login.php');
    exit;
}

$klant_id = $_SESSION['user_id'];
$dag = $_POST['dag'];
$tijdslot_id = $_POST['tijdslot'];
$papierformaat_id = $_POST['papierformaat'];
$aantal_prints = $_POST['aantal_prints'];
$status_id = 1; // Aangenomen status (bijvoorbeeld 'Bevestigd')

$connection = database_connect();

$query = "INSERT INTO Tijdsloten (Klanten_id, Dag, Tijdslot_id, Papierformaten_id, Aantal_prints, Status_id) 
          VALUES ('$klant_id', '$dag', '$tijdslot_id', '$papierformaat_id', '$aantal_prints', '$status_id')";

if (mysqli_query($connection, $query)) {
    echo "Afspraak succesvol gemaakt!";
} else {
    echo "Er is iets misgegaan!";
}
?>
