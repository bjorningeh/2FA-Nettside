<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) { header("Location: login.php"); exit(); }

$pdo = new PDO('mysql:host=localhost;dbname=2fa_nettside', 'root', '');

$email = $_SESSION['email'];
$stmt = $pdo->prepare("SELECT username FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    $username = htmlspecialchars($user['username']);
    echo "<h1>Velkommen $username!</h1>";
} else {
    echo "Feil: Bruker ikke funnet.";
}
?>
