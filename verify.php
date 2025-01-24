<?php
session_start();

$pdo = new PDO('mysql:host=localhost;dbname=2fa_nettside', 'root', '');

if (!isset($_SESSION['email'])) {
    echo "Du må logge inn først.";
    exit;
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $pdo->prepare("SELECT * FROM `2fa_code` WHERE user_id = ? AND code = ? AND expired_at > NOW()");
        $stmt->execute([$user['id'], $code]);
        $validCode = $stmt->fetch();

        if ($validCode) {
            echo "Tofaktorautentisering vellykket!";
            $_SESSION['logged_in'] = true; 
            $_SESSION['email'] = $email;  
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Ugyldig eller utløpt 2FA-kode.";
        }
    } else {
        echo "Bruker ikke funnet.";
    }
} else {
    echo "Vennligst skriv inn din 2FA-kode.";
}
?>

<form method="POST">
    <label for="code">2FA-kode:</label>
    <input type="text" name="code" id="code" required>
    <button type="submit">Verifiser</button>
</form>
