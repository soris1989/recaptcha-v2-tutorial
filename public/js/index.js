$(document).ready(function (e) {
    $("#frmContact").on("submit", function (e) {
        e.preventDefault();
        $("#mail-status").hide();
        $("#send-message").hide();
        $("#loader-icon").show();
        $.ajax({
            url: "./api/contact.php",
            type: "POST",
            dataType: "json",
            data: {
                name: $('input[name="name"]').val(),
                email: $('input[name="email"]').val(),
                phone: $('input[name="phone"]').val(),
                content: $('textarea[name="content"]').val(),
                "g-recaptcha-response": $('textarea[id="g-recaptcha-response"]').val(),
            },
            success: function (response) {
                console.log("response success: ", response);
                $("#mail-status").show();
                $("#loader-icon").hide();
                if (response.type == "error") {
                    $("#send-message").show();
                    $("#mail-status").attr("class", "error");
                } else if (response.type == "message") {
                    $("#send-message").hide();
                    $("#mail-status").attr("class", "success");
                }
                $("#mail-status").html(response.text);
            },
            error: function () {},
        });
    });
});
