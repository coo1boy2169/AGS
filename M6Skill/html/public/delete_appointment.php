<?php
session_start();
require_once('db.php');

// Controleer of de gebruiker ingelogd is en admin-rechten heeft
// Zorg ervoor dat de ID van de afspraak aanwezig is
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        // Verwijder de afspraak uit de database
        $stmt = $pdo->prepare("DELETE FROM afspraken WHERE id = :id");
        $stmt->execute([':id' => $id]);

        // Redirect terug naar het maandoverzicht
        header('Location: month_overview.php');
        exit();
    } catch (PDOException $e) {
        die("Fout bij het verwijderen van de afspraak: " . $e->getMessage());
    }
} else {
    // Als de ID niet aanwezig is, ga dan terug naar het overzicht
    header('Location: month_overview.php');
    exit();
}
?>
