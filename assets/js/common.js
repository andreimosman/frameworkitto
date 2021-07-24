/**
 * Commom js to entire application
 */

 function showButton(locator) {
    $(locator).removeClass('display-none');
}

function hideButton(locator) {
    $(locator).addClass('display-none');
}

function disableButton(locator) {
    console.log("DISABLE BUTTON: " + locator)
    $(locator).prop('disabled', true);
}

function enableButton(locator) {
    $(locator).prop('disabled', false);
}

function disableButtons(listOfButtonLocators) {
    listOfButtonLocators.map(function(button) {
        disableButton(button)
    })
}

function enableButtons(listOfButtonLocators) {
    listOfButtonLocators.map(function(button) {
        enableButton(button)
    })
}

function showNotification(message, type, target='#userNotificationArea') {
    $(target).removeClass('display-none')
    $(target).addClass('alert').addClass('alert-'+type)
    $(target).html(message)
}

var colorSchemes = ['primary','secondary','success','danger','warning','info','light','dark']

function removeNotification(target='#userNotificationArea'){
    $(target).addClass('display-none')
    colorSchemes.map(function(color) {
        $(target).removeClass('alert-'+color)
    })
    $(target).html('')

}

