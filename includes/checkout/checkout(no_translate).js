var submitter = null;
var paymentVals = new Array();

function echeck(str) {

        var at="@"
        var dot="."
        var lat=str.indexOf(at)
        var lstr=str.length
        var ldot=str.indexOf(dot)
        if (str.indexOf(at)==-1){
           return false
        }

        if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
           return false
        }

        if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
            return false
        }

         if (str.indexOf(at,(lat+1))!=-1){
            return false
         }

         if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
            return false
         }

         if (str.indexOf(dot,(lat+2))==-1){
            return false
         }
        
         if (str.indexOf(" ")!=-1){
            return false
         }
     
  var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
if (!(filter.test(str))) {return false}

          return true                    
    }

function submitFunction() {
    submitter = 1;
}

var errCSS = {
    'border-color': 'red',
    'border-style': 'solid'
};

function bindAutoFill($el){
    if ($el.attr('type') == 'select-one'){
        var method = 'change';
    }else{
        var method = 'blur';
    }
    
    $el.blur(unsetFocus).focus(setFocus);
    
    if (document.attachEvent){
        $el.get(0).attachEvent('onpropertychange', function (){
            if ($(event.srcElement).data('hasFocus') && $(event.srcElement).data('hasFocus') == 'true') return;
            if ($(event.srcElement).val() != '' && $(event.srcElement).hasClass('required')){
                $(event.srcElement).trigger(method);
            }
        });
    }else{
        $el.get(0).addEventListener('onattrmodified', function (e){
            if ($(e.currentTarget).data('hasFocus') && $(e.currentTarget).data('hasFocus') == 'true') return;
            if ($(e.currentTarget).val() != '' && $(e.currentTarget).hasClass('required')){
                $(e.currentTarget).trigger(method);
            }
        }, false);
    }
}

function setFocus(){
    $(this).data('hasFocus', 'true');
}

function unsetFocus(){
    $(this).data('hasFocus', 'false');
}

var checkout = {
    charset: 'utf8',
    pageLinks: {},
    errors:true,
    checkoutClicked:false,
    amountRemaininginTotal:true,
    billingInfoChanged: false,
    shippingInfoChanged: false,
    fieldSuccessHTML: '<div class="success_icon ui-icon-green ui-icon-circle-check"></div>',
    fieldErrorHTML: '<div class="error_icon ui-icon-red ui-icon-circle-close"></div>',
    fieldRequiredHTML: '<div class="required_icon ui-icon-red ui-icon-gear"></div>',
    showAjaxLoader: function (){
        if(this.showMessagesPopUp == true)
        {
            $('#ajaxMessages').dialog('open');
        }
        $('#ajaxLoader').show();
    },
    hideAjaxLoader: function (){
        $('#ajaxLoader').hide();
        if(this.showMessagesPopUp == true)
        {
            $('#ajaxMessages').dialog('close');
        }
    },
    showAjaxMessage: function (message){
   
            // $('#checkoutButtonContainer').hide();
        $('#checkoutButtonContainer').find('.btn').addClass('unactive');   
         

        $('#ajaxMessages').show().html('<center><img src="/includes/javascript/onepage/ui/redmond/images/ajax_load.gif"><br>' + message + '</center>');
        
            //$('#ajaxMessages').show().html('<center>Пожалуйста, проверьте еще раз свой заказ и нажмите на кнопку Продолжить<br><img src="ext/jquery/ui/redmond/images/ajax_load.gif"><br></center>');

    },
	hideAjaxMessage: function (){

	// raid ------ минимальный заказ!!!---------------- //		 
	if($('#minsum').length) {
	   $('#minimal_sum').html($('#minsum').val());
       $('#checkoutButtonContainer_minimal').css('display','block');
   } 
	// raid ------ минимальный заказ!!!---------------- //	
   else {
     // $('#checkoutButtonContainer').show();
     $('#checkoutButtonContainer').find('.btn').removeClass('unactive');
     $('#checkoutButtonContainer_minimal').css('display','none');
   }  
	 //$('#checkoutButtonContainer').show();
	
   $('#ajaxMessages').hide();
		
	},
    fieldErrorCheck: function ($element, forceCheck, hideIcon){
        
        forceCheck = forceCheck || false;
        hideIcon = hideIcon || false;
        var errMsg = this.checkFieldForErrors($element, forceCheck);
        if (hideIcon == false){
            if (errMsg != false){
                this.addIcon($element, 'error', errMsg);
                return true;
            }else{
                this.addIcon($element, 'success', errMsg);
                }
        }else{
            if (errMsg != false){
                return true;
            }
        }
        return false;
    },
    checkFieldForErrors: function ($element, forceCheck){
        var hasError = false;
        if ($element.is(':visible') && ($element.hasClass('required') || forceCheck == true)){
            var errCheck = getFieldErrorCheck($element);
            if (!errCheck.errMsg){
                return false;
            }

            switch($element.attr('type')){
                case 'password':
                if ($element.attr('name') == 'password'){
                    if ($element.val().length < errCheck.minLength){
                        hasError = true;
                    }
                }else{
                    if ($element.val() != $(':password[name="password"]', $('#billingAddress')).val() || $element.val().length <= 0){
                        hasError = true;
                    }
                }
                break;
                case 'radio':
                if ($(':radio[name="' + $element.attr('name') + '"]:checked').size() <= 0){
                    hasError = true;
                }
                break;
                case 'checkbox':
                if ($(':checkbox[name="' + $element.attr('name') + '"]:checked').size() <= 0){
                    hasError = true;
                }
                break;
                case 'select-one':
                if ($element.val() == ''){
                    hasError = true;
                }
                break;
                default:
                if ($element.val().length < errCheck.minLength){
                    hasError = true;
                } else
        if (($element.attr('name') == 'billing_email_address') && (!(echeck($element.val())))) {
            hasError = true;
        
        }
                   
        
                break;
            }
            if (hasError == true){
                return errCheck.errMsg;
            }
        }
        return hasError;
    },
    addIcon: function ($curField, iconType, title){
        title = title || false;
        $('.success_icon, .error_icon, .required_icon', $curField.parent()).hide();
        switch(iconType){
            case 'error':
            if (this.initializing == true){
                this.addRequiredIcon($curField, 'Required');
            }else{
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
    addSuccessIcon: function ($curField, title){
        if ($('.success_icon', $curField.parent()).size() <= 0){
            $curField.parent().append(this.fieldSuccessHTML);
        }
        $('.success_icon', $curField.parent()).attr('title', title).show();
    },
    addErrorIcon: function ($curField, title){
        if ($('.error_icon', $curField.parent()).size() <= 0){
            $curField.parent().append(this.fieldErrorHTML);
        }
        $('.error_icon', $curField.parent()).attr('title', title).show();
    },
    addRequiredIcon: function ($curField, title){
        if ($curField.hasClass('required')){
            if ($('.required_icon', $curField.parent()).size() <= 0){
                $curField.parent().append(this.fieldRequiredHTML);
            }
            $('.required_icon', $curField.parent()).attr('title', title).show();
        }
    },
    clickButton: function (elementName){
        if ($(':radio[name="' + elementName + '"]').size() <= 0){
            $('input[name="' + elementName + '"]').trigger('click', true);
        }else{
            $(':radio[name="' + elementName + '"]:checked').trigger('click', true);
         //   console.log(111);
        }
    
    },
    addRowMethods: function($row){
        
        var checkoutClass2 = this;
        $row.hover(function (){
            if (!$(this).hasClass('moduleRowSelected')){
                $(this).addClass('moduleRowOver');
            }
        }, function (){
            if (!$(this).hasClass('moduleRowSelected')){
                $(this).removeClass('moduleRowOver');
            }
        }).click(function (){ 
            if (!$(this).hasClass('moduleRowSelected')){
                var selector = ($(this).hasClass('shippingRow') ? '.shippingRow' : '.paymentRow') + '.moduleRowSelected';
                $(selector).removeClass('moduleRowSelected');
                $(this).removeClass('moduleRowOver').addClass('moduleRowSelected');         
			$(':radio', $(this)).click();	

			/*  ЗАКОМЕНТИРОВАТЬ ЕСЛИ НЕ НУЖЕН ship2pay */	
			 checkoutClass2.updatePaymentMethods(true);
	    // checkoutClass2.updateShippingMethods(true);
			
                if($(':radio', $(this)).is(':disabled')!==true)
                if (!$(':radio', $(this)).is(':checked')){
                    $(':radio', $(this)).attr('checked', 'checked').click();
                }
            }
        });
    }, 
    queueAjaxRequest: function (options){
        var checkoutClass = this;  
        var o = {
            url: options.url,
            cache: options.cache || false,
            dataType: options.dataType || 'html',
            type: options.type || 'GET',
            contentType: options.contentType || 'application/x-www-form-urlencoded; charset=' + this.ajaxCharset,
            data: options.data || false,
            beforeSend: options.beforeSend || function (){
                checkoutClass.showAjaxMessage(options.beforeSendMsg || 'Ajax Operation, Please Wait...');
                checkoutClass.showAjaxLoader();
            },
            complete: function (){
                    checkoutClass.hideAjaxMessage();
                    // raid!!!---------------------------
                    if(checkoutClass.errors != true) $('#onePageCheckoutForm').submit();
                    // raid!!!---------------------------
                    
                    if (document.ajaxq.q['orderUpdate'].length <= 0){
                        //alert(checkoutClass.errors);  alert(checkoutClass.checkoutClicked);
                        if(checkoutClass.errors != true && checkoutClass.checkoutClicked == true){   
                            var buttonConfirmOrder = $('.ui-dialog-buttonpane button:first');
                            buttonConfirmOrder.removeClass('ui-state-disabled');
                            $('#imgDlgLgr').hide();
                        }
                        checkoutClass.hideAjaxLoader();
                    }
            },
            success: options.success
						//,
           // error: function (XMLHttpRequest, textStatus, errorThrown){
           //     if (XMLHttpRequest.responseText == 'session_expired') document.location = this.pageLinks.shoppingCart;
           //     alert(options.errorMsg || 'There was an ajax error, please contact ' + checkoutClass.storeName + ' for support.');
                //alert(textStatus +'\n'+ errorThrown+'\n'+options.data+'\n'+options.url);
           // }
        };
        $.ajaxq('orderUpdate', o);
    },
    updateOrderTotals: function (){
        var checkoutClass = this;
        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            cache: false,
            data: 'action=getOrderTotals&randomNumber='+Math.random(),
            type: 'post',
            beforeSendMsg: 'Обновление',
            success: function (data){
                $('.orderTotals').html(data);
                checkoutClass.hideAjaxLoader();
                //checkoutClass.updateRadiosforTotal();
            },
            errorMsg: 'В процессе обновления корзины возникла ошибка, пожалуйста, проинформируйте ' + checkoutClass.storeName + ' о ней.'
        });
    },
    updateModuleMethods: function (action, noOrdertotalUpdate){
        var checkoutClass = this;    
        var descText = (action == 'shipping' ? 'Shipping' : 'Payment');
        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            data: 'action=update' + descText + 'Methods',
            type: 'post',
            beforeSendMsg: 'Обновление способа' + descText + '',
            success: function (data){
                $('#no' + descText + 'Address').hide();
                $('#' + action + 'Methods').html(data).show();
                $('.' + action + 'Row').each(function (){
                    checkoutClass.addRowMethods($(this));
                    $('input[name="' + action + '"]', $(this)).each(function (){
                        var setMethod = checkoutClass.setPaymentMethod;
                        if (action == 'shipping'){
                            setMethod = checkoutClass.setShippingMethod;
                        }
                        $(this).click(function (e, noOrdertotalUpdate){
                            setMethod.call(checkoutClass, $(this));
                        });
                    });
                });
                checkoutClass.clickButton(descText.toLowerCase()); 
                checkoutClass.updateOrderTotals();   

			// raid  - обновляем выпадалку оплаты
				  $("#current_payment_module [value='"+$("input[name=payment]:checked").val()+"']").attr("selected", "selected");
			// raid  - обновляем выпадалку оплаты           
            
			
			// raid  - показуємо додаткові поля в залежності від способів доставки
			//  console.log($('.shippingRow.moduleRowSelected input[type=radio]').val());
				var curr_sposob = $('.shippingRow.moduleRowSelected input[type=radio]').val();
			  var suburbblock = $('input[name=billing_suburb]').parent().parent();
			  var streetblock = $('input[name=billing_street_address]').parent().parent();
        
			  if(curr_sposob=='flat_flat') { // якщо курєр то адрес
				  suburbblock.fadeOut(0);
					streetblock.fadeIn(100);
				} else if(curr_sposob=='nwpochta_nwpochta'  ) { // якщо НП то       
				  streetblock.fadeOut(0);
				  suburbblock.fadeIn(100);
				} else { // якщо курєр то адрес
				  streetblock.fadeOut(0);
				  suburbblock.fadeOut(100);
				}
			// raid end
			
			      },
            errorMsg: 'В процессе обновления ' + action + ' возникла ошибка, пожалуйста, проинформируйте  ' + checkoutClass.storeName + ' о ней.'
        });
    },
    updateShippingMethods: function (noOrdertotalUpdate){
        if (this.shippingEnabled == false){
            return false;
        }
        this.updateModuleMethods('shipping', noOrdertotalUpdate);
        
    },
    updatePaymentMethods: function (noOrdertotalUpdate){
        this.updateModuleMethods('payment', noOrdertotalUpdate);
    },
    setModuleMethod: function (type, method, successFunction){
        var checkoutClass = this;  
        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            data: 'action=set' + (type == 'shipping' ? 'Shipping' : 'Payment') + 'Method&method=' + method,
            type: 'post',
            beforeSendMsg: 'Установка метода ' + (type == 'shipping' ? 'Shipping' : 'Payment'),
            dataType: 'json',
            success: successFunction,
            errorMsg: 'There was an error setting ' + type + ' method, please inform ' + checkoutClass.storeName + ' about this error.'
        });
    },
    setShippingMethod: function ($button){
        if (this.shippingEnabled == false){
            return false;
        }

        var checkoutClass = this;
        this.setModuleMethod('shipping', $button.val(), function (data){
        });
    },
    setPaymentMethod: function ($button){

        var checkoutClass = this;
        this.setModuleMethod('payment', $button.val(), function (data){
          
            $('.paymentFields').remove();
            if (data.inputFields != ''){
                $(data.inputFields).insertAfter($button.parent().parent());
                $('input,select,radio','#paymentMethods').each( function ()
                {
                    if(paymentVals[$(this).attr('name')])
                    {
                        $(this).val(paymentVals[$(this).attr('name')]);
                    }
                    $(this).blur(function (){
                        paymentVals[$(this).attr('name')] = $(this).val();
                        
                    });
                }); 
            }
     
        });
    },

    processBillingAddress: function (skipUpdateTotals){   
        var hasError = false;
        var checkoutClass = this; 
        $('select[name="billing_country"], input[name="billing_street_address"], input[name="billing_zipcode"], input[name="billing_city"], input[id="checkoutButton"], *[name="billing_state"]', $('#billingAddress')).each(function (){
      if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
                hasError = true;
            }   
        });
        if (hasError == true){ 
            return;        
        }   
    
        this.setBillTo();

        if(skipUpdateTotals == true)
        {
    //        this.updatePaymentMethods(true);
    //        this.updateShippingMethods(true);
    //        this.updateOrderTotals();
        }
        
    },
    processShippingAddress: function (skipUpdateTotals){
        var hasError = false;
        var checkoutClass = this;
        $('select[name="shipping_country"], input[name="shipping_street_address"], input[name="shipping_zipcode"], input[name="shipping_city"]', $('#shippingAddress')).each(function (){
            if (checkoutClass.fieldErrorCheck($(this), false, true) == true){
                hasError = true;
            }
        });
        if (hasError == true){
            return;
        }
    
        this.setSendTo(true);
        if (this.shippingEnabled == true && skipUpdateTotals != true){
            this.updateShippingMethods(true);
        }
        if(skipUpdateTotals == true)
        {
    //        this.updatePaymentMethods(true);
    //        this.updateShippingMethods(true);
    //        this.updateOrderTotals();
        }
    },
    setCheckoutAddress: function (type, useShipping){
        var checkoutClass = this;
        var selector = '#' + type + 'Address';
        var sendMsg = 'Установка адреса ' + (type == 'shipping' ? 'Доставки' : 'Оплаты');
        var errMsg = type + ' address';
        if (type == 'shipping' && useShipping == false){
            selector = '#billingAddress';
            sendMsg = 'Установка адреса доставки';
            errMsg = 'адрес оплаты';
        }

        action = 'setBillTo';
        if (type == 'shipping'){
            action = 'setSendTo';
        }

        this.queueAjaxRequest({
            url: this.pageLinks.checkout,
            cache: false,
            beforeSendMsg: sendMsg,
            dataType: 'json',
            data: 'action=' + action + '&' + $('*', $(selector)).serialize(),
            type: 'post'
				//		,
        //    errorMsg: 'There was an error updating your ' + errMsg + ', please inform ' + checkoutClass.storeName + ' about this error.'
        });
    },
    setBillTo: function (){
        this.setCheckoutAddress('billing', false);
    },
    setSendTo: function (useShipping){
        this.setCheckoutAddress('shipping', useShipping);
    },
     checkAllErrors: function(){
            var checkoutClass = this;
            var errMsg = '';
            if ($('.required_icon:visible', $('#billingAddress')).size() > 0){
                errMsg += 'Заполните, пожалуйста, необходимые поля в разделе "Адрес оплаты"' + "\n";
            }

            if ($('.error_icon:visible', $('#billingAddress')).size() > 0){
                errMsg += 'Проверьте, пожалуйста, корректность ввода данных в разделе "Адрес оплаты"' + "\n";
            }

            if ($('#diffShipping:checked').size() > 0){
                if ($('.required_icon:visible', $('#shippingAddress')).size() > 0){
                    errMsg += 'Заполните, пожалуйста, все необходимые поля в "Адресе доставки"' + "\n";
                }

                if ($('.error_icon:visible', $('#shippingAddress')).size() > 0){
                    errMsg += 'Проверьте, пожалуйста, корректность ввода данных в разделе "Адрес доставки"' + "\n";
                }
            }

            if (errMsg != ''){
                errMsg = '------------------------------------------------' + "\n" +
                '                 Ошибка адреса                 ' + "\n" +
                '------------------------------------------------' + "\n" +
                errMsg;
            }

            if(checkoutClass.amountRemaininginTotal == true){
                if ($(':radio[name="payment"]:checked').size() <= 0){
                if ($('input[name="payment"]:hidden').size() <= 0){
                    errMsg += '------------------------------------------------' + "\n" +
                    '           Ошибка выбора способа оплаты              ' + "\n" +
                    '------------------------------------------------' + "\n" +
                    'Вы должны выбрать способ оплаты.' + "\n";
                }
            }
                }


            if (checkoutClass.shippingEnabled === true){
                if ($(':radio[name="shipping"]:checked').size() <= 0){
                    if ($('input[name="shipping"]:hidden').size() <= 0){
                        errMsg += '------------------------------------------------' + "\n" +
                        '           Ошибка выбора способа оплаты             ' + "\n" +
                        '------------------------------------------------' + "\n" +
                        'Вы должны выбрать способ оплаты.' + "\n";
                    }
                }
            }
            if(checkoutClass.ccgvInstalled == true)
            {
                if($('input[name="gv_redeem_code"]').val() == 'redeem code')
                {
                    $('input[name="gv_redeem_code"]').val('');
                }
            }

            if(checkoutClass.kgtInstalled == true)
            {
                if($('input[name="coupon"]').val() == 'redeem code')
                {
                    $('input[name="coupon"]').val('');
                }
            }

            if (errMsg.length > 0){
                checkoutClass.errors = true;
                alert(errMsg);
                return false;
            }else{
                checkoutClass.errors = false;
		          //  if (checkoutClass.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).size() <= 0 && checkoutClass.loggedIn != true){
		            if (checkoutClass.billingInfoChanged == true && $('.required_icon:visible', $('#billingAddress')).size() <= 0){
		                //errMsg += 'You tried to checkout without first clicking update. We have updated for you. Please review your order to make sure it is correct and click checkout again.' + "\n";
		                checkoutClass.processBillingAddress();
		                checkoutClass.billingInfoChanged = false;
		            }
                return true;
            }
        },
    initCheckout: function (){
        var checkoutClass = this;
        
        /*var billingInfoChanged = false;
        if ($('#diffShipping').checked && this.loggedIn != true){
        var shippingInfoChanged = false;
        }*/
        if(this.autoshow == true &&  this.loggedIn == false){
            $('#shippingAddress').hide();
     //       this.setBillTo();
     //       this.setSendTo(false);     
            this.updatePaymentMethods(true);
            this.updateShippingMethods(true);
     //       this.updateOrderTotals();
            
        }else    if (this.loggedIn == false){
            $('#shippingAddress').hide();
            $('#shippingMethods').html('');
        }

        $('#checkoutNoScript').remove();
        $('#checkoutYesScript').show();


    //    this.updateOrderTotals();


        if (this.loggedIn == true){
            $('.shippingRow, .paymentRow').each(function (){
                checkoutClass.addRowMethods($(this));
            });

            $('input[name="payment"]').each(function (){
                $(this).click(function (){
                    checkoutClass.setPaymentMethod($(this));
                    checkoutClass.updateOrderTotals();
                });
            });

            if (this.shippingEnabled == true){
                $('input[name="shipping"]').each(function (){
                    $(this).click(function (){
                        checkoutClass.setShippingMethod($(this));
                        checkoutClass.updateOrderTotals();
                    });
                });
            }
        }

        if ($('#paymentMethods').is(':visible')){
            this.clickButton('payment');
        }

        if (this.shippingEnabled == true){
            if ($('#shippingMethods').is(':visible')){
                this.clickButton('shipping');
            }
        }
        
        

        $('input, password', $('#billingAddress')).each(function (){
            if ($(this).attr('name') != undefined && $(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio'){
                if ($(this).attr('type') == 'password'){
                    $(this).blur(function (){
                        if ($(this).hasClass('required')){
                            checkoutClass.fieldErrorCheck($(this));
                        }
                    });
                    /* Used to combat firefox 3 and it's auto-populate junk */
                    $(this).val('');

                    if ($(this).attr('name') == 'password'){
                        $(this).focus(function (){
                            $(':password[name="confirmation"]').val('');
                        });

                        var rObj = getFieldErrorCheck($(this));
                        $(this).pstrength({
                        //    addTo: '#pstrength_password',
                        //    minchar: rObj.minLength
                        });
                    }
                }else{
            //      $(this).keyup(function (){
                    $(this).blur(function (){
                                           checkoutClass.billingInfoChanged = true;
                        if ($(this).hasClass('required')){
                            checkoutClass.fieldErrorCheck($(this));
                        }
                    });
                    if($(this).attr('name')!='billing_email_address') {
	                    $(this).keyup(function (){
	                                           checkoutClass.billingInfoChanged = true;
	                        if ($(this).hasClass('required')){
	                            checkoutClass.fieldErrorCheck($(this));
	                        }
	                    });
                    }
                    bindAutoFill($(this));
                }

                if ($(this).hasClass('required')){
                    checkoutClass.billingInfoChanged = true;
                    if (checkoutClass.fieldErrorCheck($(this), true, true) == false){
                        checkoutClass.addIcon($(this), 'success');
                    }else{
                        checkoutClass.addIcon($(this), 'required');
                    }
                }
            }
        });


        
		$('input[name="billing_email_address"]').each(function (){
			$(this).unbind('blur').change(function (){
				var $thisField = $(this);
				checkoutClass.billingInfoChanged = true;
				if (checkoutClass.initializing == true){
					checkoutClass.addIcon($thisField, 'required');
				}else{
					//if (this.changed == false) return;
					if (checkoutClass.fieldErrorCheck($thisField, true, true) == false){
						this.changed = false;
						if($thisField.val() == '')
						{
							checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
						}
						checkoutClass.queueAjaxRequest({
							url: checkoutClass.pageLinks.checkout,
							data: 'action=checkEmailAddress&emailAddress=' + $thisField.val(),
							type: 'post',
							beforeSendMsg: 'Проверка E-mail адреса',
							dataType: 'json',                            
							success: function (data){
								$('.success, .error', $thisField.parent()).hide();
								if (data.success == 'false'){
									checkoutClass.addIcon($thisField, 'error', data.errMsg.replace('/n', "\n"));
							//		alert(data.errMsg.replace('/n', "\n").replace('/n', "\n").replace('/n', "\n"));
							    $("#email_error").html(data.errMsg.replace('/n', "\n").replace('/n', "\n").replace('/n', "\n"));
								}else{
								  $("#email_error").html('');
									checkoutClass.addIcon($thisField, 'success');
								}
							},
							errorMsg: 'В процессе проверки email адреса возникла ошибка, пожалуйста, проинформируйте ' + checkoutClass.storeName + ' о ней.'
						});
					}
				}
			}).keyup(function (){
				this.changed = true;
			});
			bindAutoFill($(this));
		});
		
 
        $('#checkoutButton').click(function() { 
				  checkoutClass.checkAllErrors();   
          return false;                                   
        });

        if (this.loggedIn == true && this.showAddressInFields == true){
            $('*[name="billing_state"]').trigger('change');
            $('*[name="delivery_state"]').trigger('change');
        }

        this.initializing = false;
        
        
    }
}