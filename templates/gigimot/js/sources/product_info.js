/**
 * Скрипт для страницы товара
 */

(function($){
    'use strict';
    var _document = $(document),
        _body     = $(document.body);
    _document.ready(function(){
        // Оценка на странице товара
        $('.images-and-details .rating a').click(function(){
            $.ajax(this.href, {
                'dataType': 'json',
                'beforeSend': function() {
                    this.date_start = new Date();
                    $('.images-and-details .rating .cover').css('display', 'block');
                },
                'complete': function() {
                    var date_diff = new Date() - this.date_start;
                    // Минимальная задержка в 1 секунду
                    if(date_diff < 1000)
                    {
                        setTimeout(function(){
                            $('.images-and-details .rating .cover').css('display', 'none');
                        }, 1000 - date_diff);
                    }
                    else
                    {
                        $('.images-and-details .rating .cover').css('display', 'none');
                    }
                },
                'success': function(data) {
                    $('.images-and-details .rating .indicator').attr('class', 'indicator value-' + data.rating);
                }
            });
            return false;
        });

        // Пересчитать цену товара в соответствии с выбранными опциями
        var onAttributesChange = function() {
            var base_price   = parseFloat(document.forms.add_to_cart.getAttribute('data-special-price') || document.forms.add_to_cart.getAttribute('data-price')),
                availability = document.forms.add_to_cart.hasAttribute('data-available'),
                availabilityMankovka = document.forms.add_to_cart.hasAttribute('data-mankovka-available'),
                images;
            $('.characteristics :input[name^="id["]').each(function(){
                if(this.nodeName === 'INPUT' && (this.type === 'radio' || this.type === 'checkbox') && !this.checked) {
                    // Пропускаем невыбранные опции флажки и переключатели
                    return;
                } else if(this.nodeName === 'SELECT') {
                    // Вместо селекта берём выбранную опцию
                    var _this = $(this.options[this.selectedIndex]);
                } else {
                    var _this = $(this);
                }

                // Доступность
                availability = availability && _this.attr('data-available') !== undefined;
                availabilityMankovka = availabilityMankovka && _this.attr('data-mankovka-available') !== undefined;
                // Цена
                var price = parseFloat(_this.attr('data-price'));
                if(isNaN(price))
                {
                    return;
                }
                switch(_this.attr('data-prefix'))
                {
                    case '+' :
                        base_price += price;
                        break;
                    case '-' :
                        base_price -= price;
                        break;
                    case '=' :
                        base_price = price;
                        break;
                }
                
                // Изображения
                if(window.options_images && window.options_images[this.value])
                {
                    images = window.options_images[this.value];
                }
            }).promise().done(function(){
                $('.images-and-details .price-block .price').html(currencies.get(base_price));
                if(availability)
                {
                    $('#add-to-cart-form .button-add-to-cart, #add-to-cart-form .button-buy-one-click').show();
                    $('#add-to-cart-form .button-out-of-stock').hide();
                }
                else
                {
                    $('#add-to-cart-form .button-add-to-cart, #add-to-cart-form .button-buy-one-click').hide();
                    $('#add-to-cart-form .button-out-of-stock').show();
                }
                if (availabilityMankovka && !availability) {
                    $('#add-to-cart-form .button-pre-order-click').show();
                    $('.button-out-of-stock').hide();
                    $('.delivery-info').show();
                    
                } else {
                    $('#add-to-cart-form .button-pre-order-click').hide();
                    $('.delivery-info').hide();
                }
                
                // Если у выбранных атрибутов нет изображений, то тиспользуем изображения товара
                if(!(images && images.length))
                {
                    images = window.default_images;
                }
                if(images && images.length)
                {
                    var primary_image = '',
                        secondary_images = '',
                        images_dir_uri = (window.base_url || '/') + 'images/',
                        thumb_script_uri = (window.base_url || '/') + 'r_imgs.php?w=100&h=100&thumb=',
                        i;
                    for(i = 0; i < images.length; i++)
                    {
                        // Первичное изображение
                        if(!i)
                        {
                            primary_image = '<a href="' + (images_dir_uri + encodeURIComponent(images[i])).escapeHtml() + '"><img src="' + ((window.base_url || '/') + 'r_imgs.php?w=380&h=380&thumb=' + encodeURIComponent(images[i])).escapeHtml() + '" alt="" /></a>';
                        }
                        
                        // Вторичные изображения
                        secondary_images += '<a href="' + (images_dir_uri + encodeURIComponent(images[i])).escapeHtml() + '"' + (!i ? ' class="active"' : '') + '><img src="' + (thumb_script_uri + encodeURIComponent(images[i])).escapeHtml() + '" alt="" /></a>';
                    }
                    
                    if(primary_image)
                    {
                        if($('.images-and-details .primary-image a').length)
                        {
                            $('.images-and-details .primary-image a').replaceWith(primary_image);
                        }
                        else
                        {
                            $('.images-and-details .primary-image').append(primary_image);
                        }
                    }
                    
                    if(secondary_images)
                    {
                        if($('.images-and-details .secondary-images').length)
                        {
                            $('.images-and-details .secondary-images').html(secondary_images);
                        }
                        else
                        {
                            $('.images-and-details .images').prepend('<div class="secondary-images">' + secondary_images + '</div>');
                        }
                        
                        if($('.images-and-details .secondary-images').hasClass('ps-container'))
                        {
                            $('.images-and-details .secondary-images').perfectScrollbar('update');
                        }
                        else
                        {
                            $('.images-and-details .secondary-images').perfectScrollbar();
                        }
                    }
                    else
                    {
                        $('.images-and-details .secondary-images').remove();
                    }
                }
            });
        };
        onAttributesChange();
        $('.characteristics select, .characteristics input[type="radio"]').change(onAttributesChange);

        // Купить в 1 клик
        $('.button-buy-one-click, .button-pre-order-click').click(function(){
             
            var preOrderStatus = $(this).data("pre-order");
            console.log(preOrderStatus);
            if($('.buy-one-click-popover').length)
            {
                return false;
            }
            var products_id   = add_to_cart.elements.products_id.value,
                cart_quantity = add_to_cart.elements.cart_quantity.value,
                attributes    = {},
                tittleH1 = '',
                attribute_re  = /^id\[(\d+)\]$/,
                form_items    = '',
                h_input       = function(name, value){
                    return '<input type="hidden" name="' + name.escapeHtml() + '" value="' + value.escapeHtml() + '" />';
                }, i, matches;

            // Найти все выбранные атрибуты товара
            for(i = 0; i < add_to_cart.elements.length; i++)
            {
                if(!add_to_cart.elements[i].name)
                {
                    continue;
                }
                matches = add_to_cart.elements[i].name.match(attribute_re);
                if(!matches || matches[1] in attributes)
                {
                    continue;
                }
                attributes[matches[1]] = add_to_cart.elements[i].value;
            }

            form_items = h_input('products[' + products_id + '][quantity]', cart_quantity);
            for(i in attributes)
            {
                form_items += h_input('products[' + products_id + '][options][' + i + ']', attributes[i]);
            }
            
            if(preOrderStatus){
               tittleH1 = dictionary.PRE_ORDER_POPUP_HEADER_TEXT;
            } else {
                tittleH1 = dictionary.FAST_ORDER_POPUP_HEADER_TEXT;
            }
            
            _body.append('\
<div class="popover buy-one-click-popover">\n\
    <div class="popup buy-one-click-popup">\n\
        <div class="popup-padding">\n\
            <span class="close-popup close-popup-button"></span>\n\
            <div class="popup-content">\n\
                <h1>' + tittleH1 + '</h1>\n\
                <form name="buy_one_click" method="POST" action="' + (window.base_url || '/') + 'fast_order.php">\n\
                    ' + form_items + '\n\
                    <div class="form-group">\n\
                        <input type="text" name="telephone" id="buy-one-click-form-telephone-input" class="form-control" required placeholder="' + dictionary.FAST_ORDER_TELEPHONE_INPUT_LABEL_TEXT + '" />\n\
                        <input type="hidden" name="preOrderStatus" value="' + preOrderStatus  +'" />\n\
                        <div\n\
                            class="help-block"\n\
                            data-text-usual="' + dictionary.FAST_ORDER_POPUP_EXPLANATION_TEXT + '"\n\
                            data-text-required="' + dictionary.FAST_ORDER_TELEPHONE_VALIDATION_ERROR_TEXT + '"\n\
                        >' + dictionary.FAST_ORDER_POPUP_EXPLANATION_TEXT + '</div>\n\
                    </div>\n\
                    <div class="button-block align-right">\n\
                        <button type="submit" class="button">' + dictionary.FAST_ORDER_SUBMIT_FORM_BUTTON_TEXT + '</button>\n\
                        <button type="button" class="button button-red close-popup">' + dictionary.FAST_ORDER_CLOSE_POPUP_BUTTON_TEXT + '</button>\n\
                    </div>\n\
                </form>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>');
            _body.addClass('hide-overflow');
            setTimeout(function(){
                $('.buy-one-click-popover').addClass('visible');
                $(buy_one_click.elements.telephone).mask('+380 (99) 999-99-99', {
                    'translation': {
                        '9': {
                            'pattern': /[0-9]/
                        }
                    },
                    onKeyPress: function(value){
                        var _this = $(buy_one_click.elements.telephone),
                            f_group = _this.parent(),
                            h_block = _this.siblings('.help-block');
                        if(value)
                        {
                            if(/^\+380 \(\d\d\) \d\d\d-\d\d-\d\d$/.test(value))
                            {
                                f_group.removeClass('has-error').addClass('has-success');
                                h_block.text(h_block.attr('data-text-usual'));
                            }
                            else
                            {
                                f_group.removeClass('has-success').addClass('has-error');
                                h_block.text(h_block.attr('data-text-required'));
                            }
                        }
                        else
                        {
                            f_group.removeClass('has-error has-success');
                            h_block.text(h_block.attr('data-text-usual'));
                        }
                    }
                });
            }, 0);

            return false;
        });

        _document.on('submit', 'form[name="buy_one_click"]', function(){
            var _this = $(this),
                addError = function(){
                    if(!_this.find('.alert.alert-error').length)
                    {
                        _this.prepend('<div class="alert alert-error" role="alert">' + dictionary.FAST_ORDER_ALERT_ERROR_TEXT + '</div>');
                    }
                    _this.find('input[type="text"], button').removeAttr('disabled', '');
                };
            $.ajax(this.action, {
                method: 'POST',
                data: _this.serialize(),
                dataType: 'json',
                beforeSend: function(jqXHR) {
                    _this.find('input[type="text"], button').attr('disabled', '');
                    jqXHR.setRequestHeader('X-CSRF-Token', Cookies.get('csrf_token') || '');
                },
                success: function(data) {
                    if(typeof data === 'object' && data.success)
                    {
                        _this.after('\
<div class="alert alert-success" role="alert">' + dictionary.FAST_ORDER_ALERT_SUCCESS_TEXT + '</div>\n\
    <div class="buttons-block align-right">\n\
    <span class="button close-popup">' + dictionary.FAST_ORDER_CLOSE_POPUP_BUTTON_TEXT + '</span>\n\
</div>').remove();
                    }
                    else
                    {   
                        addError();
                    }
                },
                error: addError
            });
            return false;
        });

        // Лайтбокс изображений товара
        _document.on('click', '.images-and-details .images a', function(){
            var images  = $('.images-and-details .images a'),
                _images = [],
                s_index = 0,
                href_re = /^.*?images\/(.*)$/i, image, if_name, href, i;

            if(!images.length)
            {
                return false;
            }


            for(i = 0; i < images.length; i++)
            {
                image = images.eq(i),
                href  = image.attr('href');
                if_name = href.match(href_re);
                if(if_name && _images.indexOf(if_name[1]) < 0)
                {
                    _images.push(if_name[1]);
                }
            }

            if(!_images.length)
            {
                return;
            }

            // Найти индекс изображения, по которому кликнули, в списке всех
            // изображений слайдера, чтобы сделать его активным
            if_name = $(this).attr('href').match(href_re);
            if(if_name)
            {
                for(i = 0; i < _images.length; i++)
                {
                    if(if_name[1] === _images[i])
                    {
                        s_index = i;
                        break;
                    }
                }
            }

            _body.append('\
<div class="popover lightbox-popover">\n\
    <div class="popup lightbox-popup">\n\
        <div class="popup-padding">\n\
            <span class="close-popup close-popup-button"></span>\n\
            <div class="popup-content">\n\
                <div class="arrow arrow-prev"></div>\n\
                <div class="arrow arrow-next"></div>\n\
                <div class="table">\n\
                    <div class="tbody">\n\
                        <div class="trow">\n\
                            <a href="' + (window.base_url || '/') + 'images/' + _images[s_index] + '" data-index="' + s_index + '" class="tcell primary-image">\n\
                                <img src="' + (window.base_url || '/') + 'images/' + _images[s_index] + '" alt="" />\n\
                            </a>\n\
                        </div>\n\
                        <div class="trow">\n\
                            <div class="tcell secondary-images">\n\
                                ' + (function(){
                                    var val = '';
                                    for(i = 0; i < _images.length; i++)
                                    {
                                        val += '\
<a href="' + (window.base_url || '/') + 'images/' + _images[i] + '" data-image="' + _images[i] + '" data-index="' + i + '"' + (i === s_index ? ' class="active"' : '') + '>\n\
<img src="' + (window.base_url || '/') + 'r_imgs.php?thumb=' + encodeURIComponent(_images[i]) + '&amp;w=80&amp;h=80" alt="" />\n\
</a>';
                                    }
                                    return val;
                                })() + '\n\
                            </div>\n\
                        </div>\n\
                    </div>\n\
                </div>\n\
            </div>\n\
        </div>\n\
    </div>\n\
</div>');
            _body.addClass('hide-overflow');
            setTimeout(function(){
                $('.lightbox-popover').addClass('visible');
            }, 0);

            return false;
        });
        
        // Переключение изображений
        _document.on('click', '.lightbox-popup .arrow-next, .lightbox-popup .primary-image', function(e){
            var p_anchor = $('.lightbox-popup .primary-image'),
                n_anchor = $('.lightbox-popup .secondary-images .active').next();
            if(!n_anchor.length)
            {
                n_anchor = $('.lightbox-popup .secondary-images a:first-child');
            }
            p_anchor.attr('href', (window.base_url || '/') + 'images/' + n_anchor.attr('data-image'));
            p_anchor.attr('data-index', n_anchor.attr('data-index'));
            p_anchor.children('img').attr('src', (window.base_url || '/') + 'images/' + n_anchor.attr('data-image'));
            $('.lightbox-popup .secondary-images a')
                    .removeClass('active')
                    .filter('[data-index="' + n_anchor.attr('data-index') + '"]')
                    .addClass('active');
            return false;
        });
        _document.on('click', '.lightbox-popup .arrow-prev', function(){
            var p_anchor = $('.lightbox-popup .primary-image'),
                n_anchor = $('.lightbox-popup .secondary-images .active').prev();
            if(!n_anchor.length)
            {
                n_anchor = $('.lightbox-popup .secondary-images a:last-child');
            }
            p_anchor.attr('href', (window.base_url || '/') + 'images/' + n_anchor.attr('data-image'));
            p_anchor.attr('data-index', n_anchor.attr('data-index'));
            p_anchor.children('img').attr('src', (window.base_url || '/') + 'images/' + n_anchor.attr('data-image'));
            $('.lightbox-popup .secondary-images a')
                    .removeClass('active')
                    .filter('[data-index="' + n_anchor.attr('data-index') + '"]')
                    .addClass('active');
            return false;
        });
        _document.on('click', '.lightbox-popup .secondary-images a', function(){
            var p_anchor = $('.lightbox-popup .primary-image'),
                n_anchor = $(this);
            p_anchor.attr('href', (window.base_url || '/') + 'images/' + n_anchor.attr('data-image'));
            p_anchor.attr('data-index', n_anchor.attr('data-index'));
            p_anchor.children('img').attr('src', (window.base_url || '/') + 'images/' + n_anchor.attr('data-image'));
            n_anchor.addClass('active')
                    .siblings()
                    .removeClass('active');
            return false;
        });
    });
})(window.jQuery || window.Zepto)