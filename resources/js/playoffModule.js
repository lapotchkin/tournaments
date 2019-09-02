window.TRNMNT_playoffModule = (function () {
    let _isInitialized = false;
    let _url = null;

    return {
        init: _init,
    };

    function _init(url) {
        if (_isInitialized) return;
        _isInitialized = true;
        _url = url;
        $('.savePair').on('click', _onClickSavePair);
    }

    function _onClickSavePair(event) {
        event.preventDefault();
        const $button = $(this);
        const $pair = $button.closest('li');

        const formData = {
            team_one_id: +$pair.find('select[name=team_one_id]').val(),
            team_two_id: +$pair.find('select[name=team_two_id]').val(),
            round: $pair.closest('div.tournament-bracket__round').index() + 1,
            pair: $pair.index() + 1,
        };

        TRNMNT_helpers.disableButtons();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'put',
            url: _url.createPair,
            data: JSON.stringify(formData),
            dataType: 'json',
            contentType: 'json',
            processData: false,
            success: function (response) {
                TRNMNT_helpers.enableButtons();
                TRNMNT_helpers.showNotification(response.message);
            },
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }
}());
