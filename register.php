<?php
$pdo = new PDO('mysql:host=localhost;dbname=2fa_nettside', 'root', '');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    echo "Brukeren er registrert!";
}
?>

<li><a href="index.php">Hjem</a></li>
<p><a href="login.php">Har du allerede en bruker? Logg inn her</a></p>
<p><a href="register.php">Har ikke bruker? Lag bruker her</a></p>

<form method="POST">
    <label for="username">Brukernavn:</label>
    <input type="text" name="username" id="username" required><br>
    <label for="email">E-post:</label>
    <input type="email" name="email" id="email" required><br>
    <label for="password">Passord:</label>
    <input type="password" name="password" id="password" required><br>
    <input type="submit" value="Registrer">
</form>