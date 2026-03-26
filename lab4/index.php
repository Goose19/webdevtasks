<?php
// Количество колонок в таблице
$columnCount = 4;

// Массив для генерации таблиц
// Строки - #
// ячейки внутри *
$table = array(
    'Товар*Цена*Количество*Склад#Ноутбук*45000*3*А1#Мышь*1200*15*B2',
    'Фамилия*Имя*Группа*Оценка#Иванов*Павел*ИБС-325*5#Сидорова*Анна*ИБС-345*4',
    'День недели*Предмет*Время*Аудитория#Вторник*Физика*10:15*204#Среда*Информатика*12:00*305',
    'Город*Страна*Население#Вена*Австрия*1900000#Прага*Чехия*1300000',
    'Январь*12000*14500#Февраль*13200*15000#Март*14100*16000',
    'CPU*Ryzen 5*RAM*16 GB#SSD*512 GB*GPU*RTX 4060',
    'Этап*Статус#Анализ*Выполнен#Разработка*В процессе#Тестирование*Не начато',
    'Фрукт*Цвет*Вес#Яблоко*Зелёный*150#Банан*Жёлтый*120#Апельсин*Оранжевый*180',
    'Предмет*Преподаватель#Программирование*Смирнов#Базы данных*Орлова',
    'Страна*Столица*Язык*Валюта#Япония*Токио*Японский*Иена#Франция*Париж*Французский*Евро',
    'A*B*C*D*E#1*2'
);

// Функция очистки ячейки
function cleanCell(string $cell): string
{
    return trim($cell); // Удаление пробелов в начале и конце строки
}

// Функция формирования строки таблицы
function getTR(string $data, int $columnCount): string
{
    $cells = explode('*', $data); // разбиение строки на ячейки по символу *
    $preparedCells = array(); // заполнение из массива

    foreach ($cells as $cell) { // Обработка каждой ячейки
        $cell = cleanCell($cell); // очистка ячейки
        if ($cell !== '') { // добавление только непустых значений ячейки
            $preparedCells[] = $cell; // Добавление очищенного значения ячейки в массив
        }
    }

    if (count($preparedCells) === 0) { // Строка не создается, если нет данных
        return '';
    }

    // Ограничение количества колонок
    $preparedCells = array_slice($preparedCells, 0, $columnCount); 

    // Дополнение пустыми ячейками, если самих ячеек недостаточно
    while (count($preparedCells) < $columnCount) {
        $preparedCells[] = '';
    }

    $row = '<tr>'; // Формирование HTML-строки таблицы
    foreach ($preparedCells as $cell) {
    $row .= '<td>' . $cell . '</td>'; // Добавление ячейки в текущую строку таблицы с содержимым переменной cell
    }
    $row .= '</tr>'; // завершает формирование таблицы

    return $row;
}

// Функция вывода таблицы
function outTable(string $table, int $tableNumber, int $columnCount): void
{
    echo '<section class="table-block">'; // выводит таблицу на страницу блока section
    echo '<h2>Таблица №' . $tableNumber . '</h2>';

    if ($columnCount <= 0) { // проверка корректности числа колонок
        echo '<p class="message error">Неправильное число колонок</p>';
        echo '</section>';
        return;
    }

    $strings = explode('#', $table); // разбиение структуры на строки через #

    // Проверка на отсутствие значений внутри таблицы
    if (count($strings) === 0 || (count($strings) === 1 && trim($strings[0]) === '')) {
        echo '<p class="message warning">В таблице нет строк</p>';
        echo '</section>';
        return;
    }

    $rowsHtml = ''; // Строка, в которой будет записываться HTML-код строк таблицы

    foreach ($strings as $string) { // Генерация всех строк таблицы
        $rowsHtml .= getTR($string, $columnCount);
    }

    if ($rowsHtml === '') { // Проверка на отсутствие валидных строк
        echo '<p class="message warning">В таблице нет строк с ячейками</p>';
        echo '</section>';
        return;
    }

    // Вывод таблицы
    echo '<table class="lab-table">';
    echo $rowsHtml;
    echo '</table>';
    echo '</section>';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ЛР4</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <img class="logo" src="polytech_logo.png" alt="logo">
    <h1>Лабораторная работа № 4</h1>
    <p>Пользовательские функции. Вывод таблиц</p>
</header>

<main>
    <section class="intro-card">
        <p><strong>Количество колонок:</strong> <?php echo $columnCount; ?></p>
        <p><strong>Количество структур:</strong> <?php echo count($table); ?></p>
    </section>

    <?php
    for ($i = 0; $i < count($table); $i++) { // Генерация всех таблиц, через проходы по всем элементам массива
        outTable($table[$i], $i + 1, $columnCount);
    }
    ?>
</main>

<footer>
    <p>Сулейманов Раул, 241-352</p>
</footer>
</body>
</html>
