export default {
    showPreLoader: function () {
        $('<div id="bigPreloader"></div>').appendTo('body')
            .html(`
                <span style="vertical-align:middle; display: table-cell;">
                    <i class="fas fa-cog fa-spin fa-7x"></i>
                </span>`)
            .css({
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
    hidePreLoader: function () {
        $('#bigPreloader').remove();
    },
    disableButtons: function (noPreloader) {
        $('input[type=submit], input[type=button], button').prop('disabled', true);
        this.hidePreLoader();
        if (!noPreloader) {
            this.showPreLoader();
        }
    },
    enableButtons: function () {
        $('input[type=submit], input[type=button], button').prop('disabled', false);
        this.hidePreLoader();
    },
    showNotification: function (message, params) {
        const settings = {
            blockClass: 'alert',
            duration: 10000, //Время отображения сообщения
            animationDuration: 500, //Длительность анимации
            alertType: 'success', //Цвет сообщения
            types: {//Варианты цветов сообщений
                success: 'alert-success',
                info: 'alert-info',
                warning: 'alert-warning',
                error: 'alert-danger'
            },
            position: 'se', //Позиционирование элемента
            margin: 30//Отступ
        };
        const css = {
            nw: {top: settings.margin + 'px', left: settings.margin + 'px'},
            ne: {top: settings.margin + 'px', right: settings.margin + 'px'},
            sw: {bottom: settings.margin + 'px', left: settings.margin + 'px'},
            se: {bottom: settings.margin + 'px', right: settings.margin + 'px'}
        };
        params = params || {};
        $.extend(true, settings, params);
        var direction = ['sw', 'se'].indexOf(settings.position) !== -1 ? 'bottom' : 'top';

        var $note = $('<div class="notification ' + settings.blockClass + ' ' + settings.types[settings.alertType] + '"></div>')
            .click(function (event) {
                event.preventDefault();
                removeNote($(this));
            })
            .css($.extend(true, css[settings.position], {position: 'fixed', display: 'none', 'z-index': 1050}))
            .appendTo('body')
            .html(message)
            .animate({opacity: 'show'}, settings.animationDuration)
            .delay(settings.duration)
            .animate({opacity: 'hide'}, settings.animationDuration)
            .delay(settings.animationDuration)
            .queue(function () {
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
    hideNotifications: function () {
        $('.notification').remove();
    },
    getParameterByName: function (name) {
        name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
        var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
    },
    jsonStringify: function (s, emit_unicode) {
        var json = JSON.stringify(s);
        var result;
        if (emit_unicode) {
            result = json;
        } else {
            result = json.replace(/(\\\\)/g, '/')
                .replace(/(\\n)/g, ' ')
                .replace(/(\s+\\")/g, ' «')
                .replace(/("\\")/g, '"«')
                .replace(/(\\")/g, '»');
        }
        return result;
    },
    parseUrl: function (url = window.location.href) {
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
    onErrorAjax: function (e) {
        var response;
        if (e.responseText !== undefined) {
            response = JSON.parse(e.responseText);
            let message = response.message;
            if (response.errors) {
                for (let error in response.errors) {
                    for (let serverMessage of response.errors[error]) message += `<br>${error}: ${serverMessage}`;
                }
            }
            this.showNotification(message, {alertType: 'error'});
        } else {
            this.showNotification('Server error.', {alertType: 'error'});
        }
        this.enableButtons();
        this.hidePreLoader();
    },
    updateCount: function (selector, selectorEmpty) {
        const countNode = $(selector);
        const count = parseInt(countNode.text()) - 1;
        const parentNode = countNode.parent();
        countNode.text(count);
        if (count === 0) {
            parentNode.text(parentNode.text().replace(/\s+\(.*/, ''));
            selectorEmpty && $(selectorEmpty).show();
        }
    },
    validateEmail: function (email) {
        var pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        return pattern.test(email);
    },
    validateUrl: function (url) {
        var pattern = /^(https?:\/\/)?((([a-z\d]([a-z\d-]*[a-z\d])*)\.)+[a-z]{2,}|((\d{1,3}\.){3}\d{1,3}))(\:\d+)?(\/[-a-z\d%_.~+]*)*(\?[;&a-z\d%_.~+=-]*)?(\#[-a-z\d_]*)?$/;

        return pattern.test(url);
    },
    getDatePickerSettings: function () {
        return {
            format: "yyyy-mm-dd",
            weekStart: 1,
            todayHighlight: true,
            autoclose: true,
            language: "ru"
        };
    },
    convertTimeStringToSeconds: function (time) {
        const tmp = time.split(':');
        let seconds = 0;
        for (let i = 0; i < tmp.length; i += 1) {
            const multiplier = (60 * -(i + 1 - tmp.length));
            seconds += tmp[i] * (multiplier ? multiplier : 1);
        }
        return seconds;
    },
    convertSecondsToTimeString: function (seconds) {
        const date = new Date(seconds * 1000);
        return (date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes())
            + ':'
            + (date.getSeconds() < 10 ? '0' + date.getSeconds() : date.getSeconds());
    },
};
