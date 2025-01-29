<?php
// Zet de juiste headers voor JSON-uitvoer
header('Content-Type: application/json');

// Databaseverbinding
$host = "mariadb"; // Database host
$dbname = "phplogin"; // Naam van de database
$username = "m6skill"; // Gebruikersnaam
$password = "tek2005"; // Wachtwoord

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Fout bij verbinding: " . $e->getMessage()]));
}

// Verkrijg de JSON-data van de front-end
$data = json_decode(file_get_contents("php://input"), true);

// Verkrijg de gegevens van de afspraak
$date = $data['date'];
$time = $data['time'];
$paperFormat = $data['paperFormat'];
$extraInfo = $data['extraInfo'];

// Controleer of het tijdstip al in gebruik is
$query = $pdo->prepare("SELECT COUNT(*) FROM afspraken WHERE datum = :date AND tijd = :time");
$query->execute([':date' => $date, ':time' => $time]);
$count = $query->fetchColumn();

if ($count > 0) {
    echo json_encode(["success" => false, "message" => "Dit tijdstip is al bezet. Kies een ander tijdstip."]);
    exit;
}

// Sla de afspraak op in de database
try {
    $query = $pdo->prepare("INSERT INTO afspraken (datum, tijd, papierformaat, extra_info) 
                            VALUES (:date, :time, :paperFormat, :extraInfo)");
    $query->execute([
        ':date' => $date,
        ':time' => $time,
        ':paperFormat' => $paperFormat,
        ':extraInfo' => $extraInfo
    ]);

    // Bevestiging van de succesvolle opslag
    echo json_encode(["success" => true, "message" => "Afspraak succesvol opgeslagen!"]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Er is een fout opgetreden bij het opslaan van de afspraak: " . $e->getMessage()]);
}
?>
