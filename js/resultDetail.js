$(document).ready(function () {
    $('.enc_question').each(function () {
        var encryptedText = $(this).text();
        var decryptedText = atob(encryptedText);
        $(this).text(decryptedText);
    });

    $('.enc_answer').each(function () {
        var encryptedText = $(this).text();
        var decryptedText = atob(encryptedText);
        $(this).text(decryptedText);
    });
});
