<?php
session_start();
include_once('database.php');

if ($_SESSION['user_type'] != 'admin') {
    header('Location: login.php');
    exit;
}

$connection = database_connect();

// Verkrijg alle tijdslots
$query = "SELECT * FROM Tijdsloten";
$result = mysqli_query($connection, $query);
?>

<h2>Beheer Tijdslots</h2>

<table>
    <tr>
        <th>Datum</th>
        <th>Tijdslot</th>
        <th>Status</th>
        <th>Acties</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['Dag']; ?></td>
            <td><?php echo $row['Tijdstip']; ?></td>
            <td><?php echo $row['Status']; ?></td>
            <td>
                <a href="blokkeer_tijdslot.php?id=<?php echo $row['Id']; ?>">Blokkeer</a> |
                <a href="verander_status.php?id=<?php echo $row['Id']; ?>">Verander status</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
