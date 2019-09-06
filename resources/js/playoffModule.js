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

        let pairId = +$pair.data('id');
        const formData = {
            round: $pair.closest('div.tournament-bracket__round').index() + 1,
            pair: $pair.index() + 1,
        };
        const teamOneId = +$pair.find('select[name=team_one_id]').val();
        const teamTwoId = +$pair.find('select[name=team_two_id]').val();
        if (teamOneId) formData.team_one_id = teamOneId;
        if (teamTwoId) formData.team_two_id = teamTwoId;

        // if (!teamOneId && !teamTwoId) return;

        TRNMNT_helpers.disableButtons();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: pairId ? 'post' : 'put',
            url: pairId ? _url.createPair + '/' + pairId : _url.createPair,
            data: JSON.stringify(formData),
            dataType: 'json',
            contentType: 'json',
            processData: false,
            success: function (response) {
                TRNMNT_helpers.enableButtons();
                TRNMNT_helpers.showNotification(response.message);
                const $link = $pair.find('.addGame');
                if (response.data.id) {
                    pairId = response.data.id;
                    $pair.data('id', pairId);
                }
                if ($link.length) {
                    $link.attr('href', $link.attr('href') + '/' + pairId + '/add');
                    $link.show();
                }
            },
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }
}());
