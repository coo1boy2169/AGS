<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

require_once('db.php');

// Haal gegevens uit het formulier
$maxAppointments = $_POST['maxAppointments'];
$availablePrintTypes = implode(',', $_POST['availablePrintTypes']);
$blockedDays = implode(',', $_POST['blockedDays']);
$blockedTimeSlots = $_POST['blockedTimeSlots'];
$printPrice = $_POST['printPrice'];

// Sla instellingen op in de database
$query = $pdo->prepare("REPLACE INTO settings (id, max_appointments, available_print_types, blocked_days, blocked_time_slots, print_price) 
                        VALUES (1, :maxAppointments, :availablePrintTypes, :blockedDays, :blockedTimeSlots, :printPrice)");
$query->execute([
    ':maxAppointments' => $maxAppointments,
    ':availablePrintTypes' => $availablePrintTypes,
    ':blockedDays' => $blockedDays,
    ':blockedTimeSlots' => $blockedTimeSlots,
    ':printPrice' => $printPrice
]);

header('Location: admin.php');
exit();
?>
