<?php
// Het plaintext wachtwoord
$password = 'test@test.com';

// Genereer de hash van het wachtwoord
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Toon de gehashte waarde
echo $hashedPassword;
?>
