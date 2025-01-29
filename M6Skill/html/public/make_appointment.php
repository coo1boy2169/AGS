<?php
session_start();
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tijdslot_id = $_POST['tijdslot_id'];
    $datum = $_POST['datum'];
    $tijdstip = $_POST['tijdstip'];
    $papierformaat = $_POST['papierformaat']; // Haal het papierformaat op uit het formulier
    $klanten_id = $_SESSION['klanten_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Afspraken (Tijdslot_id, Datum, Tijdstip, Klanten_id, Papierformaat) 
                               VALUES (:tijdslot_id, :datum, :tijdstip, :klanten_id, :papierformaat)");
        $stmt->execute([
            ':tijdslot_id' => $tijdslot_id,
            ':datum' => $datum,
            ':tijdstip' => $tijdstip,
            ':klanten_id' => $klanten_id,
            ':papierformaat' => $papierformaat
        ]);

        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        die("Fout bij het maken van de afspraak: " . $e->getMessage());
    }
}
?>
