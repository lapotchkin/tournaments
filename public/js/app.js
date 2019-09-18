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
window.TRNMNT_helpers = __webpack_require__(/*! ./tools/helpers */ "./resources/js/tools/helpers.js")["default"]; // require('./gameFormModule');

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
    console.log(formData);

    for (var i = 0; i < formData.length; i += 1) {
      if (formData[i].value) {
        switch (formData[i].value) {
          case 'on':
            request[formData[i].name] = 1;
            break;

          case 'off':
            request[formData[i].name] = 1;
            break;

          default:
            request[formData[i].name] = formData[i].value;
        }
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

module.exports = __webpack_require__(/*! /Users/lapotchkin/Sites/my/tournaments-laravel/resources/js/app.js */"./resources/js/app.js");


/***/ })

/******/ });