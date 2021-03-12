showAlertMessage = (boxMessage, boxTitle = "Thanks") => {
    $('#alertMessageModal .modal-title').text(boxTitle);
    $('#alertMessageModal .modal-body').text(boxMessage);
    $('#alertMessageModal').modal('show');
}