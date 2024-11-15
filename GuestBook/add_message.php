<?php
$dsn = 'mysql:host=localhost;dbname=guestbook;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $name = $_POST['name'];
    $city = $_POST['city'] ?? null;
    $email = $_POST['email'] ?? null;
    $url = $_POST['url'] ?? null;
    $msg = $_POST['msg'];
    $puttime = date('Y-m-d H:i:s');
    $hide = 'show';

    $sql = "INSERT INTO guest (name, city, email, url, msg, puttime, hide) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $city, $email, $url, $msg, $puttime, $hide]);

    $successMessage = "Сообщение успешно добавлено!";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

$pdo = null;


header("Location: view_messages.php?successMessage=" . urlencode($successMessage));

?>
