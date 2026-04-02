<?php
date_default_timezone_set('Europe/Moscow');

// Случайное вещественное число от 0 до 100
function getRandomNumber()
{
    return number_format(mt_rand(0, 10000) / 100, 2, '.', '');
}

// Преобразование строки в число
function normalizeNumber($value)
{
    // Удаление пробелов внутри строки
    $value = trim((string)$value);
    $value = str_replace(' ', '', $value); // замена пробела
    $value = str_replace(',', '.', $value); // замена запятой

    if ($value === '') { // числа нет, если строка пустая
        return null;
    }

    if (!is_numeric($value)) { // ничего не выводится, если value - строка
        return null;
    }

    return (float)$value; // вывод числа с плавающей точкой
}

// Красивый вывод числа
function formatNumber($value)
{
    $formatted = number_format((float)$value, 2, '.', ''); // приведение числа к float
    $formatted = rtrim(rtrim($formatted, '0'), '.'); // избавление от незначащих нулей (5.00 -> 5 и т.п.)

    if ($formatted === '-0') { // при полчении -0 выводится 0
        $formatted = '0';
    }

    return $formatted;
}

// Список задач
function getTaskList()
{
    return [
        'triangle_area' => 'Площадь треугольника',
        'triangle_perimeter' => 'Периметр треугольника',
        'parallelepiped_volume' => 'Объем параллелепипеда',
        'mean' => 'Среднее арифметическое',
        'sum' => 'Сумма чисел',
        'max' => 'Максимум из трех чисел'
    ];
}

// Вычисление результата
function calculateTaskResult($task, $a, $b, $c)
{
    switch ($task) {
        case 'triangle_area':
            // Площадь треугольника по основанию и высоте
            return round(($a * $b) / 2, 2);

        case 'triangle_perimeter': // периметр
            return round($a + $b + $c, 2);

        case 'parallelepiped_volume': // объем параллелепипеда
            return round($a * $b * $c, 2);

        case 'mean': // среднее арифметическое
            return round(($a + $b + $c) / 3, 2);

        case 'sum': // сумма
            return round($a + $b + $c, 2);

        case 'max': // максимальное значение
            return round(max($a, $b, $c), 2);
    }

    return null;
}

// Ссылка для повторного теста
function buildRepeatUrl($fio, $group, $about, $viewMode)
{
    $params = [ // сбор параметров в массив
        'FIO' => $fio,
        'GROUP' => $group,
        'ABOUT' => $about,
        'view_mode' => $viewMode
    ];

    return '?' . http_build_query($params); // преобразование ссылки (сохранение предыдущих введенных данных)
}

// Вывод HTML-формы теста
function outputForm($formData, $viewMode)
{
    $tasks = getTaskList(); // список доступных задач
    $showMailBlock = !empty($formData['send_mail']); // проверка необходимости показывать поле email
    
    // основная часть страницы
    echo '<main>';
    echo '<div class="content-shell">';
    echo '<div class="form-card">';
    echo '<h2>Форма теста</h2>';
    echo '<p class="helper-text">Заполните данные, выберите задачу и введите свой ответ.</p>';
    
    // заголовок формы и поясняющий текст
    echo '<form method="post" action="">';
    echo '<table class="lab-table">';
    
    // начало формы
    echo '<tr>';
    echo '<td>ФИО</td>';

    // поля ввода
    echo '<td><input type="text" name="FIO" value="' . $formData['FIO'] . '"></td>';
    echo '</tr>';

    echo '<tr>';
    echo '<td>Номер группы</td>';
    echo '<td><input type="text" name="GROUP" value="' . $formData['GROUP'] . '"></td>';
    echo '</tr>';

    // выпадающий список с выбором типа задачи
    echo '<tr>';
    echo '<td>Тип задачи</td>';
    echo '<td><select name="TASK">';
    
    // проход по выведенным задачам из массива
    foreach ($tasks as $value => $label) {
        echo '<option value="' . $value . '"';

        // если задача была ранее выбранной, то идет выделение выбора
        if ($formData['TASK'] === $value) {
            echo ' selected';
        }
        echo '>' . $label . '</option>';
    }
    echo '</select></td>';
    echo '</tr>';

    // поле ввода A
    echo '<tr>';
    echo '<td>Значение A</td>';
    echo '<td><input type="text" name="A" value="' . $formData['A'] . '"></td>';
    echo '</tr>';

    // поле ввода B
    echo '<tr>';
    echo '<td>Значение B</td>';
    echo '<td><input type="text" name="B" value="' . $formData['B'] . '"></td>';
    echo '</tr>';

    // поле ввода C
    echo '<tr>';
    echo '<td>Значение C</td>';
    echo '<td><input type="text" name="C" value="' . $formData['C'] . '"></td>';
    echo '</tr>';

    // поле ввода ответа пользователя
    echo '<tr>';
    echo '<td>Ваш ответ</td>';
    echo '<td><input type="text" name="result" value="' . $formData['result'] . '"></td>';
    echo '</tr>';

    // выбор режима отображения страницы
    echo '<tr>';
    echo '<td>Версия страницы</td>';
    echo '<td>';
    echo '<select name="view_mode">';

    // вариант для обычного просмотра в браузере
    echo '<option value="browser"' . ($viewMode === 'browser' ? ' selected' : '') . '>Версия для просмотра в браузере</option>';
    
    // вариант для печати
    echo '<option value="print"' . ($viewMode === 'print' ? ' selected' : '') . '>Версия для печати</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';

    // поле ввода доп информации о себе
    echo '<tr>';
    echo '<td>Немного о себе</td>';
    echo '<td><textarea name="ABOUT" rows="5">' . $formData['ABOUT'] . '</textarea></td>';
    echo '</tr>';

    // блок с чекбоксом email
    echo '<tr>';
    echo '<td>Отправить результат по e-mail</td>';
    echo '<td>';
    echo '<div class="checkbox-row">';
    
    // чекбокс, показывающий или скрывающий поле email
    echo '<input type="checkbox" id="send_mail" name="send_mail" value="1" onclick="toggleMailField()"' . ($showMailBlock ? ' checked' : '') . '>';
    echo '<span>Да</span>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';

    // поле ввода email (показ в случае выделения чекбокса)
    echo '<tr id="mail_block"' . ($showMailBlock ? '' : ' style="display:none;"') . '>';
    echo '<td>Ваш e-mail</td>';
    echo '<td><input type="text" name="MAIL" value="' . $formData['MAIL'] . '"></td>';
    echo '</tr>';

    // кнопка отправки формы
    echo '<tr>';
    echo '<td colspan="2" class="button-cell">';
    echo '<button type="submit" class="key">Проверить</button>';
    echo '</td>';
    echo '</tr>';

    // конец страницы
    echo '</table>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    echo '</main>';
}

// Вывод отчета
function outputReport($reportHtml, $mailMessage, $viewMode, $repeatUrl)
{
    // открытие основного контейнера, класс зависит от режима (печатная или браузерная версия)
    echo '<main class="' . ($viewMode === 'print' ? 'print-version' : 'browser-version') . '">';
    echo '<div class="content-shell">';

    // браузерная версия
    if ($viewMode === 'browser') {
        echo '<div class="ttSingleRow">';

        // заголовок и сам отчет
        echo '<h2>Результаты теста</h2>';
        echo $reportHtml;

        // вывод почты
        if ($mailMessage !== '') {
            echo '<p class="mail-note">' . $mailMessage . '</p>';
        }

        echo '<div class="repeat-wrap">';
        echo '<a href="' . $repeatUrl . '" class="key reset">Повторить тест</a>';
        echo '</div>';
        echo '</div>';

        // режим печати (без кнопки повторить)
    } else {
        echo '<div class="ttSingleRow print-report">';
        echo '<h2>Результаты теста</h2>'; // заголовок и сам отчет
        echo $reportHtml;

        // вывод почты в случае наличия
        if ($mailMessage !== '') {
            echo '<p class="mail-note">' . $mailMessage . '</p>';
        }

        echo '</div>';
    }

    // закрытие контейнеров
    echo '</div>';
    echo '</main>';
}

// массив начальных значений
$formData = [
    'FIO' => isset($_GET['FIO']) ? $_GET['FIO'] : '',
    'GROUP' => isset($_GET['GROUP']) ? $_GET['GROUP'] : '',
    'ABOUT' => isset($_GET['ABOUT']) ? $_GET['ABOUT'] : '',
    'A' => getRandomNumber(),
    'B' => getRandomNumber(),
    'C' => getRandomNumber(),
    'TASK' => 'mean', // изначальная задача, среднее арифметическое
    'result' => '', // результат
    'MAIL' => '', // поле для email
    'send_mail' => '' // флаг отправки на почту
];

// определение режима отображения страницы
// в GET есть view_mode=print (печать) или browser (обычный)
$viewMode = isset($_GET['view_mode']) && $_GET['view_mode'] === 'print' ? 'print' : 'browser';

// флаг, показывающий статус обработки формы (была ли она обработана)
$isProcessed = false;
$reportHtml = ''; // хранилище HTML отчета
$mailMessage = ''; // хранилище email (если используется)

// обработка формы
if (isset($_POST['A'])) {
    $isProcessed = true; // смена статуса

    // считывание данных из формы
    $formData['FIO'] = isset($_POST['FIO']) ? $_POST['FIO'] : '';
    $formData['GROUP'] = isset($_POST['GROUP']) ? $_POST['GROUP'] : '';
    $formData['ABOUT'] = isset($_POST['ABOUT']) ? $_POST['ABOUT'] : '';
    $formData['A'] = isset($_POST['A']) ? $_POST['A'] : '';
    $formData['B'] = isset($_POST['B']) ? $_POST['B'] : '';
    $formData['C'] = isset($_POST['C']) ? $_POST['C'] : '';
    $formData['TASK'] = isset($_POST['TASK']) ? $_POST['TASK'] : 'mean';
    $formData['result'] = isset($_POST['result']) ? $_POST['result'] : '';
    $formData['MAIL'] = isset($_POST['MAIL']) ? $_POST['MAIL'] : '';
    $formData['send_mail'] = isset($_POST['send_mail']) ? '1' : '';

    // определение режима отображения страницы
    $viewMode = (isset($_POST['view_mode']) && $_POST['view_mode'] === 'print') ? 'print' : 'browser';

    // проверка существования типа задачи
    $tasks = getTaskList();
    if (!array_key_exists($formData['TASK'], $tasks)) {
        $formData['TASK'] = 'mean';
    }

    // преобразование данных в числа
    $a = normalizeNumber($formData['A']);
    $b = normalizeNumber($formData['B']);
    $c = normalizeNumber($formData['C']);

    // переменные для хранения результатов проверки
    $programResult = null; // результат программной проверки
    $humanResult = null; // результат ввода пользователем
    $testConclusion = 'Ошибка: тест не пройден';
    $humanResultText = ''; // результат ввода польозователем - текст (для вывода)

    // при корректных данных идет вычисление результата программой
    if ($a !== null && $b !== null && $c !== null) {
        $programResult = calculateTaskResult($formData['TASK'], $a, $b, $c);
    }

    // проверка ввода ответа пользователем
    if ($formData['result'] === '') {
        $humanResultText = 'Задача самостоятельно решена не была';
    } else {
        $humanResult = normalizeNumber($formData['result']);

        // если ответ введен некорректно
        if ($humanResult === null) {
            $humanResultText = 'Введен некорректный ответ';
        } else {
            // приведение к удобному виду при корректном ответе
            $humanResultText = formatNumber($humanResult);
        }
    }

    // сравнение резульатта с программой
    if ($programResult !== null && $humanResult !== null && abs($programResult - $humanResult) < 0.00001) {
        $testConclusion = 'Тест пройден';
    } elseif ($programResult === null) {
        $testConclusion = 'Ошибка: исходные данные введены некорректно';
    } elseif ($formData['result'] === '') {
        $testConclusion = 'Ошибка: тест не пройден';
    } elseif ($humanResult === null) {
        $testConclusion = 'Ошибка: тест не пройден';
    }

    // подготовка вычисленного результата
    $programResultText = $programResult !== null ? formatNumber($programResult) : 'Невозможно вычислить результат';

    // формирование отчета
    $reportText = '';
    $reportText .= '<p><b>ФИО:</b> ' . $formData['FIO'] . '</p>';
    $reportText .= '<p><b>Группа:</b> ' . $formData['GROUP'] . '</p>';

    // добавление сведений о студенте
    if ($formData['ABOUT'] !== '') {
        $reportText .= '<p><b>Сведения о студенте:</b><br>' . nl2br($formData['ABOUT']) . '</p>';
    } else {
        $reportText .= '<p><b>Сведения о студенте:</b> не указаны</p>';
    }

    // добавление остальных результатов в отчет
    $reportText .= '<p><b>Тип задачи:</b> ' . $tasks[$formData['TASK']] . '</p>';
    $reportText .= '<p><b>Входные данные:</b> A = ' . $formData['A'] . ', B = ' . $formData['B'] . ', C = ' . $formData['C'] . '</p>';
    $reportText .= '<p><b>Предполагаемый результат:</b> ' . $humanResultText . '</p>';
    $reportText .= '<p><b>Вычисленный программой результат:</b> ' . $programResultText . '</p>';

    // вывод итога проверки разным цветом
    if ($testConclusion === 'Тест пройден') {
        $reportText .= '<p><b>Вывод:</b> <span class="success-text">' . $testConclusion . '</span></p>';
    } else {
        $reportText .= '<p><b>Вывод:</b> <span class="error-text">' . $testConclusion . '</span></p>';
    }

    // сворачивание отчета в HTML контейнер
    $reportHtml = '<div class="report-lines">' . $reportText . '</div>';

    // добавление email в отчет
    if ($formData['send_mail'] === '1' && $formData['MAIL'] !== '') {
        $plainText = '';
        $plainText .= "Результаты теста\r\n";
        $plainText .= "ФИО: " . $formData['FIO'] . "\r\n";
        $plainText .= "Группа: " . $formData['GROUP'] . "\r\n";

        // добавление сведений о студенте в текст письма
        if ($formData['ABOUT'] !== '') {
            $plainText .= "Сведения о студенте: " . $formData['ABOUT'] . "\r\n";
        } else {
            $plainText .= "Сведения о студенте: не указаны\r\n";
        }

        // добавление результатов в письмо
        $plainText .= "Тип задачи: " . $tasks[$formData['TASK']] . "\r\n";
        $plainText .= "Входные данные: A = " . $formData['A'] . ", B = " . $formData['B'] . ", C = " . $formData['C'] . "\r\n";
        $plainText .= "Предполагаемый результат: " . strip_tags($humanResultText) . "\r\n";
        $plainText .= "Вычисленный программой результат: " . $programResultText . "\r\n";
        $plainText .= "Вывод: " . $testConclusion . "\r\n";


        // заголовки письма
        $headers = "From: appletvin@yandex.ru\r\n"; // отправитель: Я
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // отправка письма (можно добавить проверку в качестве статуса отправки письма)
        @mail($formData['MAIL'], 'Результат тестирования', $plainText, $headers);

        $mailMessage = 'Результаты теста были автоматически отправлены на e-mail ' . $formData['MAIL'];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР6</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Лабораторная работа №6</h1>
    <p>Использование форм для передачи данных в программу PHP</p>
</header>

<div id="main_menu">
    <a href="?" class="selected">Форма теста</a>
</div>

<?php
if ($isProcessed) { // показ отчета при обработке формы
    // формирование ссылки для повторног прохождения теста
    $repeatUrl = buildRepeatUrl($formData['FIO'], $formData['GROUP'], $formData['ABOUT'], $viewMode);
    // вывод отчета с результатами
    outputReport($reportHtml, $mailMessage, $viewMode, $repeatUrl);
} else {
    // показ самой формы при отсутствии факта отправки таковой на проверку
    outputForm($formData, $viewMode);
}
?>

<footer>
    <?php
    if ($isProcessed) {
        echo 'Режим: ' . ($viewMode === 'browser' ? 'Просмотр в браузере' : 'Версия для печати') . '. ';
        echo date('d.m.Y H:i:s');
    } else {
        echo 'Форма готова к заполнению. ' . date('d.m.Y H:i:s');
    }
    ?>
</footer>

<script src="script.js"></script>

</body>
</html>