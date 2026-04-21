<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Сулейманов Р.А. ЛР№2, вариант 4</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header>
    <img src="polytech_logo.png" alt="Логотип университета" class="logo">
    <h1>Сулейманов Р.А., 241-352</h1>
    <h2>Лабораторная работа №2 — вариант 4</h2>
</header>

<main>

<?php

// Переменные
$x = 10; // начальное значение
$step = 2; // шаг
$count = 50; // количество вычислений
$type = 'D'; // тип верстки (A B C D E)

$min_limit = -1000; // пределы остановки
$max_limit = 1000;

// статистика
$sum = 0;
$values = [];
$iteration = 0;


// ФУНКЦИЯ (ВАРИАНТ 4)
function calcF($x)
{
    if ($x <= 10) {

        // (5 - x) / (1 - x/5)
        $den = 1 - $x / 5;

        if ($den == 0) { // проверка на 0
            return "error";
        }

        return (5 - $x) / $den;
    }
    elseif ($x > 10 && $x < 20) {

        // x^2 / 4 + 7
        return ($x * $x) / 4 + 7;
    }
    else {

        // 2*x - 21
        return 2 * $x - 21;
    }
}


// вёрстка таблицы
switch ($type) {

    case 'B': // маркированный список
        echo "<ul>";
        break;

    case 'C': // нумерованный список
        echo "<ol>";
        break;

    case 'D': // табличный список
        echo "<table border='1'>";
        echo "<tr><th>№</th><th>x</th><th>f(x)</th></tr>";
        break;

    case 'E': // блочная верстка
        echo "<div class='flex'>";
        break;
}


// цикл for
for ($i = 0; $i < $count; $i++, $x += $step) {

    $f = calcF($x); // вычисление функции
    // идет округление при отсутствии ошибок
    if ($f !== "error") {
        $f = round($f, 3); // округление до 3 знаков
        $sum += $f; // сумма
        $values[] = $f; // добавление значения в массив
    }

    $iteration++; // увеличение номера строки

    // остановка вычислений по выходу из диапазона
    if ($f !== "error" && ($f >= $max_limit || $f < $min_limit)) {
        break;
    }

    $text = "f($x) = $f"; // строка вывода


    switch ($type) { // вывод в зависимости от типа верстки

        case 'A': // текстовый ввод с переносом строки
            echo $text . "<br>";
            break;

        case 'B': // маркированный список
            echo "<li>$text</li>";
            break;

        case 'C': // нумерованный список
            echo "<li>$text</li>";
            break;

        case 'D': // табличный список
            echo "<tr>
                    <td>$iteration</td>
                    <td>$x</td>
                    <td>$f</td>
                  </tr>";
            break;

        case 'E': // блочный вывод в отдельном контейнере
            echo "<div class='box'>$text</div>";
            break;
    }
}


//Закрытие верстки (для типа А дополнительные теги не открывались, поэтому ничего не нужно делать с этим)

switch ($type) {

    case 'B':
        echo "</ul>";
        break;

    case 'C':
        echo "</ol>";
        break;

    case 'D':
        echo "</table>";
        break;

    case 'E':
        echo "</div>";
        break;
}


// Статистика

if (count($values) > 0) {

    $max = max($values); // максимум
    $min = min($values); // минимум
    $avg = round($sum / count($values), 3); // среднее значение, округленное до 3 знаков

    echo "<h3>Статистика:</h3>";
    echo "Сумма: $sum <br>";
    echo "Среднее: $avg <br>";
    echo "Максимум: $max <br>";
    echo "Минимум: $min <br>";
}
?>

</main>

<footer>
    Тип верстки: <?php echo $type; ?>
</footer>

</body>
</html>