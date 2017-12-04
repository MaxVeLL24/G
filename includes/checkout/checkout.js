var submitter = null;
var paymentVals = new Array();

function submitFunction() {
    submitter = 1;
}

var errCSS = {
    'border-color': 'red',
    'border-style': 'solid'
};

function bindAutoFill($el) {
    if ($el.attr('type') == 'select-one') {
        var method = 'change';
    } else {
        var method = 'blur';
    }

    $el.blur(unsetFocus).focus(setFocus);

    if (document.attachEvent) {
        $el.get(0).attachEvent('onpropertychange', function () {
            if ($(event.srcElement).data('hasFocus') && $(event.srcElement).data('hasFocus') == 'true')
                return;
            if ($(event.srcElement).val() != '' && $(event.srcElement).attr('required') !== undefined) {
                $(event.srcElement).trigger(method);
            }
        });
    } else {
        $el.get(0).addEventListener('onattrmodified', function (e) {
            if ($(e.currentTarget).data('hasFocus') && $(e.currentTarget).data('hasFocus') == 'true')
                return;
            if ($(e.currentTarget).val() != '' && $(e.currentTarget).attr('required') !== undefined) {
                $(e.currentTarget).trigger(method);
            }
        }, false);
    }
}

function setFocus() {
    $(this).data('hasFocus', 'true');
}

function unsetFocus() {
    $(this).data('hasFocus', 'false');
}

var checkout = {
    charset: 'utf8',
    pageLinks: {},
    errors: true,
    checkoutClicked: false,
    amountRemaininginTotal: true,
    billingInfoChanged: false,
    shippingInfoChanged: false,
    fieldSuccessHTML: '<div class="success_icon ui-icon-green ui-icon-circle-check"></div>',
    fieldErrorHTML: '<div class="error_icon ui-icon-red ui-icon-circle-close"></div>',
    fieldRequiredHTML: '<div class="required_icon ui-icon-red ui-icon-gear"></div>',
    showAjaxLoader: function () {
        if (this.showMessagesPopUp == true)
        {
            $('#ajaxMessages').dialog('open');
        }
        $('#ajaxLoader').show();
    },
    hideAjaxLoader: function () {
        $('#ajaxLoader').hide();
        if (this.showMessagesPopUp == true)
        {
            $('#ajaxMessages').dialog('close');
        }
    },
    showAjaxMessage: function (message) {
        var c_btn  = $('#checkoutButton'),
            nn_btn = $('#notNowButton');
        c_btn.attr('disabled', '');
        nn_btn.addClass('button-disabled');
        c_btn.text(c_btn.attr('data-text-wait'));
    },
    hideAjaxMessage: function () {
        var c_btn  = $('#checkoutButton'),
            nn_btn = $('#notNowButton');
        c_btn.removeAttr('disabled');
        nn_btn.removeClass('button-disabled');
        c_btn.text(c_btn.attr('data-text-submit'));
    },
    fieldErrorCheck: function ($element, forceCheck, hideIcon) {

        forceCheck = forceCheck || false;
        hideIcon = hideIcon || false;
        var errMsg = this.checkFieldForErrors($element, forceCheck);
        if (hideIcon == false) {
            if (errMsg != false) {
                this.addIcon($element, 'error', errMsg);
                return true;
            } else {
                this.addIcon($element, 'success', errMsg);
            }
        } else {
            if (errMsg != false) {
                return true;
            }
        }
        return false;
    },
    checkFieldForErrors: function ($element, forceCheck) {
        var hasError = false,
            name     = $element.attr('name'),
            value    = $element.val().trim();
        if ($element.is(':visible') && ($element.attr('required') !== undefined || forceCheck == true)) {
            var errCheck = getFieldErrorCheck($element);
            if (!errCheck.errMsg) {
                return false;
            }

            switch ($element.attr('type')) {
                case 'password':
                    if (name === 'password') {
                        if (value.length < errCheck.minLength) {
                            hasError = true;
                        }
                    } else {
                        if (value != $(':password[name="password"]', $('#billingAddress')).val() || value.length <= 0) {
                            hasError = true;
                        }
                    }
                    break;
                case 'radio':
                    if ($(':radio[name="' + name + '"]:checked').size() <= 0) {
                        hasError = true;
                    }
                    break;
                case 'checkbox':
                    if ($(':checkbox[name="' + name + '"]:checked').size() <= 0) {
                        hasError = true;
                    }
                    break;
                case 'select-one':
                    if (value === '') {
                        hasError = true;
                    }
                    break;
                default:
                    if(name === 'billing_email_address')
                    {
                        if(/@/.test(value))
                        {
                            $.post((window.base_url || '/') + 'checkout.php', {
                                'action': 'checkEmailAddress',
                                'emailAddress': value
                            }, function(data) {
                                if(typeof data !== 'object')
                                {
                                    return;
                                }
                                if(data.success)
                                {
                                    checkout.addIcon($element, 'success');
                                }
                                else
                                {
                                    checkout.addIcon($element, 'error', data.error_message);
                                }
                            });
                        }
                        else
                        {
                            hasError = true;
                        }
                    }
                    else if(name === 'billing_telephone' && !/^\+380 \(\d\d\) \d\d\d-\d\d-\d\d$/.test(value))
                    {
                        hasError = true;
                    }
                    else if (value.length < errCheck.minLength)
                    {
                        hasError = true;
                    }
                    break;
            }
            if (hasError == true) {
                return errCheck.errMsg;
            }
        }
        return hasError;
    },
    addIcon: function ($curField, iconType, title) {
        title = title || false;
        $('.success_icon, .error_icon, .required_icon', $curField.parent()).hide();
        switch (iconType) {
            case 'error':
                if (this.initializing == true) {
                    this.addRequiredIcon($curField, 'Required');
                } else {
                    this.addErrorIcon($curField, title);
                }
                break;
            case 'success':
                this.addSuccessIcon($curField, title);
                break;
            case 'required':
                this.addRequiredIcon($curField, 'Required');
                break;
        }
    },
    addSuccessIcon: function ($curField, title) {
        $curField.parents('.form-group').removeClass('has-error').addClass('has-success');
        checkout.setHelpText($curField, title);
    },
    addErrorIcon: function ($curField, title) {
        $curField.parents('.form-group').removeClass('has-success').addClass('has-error');
        checkout.setHelpText($curField, title);
    },
    setHelpText: function(e, t)
    {
        var hb = e.siblings('.help-block');
        if(!hb.length)
        {
            return;
        }
        if(t)
        {
            hb.show().html(t);
        }
        else
        {
            hb.hide();
        }
    },
    addRequiredIcon: function ($curField, title) {
        /* if ($curField.attr('required') !== undefined) {
            if ($('.required_icon', $curField.parent()).size() <= 0) {
                $curField.parent().append(this.fieldRequiredHTML);
            }
            $('.required_icon', $curField.parent()).attr('title', title).show();
        } */
    },
    /**
     * Выполняет AJAX-запрос с указанными параметрами
     * @param {Object} options Массив опций
     * @returns {undefined}
     */
    queueAjaxRequest: function (options) {
        $.ajax({
            url: options.url,
            cache: options.cache || false,
            dataType: options.dataType || 'html',
            type: options.type || 'GET',
            data: options.data || false,
            beforeSend: function () {
                checkout.showAjaxMessage(options.beforeSendMsg);
                checkout.showAjaxLoader();
                if(typeof options.beforeSend === 'function')
                {
                    options.beforeSend.apply(this, [].slice.call(arguments));
                }
            },
            complete: function () {
                checkout.hideAjaxMessage();
                checkout.hideAjaxLoader();
                if(typeof options.complete === 'function')
                {
                    options.complete.apply(this, [].slice.call(arguments));
                }
            },
            success: options.success
        });
    },
    /**
     * Обновляет общую стоиомость заказа
     * @returns {undefined}
     */
    updateOrderTotals: function () {
        checkout.queueAjaxRequest({
            url: checkout.pageLinks.checkout,
            cache: false,
            data: {
                'action': 'getOrderTotals',
                'token': Cookies.get('csrf_token') || ''
            },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $('.checkout-right .order-totals').html(data);
            }
        });
    },
    /**
     * Обновляет способы доставки
     * @param {Boolean} noOrdertotalUpdate Передайте TRUE, если не нужно обновлять
     * общую стоиомость заказа после обновления способов оплаты
     * @returns {Boolean}
     */
    updateShippingMethods: function (noOrdertotalUpdate) {
        if (!checkout.shippingEnabled)
        {
            return false;
        }
        checkout.queueAjaxRequest({
            url: checkout.pageLinks.checkout,
            data: {
                'action': 'updateShippingMethods',
                'token': Cookies.get('csrf_token') || ''
            },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $('#shippingMethods').replaceWith(data);
                if(!noOrdertotalUpdate)
                {
                    checkout.updateOrderTotals();
                }
            }
        });
    },
    /**
     * Обновляет способы оплаты
     * @param {Boolean} noOrdertotalUpdate Передайте TRUE, если не нужно обновлять
     * общую стоиомость заказа после обновления способов оплаты
     * @returns {undefined}
     */
    updatePaymentMethods: function (noOrdertotalUpdate) {
        checkout.queueAjaxRequest({
            url: checkout.pageLinks.checkout,
            data: {
                'action': 'updatePaymentMethods',
                'token': Cookies.get('csrf_token') || ''
            },
            type: 'POST',
            dataType: 'html',
            success: function (data) {
                $('#paymentMethods').replaceWith(data);
                checkout.setPaymentMethod();
                if(!noOrdertotalUpdate)
                {
                    checkout.updateOrderTotals();
                }
            }
        });
    },
    /**
     * Фиксирует выбранный метод доставки
     * @returns {Boolean}
     */
    setShippingMethod: function () {
        if (!checkout.shippingEnabled)
        {
            return false;
        }
        
        // В зависимости от способа доставки показываем дополнительные поля
        var selected_shipping_method;
        for(var i = 0; i < document.forms.checkout_form.elements.shipping.length; i++)
        {
            if(document.forms.checkout_form.elements.shipping[i].checked)
            {
                selected_shipping_method = document.forms.checkout_form.elements.shipping[i].value;
                break;
            }
        }

        // Если доставка куръером, то показываем поле "Адрес"
        if (selected_shipping_method === 'flat_flat')
        {
            $('#checkout-form-billing-suburb-input').removeAttr('required').parent().fadeOut(0);
            $('#checkout-form-billing-street-address-input').attr('required', '').parent().fadeIn(100);
        }
        // Если доставка Новой Почтой, то показываем поле "Номер отделения"
        else if (selected_shipping_method === 'nwpochta_nwpochta')
        {  
            $('#checkout-form-billing-street-address-input').removeAttr('required').parent().fadeOut(0);
            $('#checkout-form-billing-suburb-input').attr('required', '').parent().fadeIn(100);
        }
        // Во всех остальных случаях поля "Адрес" и "Номер отделения" не отображаются
        else
        {
            $('#checkout-form-billing-street-address-input').removeAttr('required').parent().fadeOut(0);
            $('#checkout-form-billing-suburb-input').removeAttr('required').parent().fadeOut(0);
        }
        
        checkout.queueAjaxRequest({
            url: checkout.pageLinks.checkout,
            data: {
                'action': 'setShippingMethod',
                'method': selected_shipping_method,
                'token': Cookies.get('csrf_token') || ''
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if(typeof data === 'object' && data.success)
                {
                    // Обновляем методы оплаты на тот случай, если включен ship2pay
                    checkout.updatePaymentMethods();
                    // Общую стоимость заказа не обновляем (в отличии от метода setPaymentMethod),
                    // так как она обновляется потом, из метода updatePaymentMethods
                }
            }
        });
    },
    /**
     * Фиксирует выбранный метод оплаты
     * @returns {undefined}
     */
    setPaymentMethod: function () {
        // Находим выбранный способ оплаты
        var selected_payment_method;
        for(var i = 0; i < document.forms.checkout_form.elements.payment.length; i++)
        {
            if(document.forms.checkout_form.elements.payment[i].checked)
            {
                selected_payment_method = document.forms.checkout_form.elements.payment[i].value;
                break;
            }
        }
        
        checkout.queueAjaxRequest({
            url: checkout.pageLinks.checkout,
            data: {
                'action': 'setPaymentMethod',
                'method': selected_payment_method,
                'token': Cookies.get('csrf_token') || ''
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                if(typeof data === 'object' && data.success)
                {
                    // Обновляем общую стоимость заказа, так как на выбранный
                    // метод оплаты может быть назначена скидка
                    checkout.updateOrderTotals();
                }
            }
        });
    },
    processBillingAddress: function (skipUpdateTotals) {
        var hasError = false;
        
        $('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"], input[id="checkoutButton"], *[name="billing_state"]', $('#billingAddress')).each(function () {
            if (checkout.fieldErrorCheck($(this), false, true) == true) {
                hasError = true;
            }
        });
        if (hasError == true) {
            return;
        }

        this.setBillTo();

        if (skipUpdateTotals == true)
        {
            //        this.updatePaymentMethods(true);
            //        this.updateShippingMethods(true);
            //        this.updateOrderTotals();
        }

    },
    processShippingAddress: function (skipUpdateTotals) {
        var hasError = false;
        
        $('select[name="shipping_country"], input[name="shipping_street_address"], input[name="shipping_zipcode"], input[name="shipping_city"]', $('#shippingAddress')).each(function () {
            if (checkout.fieldErrorCheck($(this), false, true) == true) {
                hasError = true;
            }
        });
        if (hasError === true) {
            return;
        }

        this.setSendTo(true);
        if (this.shippingEnabled === true && skipUpdateTotals !== true) {
            this.updateShippingMethods(true);
        }
        if (skipUpdateTotals === true)
        {
            //        this.updatePaymentMethods(true);
            //        this.updateShippingMethods(true);
            //        this.updateOrderTotals();
        }
    },
    setCheckoutAddress: function (type, useShipping) {
        
        var selector = '#' + type + 'Address';
        var sendMsg = rCheckoutErrors.chk_set_address + (type == 'shipping' ? 'Shipping' : 'Payment');
        var errMsg = type + ' address';
        if (type == 'shipping' && useShipping == false) {
            selector = '#billingAddress';
            sendMsg = rCheckoutErrors.chk_set_shipping_address;
            errMsg = rCheckoutErrors.chk_payment_address;
        }

        action = 'setBillTo';
        if (type == 'shipping') {
            action = 'setSendTo';
        }

        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            cache: false,
            beforeSendMsg: sendMsg,
            dataType: 'json',
            data: 'action=' + action + '&' + $('*', $(selector)).serialize(),
            type: 'post'
                    //        ,
                    //    errorMsg: 'There was an error updating your ' + errMsg + ', please inform ' + checkout.storeName + ' about this error.'
        });
    },
    setBillTo: function () {
        this.setCheckoutAddress('billing', false);
    },
    setSendTo: function (useShipping) {
        this.setCheckoutAddress('shipping', useShipping);
    },
    checkAllErrors: function () {
        
        var errMsg = '';
        if ($('.required_icon:visible', $('#billingAddress')).size() > 0) {
            errMsg += rCheckoutErrors.chk_plz_fill_paymnet + "\n";
        }

        if ($('.error_icon:visible', $('#billingAddress')).size() > 0) {
            errMsg += rCheckoutErrors.chk_plz_check_correct + "\n";
        }

        if ($('#diffShipping:checked').size() > 0) {
            if ($('.required_icon:visible', $('#shippingAddress')).size() > 0) {
                errMsg += rCheckoutErrors.chk_plz_fill_shipping + "\n";
            }

            if ($('.error_icon:visible', $('#shippingAddress')).size() > 0) {
                errMsg += rCheckoutErrors.chk_plz_check_shipping + "\n";
            }
        }

        if (errMsg != '') {
            errMsg = '------------------------------------------------' + "\n" +
                    '                 ' + rCheckoutErrors.chk_address_error + '                 ' + "\n" +
                    '------------------------------------------------' + "\n" +
                    errMsg;
        }

        if (checkout.amountRemaininginTotal == true) {
            if ($(':radio[name="payment"]:checked').size() <= 0) {
                if ($('input[name="payment"]:hidden').size() <= 0) {
                    errMsg += '------------------------------------------------' + "\n" +
                            '           ' + rCheckoutErrors.chk_err_set_payment + '              ' + "\n" +
                            '------------------------------------------------' + "\n" +
                            rCheckoutErrors.chk_choose_payment + "\n";
                }
            }
        }


        if (checkout.shippingEnabled === true) {
            if ($(':radio[name="shipping"]:checked').size() <= 0) {
                if ($('input[name="shipping"]:hidden').size() <= 0) {
                    errMsg += '------------------------------------------------' + "\n" +
                            '           ' + rCheckoutErrors.chk_err_set_payment + '             ' + "\n" +
                            '------------------------------------------------' + "\n" +
                            rCheckoutErrors.chk_choose_payment + "\n";
                }
            }
        }
        if (checkout.ccgvInstalled == true)
        {
            if ($('input[name="gv_redeem_code"]').val() == 'redeem code')
            {
                $('input[name="gv_redeem_code"]').val('');
            }
        }

        if (checkout.kgtInstalled == true)
        {
            if ($('input[name="coupon"]').val() == 'redeem code')
            {
                $('input[name="coupon"]').val('');
            }
        }

        if (errMsg.length > 0) {
            checkout.errors = true;
            alert(errMsg);
            return false;
        } else {
            checkout.errors = false;
            //  if (checkout.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).size() <= 0 && checkout.loggedIn != true){
            if (checkout.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).size() <= 0) {
                //errMsg += 'You tried to checkout without first clicking update. We have updated for you. Please review your order to make sure it is correct and click checkout again.' + "\n";
                checkout.processBillingAddress();
                checkout.billingInfoChanged = false;
            }
            return true;
        }
    },
    initCheckout: function () {
        if(checkout.shippingEnabled)
        {
            checkout.setShippingMethod();
            
            // Переключение способов доставки
            $(document).on('change', '#shippingMethods input', checkout.setShippingMethod);
        }
        else
        {
            checkout.setPaymentMethod();
            checkout.updateOrderTotals();
        }
            
        // Переключение способов оплаты
        $(document).on('change', '#paymentMethods input', checkout.setPaymentMethod);
        
        $('input', $('#billingAddress')).add('#checkout-form-billing-suburb-input, #checkout-form-billing-street-address-input').each(function () {
            if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio') {
                if ($(this).attr('type') == 'password') {
                    $(this).blur(function () {
                        if ($(this).attr('required') !== undefined) {
                            checkout.fieldErrorCheck($(this));
                        }
                    });
                    /* Used to combat firefox 3 and it's auto-populate junk */
                    $(this).val('');

                    if ($(this).attr('name') == 'password') {
                        $(this).focus(function () {
                            $(':password[name="confirmation"]').val('');
                        });
                        var rObj = getFieldErrorCheck($(this));
                    }
                } else {
                    //      $(this).keyup(function (){
                    $(this).blur(function () {
                        checkout.billingInfoChanged = true;
                        if ($(this).attr('required') !== undefined) {
                            checkout.fieldErrorCheck($(this));
                        }
                    });
                    if ($(this).attr('name') != 'billing_email_address') {
                        $(this).keyup(function () {
                            checkout.billingInfoChanged = true;
                            if ($(this).attr('required') !== undefined) {
                                checkout.fieldErrorCheck($(this));
                            }
                        });
                    }
                    bindAutoFill($(this));
                }

                if ($(this).attr('required') !== undefined) {
                    checkout.billingInfoChanged = true;
                    if (checkout.fieldErrorCheck($(this), true, true) == false) {
                        checkout.addIcon($(this), 'success');
                    } else {
                        checkout.addIcon($(this), 'required');
                    }
                }
            }
        });
        
        // Отправка формы
        $('#onePageCheckoutForm').submit(function() {
            return checkout.checkAllErrors();
        });

        if (this.loggedIn == true && this.showAddressInFields == true) {
            $('*[name="billing_state"]').trigger('change');
            $('*[name="delivery_state"]').trigger('change');
        }

        this.initializing = false;
    },
    updateCartView: function() {
        this.queueAjaxRequest({
            url: checkout.pageLinks.checkout,
            data: {
                'action': 'updateCartView',
                'token': Cookies.get('csrf_token') || ''
            },
            'type': 'POST',
            'dataType': 'html',
            'success': function(data) {
                $('.checkout-cart').replaceWith(data);
            }
        });
    }
};