window.TRNMNT_playoffModule = (function () {
    let _isInitialized = false;
    let _url = null;
    let _templates = null;

    return {
        init: _init,
    };

    function _init(url, templates) {
        if (_isInitialized) return;
        _isInitialized = true;
        _url = url;
        _templates = templates;

        TRNMNT_sendData({
            selector: '#player-add',
            method: 'put',
            url: _url.addPlayer,
            success: onSuccessAddPlayer
        });

        TRNMNT_deleteData({
            selector: '.delete-player',
            url: _url.deletePlayer,
            success: onSuccessDeletePlayer
        });

        $(document).on('click', '.captain-toggle button', onClickCaptainToggle);
    }

    function onSuccessAddPlayer(response) {
        const $select = $('#player_id');
        const playerId = $select.val();
        const $option = $('#player_id option[value=' + playerId + ']');
        const newPlayerData = getPlayerDataFromOption($option);
        console.log(newPlayerData);
        const $list = $('#team-players tbody');
        const $item = $(_templates.row.format({
            id: playerId,
            tag: newPlayerData[0],
            name: newPlayerData[1] ? newPlayerData[1] : '',
        }));
        const $items = $list.find('tr');
        if ($items.length) {
            $items.each(function (index, element) {
                const $element = $(element);
                const playerData = getPlayerDataFromLi($element.closest('tr').find('td').eq(1));
                console.log(playerData);
                if (playerData[0].toLowerCase() > newPlayerData[0].toLowerCase()) {
                    $element.before($item);
                    return false;
                }
                if ($items.length === index + 1) {
                    $element.after($item);
                    return false;
                }
            });
        } else {
            window.location.href = _url.addPlayerRedirect;
        }
        $select.val('');
        $option.remove();
    }

    function onSuccessDeletePlayer(response, $button) {
        const $item = $button.closest('tr');
        const playerId = $item.data('id');
        const removedPlayerData = getPlayerDataFromLi($item.find('td').eq(1));
        const $select = $('#player_id');
        const option = '<option value="' + playerId + '">'
            + removedPlayerData[0]
            + (removedPlayerData[1] ? '(' + removedPlayerData[1] + ')' : '')
            + '</option>';
        const $option = $(option);
        $select.find('option').each(function (index, element) {
            if (index > 0) {
                const $element = $(element);
                const playerData = getPlayerDataFromOption($element);
                if (playerData[0].toLowerCase() > removedPlayerData[0].toLowerCase()) {
                    $element.before($option);
                    return false;
                }
            }
        });
        $item.remove();
    }

    function onClickCaptainToggle(event) {
        event.preventDefault();
        const $button = $(this);
        if ($button.hasClass('active')) {
            return;
        }

        const $siblings = $button.siblings();
        const playerId = $button.closest('tr').data('id');
        const isCaptain = +$button.data('captain');

        if (isCaptain === 1) {
            $button.closest('tbody')
                .find('.captain-toggle button[data-captain=1]')
                .each(function (index, element) {
                    const $captainButton = $(element);
                    if ($captainButton.hasClass('active')) {
                        $captainButton.removeClass('active');
                        $captainButton.siblings('button[data-captain=0]').addClass('active');
                        createCaptainBadge($captainButton, 0);
                    }
                });
        }

        createCaptainBadge($button, isCaptain);
        $button.addClass('active');
        $siblings.removeClass('active');

        TRNMNT_helpers.disableButtons();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: _url.updatePlayer + '/' + playerId,
            data: 'isCaptain=' + isCaptain,
            processData: false,
            success: TRNMNT_helpers.enableButtons,
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }

    function createCaptainBadge($button, isCaptain) {
        const $badgeCell = $button.closest('tr').find('td').eq(0);
        $badgeCell.empty();
        switch (isCaptain) {
            case 1:
                $badgeCell.html('<span class="badge badge-success">C</span>')
                break;
            case 2:
                $badgeCell.html('<span class="badge badge-warning">A</span>')
                break;
        }
    }

    function getPlayerDataFromOption($option) {
        const playerData = $option.text().split(' (');
        for (let i = 0; i < playerData.length; i += 1) {
            playerData[i] = playerData[i].trim().replace(')', '');
        }
        return playerData;
    }

    function getPlayerDataFromLi($item) {
        // const playerData;
        const html = $item.html();
        const tag = html.match(/<a[\s\w=\":\/\-\.]+>([А-Яа-яЁё\w\s\-\_]+)<\/a>/);
        const name = html.match(/<small>([А-Яа-яЁё\w\s]+)/);
        return [
            tag && tag[1] ? tag[1] : '',
            name && name[1] ? name[1] : '',
        ];
    }
})();
