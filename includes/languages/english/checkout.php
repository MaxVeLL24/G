<?php
/*
  One Page Checkout, Version: 1.08

  I.T. Web Experts
  http://www.itwebexperts.com

  Copyright (c) 2009 I.T. Web Experts

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Checkout');
define('NAVBAR_TITLE_1', 'Checkout');

define('HEADING_TITLE', 'Checkout');

define('TABLE_HEADING_SHIPPING_ADDRESS', 'Shipping address');
define('TABLE_HEADING_BILLING_ADDRESS', 'Your information:');

define('TABLE_HEADING_PRODUCTS_MODEL', '#');
define('TABLE_HEADING_PRODUCTS_NAME', 'Products');
define('TABLE_HEADING_PRODUCTS_QTY', 'Qty');
define('TABLE_HEADING_PRODUCTS_PRICE', 'Price by item.');
define('TABLE_HEADING_PRODUCTS_FINAL_PRICE', 'Total');

define('TABLE_HEADING_PRODUCTS', 'Shopping cart');
define('TABLE_HEADING_TAX', 'Tax');
define('TABLE_HEADING_TOTAL', 'Total');

define('ENTRY_TELEPHONE', 'Phone: ');

define('TEXT_CHOOSE_SHIPPING_DESTINATION', 'Please choose from your address book where you would like the items to be delivered to.');
define('TEXT_SELECTED_BILLING_DESTINATION', 'Please choose from your address book where you would like the invoice to be sent to.');

define('TITLE_SHIPPING_ADDRESS', 'Shipping method:');
define('TITLE_BILLING_ADDRESS', 'Payment method:');

define('TABLE_HEADING_SHIPPING_METHOD', 'Shipping method');
define('TABLE_HEADING_PAYMENT_METHOD', 'Payment method');

define('TEXT_CHOOSE_SHIPPING_METHOD', 'Please choose shipping method.');
define('TEXT_SELECT_PAYMENT_METHOD', 'Please choose payment method.');

define('TITLE_PLEASE_SELECT', 'Choose');

define('TEXT_ENTER_SHIPPING_INFORMATION', 'This is currently the only shipping method available to use on this order.');
define('TEXT_ENTER_PAYMENT_INFORMATION', 'This is currently the only payment method available to use on this order.');

define('TABLE_HEADING_COMMENTS', 'Comments:');

define('TITLE_CONTINUE_CHECKOUT_PROCEDURE', 'Continue checkout');
define('TEXT_CONTINUE_CHECKOUT_PROCEDURE', 'for continue press "Continue"');

define('TEXT_EDIT', 'Edit');

define('TEXT_SELECTED_SHIPPING_DESTINATION', 'This is the currently selected shipping address where the items in this order will be delivered to.');
define('TABLE_HEADING_NEW_ADDRESS', 'New address');
define('TABLE_HEADING_EDIT_ADDRESS', 'Edit address');
define('TEXT_CREATE_NEW_SHIPPING_ADDRESS', 'Please use the following form to add a new shipping address for this order.');
define('TABLE_HEADING_ADDRESS_BOOK_ENTRIES', 'Address book');

define('EMAIL_SUBJECT', 'Welcome to our store ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_WELCOME', 'Welcome to our store <b>' . STORE_NAME . '</b>.' . "\n\n");
define('EMAIL_TEXT', 'You now have access to some additional features that are available to registered users'. "\n\n". '<li> <b>Shopping </b> - any products that add to the cart, will be there until you delete them or do not order it.' . "\n". '<li> <b>Address Book </b> - we can now send their products to the address that you specified in "Shipping Address".' . "\n". '<li> <b> Order History </b> - you have the opportunity to view the history of orders in our store.' . "\n\n");
define('EMAIL_CONTACT', 'If you have any questions, please contact: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '');

/*define('ONPAGE_USER_CREATED_MESSAGE', 'Now you can use these "login" and "password" when you make the following orders:<br>' .
            'Login: %s<br>' .
            'Password: %s<br><br>'.
            'Password: %s<br><br>');   */
define('ONPAGE_USER_CREATED_MESSAGE', 'Now you can use these "login" and "password" when you make the following orders:<br>');
define('ONPAGE_CRE_LOGIN', 'Login: ');
define('ONPAGE_CRE_PASS', 'Password: ');            

// Start - CREDIT CLASS Gift Voucher Contribution
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you an e-Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for the e-Gift Voucher is %s, you can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations, to make your first visit to our online shop a more rewarding experience we are sending you an e-Discount Coupon.' . "\n" .
                                        ' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
// End - CREDIT CLASS Gift Voucher Contribution

define('TEXT_AGREE_TO_TERMS', 'I agree to the terms and conditions');

define('WINDOW_BUTTON_CANCEL', 'Cancel');
define('WINDOW_BUTTON_CONTINUE', 'Continue');
//define('WINDOW_BUTTON_CONTINUE', 'Подтвердить');
define('WINDOW_BUTTON_NEW_ADDRESS', 'New Address');
define('WINDOW_BUTTON_EDIT_ADDRESS', 'Edit address');

define('CHECKOUT_EMAIL_EXIST', 'This e-mail already exists, please <a href="' . tep_href_link(FILENAME_LOGIN, 'from=' . rawurlencode(tep_href_link(FILENAME_CHECKOUT))) . '">sign</a> under the specified e-mail');
define('CHECKOUT_WRONG_EMAIL', 'You have entered an invalid e-mail.');
define('CHECKOUT_ORDER_PROCESSES', 'Orders processed wait ...');

define('TEXT_PLEASE_SELECT', 'Choose');
define('TEXT_PASSWORD_FORGOTTEN', 'forgot password?');
define('IMAGE_UPDATE_CART', 'Update cart');
define('IMAGE_LOGIN', 'Login');
define('TEXT_PAYMENT_METHOD_UPDATE_ERROR', 'Please try again and if problems persist, please try another payment method.');
define('TEXT_HAVE_COUPON_CCGV', 'Have A Coupon?');
define('TEXT_HAVE_COUPON_KGT', 'Have A Coupon?');
define('TEXT_EXISTING_CUSTOMER_LOGIN', 'Already registered?');
define('TEXT_DIFFERENT_SHIPPING', 'Shipping address and billing address is different?');
define('TEXT_SHIPPING_NO_ADDRESS', 'Please enter the address of the payment in order to get access to the methods of delivery');
define('TEXT_CHECKOUT_UPDATE_VIEW_ORDER', 'to update/view your order.');
define('CHECKOUT_BAR_CONFIRMATION', 'Checkout');
// Points/Rewards Module V2.1rc2a BOF
define('TABLE_HEADING_REDEEM_SYSTEM', 'Shopping Rewards Points Redemptions ');
define('TABLE_HEADING_REFERRAL', 'Referral System');
define('TEXT_REDEEM_SYSTEM_START', 'You have a credit balance of %s ,would you like to use it to pay for this order?<br />The estimated total of your purchase is: %s .');
define('TEXT_REDEEM_SYSTEM_SPENDING', 'Tick here to use Maximum Points allowed for this order. (%s points %s)&nbsp;&nbsp;->');
define('TEXT_REDEEM_SYSTEM_NOTE', '<span class="pointWarning">Total Purchase is greater than the maximum points allowed, you will also need to choose a payment method</span>');
define('TEXT_REFERRAL_REFERRED', 'If you were referred to us by a friend please enter their email address here. ');
// Points/Rewards Module V2.1rc2a EOF

//---checkout_errors
//rCheckoutErrors.chk_plz_fill_paymnet
define('CHECKOUT_RELOAD','Updating');
define('CHECKOUT_SHCRT_ERR','In the process of updating the basket there is an error, please contact the');
define('CHECKOUT_RELOAD_METHOD','Updating method');
define('CHECKOUT_RELOAD_PROCESS','During updating ');
define('CHECKOUT_ERR_FEED_BACK',' there was an error, please contact the ');
define('CHECKOUT_SET_METHOD','Setting method ');
define('CHECKOUT_ERR_SETTING','There was an error setting ');
define('CHECKOUT_PLZ_INFORM',' method, please inform ');
define('CHECKOUT_SET_ADDRESS','Setting the address ');
define('CHECKOUT_SHIPPING','Shipping');
define('CHECKOUT_PAYMENT','Payment');
define('CHECKOUT_SET_SHIPPING_ADDRESS','Setting delivery address');
define('CHECKOUT_PAYMENT_ADDRESS','payment address');
define('CHECKOUT_ADDRESS_ERROR','Address Error');
define('CHECKOUT_PLZ_FILL_PAYMENT','Please fill in the required fields in the "Payment Address"');
define('CHECKOUT_PLZ_CHECK_CORRECT','Please check the correct input in the "Payment Address"');
define('CHECKOUT_PLZ_FILL_SHIPPING','Please, fill in all the required fields in the "Shipping Address"');
define('CHECKOUT_PLZ_CHECK_SHIPPING','Please check the correct input in the "Shipping Address"');
define('CHECKOUT_ERR_SET_PAYMENT','Error selecting a payment method');
define('CHECKOUT_CHOOSE_PAYMENT','You must select a payment method.');
define('CHECKOUT_CHK_EMAIL','Check E-mail address');
define('CHECKOUT_ERR_EMAIL_FEED_BACK','During checking the email address error has occurred, please contact ');
define('CHECKOUT_ABOUT_ERR',' about it.');

define('CHECKOUT_MIN_SUM', 'MINIMUM ORDER:');
define('CHECKOUT_REQUIRED_FIELD', 'Required field');
define('ENTRY_COMMENT', 'Leave a comment to your order:');
define('CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_WAIT', 'Please wait');
define('CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_SUBMIT', 'Confirm');
define('CHECKOUT_FORM_TEXT_TERMS_OF_USE', 'When ordering, you consent to the processing of your personal data in accordance with the Law of Ukraine "On protection of personal data".');
define('CHECKOUT_FORM_NOT_NOW_BUTTON_TITLE', 'Back to main page');
define('CHECKOUT_FORM_NOT_NOW_BUTTON_TEXT', 'Not now');
define('CHECKOUT_FORM_NOTICE_HEADER_TEXT', 'Note');
define('CHECKOUT_CART_TEXT_PRICE', 'Price');
define('CHECKOUT_CART_TEXT_QUANTITY', 'Quantity');
define('CHECKOUT_CART_TEXT_TOTAL', 'Total');