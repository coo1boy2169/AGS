<?php
session_start();
require_once('db.php'); // Ensure the database connection is included

$error = ""; // Variable for error message

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare query to check if the user exists
    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the user exists and password matches
    if ($user && $password == $user['password']) { // No hashing, just plain password check
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role']; // This ensures admin check

        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header('Location: admin.php'); // Redirect to admin dashboard
            exit;
        } else {
            header('Location: index.php'); // Redirect to the main user page
            exit;
        }
    } else {
        $error = "Ongeldige gebruikersnaam of wachtwoord."; // Invalid username or password
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Inloggen</h1>
    <form method="POST">
        <label for="username">Gebruikersnaam:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Wachtwoord:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Inloggen!</button>
    </form>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
</body>
</html>
