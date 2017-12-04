(function($){
    'use strict';
    $(document).ready(function(){
        var _document = $(document),
            _body     = $(document.body),
            _window   = $(window);
            
        // Защита от копирования
        _document.on('copy', function(){
            return false;
        });
        if(!document.getElementsByTagName('img').length)
        {
            document.createElement('img');
        }
        _document.on('contextmenu', 'img', function(){
            return false;
        });
        
        // Если мы на мобильном устройстве, то добавить соответствующий класс к body
        if(window.is_mobile_device)
        {
            _body.addClass('mobile-device');
        }
        
        // Прибить футер к низу страницы
        if(_body.outerHeight() < _window.outerHeight())
        {
            $('.page-footer').css('margin-top', Math.floor(_window.outerHeight() - _body.outerHeight() + parseInt($('.page-footer').css('margin-top'))) + 'px');
        }
        
        var windowScrollEventHandler = function(){
            var nav = $('nav').eq(0),
                navHeight = nav.outerHeight(false),
                headerHeight = $('header').outerHeight(true),
                scrolled = window.pageYOffset || document.documentElement.scrollTop;
            if(scrolled >= headerHeight)
            {
                _body.addClass('nav-fixed').css('margin-top', navHeight + 'px');
            }
            else
            {
                _body.removeClass('nav-fixed').css('margin-top', '');
            }
        }, windowScrollEventHandlerBinded = false;
        
        _window.on('resize', function(){
            if(_window.width() <= 920)
            {
                if(!windowScrollEventHandlerBinded)
                {
                    _window.on('scroll', windowScrollEventHandler);
                    windowScrollEventHandlerBinded = true;
                    windowScrollEventHandler();
                }
            }
            else
            {
                if(windowScrollEventHandlerBinded)
                {
                    _window.off('scroll', windowScrollEventHandler);
                    windowScrollEventHandlerBinded = false;
                    _body.removeClass('nav-fixed').css('margin-top', '');
                }
            }
        }).trigger('resize');
            
        // Переопределяем методы блокировки и разблокировки корзины
        cart.lockFunctions = function() {
            $('.shopping-cart-content .button, .shopping-cart-content .form-control').attr('disabled', '');
        };
        cart.unlockFunctions = function() {
            $('.shopping-cart-content .button, .shopping-cart-content .form-control').removeAttr('disabled');
        };
        
        // Показать всплывающую корзину
        _document.on('click', '.show-cart', function(e){
            cart.show(true);
            return false;
        });
        
        // Обновление маленькой корзины в шапке
        _document.on('cart:updated', function(e, data){
            // $('.page-header .shopping-cart').html(cart_html);
            try
            {
                (new Function('$', data))($);
            }
            catch(e)
            {
                // ...
            }
            if(window.checkout)
            {
                window.checkout.updateCartView();
                window.checkout.updateOrderTotals();
            }
        });
        
        // Всплывающая корзина загрузилась
        _document.on('cart:loaded', function(e, cart_html, show_popup_window){
            if($('.cart-popover').length)
            {
                $('.cart-popup .popup-content').html(cart_html);
            }
            else if(show_popup_window)
            {
                _body.append('<div class="popover cart-popover"><div class="popup cart-popup main-width"><div class="popup-padding"><span class="close-popup close-popup-button"></span><div class="popup-content">' + cart_html + '</div></div></div></div>');
                _body.addClass('hide-overflow');
                setTimeout(function(){
                    $('.cart-popover').addClass('visible');
                }, 0);
            }
            if($(document.body).hasClass('shopping-cart-page'))
            {
                $('main').html(cart_html);
            }
        });
        
        // Скрыть всплывающее окно
        _document.on('click', '.popover, .popover .close-popup', function(){
            $('.popover').one('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd', function(){
                $(this).remove();
                _body.removeClass('hide-overflow');
            }).removeClass('visible');
            return false;
        });
        
        _document.on('click', '.popover .popup', function(e){
            e.stopPropagation();
        });
        
        // Добавить товар в корзину
        _document.on('submit', '.add-to-cart', function(e){
            e.preventDefault();
            var attributes = {}, i, j, options_id, product_id, quantity, re;
            if(this.elements.products_id)
            {
                for(i = 0; i < this.elements.length; i++)
                {
                    // Пропускаем поля с пустым именем и неотмеченные флажки и переключатели
                    if(!this.elements[i].name || (this.elements[i].nodeName === 'INPUT' && (this.elements[i].type === 'checkbox' || this.elements[i].type === 'radio') && !this.elements[i].checked))
                    {
                        continue;
                    }
                    options_id = this.elements[i].name.match(/^id\[(\d+)\]/);
                    if(!options_id)
                    {
                        continue;
                    }
                    attributes[options_id[1]] = this.elements[i].value;
                }
                cart.add(this.elements.products_id.value, this.elements.cart_quantity.value, attributes);
            }
            else if(this.elements['products_id[]'])
            {
                for(i = 0; i < this.elements['products_id[]'].length; i++)
                {
                    product_id = this.elements['products_id[]'][i].value;
                    quantity = this.elements['cart_quantity[' + product_id + ']'].value || 1;
                    attributes = {};
                    re = new RegExp('^id\\[' + product_id + '\\]\\[(\\d+)\\]');
                    for(j = 0; j < this.elements.length; j++)
                    {
                        if(!this.elements[j].name)
                        {
                            continue;
                        }
                        options_id = this.elements[j].name.match(re);
                        if(!options_id)
                        {
                            continue;
                        }
                        attributes[options_id[1]] = this.elements[j].value;
                    }
                    cart.add(product_id, quantity, attributes);
                }
            }
        });
        
        // Обработчики событий, которые инициируются после добвления или удаления товара из корзины
        _document.on('cart:added', function(e, products_id, cart_link, button_text){
            // Заменить кнопку "Купить" на переход в корзину
            $('.products-listing .product-item.product-' + products_id + ' form.add-to-cart button[type="submit"]').replaceWith('<a href="' + cart_link + '" class="button button-blue show-cart">' + button_text + '</a>');
            // Обновить информацию о содержимом корзины
            cart.update();
            // Показать всплывающую корзину
            cart.show(!_body.hasClass('shopping-cart-page'));
        }).on('cart:removed', function(e, product_id, button_text) {
            // Заменить ссылку на переход в корзину на кнопку "Купить"
            $('.products-listing .product-item.product-' + product_id + ' form.add-to-cart a').replaceWith('<button type="submit" class="button">' + button_text + '</button>');
            // Обновить информацию о содержимом корзины
            cart.update();
            // Показать всплывающую корзину
            cart.show(!_body.hasClass('shopping-cart-page'));
        });
        
        // Быстрый поиск
        $(document.forms.quick_search.elements.keywords).on('input', function(){
            var keywords = this.value.trim(),
                token    = this.form.elements.token.value;
            if(keywords.length < 3)
            {
                return;
            }
            $.get((window.base_url || '/') + 'search.php', {
                'q': keywords,
                'token': token,
                'language': window.language
            }, function(data){
                var results_div = $('.page-header .search .results');
                if(!(data instanceof Array))
                {
                    return;
                }
                if(!data.length)
                {
                    results_div.empty();
                }
                var results_html = '<div class="results-wrapper">';
                for(var i = 0; i < data.length; i++)
                {
                    results_html += '<a class="item clearfix" href="' + data[i][2] + '">';
                    // Изображение
                    if(data[i][3])
                    {
                        results_html += '<div class="image"><img src="' + data[i][3] + '" alt="' + data[i][1].escapeHtml() + '" /></div>';
                    }
                    results_html += '<div class="text">';
                    results_html += '<div class="name">' + (data[i][1] || '').escapeHtml() + '</div>';
                    results_html += '<div class="price">' + (data[i][6] ? '<span class="old">' + data[i][6] + '</span>' : '') + '<span class="new">' + data[i][5] + '</span></div>';
                    results_html += '</div></a>';
                }
                results_html += '<a class="see-all-results" href="' + (window.base_url || '/') + 'index.php?keywords=' + encodeURIComponent(keywords) + '&amp;token=' + encodeURIComponent(token) + '">' + dictionary.SHOW_ALL_SRCH_RES + '</a></div>';
                results_div.html(results_html);
            }, 'json');
        }).on('focus', function(){
            $('.page-header .search .results').show();
        }).on('click', function(e){
            e.stopPropagation();
        });
        _document.on('click', function(){
            $('.page-header .search .results').hide();
        }).on('click', '.page-header .search .results .results-wrapper', function(){
            e.stopPropagation();
        });
        
        // Удалить товар из корзины
        _document.on('click', '.shopping-cart-content .remove-item', function(){
            cart.remove(this.value);
            return false;
        });
        
        // Обновить количество какого-то товара в корзине
        _document.on('click', '.shopping-cart-content .recalculate-item', function(){
            var _this       = $(this),
                product_id  = _this.siblings('input[type="hidden"]').val(),
                quantity    = _this.siblings('input[type="number"]').val(), product;
            if(product_id && quantity)
            {
                product = cart.parseProductID(product_id);
                cart.add(product.product_id, quantity, product.attributes);
            }
            return false;
        });
        
        // Отправка формы применения купона
        _document.on('submit', '.shopping-cart-coupon-redeem', function(){
            this.elements.gv_redeem_code.value = this.elements.gv_redeem_code.value.trim();
            if(this.elements.gv_redeem_code.value)
            {
                cart.redeemCoupon(this.elements.gv_redeem_code.value);
            }
            return false;
        });
        
        // Открыть/закрыть левую боковую колонку
        $('.toggle-side-menu').click(function(){
            var column = $('.side-column.column-left');
            if(column.hasClass('visible'))
            {
                column.removeClass('visible');
                _body.removeClass('hide-overflow');
                // $('#compare_box').show();
            }
            else
            {
                column.addClass('visible');
                _body.addClass('hide-overflow');
                // $('#compare_box').hide();
            }
        });
        // Закрыть боковую колонку
        $('.side-column, .side-column .close-column').click(function(){
            $('.side-column.column-left').removeClass('visible');
            _body.removeClass('hide-overflow');
            // $('#compare_box').show();
        });
        $('.side-column .column-wrapper').click(function(e){
            e.stopPropagation();
        });
        
        // Открыть/закрыть главное меню
        $('.toggle-main-menu').click(function(){
            // $('nav .first-level-items').toggleClass('visible').is(':visible') && $('#compare_box').hide() || $('#compare_box').show();
            $('nav .first-level-items').toggleClass('visible');
        });
        $('.first-level-item').click(function(){
            var _this = $(this),
                child_items = _this.siblings('.first-level-children-wrapper');
            if(_body.hasClass('mobile-device') && child_items.length && !child_items.hasClass('visible'))
            {
                child_items.addClass('visible');
                return false;
            }
            return true;
        });
        
        // Всплвающая форма обратной связи
        $('.page-header .callback a').click(function(){
            if(!$('.callback-popover').length)
            {
                $.get((window.base_url || '/') + 'pop_contact_us.php', {language: window.language}, function(data){
                    _body.append('\n\
<div class="popover callback-popover">\n\
    <div class="popup callback-popup">\n\
        <div class="popup-padding">\n\
            <span class="close-popup close-popup-button"></span>\n\
            <div class="popup-content">' + data + '</div>\n\
        </div>\n\
    </div>\n\
</div>\n\
');
                    _body.addClass('hide-overflow');
                    setTimeout(function(){
                        $('.callback-popover').addClass('visible');
                    }, 0);
                }, 'html');
            }
            return false;
        });
        _document.on('submit', 'form[name="contact_us"]', function(){
            var form    = this,
                _form   = $(form),
                fields  = _form.serializeArray(),
                _fields = {},
                i;
            for(i = 0; i < form.elements.length; i++)
            {
                form.elements[i].disabled = true;
            }
            for(i = 0; i < fields.length; i++)
            {
                _fields[fields[i].name] = fields[i].value;
            }
            $.post(form.action, _fields, function(data){
                var i, j, classes, alert;
                if(typeof data === 'object')
                {
                    if(data.success)
                    {
                        _form.children().not('.buttons-block').remove();
                        _form.find('button[type="submit"]').remove();
                        _form.prepend('<div class="alert alert-success">' + data.message + '</div>');
                    }
                    else
                    {
                        alert = _form.children('.alert');
                        if(alert.length)
                        {
                            alert.remove();
                        }
                        _form.prepend('<div class="alert alert-error">' + data.message + '</div>');
                        for(i = 0; i < data.fields_with_errors.length; i++)
                        {
                            for(j = 0; j < form.elements.length; j++)
                            {
                                if(data.fields_with_errors[i] === form.elements[j].name)
                                {
                                    classes = form.elements[j].parentNode.className.split(' ');
                                    if(classes.indexOf('has-error') < 0)
                                    {
                                        classes.push('has-error');
                                    }
                                    form.elements[j].parentNode.className = classes.join(' ');
                                }
                            }
                        }
                    }
                }
                for(i = 0; i < form.elements.length; i++)
                {
                    form.elements[i].disabled = false;
                }
            }, 'json');
            return false;
        });
        
        // Изменение количества товара, добавляемого в корзину
        var manageQuantity = function(input, modifier) {
            if(!input)
            {
                return;
            }
            if(!(input instanceof $))
            {
                input = $(input);
            }
            modifier = modifier || 0;
            var min_order_quantity = parseInt(input.attr('min')) || 1,
                quantity = parseInt(input.val()) + modifier || min_order_quantity;
            if(quantity < min_order_quantity)
            {
                quantity = min_order_quantity;
            }
            input.val(quantity);
        };
        _document.on('change', '.add-to-cart .block-quantity input', function (){
            manageQuantity(this);
        });
        _document.on('click', '.add-to-cart .block-quantity button', function (){
            var _this = $(this);
            manageQuantity(_this.siblings('input').eq(0), _this.hasClass('plus') ? 1 : -1);
            return false;
        });
        
        // Добавление/удаление товара в/из списка сравнения
        /* _document.on('click', '.add-to-compare', function(){
            var _this = $(this),
                uprid = _this.attr('data-uprid'),
                action;
            if(uprid && window.compares instanceof Array)
            {
                action = compares.indexOf(uprid) !== -1 ? 'remove' : 'add';
                $.get((window.base_url || '/') + 'compare.php', {'action': action, 'products_id': uprid, 'language': window.language}, function(data){
                    if(data.status)
                    {
                        var i = compares.indexOf(uprid);
                        if(action === 'add' && i === -1)
                        {
                            compares.push(uprid);
                            _this.addClass('in-comparison').html(dictionary.PRODUCT_LISTING_IN_COMPARISON);
                        }
                        else if(action === 'remove' && i !== -1)
                        {
                            compares.splice(i, 1);
                            _this.removeClass('in-comparison').text(dictionary.PRODUCT_LISTING_ADD_TO_COMPARE);
                        }
                        $('.page-header .icon.icon-compare').text(compares.length);
                    }
                }, 'json');
            }
            return false;
        }); */
        
        // Добавить товар в список желания
        _document.on('click', '.add-to-wishlist', function(){
            var _this = $(this),
                uprid = _this.attr('data-uprid');
            // Если есть ID и кнопка не заблокирована
            if(uprid && !_this.hasClass('processing'))
            {
                var action = _this.hasClass('in-wishlist') ? 'remove' : 'add';
                _this.addClass('processing').text(dictionary.PRODUCT_LISTING_WAIT);
                $.get((window.base_url || '/') + 'wishlist.php', {
                    action: action,
                    products_id: uprid,
                    language: window.language
                }, function(data){
                    if(typeof data === 'object' && data.status)
                    {
                        // Есть ли этот товар в списке желаемого; определить его индекс
                        var wishlist_index = -1;
                        if(window.wishlist)
                        {
                            wishlist_index = window.wishlist.indexOf(uprid);
                        }
                        
                        // Добавить
                        if(action === 'add')
                        {
                            _this.addClass('in-wishlist').html(dictionary.PRODUCT_LISTING_IN_WISHLIST);
                            if(wishlist_index === -1)
                            {
                                window.wishlist.push(uprid);
                            }
                        }
                        // Удалить
                        else if(action === 'remove')
                        {
                            _this.removeClass('in-wishlist').text(dictionary.PRODUCT_LISTING_ADD_TO_WISHLIST);
                            if(wishlist_index !== -1)
                            {
                                window.wishlist.splice(wishlist_index, 1);
                            }
                        }
                        
                        // Обновить отображаемое на странице количество товаров в списке желаний
                        if(window.wishlist)
                        {
                            $('.icon-wishlist').text(window.wishlist.length);
                        }
                    }
                    _this.removeClass('processing');
                }, 'json');
            }
            return false;
        });
        
        // Вкладки
        $('.tabs .tab').click(function(){
            var _this = $(this),
                siblings = _this.siblings('.tab'),
                hide_rules = [], i, sibling, target;
            for(var i = 0; i < siblings.length; i++) {
                sibling = siblings.eq(i);
                target = sibling.attr('data-target');
                if(!target) {
                    continue;
                }
                hide_rules.push(target);
            }
            $(hide_rules.join(', ')).hide();
            $(_this.attr('data-target')).show();
            siblings.removeClass('active');
            _this.addClass('active');
        });
        $('.tabs').each(function(){
            var tabs = $(this).children('.tab'),
                active = tabs.filter('.active');
            if(active.length)
            {
                active.eq(0).trigger('click');
            }
            else
            {
                tabs.eq(0).trigger('click');
            }
        });
        if ($(window).width() <= 414) {
            $(document).on('click', '.second-level-item', function (e) {
                if (!$(this).hasClass('open') && $(this).siblings('.third-level-items').length > 0) {
                    $(this).addClass('open');
                    // $(this).siblings('.third-level-items').addClass('third-level-items-visible');
                    $(this).siblings('.third-level-items').show('slow');
                    e.preventDefault();
                }
            });

            $('a.first-level-item').click(function (e) {
                if ($('.first-level-children-wrapper').hasClass('visible')){
                    $('.first-level-children-wrapper').removeClass('visible');
                }
                if ($('.second-level-item').hasClass('open')){
                    $('.second-level-item').removeClass('open');
                }
                $('.third-level-items').hide();
                $(this).parent().children('.first-level-children-wrapper').addClass('visible');

            });

        }
        if ($(window).width() <= 920 && $(window).width() > 414) {
            $('a.first-level-item').click(function (e) {
                if ($('.first-level-children-wrapper').hasClass('visible')){
                    $('.first-level-children-wrapper').removeClass('visible');
                }
                $(this).parent().children('.first-level-children-wrapper').addClass('visible');
            });
        }
        $('div.main-page-default-specials').perfectScrollbar();
        $('.page-content .category-description.common-styled-block').perfectScrollbar();
    });
    $('.checkout.logined #checkout-form-billing-telephone-input').change(function (e) {
        if(!$(e.target).parent().hasClass('has-error')) {
            var new_phone=$(e.target).val(),
                customer=$(e.target).data('customer');
            $.ajax({
                url: "ajax.php",
                type: "post",
                data: {
                    'new_phone_number': new_phone,
                    'customer': customer
                },
                success: function (response) {
                    console.log(response)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }
    });
})(window.jQuery || window.Zepto);