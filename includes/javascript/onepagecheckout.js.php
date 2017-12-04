<script src="includes/checkout/checkout.js"></script>
<script>

    var onePage = checkout;
    onePage.initializing = true;
    // onePage.ajaxCharset = 'windows-1251';
    onePage.ajaxCharset = 'utf-8';
    onePage.storeName = '<?php echo STORE_NAME; ?>';
    onePage.loggedIn = <?php echo (tep_session_is_registered('customer_id') ? 'true' : 'false'); ?>;
    onePage.autoshow = <?php echo ((ONEPAGE_AUTO_SHOW_BILLING_SHIPPING == 'False') ? 'false' : 'true'); ?>;
    onePage.stateEnabled = <?php echo (ACCOUNT_STATE == 'true' ? 'true' : 'false'); ?>;
    onePage.showAddressInFields = <?php echo ((ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False') ? 'false' : 'true'); ?>;
    onePage.showMessagesPopUp = <?php echo ((ONEPAGE_CHECKOUT_LOADER_POPUP == 'True') ? 'true' : 'false'); ?>;
    onePage.ccgvInstalled = <?php echo (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true' ? 'true' : 'false'); ?>;
    //BOF KGT
    onePage.kgtInstalled = <?php echo (MODULE_ORDER_TOTAL_DISCOUNT_COUPON_STATUS == 'true' ? 'true' : 'false'); ?>;
    //EOF KGT
    //BOF POINTS
    onePage.pointsInstalled = <?php echo (((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) ? 'true' : 'false'); ?>;
    //EOF POINTS
    onePage.shippingEnabled = <?php echo ($onepage['shippingEnabled'] === true ? 'true' : 'false'); ?>;
    onePage.pageLinks = {
    }

    function getFieldErrorCheck($element) {
        var rObj = {};
        switch ($element.attr('name')) {
            case 'billing_firstname':
            case 'shipping_firstname':
                rObj.minLength = <?php echo addslashes(ENTRY_FIRST_NAME_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_FIRST_NAME_ERROR); ?>';
                break;
            case 'billing_lastname':
            case 'shipping_lastname':
                rObj.minLength = <?php echo addslashes(ENTRY_LAST_NAME_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_LAST_NAME_ERROR); ?>';
                break;
            case 'billing_email_address':
                rObj.minLength = <?php echo addslashes(ENTRY_EMAIL_ADDRESS_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_EMAIL_ADDRESS_ERROR); ?>';
                break;
            case 'billing_street_address':
            case 'shipping_street_address':
                rObj.minLength = <?php echo addslashes(ENTRY_STREET_ADDRESS_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_STREET_ADDRESS_ERROR); ?>';
                break;
            case 'billing_zipcode':
            case 'shipping_zipcode':
                rObj.minLength = <?php echo addslashes(ENTRY_POSTCODE_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_POST_CODE_ERROR); ?>';
                break;
            case 'billing_city':
            case 'shipping_city':
                rObj.minLength = <?php echo addslashes(ENTRY_CITY_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_CITY_ERROR); ?>';
                break;
            case 'billing_dob':
                rObj.minLength = <?php echo addslashes(ENTRY_DOB_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_DATE_OF_BIRTH_ERROR); ?>';
                break;
            case 'billing_telephone':
                rObj.minLength = <?php echo addslashes(ENTRY_TELEPHONE_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_TELEPHONE_NUMBER_ERROR); ?>';
                break;
            case 'billing_country':
            case 'shipping_country':
                rObj.errMsg = '<?php echo addslashes(ENTRY_COUNTRY_ERROR); ?>';
                break;
            case 'billing_state':
            case 'delivery_state':
                rObj.minLength = <?php echo addslashes(ENTRY_STATE_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_STATE_ERROR); ?>';
                break;
            case 'password':
            case 'confirmation':
                rObj.minLength = <?php echo addslashes(ENTRY_PASSWORD_MIN_LENGTH); ?>;
                rObj.errMsg = '<?php echo addslashes(ENTRY_PASSWORD_ERROR); ?>';
                break;
        }
        return rObj;
    }
    
    rCheckoutErrors = {
        chk_reload: '<?php echo addslashes(CHECKOUT_RELOAD); ?>',
        chk_shcrt_err: '<?php echo addslashes(CHECKOUT_SHCRT_ERR); ?>',
        chk_reload_method: '<?php echo addslashes(CHECKOUT_RELOAD_METHOD); ?>',
        chk_reload_process: '<?php echo addslashes(CHECKOUT_RELOAD_PROCESS); ?>',
        chk_err_feed_back: '<?php echo addslashes(CHECKOUT_ERR_FEED_BACK); ?>',
        chk_set_method: '<?php echo addslashes(CHECKOUT_SET_METHOD); ?>',
        chk_err_setting: '<?php echo addslashes(CHECKOUT_ERR_SETTING); ?>',
        chk_plz_inform: '<?php echo addslashes(CHECKOUT_PLZ_INFORM); ?>',
        chk_set_address: '<?php echo addslashes(CHECKOUT_SET_ADDRESS); ?>',
        chk_shipping: '<?php echo addslashes(CHECKOUT_SHIPPING); ?>',
        chk_payment: '<?php echo addslashes(CHECKOUT_PAYMENT); ?>',
        chk_set_shipping_address: '<?php echo addslashes(CHECKOUT_SET_SHIPPING_ADDRESS); ?>',
        chk_payment_address: '<?php echo addslashes(CHECKOUT_PAYMENT_ADDRESS); ?>',
        chk_address_error: '<?php echo addslashes(CHECKOUT_ADDRESS_ERROR); ?>',
        chk_plz_fill_paymnet: '<?php echo addslashes(CHECKOUT_PLZ_FILL_PAYMENT); ?>',
        chk_plz_check_correct: '<?php echo addslashes(CHECKOUT_PLZ_CHECK_CORRECT); ?>',
        chk_plz_fill_shipping: '<?php echo addslashes(CHECKOUT_PLZ_FILL_SHIPPING); ?>',
        chk_plz_check_shipping: '<?php echo addslashes(CHECKOUT_PLZ_CHECK_SHIPPING); ?>',
        chk_err_set_payment: '<?php echo addslashes(CHECKOUT_ERR_SET_PAYMENT); ?>',
        chk_choose_payment: '<?php echo addslashes(CHECKOUT_CHOOSE_PAYMENT); ?>',
        chk_chk_email: '<?php echo addslashes(CHECKOUT_CHK_EMAIL); ?>',
        chk_err_email_feed_back: '<?php echo addslashes(CHECKOUT_ERR_EMAIL_FEED_BACK); ?>',
        chk_about_err: '<?php echo addslashes(CHECKOUT_ABOUT_ERR); ?>'
    }
    
    $(document).ready(function(){
        // Инициализация чекаута
        onePage.initCheckout();
        
        // Переключение табов на странице оформления заказа
        var active_tab = '#checkout-new-customer',
            inactive_tab = '#checkout-returning-customer',
            tmp;
        if(location.hash.toLowerCase() === inactive_tab)
        {
            tmp = active_tab;
            active_tab = inactive_tab;
            inactive_tab = tmp;
        }
        $('.tab-item').click(function(){
            var selected_tab = $(this).attr('href').match(/#.*$/);
            if(!selected_tab || !selected_tab.length)
            {
                return true;
            }
            active_tab = selected_tab[0];
            if(active_tab === '#checkout-returning-customer')
            {
                inactive_tab = '#checkout-new-customer';
            }
            else
            {
                inactive_tab = '#checkout-returning-customer';
            }
            $('.tab-item').removeClass('active').filter('[href$="' + active_tab + '"]').addClass('active');
            $(inactive_tab).hide();
            $(active_tab).show();
            return false;
        });
        $('.tab-item[href$="' + active_tab + '"').trigger('click');
        
        // Маскировка номера телефона
        $('#checkout-form-billing-telephone-input').mask('+380 (99) 999-99-99', {
            'translation': {
                '9': {
                    'pattern': /[0-9]/
                }
            }
        });
    });
</script>