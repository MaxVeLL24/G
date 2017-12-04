<?php

/* @var $cart \shoppingCart */
/* @var $currencies \currencies */

?>
<div class="common-styled-block">
<form
    method="POST"
    action="<?php echo tep_href_link(FILENAME_CHECKOUT, '', $request_type); ?>"
    id="onePageCheckoutForm"
    name="checkout_form"
    >
    <input
        type="hidden"
        name="action"
        value="process"
        />
    <?php /* Адрес доставки */ ?>
    <div id="billingAddress">
        <?php include(DIR_WS_INCLUDES . 'checkout/billing_address.php'); ?>
    </div>
    <?php /* Способ доставки */ ?>
    <?php if($onepage['shippingEnabled'] === true && tep_count_shipping_modules() > 0) : ?>
    <h3><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h3>
    <div id="shippingMethods">
        <?php include(DIR_WS_INCLUDES . 'checkout/shipping_method.php'); ?>
    </div>
    <?php endif; ?>
    <?php /* Способ оплаты */ ?>
    <h3><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h3>
    <div id="paymentMethods">
        <?php include(DIR_WS_INCLUDES . 'checkout/payment_method.php'); ?>
    </div>
    <?php /* Адрес доставки */ ?>
    <div class="form-group">
        <label for="checkout-form-billing-street-address-input"><?php echo ENTRY_STREET_ADDRESS; ?></label>
        <input
            type="text"
            id="checkout-form-billing-street-address-input"
            name="billing_street_address"
            class="form-control"
            placeholder="<?php echo ENTRY_STREET_ADDRESS; ?>"
            required
            <?php if(!empty($billingAddress['street_address'])) : ?>value="<?php echo tep_escape($billingAddress['street_address']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <?php /* Номер отделения транспортной компании */ ?>
    <div class="form-group">
        <label for="checkout-form-billing-suburb-input"><?php echo ENTRY_SUBURB; ?></label>
        <input
            type="text"
            id="checkout-form-billing-suburb-input"
            name="billing_suburb"
            class="form-control"
            placeholder="<?php echo ENTRY_SUBURB; ?>"
            required
            <?php if(!empty($billingAddress['suburb'])) : ?>value="<?php echo tep_escape($billingAddress['suburb']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <?php /* Комментарий к заказу */ ?>
    <div class="form-group">
        <label for="checkout-form-comments-textarea"><?php echo ENTRY_COMMENT; ?></label>
        <textarea
            id="checkout-form-comments-textarea"
            name="comments"
            class="form-control"
            placeholder="<?php echo ENTRY_COMMENT; ?>"
            maxlength="65535"
            ><?php if(!empty($comments)) echo $comments; ?></textarea>
    </div>
    <p><b><?php echo CHECKOUT_FORM_NOTICE_HEADER_TEXT; ?>:</b><br /><?php echo CHECKOUT_FORM_TEXT_TERMS_OF_USE; ?></p>
    <?php /* Кнопка "Оформить заказ" */ ?>
    <div class="button-block">
        <button
            id="checkoutButton"
            type="submit"
            class="button"
            data-text-wait="<?php echo CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_WAIT; ?>"
            data-text-submit="<?php echo CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_SUBMIT; ?>"
            ><?php echo CHECKOUT_FORM_SUBMIT_BUTTON_TEXT_SUBMIT; ?></button>
        <a
            id="notNowButton"
            href="<?php echo tep_href_link(FILENAME_DEFAULT); ?>"
            class="button button-red"
            title="<?php echo CHECKOUT_FORM_NOT_NOW_BUTTON_TITLE; ?>"
            ><?php echo CHECKOUT_FORM_NOT_NOW_BUTTON_TEXT; ?></a>
    </div>
</form>
</div>
<?php return; ?>
<div class="checkout_form">
  <?php echo tep_draw_form('checkout', tep_href_link(FILENAME_CHECKOUT, '', $request_type), 'post','id=onePageCheckoutForm') . tep_draw_hidden_field('action', 'process'); ?>

    <div id="pageContentContainer"  style="display:none;">
    <table id="billingAddress" style="width:100%;" align="center">
      <tr>
        <td>
          <?php
            // Форма
            ob_start();
            include(DIR_WS_INCLUDES . 'checkout/billing_address.php');
            $billingAddress_string = ob_get_contents();
            ob_end_clean();

            $billingAddress_string = '<table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr id="logInRow"' . (isset($_SESSION['customer_id']) ? ' ' : '') . '>
              <td> </td>
            </tr>
            </table>' . $billingAddress_string;
            echo $billingAddress_string;
            //buildInfobox($header, $billingAddress_string);
          ?>
        </td>
      </tr>
      <?php
        if ($onepage['shippingEnabled'] === true){
        if (tep_count_shipping_modules() > 0) {
      ?>
      <tr>
        <td>
          <!-- SHIPPING METHOD -->
          <?php
            $header = '<div class="checkout_b_section">'.TABLE_HEADING_SHIPPING_METHOD.'</div>';
            $shippingMethod = '';
            if (isset($_SESSION['customer_id'])){
              ob_start();
              include(DIR_WS_INCLUDES . 'checkout/shipping_method.php');
              $shippingMethod = ob_get_contents();
              ob_end_clean();
            }
            $shippingMethod = '<div id="noShippingAddress" class="main noAddress" style="font-size:12px;' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '">Введите, пожалуйста, Ваши данные в вышеуказанные поля</div><div id="shippingMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $shippingMethod . '</div>';
            echo '<h3>'.TABLE_HEADING_SHIPPING_METHOD.'</h3>';
            echo $shippingMethod;
            //buildInfobox($header, $shippingMethod);
          ?>
        </td>
    </tr>
    <?php } } ?>
    <tr>
      <td>
        <!-- PAYMENT METHOD -->
          <?php
            $header = '<div class="checkout_b_section">'.TABLE_HEADING_PAYMENT_METHOD.'</div>';
            $paymentMethod = '';
            if (isset($_SESSION['customer_id'])){
              ob_start();
              include(DIR_WS_INCLUDES . 'checkout/payment_method.php');
              $paymentMethod = ob_get_contents();
              ob_end_clean();
            }
            $paymentMethod = '<div id="noPaymentAddress" class="main noAddress" style="font-size:12px;' . (isset($_SESSION['customer_id']) ? 'display:none;' : '') . '">Введите, пожалуйста, Ваши данные в вышеуказанные поля</div><div id="paymentMethods"' . (!isset($_SESSION['customer_id']) ? ' style="display:none;"' : '') . '>' . $paymentMethod . '</div>';
            echo '<h3>'.TABLE_HEADING_PAYMENT_METHOD.'</h3>';
            echo $paymentMethod;
          // buildInfobox($header, $paymentMethod);
          ?>
      </td>
    </tr>
    <tr>
      <td>
        <table  border="0" width="400px;" cellspacing="0" cellpadding="2" align="center">
          <tr>
            <td class="input_label" align="left" nowrap><?php echo ENTRY_STREET_ADDRESS; ?></td>
            <td><?php echo tep_draw_input_field('billing_street_address', (isset($billingAddress) ? $billingAddress['street_address'] : ''), 'class="required checkout_inputs "'); ?></td>
          </tr>

          <tr>
            <td class="input_label" nowrap><?php echo ENTRY_SUBURB; ?></td>
            <td><?php echo tep_draw_input_field('billing_suburb', (isset($billingAddress) ? $billingAddress['suburb'] : ''), 'class="required checkout_inputs "'); ?></td>
          </tr>
          <tr>
            <td class="input_label"></td>
            <td align="left" colspan="2"><?php echo tep_draw_textarea_field('comments', 'soft', '40', '3', $comments, 'class="checkout_inputs"'); ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents" id="checkoutYesScript" style="display:none;">
            <td id="checkoutMessage"></td>
            <td align="center">
              <?php if(ONEPAGE_CHECKOUT_LOADER_POPUP == 'False'){ ?>
                <div id="ajaxMessages" style="display:none;"></div>
              <?php }

              if(MIN_ORDER<$cart->show_total()){?>
              <div id="checkoutButtonContainer">
                <span class="btn" id="checkoutButton" formUrl="<?php tep_href_link(FILENAME_CHECKOUT_PROCESS, '', $request_type); ?>" style="cursor: pointer"><?php echo HEADER_TITLE_CHECKOUT; ?></span>
                <input type="hidden" name="formUrl" id="formUrl" value="">
              </div>


              <?php
              }
							// raid ------ минимальный заказ!!!---------------- //
            //    if(MIN_ORDER>$cart->show_total()) echo '<input type="hidden" id="minsum" value="'.MIN_ORDER.'" />';
                if(MIN_ORDER>$cart->show_total()*$currencies->currencies['UAH']['value']) echo '<input type="hidden" id="minsum" value="'.MIN_ORDER.'" />';
	            // raid ------ минимальный заказ!!!---------------- //
							?>
              <div id="checkoutButtonContainer_minimal" style="display:none;color:#D40000;">
                <div class="right" style="opacity:0.3;"></div>
                <div class="right" style="padding:12px 20px 0 0;">
                <?php
                 echo CHECKOUT_MIN_SUM.' <b><span id="minimal_sum"></span> '.$currencies->currencies[$_SESSION['currency']]['symbol_right'].'</b>'; 
                ?> 
                </div>
                <div class="clear"></div>
              </div>

              <div id="paymentHiddenFields" style="display:none;"></div>
            </td>
          </tr>
          <tr class="infoBoxContents" id="checkoutNoScript">
            <td>
              <td>
                <?php echo '<b>' . TITLE_CONTINUE_CHECKOUT_PROCEDURE . '</b><br>to update/view your order.'; ?>
              </td>
              <td align="right">
                <button class="btn" type="submit"><?php echo IMAGE_BUTTON_CHECKOUT; ?></button>
                <?php //echo tep_image_submit('button_update.gif', IMAGE_BUTTON_UPDATE); ?>
              </td>

            </td>
          </tr>
        </table>
      </td>
    </tr>
    </table>
    </div>

  </form>
</div><!-- /checkout_form -->
