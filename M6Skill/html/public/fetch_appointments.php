<?php
$host = "mariadb";
$db = "phplogin";
$user = "m6skill";
$password = "tek2005";
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Get week start from query or default to current week
$startOfWeek = isset($_GET['week_start']) ? $_GET['week_start'] : date('Y-m-d', strtotime('monday this week'));
$endOfWeek = date('Y-m-d', strtotime($startOfWeek . ' +4 days'));

$startTime = strtotime('09:00');
$endTime = strtotime('18:00');
$interval = 15 * 60; // 15 minutes
$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

foreach ($daysOfWeek as $day) {
    $dayDate = date('Y-m-d', strtotime($startOfWeek . " + " . array_search($day, $daysOfWeek) . " days"));
    echo "<div class='day-column'>";
    echo "<h3>$day</h3>";

    foreach (range($startTime, $endTime, $interval) as $time) {
        $formattedTime = date('H:i', $time);

        // Check if the slot is taken
        $query = $pdo->prepare("SELECT COUNT(*) FROM afspraken WHERE datum = :date AND tijd = :time");
        $query->execute([':date' => $dayDate, ':time' => $formattedTime]);
        $count = $query->fetchColumn();

        echo "<div class='time-slot'>";
        echo "<h4>$formattedTime</h4>";
        if ($count > 0) {
            echo "<button disabled>Bezet</button>";
        } else {
            echo "<button onclick=\"openModal('$dayDate', '$formattedTime')\">Maak afspraak</button>";
        }
        echo "</div>";
    }

    echo "</div>";
}
?>
