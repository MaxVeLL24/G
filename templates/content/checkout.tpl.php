<?php

/**
 * Шаблон странийцы оформления заказа
 */

/* @var $cart \shoppingCart */
/* @var $currencies \currencies */

?>
<div class="checkout clearfix <?php echo empty($_SESSION['customer_id']) ? 'not-logined' : 'logined'; ?>">
    <div class="checkout-right">
        <div class="column-padding">
            <div class="common-styled-block">
                <?php include(DIR_WS_INCLUDES . 'checkout/checkout_cart.php'); ?>
                <div class="order-totals order-totals-yellow"></div>
            </div>
        </div>
    </div>
    <div class="checkout-left ">
        <?php
        // Если юзер НЕ авторизированый
        if(empty($_SESSION['customer_id']))
        {
            ?>
            <div class="checkout-tabs">
                <div class="tabs">
                    <div class="tab" data-target="#checkout-new-customer"><?php echo NEW_CUSTOMER; ?></div>
                    <div class="tab" data-target="#checkout-returning-customer"><?php echo RETURNING_CUSTOMER; ?></div>
                </div>
                <div id="checkout-returning-customer" class="tab-content common-styled-block clearfix">
                    <div class="checkout_userlogin_form">
                        <form method="POST" action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process&from=' . $_SERVER['REQUEST_URI'], 'SSL'); ?>" id="login" name="login">
                            <div class="form-group">
                                <label class="control-label"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
                                <input
                                    type="email"
                                    name="email_address"
                                    class="form-control"
                                    required
                                    />
                            </div>
                            <div class="form-group">
                                <label class="control-label"><?php echo ENTRY_PASSWORD; ?></label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required
                                    />
                            </div>
                            <div class="buttons-block">
                                <button class="button" type="submit"><?php echo IMAGE_BUTTON_LOGIN; ?></button>&nbsp;&nbsp;&nbsp;
                                <?php echo '<a  href="' . tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL') . '">' . TEXT_PASSWORD_FORGOTTEN . '</a>'; ?>
                            </div>

                        </form>
                    </div>

                    <?php if(AUTH_MODULE_ENABLED == 'true'): ?>
                        <div class="login_page_soc">
                            <h2><?php echo SIGN_FROM_SOC; ?></h2>
                            <a href="javascript:showLoginvk('<?php echo 'https://oauth.vk.com/authorize?client_id=' . $vk_app_id . '&scope=&display=popup&redirect_uri=http://' . HTTP_COOKIE_DOMAIN . '/loginvk.php&response_type=code'; ?>');"><i class="icon-vk"></i>Вконтакте</a>
                            <a href="javascript:showLoginvk('<?php echo 'http://www.facebook.com/dialog/oauth/?client_id=' . $fb_app_id . '&display=popup&redirect_uri=http://' . HTTP_COOKIE_DOMAIN . '/loginfb.php&state=' . $fb_state . '&scope=email,user_photos'; ?>');"><i class="icon-fb"></i>Facebook</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div id="checkout-new-customer" class="tab-content common-styled-block clearfix">
                    <?php include(DIR_WS_INCLUDES . 'checkout/checkout_form.php'); ?>
                </div>
            </div>
            <?php
        }
        else
        {
            include(DIR_WS_INCLUDES . 'checkout/checkout_form.php');
        }
        ?>
    </div>
</div>
<?php \EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<?php include(DIR_WS_INCLUDES . 'javascript/onepagecheckout.js.php'); ?>
<?php \EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>