<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР5</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Лабораторная работа №5</h1>
    <p>Таблица умножения</p>
</header>

<div id="main_menu">
<?php

date_default_timezone_set('Europe/Moscow');

// Ссылка: табличная верстка
echo '<a href="?html_type=TABLE';

// Сохранение значение столбца при переключении верстки
if (isset($_GET['content'])) echo '&content=' . $_GET['content'];
echo '"';

// Ссылки становятся активными при выборе типа TABLE
if (isset($_GET['html_type']) && $_GET['html_type'] == 'TABLE')
    echo ' class="selected"';

echo '>Табличная верстка</a>';

// Ссылка: блочная верстка
echo '<a href="?html_type=DIV';
// Сохранение выбранного столбца
if (isset($_GET['content'])) echo '&content=' . $_GET['content'];
echo '"';

// Идет выделение, если выбран тип DIV
if (isset($_GET['html_type']) && $_GET['html_type'] == 'DIV')
    echo ' class="selected"';

echo '>Блочная верстка</a>';
?>
</div>

<div id="product_menu">
<?php
// Вся таблица
echo '<a href="?"';
if (!isset($_GET['content'])) echo ' class="selected"';
echo '>Все</a>';

// Цикл для создания ссылок 2-9
for ($i = 2; $i <= 9; $i++) {
    echo '<a href="?content=' . $i; // Вывод цифр

    if (isset($_GET['html_type'])) // Сохранение выбранного типа верстки
        echo '&html_type=' . $_GET['html_type'];

    echo '"';

    if (isset($_GET['content']) && $_GET['content'] == $i) // Выделение выбранного пункта
        echo ' class="selected"';

    echo '>' . $i . '</a>';
}
?>
</div>

<main>
<?php

// Если параметр не задан, то идет вывод табличной формы
if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE') {
    outTableForm();
} else {
    outDivForm(); // иначе блочный тип
}

// Функция вывода таблицы в табличной форме
function outTableForm() {
    echo '<table class="lab-table">';

    // Если content не выбран, то выводится вся таблица
    if (!isset($_GET['content'])) {
        for ($i = 2; $i <= 9; $i++) {
            echo '<tr>';
            echo '<td>';
            outRow($i); // вывод столбца
            echo '</td>';
            echo '</tr>';
        }
    } else { // иначе выводится выбранный столбец
        echo '<tr><td>';
        outRow($_GET['content']);
        echo '</td></tr>';
    }

    echo '</table>';
}


// Функция вывода в блочной форме
function outDivForm() {
    if (!isset($_GET['content'])) { // проверка существования таблицы
        for ($i = 2; $i <= 9; $i++) { // вывод по столбцам
            echo '<div class="ttRow">';
            outRow($i);
            echo '</div>';
        }
    } else { // вывод одного столбца
        echo '<div class="ttSingleRow">';
        outRow($_GET['content']);
        echo '</div>';
    }
}


// Функция вывода одного столбца таблицы
function outRow($n) {
    for ($i = 2; $i <= 9; $i++) {
        // Формирование строки вида: a x b = c
        echo outNumAsLink($n) . ' x ' .
            outNumAsLink($i) . ' = ' .
            outNumAsLink($n * $i) . '<br>';
    }
}


// Функция превращения числа в ссылку
function outNumAsLink($x) {
    if ($x <= 9) {
        return '<a href="?content=' . $x . '">' . $x . '</a>';
    }
    // Иначе просто выводится число
    return $x;
}

?>
</main>

<footer>
<?php
// Формирование строки с типом верстки
if (!isset($_GET['html_type']) || $_GET['html_type'] == 'TABLE')
    $s = 'Тип: Табличная верстка. ';
else
    $s = 'Тип: Блочная верстка. ';

// Добавление информации о содержимом
if (!isset($_GET['content']))
    $s .= 'Вся таблица. ';
else
    $s .= 'Таблица на ' . $_GET['content'] . '. ';

// вывод информации с учетом текущей даты и времени
echo $s . date('d.m.Y H:i:s');
?>
</footer>

</body>
</html>