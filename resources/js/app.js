window.TRNMNT_sendData = function (params) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(params.selector).submit(function (e) {
        e.preventDefault();
        var form = $(this);
        var formData = form.serializeArray();
        var request = {};
        for (var i = 0; i < formData.length; i += 1) {
            if (formData[i].value) {
                request[formData[i].name] = formData[i].value;
            }
            var field = form.find('[name=' + formData[i].name + ']');
            field.removeClass('is-invalid');
            field.closest('.form-group').find('.invalid-feedback').empty();
        }

        $.ajax({
            type: params.method,
            url: params.url,
            data: request,
            dataType: 'json',
            success: params.success,
            error: function (response) {
                for (var key in response.responseJSON.errors) {
                    var errors = response.responseJSON.errors[key];
                    var field = form.find('[name=' + key + ']');
                    field.addClass('is-invalid');
                    var message = '';
                    for (var i = 0; i < errors.length; i += 1) {
                        message += errors[i] + '<br>';
                    }
                    field.closest('.form-group').find('.invalid-feedback').html(message);
                }
            }
        });
    });
};

window.TRNMNT_deleteData = function (params) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(params.selector).click(function (e) {
        e.preventDefault();

        if (confirm('Точно удалить?')) {
            $.ajax({
                type: 'delete',
                url: params.url,
                dataType: 'json',
                success: params.success,
            });
        }
    });
};
