export default function (params) {
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
                success: function (response) {
                    TRNMNT_helpers.enableButtons();
                    params.success(response);
                },
                error: TRNMNT_helpers.onErrorAjax
            });
        }
    });
};
