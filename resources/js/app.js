window.TRNMNT_sendData = require('./tools/dataSend').default;
window.TRNMNT_deleteData = require('./tools/dataDelete').default;
window.TRNMNT_helpers = require('./tools/helpers').default;
// require('./gameFormModule');

Date.prototype.getShortDate = function (delimiter = '.', inverse = false) {
    const day = _.padStart(this.getDate().toString(), 2, '0');
    const month = _.padStart((this.getMonth() + 1).toString(), 2, '0');

    if (!inverse) return day + delimiter + month + delimiter + this.getFullYear();

    return this.getFullYear() + delimiter + month + delimiter + day;
};

/**
 * Получить дату со временем.
 * @param {String} [delimiter]
 * @returns {String}
 */
Date.prototype.getFullDate = function (delimiter = '.') {
    const day = _.padStart(this.getDate().toString(), 2, '0');
    const month = _.padStart((this.getMonth() + 1).toString(), 2, '0');
    const hour = _.padStart(this.getHours().toString(), 2, '0');
    const minute = _.padStart(this.getMinutes().toString(), 2, '0');
    // let second = _.padStart(this.getSeconds().toString(), 2, '0');

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
    let i = -1;
    const args = arguments;

    return this.replace(/#\{(.*?)\}/g, function (_, two) {
        return (typeof args[0] === 'object') ? args[0][two] : args[++i];
    });
};
