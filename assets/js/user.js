/**
 * this file is common to all user controller actions
 */
function checkPasswordAndConfirmationMatches() {
    return $('#inputPassword').val().trim() != '' && $('#inputPassword').val() == $('#inputConfirmPassword').val()
}

