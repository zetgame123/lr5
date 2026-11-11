<?php
include 'db.php';

$terms = mysqli_query($mysql, "SELECT * FROM `terms` ORDER BY `id`");
$images = mysqli_query($mysql, "SELECT * FROM `images` ORDER BY `id`");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Термины и определения</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <div class="logo">Справочник терминов</div>
    </header>

    <main>
        <h1>Термины и определения</h1>

        <table>
            <thead>
                <tr>
                    <th>№</th>
                    <th>Термин</th>
                    <th>Определение</th>
                </tr>
            </thead>
            <tbody>
                <?php $number = 1; ?>
                <?php while ($term = mysqli_fetch_assoc($terms)): ?>
                    <tr>
                        <td><?php echo $number++; ?></td>
                        <td><?php echo htmlspecialchars($term['term']); ?></td>
                        <td><?php echo htmlspecialchars($term['definition']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Иллюстрации</h2>

        <div class="gallery">
            <?php while ($image = mysqli_fetch_assoc($images)): ?>
                <img title="<?php echo htmlspecialchars($image['name']); ?>"
                     src="Data/img/<?php echo htmlspecialchars($image['img']); ?>"
                     alt="<?php echo htmlspecialchars($image['name']); ?>">
            <?php endwhile; ?>
        </div>

        <h2>Добавить термин</h2>

        <form action="save.php" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <label for="term">Термин:</label>
                <input type="text" id="term" name="term" required>
            </div>

            <div class="form-row">
                <label for="definition">Определение:</label>
                <textarea id="definition" name="definition" rows="4" required></textarea>
            </div>

            <div class="form-row">
                <label for="image">Картинка:</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>

            <div class="form-row">
                <span class="label"></span>
                <button type="submit">Добавить</button>
            </div>
        </form>
    </main>
</body>
</html>
