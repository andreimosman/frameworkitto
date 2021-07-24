/**
 * This is loaded automatically to view /user/login by function Controller::configureScriptsAndStylesBasedOnRoute()
 */



function disableLoginButton() {
    disableButtons([
        '#loginButton',
    ])
}

function enableLoginButton() {
    enableButtons([
        '#loginButton',
    ])
}

$(function() {
    $('#formLogin').on('submit', function(e) {
        e.preventDefault()

        disableLoginButton()

        $.ajax({
            type: 'POST',
            url: baseURL + "/user/login",
            data: $('#formLogin').serialize(),
            success: function(data) {
                window.location.href = data.url;
            },
            error: function(error) {

                // Default error message. (http code 401)
                var errorMessage = 'Error login in. Please check your username and password then try again.'
                var notificationType = 'danger'

                if( error.status == 403 ) { // Acount is not active. Please check your email
                    errorMessage = 'Your account is not active. Please check your email to activate your account.'
                    notificationType = 'warning'
                }

                showNotification(errorMessage,notificationType)
                enableLoginButton()

            }
        })

    })
})
