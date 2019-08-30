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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

window.TRNMNT_sendData = __webpack_require__(/*! ./tools/dataSend */ "./resources/js/tools/dataSend.js")["default"];
window.TRNMNT_deleteData = __webpack_require__(/*! ./tools/dataDelete */ "./resources/js/tools/dataDelete.js")["default"];
window.TRNMNT_helpers = __webpack_require__(/*! ./tools/helpers */ "./resources/js/tools/helpers.js")["default"];

__webpack_require__(/*! ./gameFormModule */ "./resources/js/gameFormModule.js");

Date.prototype.getShortDate = function () {
  var delimiter = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '.';
  var inverse = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  var day = this.getDate().toString().length === 1 ? '0' + this.getDate() : this.getDate();
  var month = (this.getMonth() + 1).toString().length === 1 ? '0' + (this.getMonth() + 1) : this.getMonth() + 1;
  if (!inverse) return day + delimiter + month + delimiter + this.getFullYear();
  return this.getFullYear() + delimiter + month + delimiter + day;
};
/**
 * Получить дату со временем.
 * @param {String} [delimiter]
 * @returns {String}
 */


Date.prototype.getFullDate = function (delimiter) {
  delimiter = delimiter || '.';
  var day = this.getDate().toString().length === 1 ? '0' + this.getDate() : this.getDate();
  var month = (this.getMonth() + 1).toString().length === 1 ? '0' + (this.getMonth() + 1) : this.getMonth() + 1;
  var hour = this.getHours().toString().length === 1 ? '0' + this.getHours() : this.getHours();
  var minute = this.getMinutes().toString().length === 1 ? '0' + this.getMinutes() : this.getMinutes(); // let second = this.getSeconds().toString().length === 1 ? '0' + this.getSeconds() : this.getSeconds();

  return day + delimiter + month + delimiter + this.getFullYear() + ' ' + hour + ':' + minute;
};
/**
 * Получить объект даты начала дня
 * @returns {Date}
 */


Date.prototype.getDayBegin = function () {
  return new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
};
/**
 * Подставить данные в строку
 * @link http://habrahabr.ru/post/192124/#comment_6673074
 * @returns {string}
 */


String.prototype.format = function () {
  var i = -1;
  var args = arguments;
  return this.replace(/#\{(.*?)\}/g, function (_, two) {
    return _typeof(args[0]) === 'object' ? args[0][two] : args[++i];
  });
};

/***/ }),

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
  var _gameId = null;
  var _positions = null;
  var _players = null;
  var _templates = {
    game: "\n            <tr>\n                <td>#{date}</td>\n                <td class=\"text-right\">#{home_team}</td>\n                <td class=\"text-right\">\n                    <span class=\"badge badge-primary badge-pill\">#{home_score}</span>\n                </td>\n                <td class=\"text-center\">:</td>\n                <td>\n                    <span class=\"badge badge-primary badge-pill\">#{away_score}</span>\n                </td>\n                <td>#{away_team}</td>\n                <td class=\"text-right\">\n                    <button type=\"button\" class=\"btn btn-primary btn-sm\">\u0417\u0430\u043F\u043E\u043B\u043D\u0438\u0442\u044C</button>\n                </td>\n            </tr>",
    player: "\n            <tr data-id=\"#{id}\">\n                <td>#{tag}</td>\n                <td class=\"text-center\">#{position}</td>\n                <td class=\"text-center\">#{goals}</td>\n                <td class=\"text-center\">#{assists}</td>\n                <td class=\"text-center text-nowrap\">#{stars}</td>\n                <td></td>\n            </tr>",
    playerForm: "\n            <tr data-id=\"#{id}\">\n                <td>#{player}</td>\n                <td class=\"text-center\">#{position}</td>\n                <td><input type=\"text\" class=\"text-right form-control\" name=\"goals\" value=\"#{goals}\"></td>\n                <td><input type=\"text\" class=\"text-right form-control\" name=\"assists\" value=\"#{assists}\"></td>\n                <td>#{stars}</td>\n                <td class=\"text-nowrap\">#{button}</td>\n            </tr>\n            "
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
    _isInitialized = true;
    _url = url;
    _positions = positions;
    _players = players;
    _gameId = +TRNMNT_helpers.parseUrl().segments[4];
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

    if (!matchId) {
      $homeAddForm = _createProtocolAddForm(_$homePlayers, players.home);
      $awayAddForm = _createProtocolAddForm(_$awayPlayers, players.away);
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
      button: '<button class="btn btn-primary" type="submit"><i class="fas fa-user-plus"></i></button>'
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
      goals: formData.goals,
      assists: formData.assists,
      stars: _getStarsSelect(formData.star),
      button: '<button class="btn btn-primary"><i class="fas fa-edit"></i></button> <button class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>'
    }));
    $row.closest('tbody').prepend($protocolRow);
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
    $.ajax({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'post',
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
      $row.find('button').click(function () {
        _$resetGame.removeClass('d-none');

        _$eaGames.find('button').prop('disabled', false);

        $(this).prop('disabled', true);

        _fillGameProtocol(response.data[gameId]);
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
            stars: _getStarsSelect
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

/***/ "./resources/js/tools/dataDelete.js":
/*!******************************************!*\
  !*** ./resources/js/tools/dataDelete.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (function (params) {
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
        success: function success(response) {
          TRNMNT_helpers.enableButtons();
          params.success(response);
        },
        error: TRNMNT_helpers.onErrorAjax
      });
    }
  });
});
;

/***/ }),

/***/ "./resources/js/tools/dataSend.js":
/*!****************************************!*\
  !*** ./resources/js/tools/dataSend.js ***!
  \****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (function (params) {
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(params.selector).submit(function (e) {
    e.preventDefault();
    TRNMNT_helpers.disableButtons();
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
      success: function success(response) {
        TRNMNT_helpers.enableButtons();
        params.success(response);
      },
      error: function error(response) {
        TRNMNT_helpers.onErrorAjax(response);

        for (var key in response.responseJSON.errors) {
          var errors = response.responseJSON.errors[key];

          var _field = form.find('[name=' + key + ']');

          _field.addClass('is-invalid');

          var message = '';

          for (var _i = 0; _i < errors.length; _i += 1) {
            message += errors[_i] + '<br>';
          }

          _field.closest('.form-group').find('.invalid-feedback').html(message);
        }
      }
    });
  });
});
;

/***/ }),

/***/ "./resources/js/tools/helpers.js":
/*!***************************************!*\
  !*** ./resources/js/tools/helpers.js ***!
  \***************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ({
  showPreLoader: function showPreLoader() {
    $('<div id="bigPreloader"></div>').appendTo('body').html("\n                <span style=\"vertical-align:middle; display: table-cell;\">\n                    <i class=\"fas fa-cog fa-spin fa-7x\"></i>\n                </span>").css({
      position: 'fixed',
      width: '100%',
      height: '100%',
      background: 'rgba(255,255,255,0.9)',
      top: 0,
      left: 0,
      'z-index': 100000,
      'text-align': 'center',
      'display': 'table'
    });
  },
  hidePreLoader: function hidePreLoader() {
    $('#bigPreloader').remove();
  },
  disableButtons: function disableButtons(noPreloader) {
    $('input[type=submit], input[type=button], button').prop('disabled', true);
    this.hidePreLoader();

    if (!noPreloader) {
      this.showPreLoader();
    }
  },
  enableButtons: function enableButtons() {
    $('input[type=submit], input[type=button], button').prop('disabled', false);
    this.hidePreLoader();
  },
  showNotification: function showNotification(message, params) {
    var settings = {
      blockClass: 'alert',
      duration: 10000,
      //Время отображения сообщения
      animationDuration: 500,
      //Длительность анимации
      alertType: 'success',
      //Цвет сообщения
      types: {
        //Варианты цветов сообщений
        success: 'alert-success',
        info: 'alert-info',
        warning: 'alert-warning',
        error: 'alert-danger'
      },
      position: 'se',
      //Позиционирование элемента
      margin: 30 //Отступ

    };
    var css = {
      nw: {
        top: settings.margin + 'px',
        left: settings.margin + 'px'
      },
      ne: {
        top: settings.margin + 'px',
        right: settings.margin + 'px'
      },
      sw: {
        bottom: settings.margin + 'px',
        left: settings.margin + 'px'
      },
      se: {
        bottom: settings.margin + 'px',
        right: settings.margin + 'px'
      }
    };
    params = params || {};
    $.extend(true, settings, params);
    var direction = ['sw', 'se'].indexOf(settings.position) !== -1 ? 'bottom' : 'top';
    var $note = $('<div class="notification ' + settings.blockClass + ' ' + settings.types[settings.alertType] + '"></div>').click(function (event) {
      event.preventDefault();
      removeNote($(this));
    }).css($.extend(true, css[settings.position], {
      position: 'fixed',
      display: 'none',
      'z-index': 1050
    })).appendTo('body').html(message).animate({
      opacity: 'show'
    }, settings.animationDuration).delay(settings.duration).animate({
      opacity: 'hide'
    }, settings.animationDuration).delay(settings.animationDuration).queue(function () {
      $(this).remove();
    });
    $('.' + settings.blockClass).not($note).each(function (index, element) {
      var block = $(element);
      var height = $note.height() + parseInt($note.css('padding')) * 2 + 10;
      block.css(direction, parseInt(block.css(direction)) + height + 'px');
    });

    function removeNote($noteToRemove) {
      var $notes = $('.' + settings.blockClass);
      var height = $noteToRemove.height() + parseInt($noteToRemove.css('padding')) * 2 + 10;
      var noteToRemoveIndex = $notes.index($noteToRemove);
      $noteToRemove.hide();
      $notes.each(function (index, element) {
        var block = $(element);

        if (index < noteToRemoveIndex) {
          block.css(direction, parseInt(block.css(direction)) - height + 'px');
        }
      });
      $noteToRemove.remove();
    }
  },
  hideNotifications: function hideNotifications() {
    $('.notification').remove();
  },
  getParameterByName: function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  },
  jsonStringify: function jsonStringify(s, emit_unicode) {
    var json = JSON.stringify(s);
    var result;

    if (emit_unicode) {
      result = json;
    } else {
      result = json.replace(/(\\\\)/g, '/').replace(/(\\n)/g, ' ').replace(/(\s+\\")/g, ' «').replace(/("\\")/g, '"«').replace(/(\\")/g, '»');
    }

    return result;
  },
  parseUrl: function parseUrl() {
    var url = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : window.location.href;
    var a = document.createElement('a'),
        params = null,
        segments = [],
        tmp;
    a.href = url;
    tmp = a.search.replace('?', '');

    if (tmp.length) {
      params = {};
      tmp = decodeURI(tmp);
      tmp = tmp.split('&');
      tmp.forEach(function (p) {
        var t = p.split('=');
        params[t[0]] = t[1];
      });
    }

    tmp = a.pathname.split('/');

    for (var i = 0; i < tmp.length; i++) {
      if (tmp[i] !== '') {
        segments.push(tmp[i]);
      }
    }

    return {
      url: a.href,
      protocol: a.protocol.replace(':', ''),
      host: a.host,
      port: a.port,
      path: a.pathname,
      search: a.search,
      params: params,
      segments: segments
    };
  },
  onErrorAjax: function onErrorAjax(e) {
    var response;

    if (e.responseText !== undefined) {
      response = JSON.parse(e.responseText);
      var message = response.message;

      if (response.errors) {
        for (var error in response.errors) {
          var _iteratorNormalCompletion = true;
          var _didIteratorError = false;
          var _iteratorError = undefined;

          try {
            for (var _iterator = response.errors[error][Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
              var serverMessage = _step.value;
              message += "<br>".concat(error, ": ").concat(serverMessage);
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

      this.showNotification(message, {
        alertType: 'error'
      });
    } else {
      this.showNotification('Server error.', {
        alertType: 'error'
      });
    }

    this.enableButtons();
    this.hidePreLoader();
  },
  updateCount: function updateCount(selector, selectorEmpty) {
    var countNode = $(selector);
    var count = parseInt(countNode.text()) - 1;
    var parentNode = countNode.parent();
    countNode.text(count);

    if (count === 0) {
      parentNode.text(parentNode.text().replace(/\s+\(.*/, ''));
      selectorEmpty && $(selectorEmpty).show();
    }
  },
  validateEmail: function validateEmail(email) {
    var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return pattern.test(email);
  },
  validateUrl: function validateUrl(url) {
    var pattern = /^(https?:\/\/)?((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/;
    return pattern.test(url);
  },
  getDatePickerSettings: function getDatePickerSettings() {
    return {
      format: "yyyy-mm-dd",
      weekStart: 1,
      todayHighlight: true,
      autoclose: true,
      language: "ru"
    };
  }
});

/***/ }),

/***/ 1:
/*!***********************************!*\
  !*** multi ./resources/js/app.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/lapotchkin/Sites/tournaments-laravel/resources/js/app.js */"./resources/js/app.js");


/***/ })

/******/ });