<?php
session_start();
require_once('db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not logged in as admin
    exit;
}


// Haal de geselecteerde week op (of gebruik de huidige week)
$week = isset($_GET['week']) ? $_GET['week'] : date('W');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Haal het begin en einde van de week op
$startDate = date('Y-m-d', strtotime("{$year}-W{$week}-1"));
$endDate = date('Y-m-d', strtotime("{$year}-W{$week}-7"));

// Haal afspraken op voor de geselecteerde week
try {
    $stmt = $pdo->prepare("SELECT * FROM afspraken WHERE datum BETWEEN :startDate AND :endDate");
    $stmt->execute([':startDate' => $startDate, ':endDate' => $endDate]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fout bij ophalen van gegevens: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Weekoverzicht</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Weekoverzicht - Week <?= htmlspecialchars($week) ?> van <?= htmlspecialchars($year) ?></h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Klant ID</th>
                <th>Datum</th>
                <th>Tijd</th>
                <th>Status</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?= htmlspecialchars($appointment['id']) ?></td>
                    <td><?= htmlspecialchars($appointment['klant_id'] ?? 'Geen klant-ID') ?></td>
                    <td><?= htmlspecialchars($appointment['datum']) ?></td>
                    <td><?= htmlspecialchars($appointment['tijd']) ?></td>
                    <td><?= htmlspecialchars($appointment['status']) ?></td>
                    <td>
                        <!-- Formulier voor status aanpassen -->
                        <form action="update_state.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($appointment['id']) ?>">
                            <select name="status">
                                <option value="pending" <?= $appointment['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $appointment['status'] === 'approved' ? 'selected' : '' ?>>Goedgekeurd</option>
                                <option value="rejected" <?= $appointment['status'] === 'rejected' ? 'selected' : '' ?>>Geweigerd</option>
                            </select>
                            <button type="submit">Opslaan</button>
                        </form>

                        <!-- Formulier voor afspraak verwijderen -->
                        <form action="delete_appointment.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($appointment['id']) ?>">
                            <button type="submit" style="background-color: #f44336; color: white; border: none; padding: 6px 12px; cursor: pointer;">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
