$(document).ready(function () {
    var decryptedText = atob($('#question_editor').val());
    $('#question_editor').val(decryptedText);
});