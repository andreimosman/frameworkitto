
function disableSignupButton() {
    disableButtons([
        '#signupButton',
    ])
}

function enableSignupButton() {
    enableButtons([
        '#signupButton',
    ])
}

function createAccount() {
    removeNotification()
    disableSignupButton()

    if( !checkPasswordAndConfirmationMatches() ) {
        showNotification('Password and confirmation mismatch!', 'danger')
        enableSignupButton()
        return false
    }

    $.ajax({
        type: 'POST',
        url: baseURL + "/user/signup",
        data: $('#formSignUp').serialize(),
        success: function(data) {
            // window.location.href = data.url;
            showNotification(data.message,"success")
            enableSignupButton()
        },
        error: function(error) {
            var data = error.responseJSON
            if( !data ) data = {}
            showNotification(data.message,"danger")
            enableSignupButton()
        }
    })


}


$(function() {
    $('#formSignUp').on('submit', function(e) {
        e.preventDefault()

        createAccount()
    })
})