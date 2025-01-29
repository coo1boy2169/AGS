<?php
// Database configuration
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

// Week calculation
$currentDate = $_GET['week_start'] ?? date('Y-m-d');
$startOfWeek = date('Y-m-d', strtotime('monday this week', strtotime($currentDate)));
$endOfWeek = date('Y-m-d', strtotime('friday this week', strtotime($currentDate)));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afspraak Systeem</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js" defer></script>
    <style>
        /* Basic styling */
        .time-grid { display: flex; justify-content: space-around; margin-top: 20px; }
        .day-column { border: 1px solid #ccc; padding: 10px; width: 18%; }
        .day-column h3 { text-align: center; }
        .time-slot { text-align: center; margin: 5px 0; }
        button { padding: 5px 10px; cursor: pointer; }
        button:disabled { background-color: #ccc; cursor: not-allowed; }
        .modal { display: none; position: fixed; top: 10; left: 10; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); }
        .modal-content { background: #fff; padding: 20px; margin: 10% auto; width: 50%; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
        .close-btn { float: right; cursor: pointer; font-size: 20px; }
    </style>
</head>
<body>
    <h1>Afspraak Systeem</h1>

    <!-- Message for feedback -->
    <div id="message" style="display: none; margin: 10px 0; padding: 10px; border: 1px solid #ccc; background-color: #f8f8f8;"></div>

    <!-- Week navigation -->
    <div id="weekSelection">
        <button id="prevWeek">Vorige Week</button>
        <h2 id="weekHeader">Week van <?= date('d-m-Y', strtotime($startOfWeek)) ?> tot <?= date('d-m-Y', strtotime($endOfWeek)) ?></h2>
        <button id="nextWeek">Volgende Week</button>
    </div>

    <!-- Appointment Grid -->
    <div id="appointmentGrid">
        <?php
        $startTime = strtotime('09:00');
        $endTime = strtotime('18:00');
        $interval = 15 * 60; // 15 minutes
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        echo "<div class='time-grid'>";
        foreach ($daysOfWeek as $day) {
            $dayDate = date('Y-m-d', strtotime($day, strtotime($startOfWeek)));
            echo "<div class='day-column'>";
            echo "<h3>$day (" . date('d-m-Y', strtotime($dayDate)) . ")</h3>";

            foreach (range($startTime, $endTime, $interval) as $time) {
                $formattedTime = date('H:i', $time);

                // Check if appointment exists
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
        echo "</div>";
        ?>
    </div>

    <!-- Modal for making an appointment -->
    <div class="modal" id="appointmentModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">×</span>
            <h3>Bevestig Afspraak</h3>
            <p>Datum: <span id="modalDate"></span></p>
            <p>Tijd: <span id="modalTime"></span></p>
            <label for="modalPaper">Papierformaat:</label>
            <select id="modalPaper">
                <option value="A4">A4</option>
                <option value="A3">A3</option>
                <option value="A5">A5</option>
            </select>
            <br><br>
            <label for="modalExtraInfo">Extra Info:</label>
            <textarea id="modalExtraInfo" rows="4" style="width: 100%;"></textarea>
            <br><br>
            <button id="confirmAppointment">Bevestigen</button>
            <button onclick="closeModal()">Annuleren</button>
        </div>
    </div>

    <script>
        let currentWeekStart = new Date('<?= $startOfWeek ?>');

        // Update week header dynamically
        function updateWeekHeader() {
            const weekStart = new Date(currentWeekStart);
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 4);
            document.getElementById('weekHeader').innerText =
                `Week van ${weekStart.toLocaleDateString()} tot ${weekEnd.toLocaleDateString()}`;
        }

        // Fetch appointments
        function fetchAppointments() {
            const formattedDate = currentWeekStart.toISOString().split('T')[0];
            window.location.href = `index.php?week_start=${formattedDate}`;
        }

        // Navigation buttons
        document.getElementById('prevWeek').addEventListener('click', () => {
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
            fetchAppointments();
        });

        document.getElementById('nextWeek').addEventListener('click', () => {
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            fetchAppointments();
        });

        // Modal functions
        function openModal(date, time) {
            document.getElementById('modalDate').innerText = date;
            document.getElementById('modalTime').innerText = time;
            document.getElementById('appointmentModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('appointmentModal').style.display = 'none';
        }

        // Confirm appointment
        document.getElementById('confirmAppointment').addEventListener('click', () => {
            const date = document.getElementById('modalDate').innerText;
            const time = document.getElementById('modalTime').innerText;
            const paperFormat = document.getElementById('modalPaper').value;
            const extraInfo = document.getElementById('modalExtraInfo').value;

            // Simulate saving the appointment (you can make an AJAX call here)
            alert(`Afspraak succesvol opgeslagen!\nDatum: ${date}\nTijd: ${time}\nPapierformaat: ${paperFormat}\nExtra Info: ${extraInfo}`);
            closeModal();
        });
    </script>
</body>
</html>
