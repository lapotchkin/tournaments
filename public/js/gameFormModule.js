/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/gameFormModule.js":
/*!****************************************!*\
  !*** ./resources/js/gameFormModule.js ***!
  \****************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.TRNMNT_gameFormModule = function () {
  var _isInitialized = false;
  var _$eaGames = null;
  var _$getGames = null;
  var _$resetGame = null;
  var _$homePlayers = null;
  var _$awayPlayers = null;
  var _$gameForm = null;
  var _gameToSave = null;
  var _url = null;
  var _pairId = null;
  var _gameId = null;
  var _positions = null;
  var _players = null;
  var _templates = {
    game: "\n            <tr>\n                <td>#{date}</td>\n                <td class=\"text-right\">#{home_team}</td>\n                <td class=\"text-right\">\n                    <span class=\"badge badge-primary badge-pill\">#{home_score}</span>\n                </td>\n                <td class=\"text-center\">:</td>\n                <td>\n                    <span class=\"badge badge-primary badge-pill\">#{away_score}</span>\n                </td>\n                <td>#{away_team}</td>\n                <td class=\"text-right\">\n                    <button type=\"button\" class=\"result-fill btn btn-primary btn-sm\">\u0417\u0430\u043F\u043E\u043B\u043D\u0438\u0442\u044C</button>\n                    <button type=\"button\" class=\"result-concat btn btn-success btn-sm\" disabled>\u041F\u0440\u0438\u0441\u043E\u0435\u0434\u0438\u043D\u0438\u0442\u044C</button>\n                </td>\n            </tr>",
    player: "\n            <tr data-id=\"#{id}\">\n                <td>#{tag}</td>\n                <td class=\"text-center\">#{position}</td>\n                <td class=\"text-center\">#{goals}</td>\n                <td class=\"text-center\">#{assists}</td>\n                <td class=\"text-center text-nowrap\">#{stars}</td>\n                <td></td>\n            </tr>",
    playerForm: "\n            <tr data-id=\"#{id}\" style=\"#{style}\">\n                <td>#{player}</td>\n                <td class=\"text-center\">#{position}</td>\n                <td><input type=\"text\" class=\"text-right form-control\" name=\"goals\" value=\"#{goals}\"></td>\n                <td><input type=\"text\" class=\"text-right form-control\" name=\"assists\" value=\"#{assists}\"></td>\n                <td>#{stars}</td>\n                <td class=\"text-nowrap\">#{button}</td>\n            </tr>\n            "
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
    var parsedUrl = TRNMNT_helpers.parseUrl();
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

    var $homeAddForm = null;
    var $awayAddForm = null;

    if (!matchId && _players) {
      $homeAddForm = _createProtocolAddForm(_$homePlayers, _players.home);
      $awayAddForm = _createProtocolAddForm(_$awayPlayers, _players.away);
    }

    for (var side in protocols) {
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = protocols[side][Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var player = _step.value;
          var $tbody = side === 'home' ? _$homePlayers : _$awayPlayers;

          if (matchId) {
            // console.log(player);
            $tbody.append(_templates.player.format({
              tag: player.player_tag,
              position: _getPlayerBadge(player.position_id, player.position),
              goals: !player.isGoalie ? player.goals : '—',
              assists: !player.isGoalie ? player.assists : '—',
              id: player.player_id,
              stars: _getStars(player.star)
            }));
          } else {
            _onSuccessAddProtocol({
              player_id: player.player_id,
              position_id: player.position_id,
              goals: player.goals,
              assists: player.assists,
              star: player.star
            }, player.id, side === 'home' ? $homeAddForm : $awayAddForm);
          }
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator["return"] != null) {
            _iterator["return"]();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
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
    var playersSelect = '';
    players.forEach(function (player) {
      playersSelect += "<option value=\"".concat(player.id, "\">").concat(player.tag, "</option>");
    });
    var $row = $(_templates.playerForm.format({
      id: '',
      player: "<select class=\"form-control\" name=\"player_id\">".concat(playersSelect, "</select>"),
      position: _getPositionSelect(),
      stars: _getStarsSelect(),
      goals: '',
      assists: '',
      button: '<button class="btn btn-primary" type="submit"><i class="fas fa-user-plus"></i></button>',
      style: 'border-top: 3px solid red;'
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
    var positionSelect = '';

    _positions.forEach(function (position) {
      var selected = playerPosition === position.id ? 'selected' : '';
      positionSelect += "<option value=\"".concat(position.id, "\" ").concat(selected, ">").concat(position.short_title, "</option>");
    });

    return "<select class=\"form-control\" name=\"position_id\">".concat(positionSelect, "</select>");
  }
  /**
   * @param playerStar
   * @returns {string}
   * @private
   */


  function _getStarsSelect(playerStar) {
    var stars = ['—', '1', '2', '3'];
    var starsSelect = '';

    for (var i = 0; i < stars.length; i += 1) {
      var selected = playerStar === i ? 'selected' : '';
      starsSelect += "<option value=\"".concat(i, "\" ").concat(selected, ">").concat(stars[i], "</option>");
    }

    return "<select class=\"form-control\" name=\"star\">".concat(starsSelect, "</select>");
  }
  /**
   * @param event
   * @private
   */


  function _onClickAddProtocol(event) {
    event.preventDefault();
    var $row = $(this).closest('tr');
    var formData = {
      game_id: _gameId,
      team_id: +$row.closest('table').data('id'),
      player_id: +$row.find('select[name=player_id]').val(),
      position_id: +$row.find('select[name=position_id]').val(),
      goals: +$row.find('input[name=goals]').val(),
      assists: +$row.find('input[name=assists]').val(),
      star: +$row.find('select[name=star]').val()
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
      success: function success(response) {
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
    var $playerOption = $row.find('select[name=player_id] option[value=' + formData.player_id + ']');
    var $protocolRow = $(_templates.playerForm.format({
      id: protocolId,
      player: $playerOption.text(),
      position: _getPositionSelect(formData.position_id),
      goals: formData.goals !== null ? formData.goals : '',
      assists: formData.assists !== null ? formData.assists : '',
      stars: _getStarsSelect(formData.star),
      button: '<button class="btn btn-primary"><i class="fas fa-edit"></i></button> <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>'
    }));
    $row.closest('tbody').prepend($protocolRow);
    $protocolRow.find('button.btn-primary').on('click', _onClickEditProtocol);
    $protocolRow.find('button.btn-danger').on('click', _onClickRemoveProtocol);
    $playerOption.remove();
    var $playerOptions = $row.find('select[name=player_id] option');

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
      var $row = $(this).closest('tr');
      var playerTag = $($row.find('td')[0]).text();

      for (var side in _players) {
        var _iteratorNormalCompletion2 = true;
        var _didIteratorError2 = false;
        var _iteratorError2 = undefined;

        try {
          for (var _iterator2 = _players[side][Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
            var player = _step2.value;
            if (playerTag !== player.tag) continue;
            var $playerSelect = $row.closest('table').find('select[name=player_id]');
            $playerSelect.append("<option value=\"".concat(player.id, "\">").concat(player.tag, "</option>"));
            $playerSelect.closest('tr').show();
          }
        } catch (err) {
          _didIteratorError2 = true;
          _iteratorError2 = err;
        } finally {
          try {
            if (!_iteratorNormalCompletion2 && _iterator2["return"] != null) {
              _iterator2["return"]();
            }
          } finally {
            if (_didIteratorError2) {
              throw _iteratorError2;
            }
          }
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
        success: function success(response) {
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
    var $row = $(this).closest('tr');
    var formData = {
      position_id: +$row.find('select[name=position_id]').val(),
      goals: +$row.find('input[name=goals]').val(),
      assists: +$row.find('input[name=assists]').val(),
      star: +$row.find('select[name=star]').val()
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
      success: function success(response) {
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
    var stars = '';

    for (var i = 0; i < star; i += 1) {
      stars += '<i class="fas fa-star text-danger"></i>';
    }

    return stars;
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
    for (var field in _gameToSave.game) {
      var $field = $("#".concat(field));

      if ($field.prop('readonly') === false) {
        var val = $field.val();

        if (field.indexOf('_percent') !== -1) {
          val = val ? parseFloat(val.replace(',', '.')) : 0;
        }

        _gameToSave.game[field] = val;
      }
    }

    var players = {};

    _$homePlayers.find('select').each(setPlayer);

    _$awayPlayers.find('select').each(setPlayer);

    for (var side in _gameToSave.players) {
      var _iteratorNormalCompletion3 = true;
      var _didIteratorError3 = false;
      var _iteratorError3 = undefined;

      try {
        for (var _iterator3 = _gameToSave.players[side][Symbol.iterator](), _step3; !(_iteratorNormalCompletion3 = (_step3 = _iterator3.next()).done); _iteratorNormalCompletion3 = true) {
          var player = _step3.value;
          player.star = players[player.player_id];
        }
      } catch (err) {
        _didIteratorError3 = true;
        _iteratorError3 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion3 && _iterator3["return"] != null) {
            _iterator3["return"]();
          }
        } finally {
          if (_didIteratorError3) {
            throw _iteratorError3;
          }
        }
      }
    }

    return JSON.stringify(_gameToSave);

    function setPlayer(index, element) {
      var $element = $(element);
      var playerId = $element.closest('tr').data('id');
      players[playerId] = +$element.val();
    }
  }
  /**
   * @returns {string}
   * @private
   */


  function _getFormData() {
    var formData = _$gameForm.serializeArray();

    var request = {
      game: {}
    };

    for (var i = 0; i < formData.length; i += 1) {
      if (formData[i].value) {
        var val = formData[i].value;

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
    if (response.data.id) {
      window.location.href = window.location.href.replace('add', response.data.id) + '/edit';
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
        var $field = $(element);

        if (['checkbox', 'radio'].indexOf($field.attr('type')) !== -1) {
          $field.prop('checked', false);
        } else if (['submit'].indexOf($field.attr('type')) !== -1) {//do nothing
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
    var $table = $('<table class="table table-sm table-striped mt-3"/>');

    _$eaGames.append($table);

    var $tbody = $('<tbody/>');
    $table.append($tbody);

    var _loop = function _loop(gameId) {
      var game = response.data[gameId].game;
      var date = new Date(game.playedAt);
      var $row = $(_templates.game.format({
        date: date.getShortDate(),
        home_team: game.home_team,
        away_team: game.away_team,
        home_score: game.home_score,
        away_score: game.away_score
      }));
      $row.find('button.result-fill').click(function () {
        var $this = $(this);

        _$resetGame.removeClass('d-none');

        _$eaGames.find('button.result-fill, button.result-concat').prop('disabled', false);

        $this.prop('disabled', true);
        $this.siblings().prop('disabled', true);

        _fillGameProtocol(response.data[gameId]);
      });
      $row.find('button.result-concat').click(function () {
        var $this = $(this);
        $this.prop('disabled', true);

        _concatGameProtocol(response.data[gameId]);
      });
      $tbody.append($row);
    };

    for (var gameId in response.data) {
      _loop(gameId);
    }
  }
  /**
   * @param game
   * @private
   */


  function _fillGameProtocol(game) {
    _gameToSave = game;

    for (var field in _gameToSave.game) {
      var $field = $("#".concat(field));

      if (['checkbox', 'radio'].indexOf($field.attr('type')) !== -1) {
        $field.prop('checked', !!_gameToSave.game[field]);
      } else {
        $field.val(_gameToSave.game[field]);
      }

      if (_gameToSave.game[field] !== '') $field.prop('readonly', true);
    }

    _fillPlayers(_gameToSave.players);
  }

  function _concatGameProtocol(game) {
    console.log(game);

    for (var field in game.game) {
      if (_.isInteger(game.game[field])) {
        _gameToSave.game[field] += game.game[field];
      } else if (field.indexOf('time') !== -1) {
        var seconds = TRNMNT_helpers.convertTimeStringToSeconds(_gameToSave.game[field]);
        var additionalSeconds = TRNMNT_helpers.convertTimeStringToSeconds(game.game[field]);
        console.log(field, _gameToSave.game[field], game.game[field], seconds, additionalSeconds);
        _gameToSave.game[field] = TRNMNT_helpers.convertSecondsToTimeString(seconds + additionalSeconds);
      }

      _concatPlayers(game.players);
    }

    _fillGameProtocol(_gameToSave);
  }
  /**
   * @param players
   * @private
   */


  function _fillPlayers(players) {
    _$homePlayers.empty();

    _$awayPlayers.empty();

    for (var side in players) {
      var $tbody = side === 'home' ? _$homePlayers : _$awayPlayers;
      var _iteratorNormalCompletion4 = true;
      var _didIteratorError4 = false;
      var _iteratorError4 = undefined;

      try {
        for (var _iterator4 = players[side][Symbol.iterator](), _step4; !(_iteratorNormalCompletion4 = (_step4 = _iterator4.next()).done); _iteratorNormalCompletion4 = true) {
          var player = _step4.value;
          $tbody.append(_templates.player.format({
            tag: player.name,
            position: _getPlayerBadge(player.position_id, player.position),
            goals: player.goals,
            assists: player.assists,
            id: player.player_id,
            stars: _getStarsSelect()
          }));
        }
      } catch (err) {
        _didIteratorError4 = true;
        _iteratorError4 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion4 && _iterator4["return"] != null) {
            _iterator4["return"]();
          }
        } finally {
          if (_didIteratorError4) {
            throw _iteratorError4;
          }
        }
      }
    }
  }

  function _concatPlayers(players) {
    for (var side in _gameToSave.players) {
      var _iteratorNormalCompletion5 = true;
      var _didIteratorError5 = false;
      var _iteratorError5 = undefined;

      try {
        for (var _iterator5 = _gameToSave.players[side][Symbol.iterator](), _step5; !(_iteratorNormalCompletion5 = (_step5 = _iterator5.next()).done); _iteratorNormalCompletion5 = true) {
          var player = _step5.value;

          var existingPlayer = _.find(players[side], {
            id: 1,
            'active': true
          });
        }
      } catch (err) {
        _didIteratorError5 = true;
        _iteratorError5 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion5 && _iterator5["return"] != null) {
            _iterator5["return"]();
          }
        } finally {
          if (_didIteratorError5) {
            throw _iteratorError5;
          }
        }
      }
    }
  }
  /**
   * @param positionId
   * @param position
   * @returns {string}
   * @private
   */


  function _getPlayerBadge(positionId, position) {
    var badgeClass = '';

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

    return "<span class=\"badge ".concat(badgeClass, "\">").concat(position.short_title, "</span>");
  }
}();

/***/ }),

/***/ 3:
/*!**********************************************!*\
  !*** multi ./resources/js/gameFormModule.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/lapotchkin/Sites/my/tournaments-laravel/resources/js/gameFormModule.js */"./resources/js/gameFormModule.js");


/***/ })

/******/ });