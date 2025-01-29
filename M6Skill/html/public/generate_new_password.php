<?php
// Het wachtwoord voor admin
$password = 'adminWachtwoord123'; // Het wachtwoord dat je voor de admin wilt gebruiken

// Genereer de hash van het wachtwoord
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Toon het gehashte wachtwoord
echo "Nieuwe gehashte wachtwoord: " . $hashed_password;
?>
