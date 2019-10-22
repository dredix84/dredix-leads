/**
 * Shows an error message
 * @param messageText
 * @param messageTitle
 * @param messageType
 */
function showError(messageText = "Something went wrong. Refresh and try again.", messageTitle = 'Oops...', messageType = 'error'){
    Swal.fire({
        type: messageType,
        title: messageTitle,
        text: messageText
    });
}
