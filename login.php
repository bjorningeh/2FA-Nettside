<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=2fa_nettside', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Ugyldig e-postadresse.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $user['username'];

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Feil e-postadresse eller passord.";
    }
}
?>

<li><a href="index.php">Hjem</a></li>
<p><a href="login.php">Har du allerede en bruker? Logg inn her</a></p>
<p><a href="register.php">Har ikke bruker? Lag bruker her</a></p>

<form method="POST">
    <label for="email">E-post:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="password">Passord:</label>
    <input type="password" name="password" id="password" required><br>
    <input type="submit" value="Logg inn">
</form>
