$(document).ready(function () {
    $("#secureForm").submit(function () {
        event.preventDefault();
        $("#submitButton").prop("disabled", true);
        let data = {}; // Objek untuk menyimpan data

        // Mendapatkan URL saat ini dari browser
        let currentURL = window.location.href;
        // Mencari parameter 'id' dalam URL saat ini
        let urlParams = new URLSearchParams(new URL(currentURL).search);
        let id = urlParams.get('id'); // Mengambil nilai dari parameter 'id'
        let questionid = urlParams.get('questionid'); // Mengambil nilai dari parameter 'questionid'

        // Membuat URL dinamis berdasarkan ID yang diambil
        let dynamicURL = "ajax.php?id=" + id;

        // Menambahkan parameter 'questionid' ke dynamicURL jika ada
        if (questionid) {
            dynamicURL += "&questionid=" + questionid;
        }

        // Mengambil semua input dengan kelas "question_input"
        $(".input_tag").each(function (index, element) {
            let name = $(element).attr("name");
            let value = $(element).val();

            if (name !== 'bobot_nilai') {
                value = btoa(value);
            }

            data[name] = value;
        });

        $("#secureForm")[0].reset();
        $("#secureForm input[type='text']").val('');
        $("#secureForm textarea").val('');

        $.ajax({
            url: dynamicURL,
            type: "POST",
            data: data,
            dataType: "json",
            success: function (response) {
                window.location.replace(response.url);
                // Me-refresh halaman saat ini
                // location.reload();
            },
            error: function (xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    });
});