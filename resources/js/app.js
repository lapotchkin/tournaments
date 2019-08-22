window.TRNMNT_sendData = require('./tools/dataSend').default;
window.TRNMNT_deleteData = require('./tools/dataDelete').default;
window.TRNMNT_helpers = require('./tools/helpers').default;

Date.prototype.getShortDate = function (delimiter = '.', inverse = false) {
    const day = this.getDate().toString().length === 1 ? '0' + this.getDate() : this.getDate();
    const month = (this.getMonth() + 1).toString().length === 1 ? '0' + (this.getMonth() + 1) : (this.getMonth() + 1);

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

    const day = this.getDate().toString().length === 1 ? '0' + this.getDate() : this.getDate();
    const month = (this.getMonth() + 1).toString().length === 1 ? '0' + (this.getMonth() + 1) : (this.getMonth() + 1);
    const hour = this.getHours().toString().length === 1 ? '0' + this.getHours() : this.getHours();
    const minute = this.getMinutes().toString().length === 1 ? '0' + this.getMinutes() : this.getMinutes();
    // let second = this.getSeconds().toString().length === 1 ? '0' + this.getSeconds() : this.getSeconds();

    return day + delimiter + month + delimiter + this.getFullYear() + ' ' + hour + ':' + minute;
};

/**
 * Получить объект даты начала дня
 * @returns {Date}
 */
Date.prototype.getDayBegin = function () {
    return new Date(this.getFullYear(), this.getMonth(), this.getDate(), 0, 0, 0);
};
