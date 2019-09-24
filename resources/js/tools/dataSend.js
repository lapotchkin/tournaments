export default function (params) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(params.selector).submit(function (e) {
        e.preventDefault();
        TRNMNT_helpers.disableButtons();

        const form = $(this);
        const formData = form.serializeArray();
        const request = {};
        for (let i = 0; i < formData.length; i += 1) {
            if (formData[i].value) {
                switch (formData[i].value) {
                    case 'on':
                        request[formData[i].name] = 1;
                        break;
                    case 'off':
                        request[formData[i].name] = 1;
                        break;
                    default:
                        request[formData[i].name] = formData[i].value;
                }
            }
            const field = form.find('[name=' + formData[i].name + ']');
            field.removeClass('is-invalid');
            field.closest('.form-group').find('.invalid-feedback').empty();
        }

        $.ajax({
            type: params.method,
            url: params.url,
            data: request,
            dataType: 'json',
            success: function (response) {
                TRNMNT_helpers.enableButtons();
                params.success(response);
            },
            error: function (response) {
                TRNMNT_helpers.onErrorAjax(response);
                for (let key in response.responseJSON.errors) {
                    const errors = response.responseJSON.errors[key];
                    const field = form.find('[name=' + key + ']');
                    field.addClass('is-invalid');
                    let message = '';
                    for (let i = 0; i < errors.length; i += 1) {
                        message += errors[i] + '<br>';
                    }
                    field.closest('.form-group').find('.invalid-feedback').html(message);
                }
            }
        });
    });
};
