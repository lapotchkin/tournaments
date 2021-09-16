window.TRNMNT_gameFormModule = (function () {
    let _isInitialized = false;
    let _$eaGames = null;
    let _$getGames = null;
    let _$resetGame = null;
    let _$homePlayers = null;
    let _$awayPlayers = null;
    let _$gameForm = null;
    let _gameToSave = null;
    let _url = null;
    let _pairId = null;
    let _gameId = null;
    let _positions = null;
    let _players = null;
    const _templates = {
        game: `
            <tr>
                <td>#{date}</td>
                <td class="text-end">#{home_team}</td>
                <td class="text-end">
                    <span class="badge bg-primary rounded-pill">#{home_score}</span>
                </td>
                <td class="text-center">:</td>
                <td>
                    <span class="badge bg-primary rounded-pill">#{away_score}</span>
                </td>
                <td>#{away_team}</td>
                <td class="text-end">
                    <button type="button" class="result-fill btn btn-primary btn-sm">Заполнить</button>
                    <button type="button" class="result-concat btn btn-success btn-sm" disabled>Присоединить</button>
                </td>
            </tr>`,
        player: `
            <tr data-id="#{id}">
                <td>#{tag} <small class="text-muted">#{name}</small></td>
                <td class="text-center">#{position}</td>
                <td class="text-center">#{goals}</td>
                <td class="text-center">#{assists}</td>
                <td class="text-center text-nowrap">#{stars}</td>
                <td></td>
            </tr>`,
        playerForm: `
            <tr data-id="#{id}" style="#{style}">
                <td>#{player}</td>
                <td class="text-center">#{position}</td>
                <td><input type="text" class="text-end form-control" name="goals" value="#{goals}"></td>
                <td><input type="text" class="text-end form-control" name="assists" value="#{assists}"></td>
                <td>#{stars}</td>
                <td class="text-nowrap">#{button}</td>
            </tr>
            `,
    };

    return {
        init: _init
    };

    /**
     * @param url
     * @param protocols
     * @param players
     * @param positions
     * @param matchId
     * @private
     */
    function _init(url, protocols, players, positions, matchId) {
        if (_isInitialized) return;
        const parsedUrl = TRNMNT_helpers.parseUrl();
        _isInitialized = true;
        _url = url;
        _positions = positions;
        _players = players;
        _pairId = parsedUrl.segments[2] === 'playoff' ? +parsedUrl.segments[4] : null;
        if (parsedUrl.segments[2] === 'playoff') {
            _gameId = parsedUrl.segments[5] === 'add' ? null : +TRNMNT_helpers.parseUrl().segments[5];
        } else {
            _gameId = +TRNMNT_helpers.parseUrl().segments[4];
        }
        console.log('_pairId', _pairId);
        console.log('_gameId', _gameId);
        _$eaGames = $('#eaGames');
        _$getGames = $('#getGames');
        _$resetGame = $('#resetGame');
        _$homePlayers = $('#homePlayers').find('tbody');
        _$awayPlayers = $('#awayPlayers').find('tbody');
        _$gameForm = $('#game-form');

        _$gameForm.on('submit', _onSubmitGame);
        _$getGames.on('click', _onClickGetGames);
        _$resetGame.on('click', _onClickResetGames);

        let $homeAddForm = null;
        let $awayAddForm = null;
        if (!matchId && _players) {
            $homeAddForm = _createProtocolAddForm(_$homePlayers, _players.home);
            $awayAddForm = _createProtocolAddForm(_$awayPlayers, _players.away);
        }
        for (let side in protocols) {
            for (let player of protocols[side]) {
                const $tbody = side === 'home' ? _$homePlayers : _$awayPlayers;
                if (matchId) {
                    // console.log(player);
                    $tbody.append(_templates.player.format({
                        tag: player.player_tag,
                        name: player.name,
                        position: _getPlayerBadge(player.position_id, player.position),
                        goals: !player.isGoalie ? player.goals : '—',
                        assists: !player.isGoalie ? player.assists : '—',
                        id: player.player_id,
                        stars: _getStars(player.star),
                    }));
                } else {
                    _onSuccessAddProtocol({
                        player_id: player.player_id,
                        position_id: player.position_id,
                        goals: player.goals,
                        assists: player.assists,
                        star: player.star,
                    }, player.id, side === 'home' ? $homeAddForm : $awayAddForm);
                }
            }
        }
    }

    /**
     * @param $form
     * @param players
     * @returns {jQuery.fn.init|jQuery|HTMLElement}
     * @private
     */
    function _createProtocolAddForm($form, players) {
        let playersSelect = '';
        players.forEach(function (player) {
            playersSelect += `<option value="${player.id}">${player.tag} (${player.name})</option>`;
        });
        const $row = $(_templates.playerForm.format({
            id: '',
            player: `<select class="form-select" name="player_id">${playersSelect}</select>`,
            position: _getPositionSelect(),
            stars: _getStarsSelect(),
            goals: '',
            assists: '',
            button: '<button class="btn btn-primary" type="submit"><i class="fas fa-user-plus"></i></button>',
            style: 'border-top: 3px solid red;',
        }));
        $row.find('button').on('click', _onClickAddProtocol);
        $form.append($row);

        return $row;
    }

    /**
     * @param playerPosition
     * @returns {string}
     * @private
     */
    function _getPositionSelect(playerPosition) {
        console.log(playerPosition);
        let positionSelect = '';
        _positions.forEach(function (position) {
            const selected = playerPosition === position.id ? 'selected' : '';
            positionSelect += `<option value="${position.id}" ${selected}>${position.short_title}</option>`;
        });
        return `<select class="form-select" name="position_id">${positionSelect}</select>`;
    }

    /**
     * @param {string} [playerStar]
     * @returns {string}
     * @private
     */
    function _getStarsSelect(playerStar) {
        const stars = ['—', '1', '2', '3'];
        let starsSelect = '';
        for (let i = 0; i < stars.length; i += 1) {
            const selected = playerStar === i ? 'selected' : '';
            starsSelect += `<option value="${i}" ${selected}>${stars[i]}</option>`;
        }
        return `<select class="form-select" name="star">${starsSelect}</select>`;
    }

    /**
     * @param event
     * @private
     */
    function _onClickAddProtocol(event) {
        event.preventDefault();
        const $row = $(this).closest('tr');
        const formData = {
            game_id: _gameId,
            team_id: +$row.closest('table').data('id'),
            player_id: +$row.find('select[name=player_id]').val(),
            position_id: +$row.find('select[name=position_id]').val(),
            goals: +$row.find('input[name=goals]').val(),
            assists: +$row.find('input[name=assists]').val(),
            star: +$row.find('select[name=star]').val(),
        };
        formData.isGoalie = formData.position_id === 0 ? 1 : 0;

        TRNMNT_helpers.disableButtons();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'put',
            url: _url.protocol,
            dataType: 'json',
            contentType: 'json',
            processData: false,
            data: JSON.stringify(formData),
            success: response => {
                TRNMNT_helpers.enableButtons();
                _onSuccessAddProtocol(formData, response.data.id, $row);
                $row.find('select[name=position_id]').val('0');
                $row.find('input[name=goals]').val('');
                $row.find('input[name=assists]').val('');
                $row.find('select[name=star]').val('0');
            },
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }

    /**
     * @param formData
     * @param protocolId
     * @param $row
     * @private
     */
    function _onSuccessAddProtocol(formData, protocolId, $row) {
        const $playerOption = $row.find('select[name=player_id] option[value=' + formData.player_id + ']');
        const $protocolRow = $(_templates.playerForm.format({
            id: protocolId,
            player: $playerOption.text().replace('(', '<small class="text-muted">').replace(')', '</small>'),
            position: _getPositionSelect(formData.position_id),
            goals: formData.goals !== null ? formData.goals : '',
            assists: formData.assists !== null ? formData.assists : '',
            stars: _getStarsSelect(formData.star),
            button: '<button class="btn btn-primary"><i class="fas fa-edit"></i></button> <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>',
        }));
        $row.closest('tbody').prepend($protocolRow);
        $protocolRow.find('button.btn-primary').on('click', _onClickEditProtocol);
        $protocolRow.find('button.btn-danger').on('click', _onClickRemoveProtocol);
        $playerOption.remove();
        const $playerOptions = $row.find('select[name=player_id] option');
        if (!$playerOptions.length) {
            $row.hide();
        }
    }

    /**
     * @param event
     * @private
     */
    function _onClickRemoveProtocol(event) {
        event.preventDefault();
        if (confirm('Удалить протокол')) {
            const $row = $(this).closest('tr');
            const playerTag = $($row.find('td')[0]).text();
            for (let side in _players) {
                for (let player of _players[side]) {
                    if (playerTag !== player.tag) continue;
                    const $playerSelect = $row.closest('table')
                        .find('select[name=player_id]');
                    $playerSelect.append(`<option value="${player.id}">${player.tag}</option>`);
                    $playerSelect.closest('tr').show();
                }
            }
            $row.remove();
            TRNMNT_helpers.disableButtons();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'delete',
                url: _url.protocol + '/' + $row.data('id'),
                dataType: 'json',
                contentType: 'json',
                processData: false,
                success: response => {
                    TRNMNT_helpers.enableButtons();
                },
                error: TRNMNT_helpers.onErrorAjax,
                context: TRNMNT_helpers
            });
        }
    }

    /**
     * @param event
     * @private
     */
    function _onClickEditProtocol(event) {
        event.preventDefault();
        const $row = $(this).closest('tr');
        const formData = {
            position_id: +$row.find('select[name=position_id]').val(),
            goals: +$row.find('input[name=goals]').val(),
            assists: +$row.find('input[name=assists]').val(),
            star: +$row.find('select[name=star]').val(),
        };
        formData.isGoalie = formData.position_id === 0 ? 1 : 0;
        TRNMNT_helpers.disableButtons();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: _url.protocol + '/' + $row.data('id'),
            dataType: 'json',
            contentType: 'json',
            data: JSON.stringify(formData),
            processData: false,
            success: response => {
                TRNMNT_helpers.enableButtons();
            },
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }

    /**
     * @param star
     * @returns {string}
     * @private
     */
    function _getStars(star) {
        let stars = '';
        for (let i = 0; i < star; i += 1) {
            stars += '<i class="fas fa-star text-danger"></i>';
        }
        return stars
    }

    /**
     * @param event
     * @private
     */
    function _onSubmitGame(event) {
        event.preventDefault();
        TRNMNT_helpers.disableButtons();

        console.log(_gameToSave);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: _pairId && !_gameId ? 'put' : 'post',
            url: _url.saveGame,
            dataType: 'json',
            contentType: 'json',
            processData: false,
            data: _gameToSave ? _getFormDataEA() : _getFormData(),
            success: _onSuccessSubmitGame,
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }

    /**
     * @returns {string}
     * @private
     */
    function _getFormDataEA() {
        for (let field in _gameToSave.game) {
            const $field = $(`#${field}`);
            if ($field.prop('readonly') === false) {
                let val = $field.val();
                if (field.indexOf('_percent') !== -1) {
                    val = val ? parseFloat(val.replace(',', '.')) : 0;
                }
                _gameToSave.game[field] = val;
            }
        }

        const players = {};
        _$homePlayers.find('select').each(setPlayer);
        _$awayPlayers.find('select').each(setPlayer);

        for (let side in _gameToSave.players) {
            for (let player of _gameToSave.players[side]) {
                player.star = players[player.player_id];
            }
        }

        return JSON.stringify(_gameToSave);

        function setPlayer(index, element) {
            const $element = $(element);
            const playerId = $element.closest('tr').data('id');
            players[playerId] = +$element.val();
        }
    }

    /**
     * @returns {string}
     * @private
     */
    function _getFormData() {
        const formData = _$gameForm.serializeArray();
        const request = {
            game: {}
        };
        for (let i = 0; i < formData.length; i += 1) {
            if (formData[i].value) {
                let val = formData[i].value;
                if (formData[i].name.indexOf('_percent') !== -1) {
                    val = parseFloat(val.replace(',', '.'));
                }
                request.game[formData[i].name] = val;
            }
        }
        _$gameForm.find('input[type=checkbox]').each(function (index, element) {
            request.game[element.id] = +$(element).prop('checked');
        });

        return JSON.stringify(request);
    }

    /**
     * @param response
     * @private
     */
    function _onSuccessSubmitGame(response) {
        const redirect = $('#redirectToProtocol').val() === 'true';

        if (response.data.id) {
            const url = window.location.href.replace('add', response.data.id);
            window.location.href = redirect ? url : url + '/edit';
            return;
        }

        if (redirect) {
            window.location.href = window.location.href.replace('/edit', '');
            return;
        }

        TRNMNT_helpers.enableButtons();
        TRNMNT_helpers.showNotification(response.message);

        if (_gameToSave) {
            _gameToSave = null;
        }
    }

    /**
     * @private
     */
    function _onClickGetGames() {
        TRNMNT_helpers.disableButtons();
        _$eaGames.empty();
        $.ajax({
            url: _url.lastGames,
            success: _onSuccessGetGames,
            error: TRNMNT_helpers.onErrorAjax,
            context: TRNMNT_helpers
        });
    }

    /**
     * @private
     */
    function _onClickResetGames() {
        if (confirm('На самом деле хотите обнулить протокол?')) {
            _$gameForm.find('input').each(function (index, element) {
                const $field = $(element);
                if (['checkbox', 'radio'].indexOf($field.attr('type')) !== -1) {
                    $field.prop('checked', false);
                } else if (['submit'].indexOf($field.attr('type')) !== -1) {
                    //do nothing
                } else {
                    $field.val('');
                    if (element.id !== 'playedAt') $field.prop('readonly', false);
                }
            });

            if (_gameToSave) {
                _gameToSave = null;
            } else {
                TRNMNT_helpers.disableButtons();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: _url.resetGame,
                    dataType: 'json',
                    contentType: 'json',
                    processData: false,
                    success: _onSuccessSubmitGame,
                    error: TRNMNT_helpers.onErrorAjax,
                    context: TRNMNT_helpers
                });
            }
            _$homePlayers.empty();
            _$awayPlayers.empty();
            _$resetGame.addClass('d-none');
            _$eaGames.find('button').prop('disabled', false);
        }
    }

    /**
     * @param response
     * @private
     */
    function _onSuccessGetGames(response) {
        TRNMNT_helpers.enableButtons();

        const $table = $('<table class="table table-sm table-striped mt-3"/>');
        _$eaGames.append($table);

        const $tbody = $('<tbody/>');
        $table.append($tbody);

        for (let gameId in response.data) {
            const game = response.data[gameId].game;
            let date = new Date(game.playedAt.replace(' ', 'T'));
            date.setTime(date.getTime() + date.getTimezoneOffset() * 60 * 1000);
            console.log(game.playedAt, date);
            const $row = $(_templates.game.format({
                date: date.getFullDate(),
                home_team: game.home_team,
                away_team: game.away_team,
                home_score: game.home_score,
                away_score: game.away_score,
            }));
            $row.find('button.result-fill').click(function () {
                const $this = $(this);
                _$resetGame.removeClass('d-none');
                _$eaGames.find('button.result-fill, button.result-concat').prop('disabled', false);
                $this.prop('disabled', true);
                $this.siblings().prop('disabled', true);
                _fillGameProtocol(response.data[gameId])
            });
            $row.find('button.result-concat').click(function () {
                const $this = $(this);
                $this.prop('disabled', true);
                _concatGameProtocol(response.data[gameId])
            });
            $tbody.append($row);
        }
    }

    /**
     * @param game
     * @private
     */
    function _fillGameProtocol(game) {
        _gameToSave = game;
        for (let field in _gameToSave.game) {
            const $field = $(`#${field}`);
            if (['checkbox', 'radio'].indexOf($field.attr('type')) !== -1) {
                $field.prop('checked', !!_gameToSave.game[field]);
            } else {
                $field.val(_gameToSave.game[field]);
            }
            if (_gameToSave.game[field] !== '') $field.prop('readonly', true);
        }
        console.log(_gameToSave);
        _fillPlayers(_gameToSave.players);
    }

    function _concatGameProtocol(game) {
        let index = 2;
        const forAvg = {};
        for (let field in game.game) {
            if (field.indexOf('percent') !== -1 && game.game[field] > 0) {
                forAvg[field] = forAvg.hasOwnProperty(field)
                    ? forAvg[field] + game.game[field]
                    : _gameToSave.game[field] + game.game[field];
            } else if (_.isInteger(game.game[field])) {
                _gameToSave.game[field] += game.game[field];
            } else if (field.indexOf('time') !== -1 && field.indexOf('_powerplay_time') === -1) {
                const seconds = TRNMNT_helpers.convertTimeStringToSeconds(_gameToSave.game[field]);
                const additionalSeconds = TRNMNT_helpers.convertTimeStringToSeconds(game.game[field]);
                _gameToSave.game[field] = TRNMNT_helpers.convertSecondsToTimeString(seconds + additionalSeconds);
            }
        }
        for (let field in forAvg) {
            _gameToSave.game[field] = _.round(forAvg[field] / index, 1);
        }
        _concatPlayers(game.players);
        _fillGameProtocol(_gameToSave);
    }

    /**
     * @param players
     * @private
     */
    function _fillPlayers(players) {
        _$homePlayers.empty();
        _$awayPlayers.empty();
        for (let side in players) {
            const $tbody = side === 'home' ? _$homePlayers : _$awayPlayers;
            for (let player of players[side]) {
                $tbody.append(_templates.player.format({
                    tag: player.name,
                    position: _getPlayerBadge(player.position_id, player.position),
                    goals: player.goals,
                    assists: player.assists,
                    id: player.player_id,
                    stars: _getStarsSelect(),
                }));
            }
        }
    }

    function _concatPlayers(players) {
        for (let side in _gameToSave.players) {
            for (let player of _gameToSave.players[side]) {
                const existingPlayer = _.find(players[side], {player_id: player.player_id});

                if (!existingPlayer) continue;
                for (let key in existingPlayer) {
                    if (key.indexOf('_id') !== -1 || ['isGoalie', 'isWin', 'name', 'position'].indexOf(key) !== -1) {
                        continue;
                    }
                    if (key.indexOf('rating_') !== -1) {
                        player[key] = (player[key] + existingPlayer[key]) / 2;
                    } else {
                        player[key] += existingPlayer[key];
                    }
                }
            }
        }
        _fillPlayers(players);
    }

    /**
     * @param positionId
     * @param position
     * @returns {string}
     * @private
     */
    function _getPlayerBadge(positionId, position) {
        let badgeClass = '';
        switch (positionId) {
            case 0:
                badgeClass = 'badge-goalie';
                break;
            case 1:
                badgeClass = 'badge-defender';
                break;
            case 3:
                badgeClass = 'badge-left_wing';
                break;
            case 4:
                badgeClass = 'badge-center';
                break;
            case 5:
                badgeClass = 'badge-right_wing';
                break;
        }
        return `<span class="badge ${badgeClass}">${position.short_title}</span>`;
    }
}());
