// функция для установки HTML блоков внутрь элемента
function setHTML(element, txt) {
    // применение innerHTML, если браузер его поддерживает
    if (typeof element.innerHTML !== 'undefined') {
        element.innerHTML = txt;
    } else { // иначе создается элемент и вставляется внутрь новое содержимое, удалив старое
        var range = document.createRange();
        range.selectNodeContents(element);
        range.deleteContents();
        // создание HTML-фрагмента из строки
        var fragment = range.createContextualFragment(txt);
        element.appendChild(fragment); // установки нового HTML-блока
    }
}

// функция добавления нового элемента (строки в таблицу)
function addElement() {

    // получение таблицы и поле длины массива
    var table = document.getElementById('elements_table');
    var arrLengthInput = document.getElementById('arrLength');

    if (!table || !arrLengthInput) { // ничего не выводится в случае ошибки 
        return;
    }

    // перменные индекса и строки
    var index = table.rows.length; // индекс новой строки (по количеству строк)
    var row = table.insertRow(index); // создание новой строки

    // закрепление номера элемента в первой ячейке
    var cellIndex = row.insertCell(0);
    cellIndex.className = 'index-cell';
    setHTML(cellIndex, index);

    // определение поля ввода во второй ячейке
    var cellInput = row.insertCell(1);
    setHTML(cellInput, '<input type="text" name="element' + index + '">');

    // обновление длины массива
    arrLengthInput.value = table.rows.length;
}

// после загрузки страницы
document.addEventListener('DOMContentLoaded', function () {
    // получение длины массива и таблицы
    var arrLengthInput = document.getElementById('arrLength');
    var table = document.getElementById('elements_table');

    // запись текущей длины массива, если данные в наличии
    if (arrLengthInput && table) {
        arrLengthInput.value = table.rows.length;
    }
});