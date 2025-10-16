$(document).ajaxSend(function (event, jqXHR, settings) {
    if (!['get-short-urls', 'get-companies', 'get-users'
    ].some(url => settings.url.includes(url))) {
        $('#ajax-loader').fadeIn();
    }
}).ajaxComplete(function () {
    $('#ajax-loader').fadeOut();
});

function successToast(message) {
    $('#toastMessage').text(message);
    $('#toastIcon i').attr('class', 'ri-checkbox-circle-fill');
    $('#dynamicToast').removeClass().addClass('toast toast-border-success overflow-hidden mt-3');
    const toastElement = $('#dynamicToast');
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    toast.show();
}

function errorToast(message) {
    $('#toastMessage').text(message);
    $('#toastIcon i').attr('class', 'ri-error-warning-fill');
    $('#dynamicToast').removeClass().addClass('toast toast-border-danger overflow-hidden mt-3');
    const toastElement = $('#dynamicToast');
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    toast.show();
}