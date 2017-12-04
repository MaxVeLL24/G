(function(){
    'use strict';
    if(!String.prototype.escapeHtml)
    {
        /**
         * Экранирует управляющие символы HTML в строке
         * @returns {String}
         */
        String.prototype.escapeHtml = function(){
            var entityMap = {
                "&" : "&amp;",
                "<" : "&lt;",
                ">" : "&gt;",
                '"' : '&quot;',
                "'" : '&#39;',
                "/" : '&#x2F;'
            };
            return this.replace(/[&<>"'\/]/g, function (s) {
                return entityMap[s];
            });
        };
    }
    if(!String.prototype.trim)
    {
        /**
         * Удаляет начальные и конечные пробельные символы в строке
         * @returns {String}
         */
        String.prototype.trim = function(){
            return this.replace(/^\s+|\s+$/g, '');
        };
    }
    if(!window.encodeURIComponent)
    {
        /**
         * Кодирует строку согласно RFC 3986
         * @param {String} str Строка, которую нужно закодировать
         * @returns {String} Закодириванная строка
         */
        window.encodeURIComponent = function(str) {
            return escape(str).replace('@', '%40').replace('+', '%2B').replace('/', '%2F');
        }
    }
    if(!window.decodeURIComponent)
    {
        /**
         * Декодирует URL-кодированную строку
         * @param {String} str Закодириванная строка
         * @returns {String} Раскодированная строка
         */
        window.decodeURIComponent = function(str) {
            return unescape(str);
        }
    }
    if(!Array.prototype.unique)
    {
        /**
         * Возвращает массив, содержащий только уникальные значения текущего массива
         * @returns {Array}
         */
        Array.prototype.unique = function() {
            var result = [], i;
            for(i = 0; i < this.length; i++)
            {
                if(result.indexOf(this[i]) >= 0)
                {
                    continue;
                }
                result.push(this[i]);
            }
            return result;
        };
    }
})();