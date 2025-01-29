<?php
session_start();
include_once('database.php');

if ($_SESSION['user_type'] != 'student') {
    header('Location: login.php');
    exit;
}

$klant_id = $_SESSION['user_id'];
$connection = database_connect();
$query = "SELECT * FROM Tijdsloten WHERE Klanten_id = '$klant_id'";
$result = mysqli_query($connection, $query);
?>

<h2>Jouw Afspraken</h2>
<table>
    <tr>
        <th>Datum</th>
        <th>Tijdslot</th>
        <th>Papierformaat</th>
        <th>Aantal Prints</th>
        <th>Status</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['Dag']; ?></td>
            <td><?php echo $row['Tijdstip']; ?></td>
            <td><?php echo $row['Papierformaat']; ?></td>
            <td><?php echo $row['Aantal_prints']; ?></td>
            <td><?php echo $row['Status']; ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<a href="afspraak_maken.php">Maak een nieuwe afspraak</a>
