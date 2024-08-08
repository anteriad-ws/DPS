jQuery(document).ready(function ($) {
    $('#custom-password-reset-form').on('submit', function (event) {
        event.preventDefault();

        var userLogin = $('#user-login').val();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'custom_password_reset',
                user_login: userLogin,
            },
            beforeSend: function () {
                // Display loading spinner or any other UI indication
            },
            success: function (response) {
                alert(response.data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('Failed to reset password: ' + errorThrown);
            },
        });
    });
});
