<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР3 — Виртуальная клавиатура</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
// Строка результата
// Проверка факта передачи параметра строки результата
if (!isset($_GET['store'])) {
    $_GET['store'] = ''; // Вывод пустой строки при первом запуске страницы
}

// Счётчик нажатий
// Проверка факта передачи счётчика нажатий
if (!isset($_GET['count'])) {
    $_GET['count'] = 0; // При первом запуске выводится 0
}

// Нажатие кнопки
// Проверка факта нажатия какой-либо кнопки
if (isset($_GET['key'])) {
    // Добавление нажатой цифры к результирующей строке
    $_GET['store'] .= $_GET['key'];
    $_GET['count']++; // увеличение счётчика нажатий
}
?>

<header>
    <img src="polytech_logo.png" alt="Логотип университета" class="logo">
    <h1>Виртуальная клавиатура</h1>
    <p>Лабораторная работа №3</p>
</header>

<main>
    <section class="keyboard-wrapper">
        <div class="result">
            <?php echo $_GET['store']; ?>
        </div>    

        <div class="keyboard">
            <?php
            // Цикл генерации кнопок цифр от 1 до 9
            // представляющая собой ссылку, передающая параметры через GET
            for ($i = 1; $i <= 9; $i++) {
                echo '<a class="key" href="?key=' . $i .
                '&store=' . $_GET['store'] . // Передача текущей строки результата
                '&count=' . $_GET['count'] . // Передача текущего количества нажатий
                '">' . $i . '</a>';
                
                }
            ?>
            <!-- Кнопка 0 -->
            <a class="key key-zero" href="?key=0&store=<?php echo $_GET['store']; ?>&count=<?php echo $_GET['count']; ?>">0</a>
            <a class="key reset" href="?store=&count=<?php echo $_GET['count']; ?>">СБРОС</a> <!-- Сброс -->
        </div>
    </section>
</main>

<footer>
    Общее число нажатий: <?php echo (int)$_GET['count']; ?>
</footer>

</body>
</html>