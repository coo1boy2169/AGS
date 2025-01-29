<?php
session_start();
include_once('database.php');

if ($_SESSION['user_type'] != 'student') {
    header('Location: login.php');
    exit;
}

$connection = database_connect();

// Verkrijg beschikbare tijdslots
$query = "SELECT * FROM Tijdsloten WHERE Klanten_id IS NULL";
$result = mysqli_query($connection, $query);
?>

<h2>Maak een nieuwe afspraak</h2>

<form method="POST" action="afspraak_verwerken.php">
    <label for="dag">Datum:</label>
    <input type="date" name="dag" required><br>
    
    <label for="tijdslot">Tijdslot:</label>
    <select name="tijdslot" required>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <option value="<?php echo $row['Id']; ?>"><?php echo $row['Tijdstip']; ?></option>
        <?php endwhile; ?>
    </select><br>

    <label for="papierformaat">Papierformaat:</label>
    <select name="papierformaat" required>
        <option value="1">A5</option>
        <option value="2">A4</option>
        <option value="3">A3</option>
        <option value="4">A2</option>
        <option value="5">A1</option>
    </select><br>

    <label for="aantal_prints">Aantal Prints:</label>
    <input type="number" name="aantal_prints" required><br>

    <button type="submit">Afspraken Maken</button>
</form>
