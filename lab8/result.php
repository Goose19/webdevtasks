<?php
header('Content-Type: text/html; charset=UTF-8'); // кодировка для отображения UTF-8 текста

// Проверка наличия функций
function hasMbSupport()
{
    return function_exists('mb_strlen')
        && function_exists('mb_substr')
        && function_exists('mb_strtolower')
        && function_exists('mb_strtoupper');
}

// определение длины строки
function textLength($text)
{
    if (hasMbSupport()) { // проверка доступности функции
        return mb_strlen($text, 'UTF-8');
    }
    // иначе текст перекодируется в CP1251 и так подсчитывается длина
    return strlen(iconv('UTF-8', 'CP1251//IGNORE', $text));
}

// получение одного символа строки
function getCharAt($text, $index)
{
    if (hasMbSupport()) { // проверка доступности функции
        return mb_substr($text, $index, 1, 'UTF-8'); // получение одного символа строки
    }

    // иначе идет перевод в однобайтовую кодировку
    $converted = iconv('UTF-8', 'CP1251//IGNORE', $text);
    // далее используется обычная функция получения одного символа строки
    $char = substr($converted, $index, 1);

    // возвращается символ в utf-8
    return iconv('CP1251', 'UTF-8//IGNORE', $char);
}

// Перевод строки в нижний регистр
function toLowerText($text)
{
    if (hasMbSupport()) { // проверка доступности функции
        return mb_strtolower($text, 'UTF-8'); // перевод строки на нижний регистр
    }

    // идет перевод строки в cp1251 далее переводится на нижний регистр, откуда идет перевод в utf-8
    return iconv('CP1251', 'UTF-8//IGNORE', strtolower(iconv('UTF-8', 'CP1251//IGNORE', $text)));
}

// Перевод строки в верхний регистр
function toUpperText($text)
{
    if (hasMbSupport()) { // проверка доступности функции
        return mb_strtoupper($text, 'UTF-8'); // перевод строки в верхний регистрт
    }

    // идет перевод строки в cp1251, далее переводится на верхний регистр, откуда идет перевод в utf-8
    return iconv('CP1251', 'UTF-8//IGNORE', strtoupper(iconv('UTF-8', 'CP1251//IGNORE', $text)));
}

// проверка символа: является ли он буквой
function isLetter($char)
{
    // возвращается результат проверки на кириллицу и латиницу
    return preg_match('/[A-Za-zА-Яа-яЁё]/u', $char) === 1;
}

// проверка символа: является ли он цифрой
function isDigitSymbol($char)
{
    // возвращается результат проверки на цифры
    return preg_match('/[0-9]/u', $char) === 1;
}

// проверка символа: является ли он знаком препинания
function isPunctuationSymbol($char)
{
    // возвращается результат проверки на знак
    return preg_match('/[.,!?:;"\'()\[\]\{\}\-—…«»]/u', $char) === 1;
}

// проверка символа: является ли он пробелом/табуляцией/переносом строки
function isSpaceSymbol($char)
{
    // возвращается проверка на пробел/табуляцией/переносом строки
    return preg_match('/\s/u', $char) === 1;
}

// проверка символа: является ли он заглавной буквой
function isUpperLetter($char)
{
    if (!isLetter($char)) { // проверка, является ли этот символ буквой
        return false;
    }

    // потом возвращается проверка на верхний регистр (дополнительно проверяется на нижний регистр через "не")
    return $char === toUpperText($char) && $char !== toLowerText($char);
}

// проверка символа: является ли он строчной буквой
function isLowerLetter($char)
{
    if (!isLetter($char)) { // проверка, является ли этот символ буквой
        return false;
    }

    // потом возвращается проверка на нижний регистр (дополнительно проверяется на верхний регистр)
    return $char === toLowerText($char) && $char !== toUpperText($char);
}

// функция подсчета количества вхождений символов без учета регистра
function testSymbs($text)
{
    $symbs = []; // символьный массив
    $length = textLength($text); // перменная длины текста

    for ($i = 0; $i < $length; $i++) { // функция прохода по каждому символу в тексте
        $char = getCharAt($text, $i); // определение индекса символа (для UTF-8)
        $char = toLowerText($char); // приведение текста к нижнему регистру

        if (isset($symbs[$char])) { // проверка наличия этого символа
            $symbs[$char]++; // увеличивается счетчик (проход к след символу)
        } else {
            $symbs[$char] = 1; // иначе значение в массиве становится 1
        }
    }

    // сортировка массива по символам в алфавитном порядке
    ksort($symbs);
    return $symbs; // возвращение массива в виде символ -> количество
}

// Подсчет слов и количества их вхождений
function testWords($text)
{
    $words = []; // массив слов
    $matches = []; // массив найденных слов

    // поиск слов с поддержкой UTF-8, + - одно или более вхождений подряд
    preg_match_all('/[A-Za-zА-Яа-яЁё0-9]+/u', $text, $matches);

    if (!empty($matches[0])) { // проверка наличия найденных слов
        foreach ($matches[0] as $word) { // сохранение в массив и прохождение по каждому элементу
            $word = toLowerText($word); // приведение слова к нижнему регистру

            // проверка: встречалось ли это слов
            if (isset($words[$word])) {
                $words[$word]++; // увеличивается счетчик (переход к новому слову)
            } else {
                $words[$word] = 1; // иначе значение в массиве становится 1
            }
        }
    }

    // далее идет сортировка по словам в алфавитном порядке
    ksort($words);
    return $words; // возвращается массив в виде: слово -> количество
}

// Формирование таблицы анализа
function buildAnalysis($text)
{
    // определение длины текста
    $length = textLength($text);

    // инициация счетчиков
    $lettersAmount = 0; // количество букв
    $lowerLettersAmount = 0; // количество строчных букв
    $upperLettersAmount = 0; // количество заглавных букв
    $punctuationAmount = 0; // количество знаков препинания
    $digitsAmount = 0; // количество цифр

    for ($i = 0; $i < $length; $i++) { // проход по каждому символу текста
        $char = getCharAt($text, $i); // получение символа по индексу

        if (isLetter($char)) { // проверка: является ли этот символ буквой
            $lettersAmount++; // увеличение количества букв

            if (isLowerLetter($char)) { // проверка на строчную букву
                $lowerLettersAmount++;
            }

            if (isUpperLetter($char)) { // проверка на заглавную букву
                $upperLettersAmount++;
            }
        }

        if (isPunctuationSymbol($char)) { // проверка на знак препинания
            $punctuationAmount++;
        }

        if (isDigitSymbol($char)) { // проверка на цифру
            $digitsAmount++;
        }
    }

    // массив слов
    $words = testWords($text);
    $symbs = testSymbs($text); // массив символов

    // создание страницы со статистикой
    $html = '';
    $html .= '<table class="analysis-table">';
    $html .= '<tr><td>Количество символов (включая пробелы)</td><td>' . $length . '</td></tr>';
    $html .= '<tr><td>Количество букв</td><td>' . $lettersAmount . '</td></tr>';
    $html .= '<tr><td>Количество строчных букв</td><td>' . $lowerLettersAmount . '</td></tr>';
    $html .= '<tr><td>Количество заглавных букв</td><td>' . $upperLettersAmount . '</td></tr>';
    $html .= '<tr><td>Количество знаков препинания</td><td>' . $punctuationAmount . '</td></tr>';
    $html .= '<tr><td>Количество цифр</td><td>' . $digitsAmount . '</td></tr>';
    $html .= '<tr><td>Количество слов</td><td>' . count($words) . '</td></tr>';
    $html .= '</table>';

    $html .= '<div class="ttSingleRow">';
    $html .= '<h2>Количество вхождений каждого символа</h2>';

    $html .= '<table class="analysis-table">';
    $html .= '<tr><td><b>Символ</b></td><td><b>Количество</b></td></tr>';

    // проход по массиву слов
    foreach ($symbs as $symbol => $count) {
        // закрепление символа в другой переменной для отображения на странице
        $showSymbol = $symbol;

        // замена служебных символов на слова
        if ($symbol === ' ') {
            $showSymbol = '[пробел]';
        } elseif ($symbol === "\n") {
            $showSymbol = '[перенос строки]';
        } elseif ($symbol === "\r") {
            $showSymbol = '[возврат каретки]';
        } elseif ($symbol === "\t") {
            $showSymbol = '[табуляция]';
        }

        // построение страницы
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($showSymbol, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td>';
        $html .= '<td>' . $count . '</td>';
        $html .= '</tr>';
    }

    $html .= '</table>';
    $html .= '</div>';

    $html .= '<div class="ttSingleRow">';
    $html .= '<h2>Список всех слов и количество их вхождений</h2>';

    $html .= '<table class="analysis-table">';
    $html .= '<tr><td><b>Слово</b></td><td><b>Количество</b></td></tr>';

    // проверка нахождения слов в тексте
    if (count($words) > 0) {
        // проход по каждому слову в тексте со счетчиком
        foreach ($words as $word => $count) {
            $html .= '<tr>';
            // преобразование спецсимволов в объекты HTML
            $html .= '<td>' . htmlspecialchars($word, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</td>';
            $html .= '<td>' . $count . '</td>';
            $html .= '</tr>';
        }
    } else { // вывод ошибки в случае нахождения ошибок
        $html .= '<tr><td colspan="2">Слова в тексте не найдены</td></tr>';
    }

    $html .= '</table>';
    $html .= '</div>';

    return $html;
}

// Получение текста из формы
// проверка, есть ли data в запросе
if (isset($_POST['data'])) {
    $sourceText = (string)$_POST['data'];
} else {
    $sourceText = '';
}

// проверка на пустой текст с учетом удаления пробелов по краям
$hasText = trim($sourceText) !== '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР8</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Лабораторная работа №8</h1>
    <p>Основы работы со строковыми данными в PHP. Кодировка. Анализ текста</p>
</header>

<div id="main_menu">
    <a href="index.html" class="selected">Другой анализ</a>
</div>

<main>
    <div class="content-shell">
        <div class="ttSingleRow">
            <h2>Исходный текст</h2>

            <?php
            if ($hasText) {
                echo '<div class="src_text">' . $sourceText . '</div>';
            } else {
                echo '<div class="src_error">Нет текста для анализа</div>';
            }
            ?>
        </div>

        <?php
        if ($hasText) {
            echo '<div class="ttSingleRow">';
            echo '<h2>Информация о тексте</h2>';
            echo buildAnalysis($sourceText);
            echo '</div>';
        }
        ?>

        <div class="repeat-wrap">
            <a href="index.html" class="key reset">Другой анализ</a>
        </div>
    </div>
</main>

<footer>
    Результат анализа текста
</footer>

<script src="script.js"></script>

</body>
</html>