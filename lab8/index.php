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
    <a href="index.html" class="selected">Форма ввода</a>
</div>

<main>
    <div class="content-shell">
        <div class="form-card">
            <h2>Анализ текста</h2>
            <p class="helper-text">Введите русский или английский текст и нажмите кнопку анализа.</p>

            <form method="post" action="result.php" accept-charset="UTF-8">
                <table class="lab-table">
                    <tr>
                        <td>Текст для анализа</td>
                        <td>
                            <textarea name="data" id="data" rows="12" placeholder="Введите текст здесь..."></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="button-cell">
                            <button type="submit" class="key">Анализировать</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</main>

<footer>
    Форма готова к заполнению
</footer>

<script src="script.js"></script>

</body>
</html>