<?php
session_start();
require_once('db.php');



// Zorg ervoor dat de POST-gegevens aanwezig zijn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Valideer de status
    $allowedStatuses = ['pending', 'approved', 'rejected'];
    if (!in_array($status, $allowedStatuses)) {
        die('Ongeldige statuswaarde.');
    }

    try {
        // Update de status in de afspraken tabel
        $stmt = $pdo->prepare("UPDATE afspraken SET status = :status WHERE id = :id");
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);

        // Redirect terug naar maandoverzicht
        header('Location: month_overview.php');
        exit();
    } catch (PDOException $e) {
        die("Fout bij het bijwerken van de status: " . $e->getMessage());
    }
} else {
    // Als de gegevens niet correct zijn, ga dan terug naar het overzicht
    header('Location: month_overview.php');
    exit();
}
?>
