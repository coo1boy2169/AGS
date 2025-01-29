<?php
session_start();
session_unset(); // Verwijder alle sessievariabelen
session_destroy(); // Vernietig de sessie
header('Location: login.php'); // Redirect naar de loginpagina
exit();
?>
