<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Гостевая книга</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Оставьте сообщение в гостевой книге</h2>
<form action="add_message.php" method="POST">
    <label for="name">Имя:</label>
    <input type="text" name="name" required>

    <label for="city">Город:</label>
    <input type="text" name="city">

    <label for="email">E-mail:</label>
    <input type="email" name="email">

    <label for="url">Сайт:</label>
    <input type="url" name="url">

    <label for="msg">Сообщение:</label>
    <textarea name="msg" required></textarea>

    <button type="submit">Отправить сообщение</button>
</form>

<?php if (!empty($successMessage)): ?>
    <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
<?php endif; ?>

<div class="center-link">
    <a href="admin.php">Перейти к администрированию</a>
</div>

<h1>Гостевая книга</h1>
<?php if (!empty($messages)): ?>
    <?php foreach ($messages as $row): ?>
        <div class="message">
            <h3><?= htmlspecialchars($row['name']) ?><?php if (!empty($row['city'])): ?> (<?= htmlspecialchars($row['city']) ?>)<?php endif; ?></h3>
            <p><?= htmlspecialchars($row['msg']) ?></p>
            <?php if (!empty($row['email'])): ?>
                <p>Email: <a href="mailto:<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></a></p>
            <?php endif; ?>
            <?php if (!empty($row['url'])): ?>
                <p>Сайт: <a href="<?= htmlspecialchars($row['url']) ?>" target="_blank"><?= htmlspecialchars($row['url']) ?></a></p>
            <?php endif; ?>
            <?php if ($row['answer']): ?>
                <p><strong>Ответ администратора:</strong> <?= htmlspecialchars($row['answer']) ?></p>
            <?php endif; ?>
            <p><em>Добавлено: <?= $row['puttime'] ?></em></p>
        </div>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>Нет сообщений для отображения.</p>
<?php endif; ?>

</body>
</html>
