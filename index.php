<?php
// Папка для збереження файлів
$uploadDir = 'uploads/';

// Створюємо папку, якщо її не існує
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Завантаження файлів
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    // Перевірка на помилки під час завантаження
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Перевірка, чи файл уже існує
        if (!file_exists($targetPath)) {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $message = "Файл успішно завантажено.";
            } else {
                $message = "Помилка під час завантаження файлу.";
            }
        } else {
            $message = "Файл з такою назвою вже існує.";
        }
    } else {
        $message = "Помилка завантаження: " . $file['error'];
    }
}

// Отримання списку файлів у папці
$files = array_diff(scandir($uploadDir), ['.', '..']);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Файловий менеджер</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Файловий менеджер</h1>

    <!-- Повідомлення -->
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Форма завантаження -->
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Завантажити</button>
    </form>

    <!-- Список файлів -->
    <div class="file-list">
        <h2>Завантажені файли:</h2>
        <ul>
            <?php if (count($files) > 0): ?>
                <?php foreach ($files as $file): ?>
                    <li>
                        <a href="<?= $uploadDir . $file ?>" target="_blank"><?= htmlspecialchars($file) ?></a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>Немає завантажених файлів.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
</body>
</html>
