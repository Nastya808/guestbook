<?php
$successMessage = $_GET['successMessage'] ?? '';

$dsn = 'mysql:host=localhost;dbname=guestbook;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM guest WHERE hide = 'show' ORDER BY puttime DESC";
    $stmt = $pdo->query($sql);

    $messages = [];
    foreach ($stmt as $row) {
        $messages[] = $row;
    }

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

$pdo = null;

include 'guestbook.php';
?>
