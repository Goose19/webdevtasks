<?php
date_default_timezone_set('Europe/Moscow');

// Проверка существования элемента таблицы (переданы ли данные)
if (!isset($_POST['element0'])) {
    // В случае False выводится ошибка
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Ошибка</title>';
    echo '<link rel="stylesheet" href="style.css"></head><body>';
    echo '<main><div class="content-shell"><div class="ttSingleRow">';
    echo '<h2>Результаты сортировки</h2>';
    echo '<p class="error-text">Массив не задан, сортировка невозможна.</p>';
    echo '</div></div></main>';
    echo '</body></html>';
    exit();
}

// Проверка числа
function arg_is_not_Num($arg)
{
    $arg = trim((string)$arg); // приведение числа к строке и удаление пробелов
    $arg = str_replace(' ', '', $arg); // замена пробелов
    $arg = str_replace(',', '.', $arg); // замена запятой

    if ($arg === '') { // Полученная строка не число (пусто)
        return true;
    }

    if (!is_numeric($arg)) { // полученная строка не число (иные символы)
        return true;
    }

    return false; // число корректное
}


// Красивый вывод числа
function formatNumber($value)
{
    // приведене числа к формату float и форматирование (до 2 знаков после запятой)
    $formatted = number_format((float)$value, 2, '.', ''); // разделитель дробной части: ".", для тысяч ничего
    $formatted = rtrim(rtrim($formatted, '0'), '.'); // Удаление лишних нулей справа (дробная часть)

    if ($formatted === '-0') { // фикс "-0" (выскакивает из-за округления отрицательных чисел)
        $formatted = '0';
    }

    return $formatted; // итоговая строка
}

// Вывод массива
function arrayToHtml($arr)
{
    $html = '<div class="array-line">'; // контейнер для всей строки массива
    for ($i = 0; $i < count($arr); $i++) { // проход по всем элементам массива с целью добавления
    // в контейнер
        $html .= '<div class="arr_element">' . $i . ': ' . formatNumber($arr[$i]) . '</div>';
    }
    // фикс css
    $html .= '<div style="clear:both;"></div></div>'; // (элементы налезают друг на друга, что портит отображение)

    return $html;
}

// Сортировка выбором
// Последовательный поиск минимального элемента в неосортированной части массива с целью
// переноса в начало
function sort_choice(&$arr)
{
    $iter = 0; // счетчик шагов
    $html = ''; // HTML блок для вывода шагов

    for ($i = 0; $i < count($arr) - 1; $i++) {
        $min = $i; // текущий элемент цикла минимальный

        // цикл поиска минимального элемента в остальной части массива
        for ($j = $i + 1; $j < count($arr); $j++) {
            $iter++; // увеличение счетчика шагов

            if ($arr[$j] < $arr[$min]) { // проверка элементов
                $min = $j; // замена минимального элемента
            }

            // вывод текущего состояния массива
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }

        // идет замена местами, если текущий минимум не равен элементу
        if ($min != $i) {
            // обмен через временную переменную
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;

            $iter++; // увеличение счетчика

            // отображение текущего состояния массива после перестановки
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }
    }
    // вывод: количества шагов и массива на каждом шаге
    return ['iterations' => $iter, 'html' => $html];
}

// Пузырьковая сортировка
// Попарное сравнение соседних элементов массива и замена их местами в случае нахождения в неправильном порядке
function sort_bubble(&$arr)
{
    $iter = 0; // счетчик шагов
    $html = ''; // HTML блок для вывода шагов

    // Проход по массиву (второй цикл меньше первого)
    for ($i = 0; $i < count($arr) - 1; $i++) {
        for ($j = 0; $j < count($arr) - 1 - $i; $j++) {
            $iter++; // увеличение счетчика

            if ($arr[$j] > $arr[$j + 1]) { // сравнение соседних элементов
                $temp = $arr[$j]; // замена через временную переменную
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
            }
            // вывод текущего состояния массива
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }
    }

    // возвращение количества шагов и каждый этап сортировки
    return ['iterations' => $iter, 'html' => $html];
}

// Сортировка Шелла
// сравниваются элементы, находящиеся на значительном расстоянии друг от друга, уменьшая это расстояние до 1
function sort_shell(&$arr)
{
    $iter = 0; // счетчик шагов
    $html = ''; // HTML блок для вывода шагов
    $n = count($arr); // размер массива
    $gap = (int)($n / 2); // начальное расстояние между элементами (пополам)

    while ($gap > 0) {
        for ($i = $gap; $i < $n; $i++) { // цикл прохода по массиву
            $temp = $arr[$i]; // сохранение текущего элемента
            $j = $i; // запоминание позиции
            
            // сдвиг элементов, если они больше текущего (цикл)
            while ($j >= $gap && $arr[$j - $gap] > $temp) {
                $iter++; // увеличение счетчика
                $arr[$j] = $arr[$j - $gap]; // сдвиг элемента вправо
                $j -= $gap; // переход дальше (назад по шагу)

                // отображение текущего состояния массива
                $html .= '<p><b>Итерация ' . $iter . '</b></p>';
                $html .= arrayToHtml($arr);
            }

            // выставление элемента на нужное место
            $arr[$j] = $temp;
            $iter++; // увеличение счетчика

            // отображение состояния массива после вставки
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }

        $gap = (int)($gap / 2); // уменьшение расстояния
    }
    // возвращение количества шагов и каждого этапа сортировки
    return ['iterations' => $iter, 'html' => $html];
}

// сортировка садового гнома (забавно)
// гном смотрит на два соседних горшка, если они в верном порядке, то он шагает вперед, иначе меняет
// местами и идет на 1 шаг назад
function sort_gnome(&$arr)
{
    $iter = 0; // счетчик шагов
    $html = ''; // HTML блок для вывода шагов
    $i = 1; // второй элемент массива
    $n = count($arr); // определение размера массива

    while ($i < $n) { // цикл прохождения до конца массива
        $iter++; // увеличение счетчика

        // гном идет вперед, если он в начале массива или если элементы стоят правильно (по возрастанию)
        if ($i == 0 || $arr[$i - 1] <= $arr[$i]) {
            $i++;
        } else { // иначе идет замена элементов местами
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            $i--; // сам гном идет назад
        }

        // отображение текущего состояния массива
        $html .= '<p><b>Итерация ' . $iter . '</b></p>';
        $html .= arrayToHtml($arr);
    }
    // возвращение количества шагов и каждого этапа сортировки
    return ['iterations' => $iter, 'html' => $html];
}

// быстрая сортировка (алгоритм)
// алгоритм определяет спорный элемент, делит массив на 2 подмассива и рекурсивно их сортирует
function quick_sort_steps(&$arr, $left, $right, &$iter, &$html)
{
    $i = $left; // левая граница подмассива
    $j = $right; // правая граница подмассива
    $pivot = $arr[(int)(($left + $right) / 2)]; // спорный элемент (середина)

    // идет поиск элемента слева, который меньше спорного, пока границы не пересеклись
    while ($i <= $j) {
        while ($arr[$i] < $pivot) {
            $i++; // сокращение левой границы
            $iter++; // увеличение счетчика шагов

            // отображение текущего состояния массива
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }

        // поиск правого элемента, который не больше спорного
        while ($arr[$j] > $pivot) {
            $j--; // расширение левой границы
            $iter++; // увеличение счетчика шагов

            // отображение текущего состояния массива
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }

        // замена элементов, если они были найдены, и границы не пересклись
        if ($i <= $j) {
            $temp = $arr[$i]; // замена элементов через временную переменную
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            // сдвиг границ к центру
            $i++;
            $j--;

            $iter++; // увеличение счетчика шагов
            
            // отображение текущего состояния массива (после обмена)
            $html .= '<p><b>Итерация ' . $iter . '</b></p>';
            $html .= arrayToHtml($arr);
        }
    }

    if ($left < $j) { // сортировка левой части
        quick_sort_steps($arr, $left, $j, $iter, $html);
    }

    if ($i < $right) { // сортировка правой части
        quick_sort_steps($arr, $i, $right, $iter, $html);
    }
}

// быстрая сортировка (отдельная функция)
function sort_quick(&$arr)
{
    $iter = 0; // счетчик шагов
    $html = ''; // HTML блок для вывода шагов

    if (count($arr) > 1) { // идет сортировка, если в массиве больше одного элемента
        quick_sort_steps($arr, 0, count($arr) - 1, $iter, $html);
    }

    // вывод количества шагов и каждого этапа сортировки
    return ['iterations' => $iter, 'html' => $html];
}

// встроенная сортировка
// применение алгоритма, предлоставляемый языком программирования
function sort_builtin(&$arr)
{
    $html = ''; // HTML блок для вывода шагов
    sort($arr); // сортировка массива по возрастанию
    
    // вывод результата
    $html .= '<p><b>Результат встроенной сортировки</b></p>';
    $html .= arrayToHtml($arr);

    // возвращение результата (шагов нет, т.к. сортировка уже прошла внутри встроенной функции)
    return ['iterations' => 0, 'html' => $html];
}

// получение названия алгоритма сортировки
function getAlgorithmName($algoritm)
{
    switch ($algoritm) {
        case '0': return 'Сортировка выбором';
        case '1': return 'Пузырьковая сортировка';
        case '2': return 'Сортировка Шелла';
        case '3': return 'Сортировка садового гнома';
        case '4': return 'Быстрая сортировка';
        case '5': return 'Встроенная функция PHP sort()';
    }

    return 'Неизвестный алгоритм';
}

// пустой массив
$arr = [];
$arrLength = 0; // начальное значение длины массива из формы
if (isset($_POST['arrLength'])) { // получение длины массива из формы
    $arrLength = (int)$_POST['arrLength'];
}

if ($arrLength <= 0) { // выводится ошибка, если длина массива не задана
    echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Ошибка</title>';
    echo '<link rel="stylesheet" href="style.css"></head><body>';
    echo '<main><div class="content-shell"><div class="ttSingleRow">';
    echo '<h2>Результаты сортировки</h2>';
    echo '<p class="error-text">Входные данные отсутствуют.</p>';
    echo '</div></div></main>';
    echo '</body></html>';
    exit();
}

// проход по всем элементам, которые пришли из формы
for ($i = 0; $i < $arrLength; $i++) {
    $value = ''; // пустое значение элемента массива
    if (isset($_POST['element' . $i])) { // берется значение элемента массива, если оно существует
    $value = $_POST['element' . $i];
    }

    if (arg_is_not_Num($value)) { // проверка на число
        echo '<!DOCTYPE html><html lang="ru"><head><meta charset="UTF-8"><title>Ошибка</title>';
        echo '<link rel="stylesheet" href="style.css"></head><body>';
        echo '<main><div class="content-shell"><div class="ttSingleRow">';
        echo '<h2>Результаты сортировки</h2>';
        echo '<p class="error-text">Элемент массива "' . htmlspecialchars($value) . '" — не число.</p>';
        echo '</div></div></main>';
        echo '</body></html>';
        exit();
    }

    // форматирование (удаление пробелов по краям и замена пробелов внутри числа и запятой)
    $value = trim((string)$value);
    $value = str_replace(' ', '', $value);
    $value = str_replace(',', '.', $value);

    $arr[] = (float)$value; // преобразование в float
}
// выбранный алгоритм
$algoritm = '0'; 

if (isset($_POST['algoritm'])) { // получение номера выбранного алгоритма
    $algoritm = $_POST['algoritm'];
}

// получение название выбранного алгоритма для вывода
$algorithmName = getAlgorithmName($algoritm);

// запуск сортировки и определение времени начала работы алгоритма
$timeStart = microtime(true);

switch ($algoritm) { // выбор нужного алгоритма сортировки
    case '0':
        $result = sort_choice($arr);
        break;
    case '1':
        $result = sort_bubble($arr);
        break;
    case '2':
        $result = sort_shell($arr);
        break;
    case '3':
        $result = sort_gnome($arr);
        break;
    case '4':
        $result = sort_quick($arr);
        break;
    case '5':
        $result = sort_builtin($arr);
        break;
    default: // по стандарту выбран этот алгоритм
        $result = sort_choice($arr);
        $algorithmName = 'Сортировка выбором';
        break;
}

// время окончания сортировки
$timeEnd = microtime(true);
$duration = $timeEnd - $timeStart; // определение времени работы

// вывод страницы отчета
echo '<!DOCTYPE html>';
echo '<html lang="ru">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<title>Результаты сортировки</title>';
echo '<link rel="stylesheet" href="style.css">';
echo '</head>';
echo '<body>';

echo '<header>';
echo '<h1>Лабораторная работа №7</h1>';
echo '<p>Результаты сортировки массива</p>';
echo '</header>';

echo '<main>';
echo '<div class="content-shell">';
echo '<div class="ttSingleRow">';

echo '<h2>' . $algorithmName . '</h2>';

echo '<p><b>Исходный массив:</b></p>';
echo arrayToHtml($arr);

echo '<p class="success-text">Массив проверен, сортировка возможна.</p>';

echo '<hr>';
echo '<h3>Ход сортировки</h3>';

echo $result['html'];

echo '<hr>';
echo '<p><b>Отсортированный массив:</b></p>';
echo arrayToHtml($arr);

echo '<p><b>Сортировка завершена, проведено ' . $result['iterations'] . ' итераций.</b></p>';
echo '<p><b>Сортировка заняла ' . number_format($duration, 6, '.', '') . ' секунд.</b></p>';

echo '<div class="repeat-wrap">';
echo '<a href="index.php" class="key reset">Вернуться назад</a>';
echo '</div>';

echo '</div>';
echo '</div>';
echo '</main>';

echo '<footer>';
echo '<p>';
echo 'Дата и время: ' . date('d.m.Y H:i:s');
echo '</p>';
echo '</footer>';

echo '</body>';
echo '</html>';
?>