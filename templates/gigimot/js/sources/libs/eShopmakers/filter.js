(function($){
    'use strict';
    /**
     * Фильтр категории
     * @param {String} base_url URL, на который будут посылаться запросы
     * @returns {Filter}
     */
    window.Filter = function(base_url) {
        this.base_url = base_url;
        this.attributes = {};
        this.other_params = {};
    };
    /**
     * Прототип для конструктора фильтров
     * @type Object
     * @property {String} base_url Базовый URL, к которому будет добавляться строка запроса
     * @property {Object} attributes Список атрибутов
     * @property {Object} other_params Список прочих параметров, которые не используются фильтром,
     * но присутствовали в исходном URL, если объект был получен при помощи метода Filter.fromCurrent.
     * Можно так же использовать для добавления ползовательских параметров запроса.
     * @property {Number} price_min Минимальная стоимость
     * @property {Number} price_max Максимальная стоимость
     * @property {String} search Ключевое слово для поиска по названию и модели
     * @property {String} filter_slov Ключевое слово для поиска только по названию
     * @property {String} sort Порядок сортировки
     * @property {Number} row_by_page Количество отображаемых товаров на странице
     * @property {String} display Cпособа отображения товаров (список/плитка)
     */
    window.Filter.prototype = {
        /**
         * Добавляет указанный атрибут к фильтру
         * @param {Number} option_id ID опции
         * @param {Number} option_value_id ID значения опции
         * @returns {undefined}
         */
        addAttribute : function(option_id, option_value_id) {
            option_id       = parseInt(option_id);
            option_value_id = parseInt(option_value_id);
            if(isNaN(option_id) || isNaN(option_value_id))
            {
                return;
            }
            if(!(option_id in this.attributes) || !(this.attributes[option_id] instanceof Array))
            {
                this.attributes[option_id] = [];
            }
            if(this.attributes[option_id].indexOf(option_value_id) < 0)
            {
                this.attributes[option_id].push(option_value_id);
            }
        },
        /**
         * Удаляет указанный атрибут из фильтра
         * @param {Number} option_id ID опции
         * @param {Number} option_value_id ID значения опции
         * @returns {undefined}
         */
        removeAttribute : function(option_id, option_value_id) {
            option_id       = parseInt(option_id);
            option_value_id = parseInt(option_value_id);
            if(isNaN(option_id) || isNaN(option_value_id))
            {
                return;
            }
            if(option_id in this.attributes && this.attributes[option_id] instanceof Array)
            {
                var i = this.attributes[option_id].indexOf(option_value_id);
                if(i >= 0)
                {
                    this.attributes[option_id].splice(i, 1);
                }
                if(!this.attributes[option_id].length)
                {
                    delete this.attributes[option_id];
                }
            }
        },
        /**
         * Применить фильтры, получить список соответствующих товаров и отобразить его
         * @param {Function} callback_function Callback-функция, в которую будет передан результат запроса после применения фильтров
         * @returns {undefined}
         */
        filter : function(callback_function) {
            var request_uri  = ( this.base_url || window.base_url || '/'),
                query_params = ['language=' + encodeURIComponent(window.language)];
            // Добавляем к запросу атрибуты
            for(var option_id in this.attributes)
            {
                if(this.attributes[option_id].length)
                {
                    query_params.push(option_id + '=' + this.attributes[option_id].unique().join('-'));
                }
            }
            // Минимальная цена
            if(this.price_min)
            {
                query_params.push('rmin=' + this.price_min);
            }
            // Максимальная цена
            if(this.price_max)
            {
                query_params.push('rmax=' + this.price_max);
            }
            // Порядок сортировки
            if(this.sort)
            {
                query_params.push('sort=' + this.sort);
            }
            // Количество отображаемых товаров на странице
            if(this.row_by_page)
            {
                query_params.push('row_by_page=' + this.row_by_page);
            }
            // Cпособа отображения товаров (список/плитка)
            if(this.display)
            {
                query_params.push('display=' + this.display);
            }
            // Поиск
            if(this.search)
            {
                query_params.push('keywords=' + encodeURIComponent(this.search));
            }
            // Поиск только в названии товара
            if(this.search_name)
            {
                query_params.push('filter_slov=' + encodeURIComponent(this.search_name));
            }
            // Все прочие параметры
            for(var param_name in this.other_params)
            {
                query_params.push(encodeURIComponent(param_name) + (this.other_params[param_name] ? '=' : '') + encodeURIComponent(this.other_params[param_name]));
            }
            // CSRF токен
            /* if(!this.other_params.token)
            {
                query_params.push('token=' + encodeURIComponent(Cookies.get('csrf_token')));
            } */
            request_uri += (query_params.length > 0 ? '?' : '') + query_params.join('&');
            // Если доступно history api, то подгружаем контент AJAX-ом
            if(typeof window.history === 'object' && history.replaceState)
            {
                history.replaceState(null, null, request_uri);
                if(typeof callback_function === 'function')
                {
                    $.get(request_uri, callback_function, 'xml');
                }
            }
            // В противном случае - перенаправляем
            else
            {
                window.location = request_uri;
            }
        }
    };
    window.Filter.prototype.constructor = window.Filter;
    /**
     * Сгенерировать объект фильтра основываясь на текущем URL
     * @returns {Filter}
     */
    window.Filter.fromCurrent = function() {
        var f = new Filter(location.origin + location.pathname);
        if(location.search)
        {
            var get_params = location.search.replace(/^\?/, '').split('&'),
                counter    = get_params.length, param, values, i;
            while(counter--)
            {
                if(!get_params[counter])
                {
                    continue;
                }
                param = get_params[counter].split('=');
                param[0] = decodeURIComponent(param[0]);
                // Если имя параметра - число, то это атрибут
                if(!isNaN(parseInt(param[0])))
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    // Получаем значения опций
                    values = param[1].split('-');
                    for(i = 0; i < values.length; i++)
                    {
                        values[i] = parseInt(values[i]);
                        if(isNaN(values[i]))
                        {
                            values.splice(i, 1);
                        }
                    }
                    if(values.length)
                    {
                        f.attributes[parseInt(param[0])] = values;
                    }
                }
                // Минимальная цена
                else if(param[0] === 'rmin')
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    values = parseInt(param[1]);
                    if(!isNaN(values))
                    {
                        f.price_min = values;
                    }
                }
                // Максимальная цена
                else if(param[0] === 'rmax')
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    values = parseInt(param[1]);
                    if(!isNaN(values))
                    {
                        f.price_max = values;
                    }
                }
                // Порядок сортировки
                else if(param[0] === 'sort')
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    f.sort = param[1];
                }
                // Количество отображаемых товаров на странице
                else if(param[0] === 'row_by_page')
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    values = parseInt(param[1]);
                    if(!isNaN(values))
                    {
                        f.row_by_page = values;
                    }
                }
                // Cпособа отображения товаров (список/плитка)
                else if(param[0] === 'display')
                {
                    f.display = param[1] === 'list' ? 'list' : 'columns';
                }
                // Поиск
                else if(param[0] === 'keywords')
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    values = decodeURIComponent(param[1]).trim();
                    if(values)
                    {
                        f.search = values;
                    }
                }
                // Поиск только в названии товара
                else if(param[0] === 'filter_slov')
                {
                    if(param.length < 1 || !param[1])
                    {
                        continue;
                    }
                    values = decodeURIComponent(param[1]).trim();
                    if(values)
                    {
                        f.search_name = values;
                    }
                }
                // Все прочие параметры
                else
                {
                    f.other_params[param[0]] = param.length > 1 && param[1] ? decodeURIComponent(param[1]).trim() : undefined;
                }
            }
        }
        return f;
    };
})(window.jQuery || window.Zepto);