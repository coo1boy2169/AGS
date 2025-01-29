<?php
session_start();
include_once('database.php');

if ($_SESSION['user_type'] != 'admin') {
    header('Location: login.php');
    exit;
}

$connection = database_connect();
$id = $_GET['id'];
$status_id = $_POST['status_id']; // Dit zou een dropdown of een andere interface kunnen zijn

$query = "UPDATE Tijdsloten SET Status_id = '$status_id' WHERE Id = '$id'";
mysqli_query($connection, $query);
header("Location: admin_dashboard.php");
?>
