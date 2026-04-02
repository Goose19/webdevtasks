function toggleMailField() {
    var block = document.getElementById('mail_block');
    var checkbox = document.getElementById('send_mail');

    if (!block || !checkbox) {
        return;
    }

    if (checkbox.checked) {
        block.style.display = 'table-row';
    } else {
        block.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    toggleMailField();
});