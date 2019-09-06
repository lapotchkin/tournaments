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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/playoffModule.js":
/*!***************************************!*\
  !*** ./resources/js/playoffModule.js ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

window.TRNMNT_playoffModule = function () {
  var _isInitialized = false;
  var _url = null;
  return {
    init: _init
  };

  function _init(url) {
    if (_isInitialized) return;
    _isInitialized = true;
    _url = url;
    $('.savePair').on('click', _onClickSavePair);
  }

  function _onClickSavePair(event) {
    event.preventDefault();
    var $button = $(this);
    var $pair = $button.closest('li');
    var pairId = +$pair.data('id');
    var formData = {
      round: $pair.closest('div.tournament-bracket__round').index() + 1,
      pair: $pair.index() + 1
    };
    var teamOneId = +$pair.find('select[name=team_one_id]').val();
    var teamTwoId = +$pair.find('select[name=team_two_id]').val();
    if (teamOneId) formData.team_one_id = teamOneId;
    if (teamTwoId) formData.team_two_id = teamTwoId; // if (!teamOneId && !teamTwoId) return;

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
      success: function success(response) {
        TRNMNT_helpers.enableButtons();
        TRNMNT_helpers.showNotification(response.message);
        var $link = $pair.find('.addGame');

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
}();

/***/ }),

/***/ 4:
/*!*********************************************!*\
  !*** multi ./resources/js/playoffModule.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/lapotchkin/Sites/tournaments-laravel/resources/js/playoffModule.js */"./resources/js/playoffModule.js");


/***/ })

/******/ });