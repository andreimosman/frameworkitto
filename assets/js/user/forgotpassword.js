function disableSignupButton() {
    disableButtons([
        '#recoverButton',
    ])
}

function enableSignupButton() {
    enableButtons([
        '#recoverButton',
    ])
}

function recoverPassword() {
    removeNotification()
    disableSignupButton()

    $.ajax({
        type: 'POST',
        url: baseURL + "/user/forgotpassword",
        data: $('#formForgotPassword').serialize(),
        success: function(data) {
            // window.location.href = data.url;
            showNotification(data.message,"success")
            enableSignupButton()
        },
        error: function(error) {
            showNotification("Error recovering password. Please check if e-mail address is correct or try to create a new account.", "warning")
            enableSignupButton()
        }
    })

}


$(function() {
    $('#formForgotPassword').on('submit', function(e) {
        e.preventDefault()
        recoverPassword()
    })
})
