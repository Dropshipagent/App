showAlertMessage = (boxMessage, boxTitle = "Thanks") => {
    $('#alertMessageModal .modal-title').text(boxTitle);
    $('#alertMessageModal .modal-body').html(boxMessage);
    $('#alertMessageModal').modal('show');
}