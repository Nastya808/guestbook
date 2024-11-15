<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрирование гостевой книги</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
session_start();

$isAdmin = true;

$dsn = 'mysql:host=localhost;dbname=guestbook;charset=utf8';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        handleFormSubmission($pdo);
    }

    $messages = fetchMessages($pdo);

    echo "<h1>Администрирование гостевой книги</h1>";
    echo "<div class='messages-container'>";

    foreach ($messages as $message) {
        renderMessage($message);
    }

    echo "</div>";

} catch (PDOException $e) {
    echo "<div class='error-message'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</div>";
}

$pdo = null;

function handleFormSubmission($pdo)
{
    $id_msg = filter_input(INPUT_POST, 'id_msg', FILTER_VALIDATE_INT);

    if (isset($_POST['answer'])) {
        $answer = filter_input(INPUT_POST, 'answer', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sql = "UPDATE guest SET answer = ? WHERE id_msg = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$answer, $id_msg]);
    }

    if (isset($_POST['hide'])) {
        $hide = $_POST['hide'] === 'show' ? 'hide' : 'show';
        $sql = "UPDATE guest SET hide = ? WHERE id_msg = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$hide, $id_msg]);
    }

    echo "<div class='success-message'>Сообщение обновлено успешно.</div>";
}

function fetchMessages($pdo)
{
    $sql = "SELECT * FROM guest ORDER BY puttime DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

function renderMessage($message)
{
    ?>
    <div class="message">
        <h3><?= htmlspecialchars($message['name']) ?>
            <?php if (!empty($message['city'])): ?>
                <span>(<?= htmlspecialchars($message['city']) ?>)</span>
            <?php endif; ?>
        </h3>
        <p><strong>Сообщение:</strong> <?= htmlspecialchars($message['msg']) ?></p>
        <?php if (!empty($message['email'])): ?>
            <p>Email: <a href="mailto:<?= htmlspecialchars($message['email']) ?>"><?= htmlspecialchars($message['email']) ?></a></p>
        <?php endif; ?>
        <?php if (!empty($message['url'])): ?>
            <p>Сайт: <a href="<?= htmlspecialchars($message['url']) ?>" target="_blank"><?= htmlspecialchars($message['url']) ?></a></p>
        <?php endif; ?>
        <p><strong>Ответ:</strong> <?= htmlspecialchars($message['answer']) ?></p>
        <p><em>Добавлено: <?= htmlspecialchars($message['puttime']) ?></em></p>
        <p><strong>Статус:</strong> <?= $message['hide'] === 'show' ? "Показано" : "Скрыто" ?></p>

        <form action="" method="POST" class="admin-form">
            <input type="hidden" name="id_msg" value="<?= htmlspecialchars($message['id_msg']) ?>">
            <label for="answer">Ответ администратора:</label><br>
            <label>
                <textarea name="answer"><?= htmlspecialchars($message['answer']) ?></textarea>
            </label><br>
            <button type="submit" name="hide" value="<?= $message['hide'] ?>">
                <?= $message['hide'] === 'show' ? "Скрыть" : "Показать" ?>
            </button>
            <button type="submit">Обновить сообщение</button>
        </form>
        <hr>
    </div>
    <?php
}
?>

<div class="center-link">
    <a href="view_messages.php">Перейти на главную</a>
</div>

</body>
</html>
