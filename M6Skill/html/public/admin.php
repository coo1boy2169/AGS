<?php
session_start();

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not logged in as admin
    exit;
}

require_once('db.php'); // Make sure the database connection is included

// Fetch settings from the database
$query = $pdo->query("SELECT * FROM settings LIMIT 1");
$settings = $query->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <p>Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <p><a href="logout.php">Uitloggen</a></p>

    <h2>Instellingen voor Printafspraken</h2>

    <form action="save_settings.php" method="POST">
        <label for="maxAppointments">Maximaal aantal afspraken per kwartier:</label>
        <input type="number" id="maxAppointments" name="maxAppointments" min="1" required value="<?= htmlspecialchars($settings['max_appointments'] ?? 1); ?>"><br>

        <label for="availablePrintTypes">Beschikbare soorten printafspraken:</label>
        <select id="availablePrintTypes" name="availablePrintTypes[]" multiple>
            <?php
            $types = ['A4', 'A3', 'A5'];
            $selectedTypes = explode(',', $settings['available_print_types'] ?? '');
            foreach ($types as $type) {
                $selected = in_array($type, $selectedTypes) ? 'selected' : '';
                echo "<option value='$type' $selected>$type</option>";
            }
            ?>
        </select><br>

        <label for="blockedDays">Blokkeer dagen (bijv. weekend):</label>
        <select id="blockedDays" name="blockedDays[]" multiple>
            <?php
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $blockedDays = explode(',', $settings['blocked_days'] ?? '');
            foreach ($days as $day) {
                $selected = in_array($day, $blockedDays) ? 'selected' : '';
                echo "<option value='$day' $selected>$day</option>";
            }
            ?>
        </select><br>

        <label for="blockedTimeSlots">Blokkeer tijdstippen (bijv. pauzes):</label>
        <input type="text" id="blockedTimeSlots" name="blockedTimeSlots" placeholder="Bijv. 12:00-13:00" value="<?= htmlspecialchars($settings['blocked_time_slots'] ?? ''); ?>"><br>

        <label for="printPrice">Prijs per print (per pagina):</label>
        <input type="number" id="printPrice" name="printPrice" step="0.01" required value="<?= htmlspecialchars($settings['print_price'] ?? 0.50); ?>"><br>

        <button type="submit">Instellingen opslaan</button>
    </form>

    <h2>Overzichten</h2>
    <ul>
        <li><a href="month_overview.php">Maandoverzicht</a></li>
        <li><a href="week_overview.php">Weekoverzicht</a></li>
        <li><a href="day_overview.php">Dagoverzicht</a></li>
    </ul>

</body>
</html>
