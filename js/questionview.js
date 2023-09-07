$(document).ready(function () {
    $('tbody tr').each(function () {
        // Mendapatkan teks terenkripsi dari kolom "question"
        var encryptedText = $(this).find('td:eq(1)').text();
        // Mendekripsi teks menggunakan Base64
        var decryptedText = atob(encryptedText);
        // Memasukkan teks terdekripsi kembali ke dalam kolom "question"
        $(this).find('td:eq(1)').text(decryptedText);
    });

    // var decryptedText = atob($('#question_editor').val());
    // $('#question_editor').val(decryptedText);

    // $('.question').each(function () {
    //     // Mendapatkan teks terenkripsi dari elemen <p>
    //     var encryptedText = $(this).text().trim();

    //     // Mendekripsi teks menggunakan Base64
    //     var decryptedText = atob(encryptedText);

    //     // Mengganti teks terenkripsi dengan teks terdekripsi
    //     $(this).text(decryptedText);
    // });

});