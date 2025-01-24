<?php
session_start();

require 'vendor/autoload.php';

use SendGrid\Mail\Mail;
use SendGrid\Mail\From;

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
        $code = rand(100000, 999999);

        $stmt = $pdo->prepare("INSERT INTO `2fa_code` (user_id, code, expired_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
        $stmt->execute([$user['id'], $code]);

        $stmt = $pdo->prepare("SELECT sendgrid_api_key FROM API WHERE id = 1");
        $stmt->execute();
        $settings = $stmt->fetch();
        $sendgrid_api_key = $settings['sendgrid_api_key'];

        $mail = new Mail();
        $mail->setFrom(new From('mathiashansen2007@gmail.com', "2FA"));
        $mail->setSubject('Din 2FA-kode');
        $mail->addTo($email);
        $mail->addContent("text/plain", "Din 2FA-kode er: $code");

        $sendgrid = new \SendGrid($sendgrid_api_key);
        try {
            $response = $sendgrid->send($mail);
            echo "En e-post med 2FA-kode har blitt sendt!";

            $_SESSION['email'] = $email;

            header("Location: verify.php");
            exit();
        } catch (Exception $e) {
            echo 'Feil ved sending av e-post: ' . $e->getMessage();
        }
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
