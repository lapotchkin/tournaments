export default function (params) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', params.selector, function (e) {
        e.preventDefault();
        const $button = $(this);
        const id = $button.data('id');
        const url = id ? params.url + '/' + id : params.url;

        if (confirm('Точно удалить?')) {
            $.ajax({
                type: 'delete',
                url: url,
                dataType: 'json',
                success: function (response) {
                    TRNMNT_helpers.enableButtons();
                    params.success(response, $button);
                },
                error: TRNMNT_helpers.onErrorAjax
            });
        }
    });
};
