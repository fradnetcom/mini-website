$('.offer-list .btn-remove').on('click', function (event) {
    event.preventDefault();

    var offerList = $('.offer-list'),
        btnRemove = $(this);

    $(document).find('.input-error-message').remove();
    $(document).find('.alert').remove();

    $.ajax({
        url: Routing.generate('offer_remove'),
        type: "post",
        data: {
            'id': $(this).attr('data-id')
        },
        dataType: 'json'
    }).done(function (data) {
        switch (data.status) {
            case 'success':
                offerList.before('<div class="alert alert-success">Oferta została usunięta</div>');
                btnRemove.parent().parent().addClass('hide');
                break;
            case 'error':
                offerList.before('<div class="alert alert-danger">' + data.errors['message'] + '</div>');
                break;
        }
    }).fail(function () {
        offerList.before('<div class="alert alert-danger">Wystąpił błąd, spróbuj ponownie!</div>');
    });

    return false;
});