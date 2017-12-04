(function($){
    /**
     * Прототип для объкта-интерфейса для оформления быстрого заказа
     * @type Object
     */
    var fast_order_prototype = {
        /**
         * Добавляет к быстрому заказу указанный товар в указанном количестве
         * @param {Number} product_id ID товара
         * @param {Number} quantity Количество товара
         * @returns {undefined}
         */
        addProduct : function(product_id, quantity) {
            product_id = parseInt(product_id);
            if(isNaN(product_id) || product_id < 1)
            {
                return;
            }
            quantity = quantity || 1;
            if(!this.products)
            {
                this.products = {};
            }
            if(!this.products[product_id])
            {
                this.products[product_id] = {
                    'quantity' : quantity,
                    'options'  : {}
                };
            }
            else
            {
                this.products[product_id].quantity = quantity;
            }
        },
        /**
         * Добавляет для товара, ранее добавленного к быстрому заказу, атрибут
         * с указанными параметрами опции и значения опции
         * @param {Number} product_id ID товара
         * @param {Number} option_id ID опции
         * @param {Number} option_value_id ID значения опции
         * @returns {undefined}
         */
        addProductAttribute : function(product_id, option_id, option_value_id) {
            product_id = parseInt(product_id);
            if(isNaN(product_id) || product_id < 1)
            {
                return;
            }
            option_id = parseInt(option_id);
            if(isNaN(option_id) || option_id < 1)
            {
                return;
            }
            option_value_id = parseInt(option_value_id);
            if(isNaN(option_value_id) || option_value_id < 1)
            {
                return;
            }
            if(!this.products[product_id].options[option_id])
            {
                this.products[product_id].options[option_id] = [];
            }
            if(this.products[product_id].options[option_id].indexOf(option_value_id) === -1)
            {
                this.products[product_id].options[option_id].push(option_value_id);
            }
        },
        valueOf : function() {
            return this.products;
        }
    };
    /**
     * Конструктор объекта, который представляет собой интерфейс для оформления быстрых заказов
     * @param {String} phone Номер телефона зщаказчика
     * @returns {FastOrder}
     */
    function FastOrder (phone) {
        var self = this;
        if(typeof phone === 'string')
        {
            phone = phone.replace(/\D/g, '');
        }
        if(!phone)
        {
            throw 'Номер телефона не может быть пустым!';
        }
        /**
         * Выполняет запрос, отправляет данные о быстром заказе на сервер
         * @param {Function} callback Callback-функция, которая будет вызвана после того, как запрос будет выполнен
         * @param {Boolean} clear_cart Очистить корзину после оформления заказа?
         * @returns {undefined}
         */
        this.send = function(callback, clear_cart) {
            var me = this;
            if(!this.products)
            {
                throw 'Список товаров не может быть пустым!';
            }
            if(!this.sending)
            {
                this.sending = true;
                $.ajax((window.base_url ? window.base_url : '/') + 'fast_order.php?language=' + encodeURIComponent(window.language), {
                    'type'     : 'POST',
                    'dataType' : 'json',
                    'data'     : {
                        'telephone'  : phone,
                        'products'   : self.products,
                        'clear_cart' : !!clear_cart
                    },
                    'success' : function(data) {
                        this._data = data;
                    },
                    'complete' : function() {
                        if(typeof callback === 'function')
                        {
                            if(!this._data)
                            {
                                this._data = {};
                            }
                            callback.call(null, !!this._data.success, this._data.order_id, this._data.phone);
                        }
                        this.sending = false;
                    }
                });
            }
        };
    };
    FastOrder.prototype = fast_order_prototype;
    FastOrder.prototype.constructor = FastOrder;
    window.FastOrder = FastOrder;
})(window.jQuery || window.Zepto);
