<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=2fa_nettside', 'root', ''); 

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
</body>
</html>