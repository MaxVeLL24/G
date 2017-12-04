(function($){
    /**
     * window.document в обёртке jQuery
     * @type {jQuery|Zepto}
     */
    var _document = $(document);
    /**
     * Пространство имён, содержащее методы для работы с корзиной
     * @namespace
     */
    var cart = {
        /**
         * Добавляет товар в корзину
         * @param {Number} products_id ID товара, добавляемого в корзину
         * @param {Number} quantity Количество товара, добавляемого в корзину
         * @param {Object} options Перечень выбранных опций товара, добавляемого в корзину в виде option_id: option_value_id
         * @returns {undefined}
         */
        add : function(products_id, quantity, options) {
            this.lockFunctions();
            $.post((window.base_url || '/') + 'cart.php?language=' + encodeURIComponent(window.language), {
                'action': 'add',
                'product': products_id,
                'quantity': quantity,
                'options': options,
                'token': Cookies.get('csrf_token') || ''
            }, function(data) {
                if(typeof data === 'object' && data.status)
                {
                    // Инициируем событие, извещающее о том, что в корзину был добавлен новый товар
                    _document.trigger('cart:added', [products_id, data.cart_link, data.button_text]);
                }
                else
                {
                    cart.unlockFunctions();
                }
            }, 'json');
        },
        /**
         * Удаляет указанный товар из корзины
         * @param {Number} products_id ID товара, который необходимо удалить из корзины,
         * в том виде, в котором они хранятся в корзине:
         * product_id{option_id}option_value_id{option_id}option_value_id,
         * например:
         * 15{67}89{90}12
         * @returns {undefined}
         */
        remove: function(products_id) {
            this.lockFunctions();
            // Обновить маленькую корзину
            $.post((window.base_url || '/') + 'cart.php?language=' + encodeURIComponent(window.language), {
                'action': 'remove',
                'product': products_id,
                'token': Cookies.get('csrf_token') || ''
            }, function(data) {
                if(typeof data === 'object' && data.status)
                {
                    // Инициируем событие, извещающее о том, что из корзины был удалён товар
                    _document.trigger('cart:removed', [cart.parseProductID(products_id)['product_id'], data.button_text]);
                }
                else
                {
                    cart.unlockFunctions();
                }
            }, 'json');
        },
        /**
         * Показать всплывающее окно корзины
         * @parma {Boolean} Показать всплывающую корзину или нет. По-умолчанию: нет.
         * @returns {undefined}
         */
        show : function(show_popup_window) {
            this.lockFunctions();
            $.get((window.base_url || '/') + 'shopping_cart.php', {language: window.language}, function(data) {
                _document.trigger('cart:loaded', [data, show_popup_window || false]);
                cart.unlockFunctions();
            }, 'html');
        },
        /**
         * Запросить информацию о количестве и общей стоиомсти товаров в корзине
         * @returns {undefined}
         */
        update : function() {
            this.lockFunctions();
            $.get((window.base_url || '/') + 'cart.php', {
                'action':   'get_total',
                'language': window.language,
                'token':    Cookies.get('csrf_token') || ''
            }, function(data) {
                _document.trigger('cart:updated', [data]);
                cart.unlockFunctions();
            });
        },
        /**
         * Парсит строку, содержащую ID-шник товара в том виде, в которм он хранится в корзине,
         * и возвращает его компоненты: собственно ID товара и перечень ID-шников выбранных
         * опций и их значений.
         * @param {String} product_id Строка, содержащая ID-шник товара в том виде, в которм
         * он хранится в корзине.
         * @returns {Object|null} Возвращает объект, в свойстве product_id которого содержится
         * ID-шник товара, а в свойстве attributes - объект, именами свойств которого есть ID-шники
         * выбранных опций, а их значениями - ID-шники значений этих опций.
         */
        parseProductID: function(product_id) {
            var result = {},
                _product_id = /^(\d+)/.exec(product_id),
                attributes_re = /\{(\d+)\}(\d+)/g,
                attributes = {},
                _attributes;
            if(_product_id && (_product_id = parseInt(_product_id[1])))
            {
                result['product_id'] = _product_id;
                while((_attributes = attributes_re.exec(product_id)))
                {
                    _attributes[1] = parseInt(_attributes[1]);
                    _attributes[2] = parseInt(_attributes[2]);
                    if(_attributes[1] && _attributes[2])
                    {
                        attributes[_attributes[1]] = _attributes[2];
                    }
                }
                if(attributes)
                {
                    result['attributes'] = attributes;
                }
            }
            return result.product_id || result.attributes ? result : null;
        },
        /**
         * Генерирует ID товара в виде, в котором они зранятся в корзине.
         * @param {Number} products_id ID-товара
         * @param {Object} attributes Перечень выбранных опций
         * @returns {String}
         */
        generateProductID: function(products_id, attributes) {
            var i, _i, j;
            if(typeof attributes === 'object')
            {
                for(i in attributes)
                {
                    _i = parseInt(i);
                    if(!isNaN(_i))
                    {
                        j = parseInt(attributes[_i]);
                        if(!isNaN(j))
                        {
                            products_id += '{' + _i + '}' + j;
                        }
                    }
                }
            }
            return products_id;
        },
        /**
         * Применяет указанный код купона
         * @param {String} coupon_code
         * @returns {undefined}
         */
        redeemCoupon : function(coupon_code) {
            cart.lockFunctions();
            $.post((window.base_url || '/') + 'shopping_cart.php?language=' + encodeURIComponent(window.language), {gv_redeem_code: coupon_code}, function(){
                cart.update();
                cart.show(!$(document.body).hasClass('shopping-cart-page'));
            }, 'json');
        },
        /**
         * Вызывается, чтобы заблокировать функции корзины при выполнении AJAX запроса.
         * Этот метод необходимо переопределить, добавив в него логику, соответствующую
         * данной задаче.
         * @returns {undefined}
         */
        lockFunctions: function() {
            
        },
        /**
         * Вызывается, чтобы разблокировать функции корзины после того, как AJAX-запрос будет выполнен.
         * Этот метод необходимо переопределить, добавив в него логику, соответствующую
         * данной задаче.
         * @returns {undefined}
         */
        unlockFunctions: function() {
            
        }
    };
    window.cart = cart;
})(window.jQuery || window.Zepto);