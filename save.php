<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$term = isset($_POST['term']) ? trim($_POST['term']) : '';
$definition = isset($_POST['definition']) ? trim($_POST['definition']) : '';

$success = false;
$error = '';

// Допустимые форматы картинок
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
$maxFileSize = 5 * 1024 * 1024; // 5 МБ

if ($term === '' || $definition === '') {
    $error = 'Заполните все поля формы.';
} elseif (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    $error = 'Выберите картинку для термина.';
} elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    $error = 'Ошибка при загрузке файла.';
} else {
    $file = $_FILES['image'];

    if ($file['size'] > $maxFileSize) {
        $error = 'Файл слишком большой (максимум 5 МБ).';
    } else {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($extension === 'jpeg') {
            $extension = 'jpg';
        }

        if (!in_array($extension, $allowedExtensions, true)) {
            $error = 'Допустимые форматы картинки: jpg, png, gif, webp, svg.';
        } elseif ($extension !== 'svg' && getimagesize($file['tmp_name']) === false) {
            $error = 'Загруженный файл не является изображением.';
        }
    }
}

if ($error === '') {
    // Имя файла формируется из термина (русская раскладка транслитерируется)
    $translit = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
        'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    $name = mb_strtolower(strtr($term, $translit));
    $name = preg_replace('/[^a-z0-9]+/', '-', $name);
    $name = trim($name, '-');
    $fileName = $name !== '' ? $name : 'image';
    $fileName .= '.' . $extension;

    // Если файл с таким именем уже есть - добавляем номер
    $number = 1;
    while (file_exists(__DIR__ . '/Data/img/' . $fileName)) {
        $number++;
        $fileName = $name !== '' ? $name : 'image';
        $fileName .= '-' . $number . '.' . $extension;
    }

    if (!is_dir(__DIR__ . '/Data/img')) {
        mkdir(__DIR__ . '/Data/img', 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], __DIR__ . '/Data/img/' . $fileName)) {
        $mysql->begin_transaction();

        $stmt = mysqli_prepare($mysql, "INSERT INTO `terms` (`term`, `definition`) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt, 'ss', $term, $definition);
        $termInserted = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $imageInserted = false;

        if ($termInserted) {
            $stmt = mysqli_prepare($mysql, "INSERT INTO `images` (`name`, `img`) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, 'ss', $term, $fileName);
            $imageInserted = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }

        if ($termInserted && $imageInserted) {
            $mysql->commit();
            $success = true;
        } else {
            $mysql->rollback();
            unlink(__DIR__ . '/Data/img/' . $fileName);
            $error = 'Не удалось сохранить данные в БД: ' . mysqli_error($mysql);
        }
    } else {
        $error = 'Не удалось сохранить файл на сервере.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление данных</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <div class="logo">Справочник терминов</div>
    </header>

    <main>
        <h1>Добавление данных</h1>

        <?php if ($success): ?>
            <p class="notice success">Данные успешно занесены в БД.</p>
        <?php else: ?>
            <p class="notice error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <p><a class="btn" href="index.php">Вернуться на главную</a></p>
    </main>
</body>
</html>
