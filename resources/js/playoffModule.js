window.TRNMNT_playoffModule = (function () {
    let _isInitialized = false;
    let _url = null;
    let _mode = 'team';

    return {
        init: _init,
    };

    function _init(url, mode = 'team') {
        if (_isInitialized) return;
        _isInitialized = true;
        _url = url;
        _mode = mode;
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
        const competitorOneId = +$pair.find('select[name=' + _mode + '_one_id]').val();
        const competitorTwoId = +$pair.find('select[name=' + _mode + '_two_id]').val();
        if (competitorOneId) formData[_mode + '_one_id'] = competitorOneId;
        if (competitorTwoId) formData[_mode + '_two_id'] = competitorTwoId;

        // if (!competitorOneId && !competitorTwoId) return;

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
