<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ЛР7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Лабораторная работа №7</h1>
    <p>Ввод массива и сортировка различными алгоритмами</p>
</header>

<main>
    <div class="content-shell">
        <div class="form-card">
            <h2>Форма ввода массива</h2>
            <p class="helper-text">
                Введите элементы массива, выберите алгоритм сортировки и запустите обработку.
            </p>

            <form method="post" action="sort.php" target="_blank">
                <table class="lab-table">
                    <tr>
                        <td>Элементы массива</td>
                        <td>
                            <table id="elements_table" class="inner-table">
                                <tr>
                                    <td class="index-cell">0</td>
                                    <td><input type="text" name="element0"></td>
                                </tr>
                            </table>

                            <input type="hidden" id="arrLength" name="arrLength" value="1">

                            <div class="button-row">
                                <button type="button" class="key secondary" onclick="addElement()">Добавить еще один элемент</button>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>Алгоритм сортировки</td>
                        <td>
                            <select name="algoritm">
                                <option value="0">Сортировка выбором</option>
                                <option value="1">Пузырьковая сортировка</option>
                                <option value="2">Сортировка Шелла</option>
                                <option value="3">Сортировка садового гнома</option>
                                <option value="4">Быстрая сортировка</option>
                                <option value="5">Встроенная функция sort()</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" class="button-cell">
                            <button type="submit" class="key">Сортировать массив</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</main>

<footer>
    <p><?php echo 'Дата и время: ' . date('d.m.Y H:i:s');?></p>
</footer>
<script src="script.js"></script>
</body>
</html>