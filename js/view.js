$(document).ready(function () {
    $('.question').each(function () {
        // Mendapatkan teks terenkripsi dari elemen <p>
        var encryptedText = $(this).text().trim();

        // Mendekripsi teks menggunakan Base64
        var decryptedText = atob(encryptedText);

        // Mengganti teks terenkripsi dengan teks terdekripsi
        $(this).text(decryptedText);
    });
});