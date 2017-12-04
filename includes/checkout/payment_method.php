<?php

/**
 * Методы оплаты
 */

/* @var $cart \shoppingCart */
/* @var $currencies \currencies */
/* @var $payment_modules \payment */

$modules = $payment_modules->selection();

if(empty($_SESSION['onepage']['info']['payment_method']))
{
    $_SESSION['onepage']['info']['payment_method'] = ONEPAGE_DEFAULT_PAYMENT;
}

$module_found = false;

foreach($modules as $i => $module)
{
    if($module['id'] === 'googlecheckout' || $module['id'] === 'ccerr')
    {
        unset($modules[$i]);
    }
    if($module['id'] === $_SESSION['onepage']['info']['payment_method'])
    {
        $module_found = true;
    }
}

if(!$module_found)
{
    $_SESSION['onepage']['info']['payment_method'] = $modules[0]['id'];
}

?>
<div id="paymentMethods" class="shipping-and-payment-methods">
    <?php foreach($modules as $module) : ?>
    <div class="method-item clearfix">
        <div class="custom-radiobox">
            <input
                type="radio"
                name="payment"
                value="<?php echo $module['id']; ?>"
                id="checkout-form-payment-method-<?php echo $module['id']; ?>"
                <?php if($_SESSION['onepage']['info']['payment_method'] === $module['id']) : ?>checked<?php endif; ?>
                />
            <label for="checkout-form-payment-method-<?php echo $module['id']; ?>"></label>
        </div>
        <label for="checkout-form-payment-method-<?php echo $module['id']; ?>" class="everything-else">
            <div class="module-title"><?php echo tep_escape($module['module']); ?></div>
        </label>
    </div>
    <?php endforeach; ?>
</div>
<?php return; ?>
<table id="paymentMethods" border="0" width="100%" cellspacing="0" cellpadding="0">
<?php
  $selection = $payment_modules->selection();
  // *** BEGIN GOOGLE CHECKOUT ***
  // Skips Google Checkout as a payment option on the payments page since that option
  // is provided in the checkout page.
  for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
    if ($selection[$i]['id'] == 'googlecheckout') {
      array_splice($selection, $i, 1);
      break;
    }
  }
  // *** END GOOGLE CHECKOUT ***
  for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
    if ($selection[$i]['id'] == 'ccerr') {
      array_splice($selection, $i, 1);
      break;
    }
  }

  $paymentMethod = '';
  if (tep_session_is_registered('onepage')){
	  $paymentMethod = $onepage['info']['payment_method'];
  }

  if ($paymentMethod == ''){
	$paymentMethod = ONEPAGE_DEFAULT_PAYMENT;
  }

  if (sizeof($selection) > 1) {
?>
 <!-- <tr>
       <td class="main" width="100%" valign="top" colspan="2" class="checkout_b_section">
       -->     <?php //echo TEXT_SELECT_PAYMENT_METHOD; ?>
       <!-- </td> -->
 <!-- </tr> -->
<?php
  } else {
?>
 <tr>
  <td width="100%" colspan="2" style="line-height:22px;"><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></td>
 </tr>
<?php
  }

  $radio_buttons = 0;
  for ($i=0, $n=sizeof($selection); $i<$n; $i++) {
?>
 <tr>
  <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
   <tr class="moduleRow paymentRow<?php echo ($selection[$i]['id'] == $paymentMethod ? ' moduleRowSelected' : '');?>">
	<td width="10" style="padding: 3px;"><?php
	 if (sizeof($selection) > 1) {
     echo tep_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $paymentMethod ? true : ($i=='0' ? true : false)));
	 } else {
		 echo tep_draw_hidden_field('payment', $selection[$i]['id'],true);
	 }
	?></td>
	<td width="100%" style="font-size:12px;padding: 3px;"><b><nobr><?php echo $selection[$i]['module']; ?></nobr></b></td>
   </tr>
<?php
	if (isset($selection[$i]['error'])) {
?>
   <tr>
	<td colspan="2"><?php echo $selection[$i]['error']; ?></td>
   </tr>
<?php
	} elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields']) && ($selection[$i]['id'] == $paymentMethod)) {
?>
   <tr>
	<td colspan="2"><table border="0" cellspacing="0" cellpadding="2" width="100%">
<?php
	  for ($j=0, $n2=sizeof($selection[$i]['fields']); $j<$n2; $j++) {
?>
	 <tr>

	  <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>

	  <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>

	 </tr>
<?php
	  }
?>
	</table></td>

   </tr>
<?php
	}
?>
  </table></td>
 </tr>
<?php
	$radio_buttons++;
  }

  // Start - CREDIT CLASS Gift Voucher Contribution
  if(MODULE_ORDER_TOTAL_COUPON_STATUS == 'true')
  if (tep_session_is_registered('customer_id')) {
	  $gv_query = tep_db_query("select amount from " . TABLE_COUPON_GV_CUSTOMER . " where customer_id = '" . $customer_id . "'");
  	$gv_result = tep_db_fetch_array($gv_query);
    if ($gv_result['amount']>0){
  		echo '              <tr><td width="10">' .  tep_draw_separator('pixel_trans.gif', '10', '1') .'</td><td colspan=2>' . "\n" .
  			 '              <table border="0" cellpadding="2" cellspacing="0" width="100%"><tr class="moduleRow" onclick="clearRadeos()">' . "\n" .
  			 '              <td width="10">' .  tep_draw_separator('pixel_trans.gif', '10', '1') .'</td><td>' . $gv_result['text'];

  		echo $order_total_modules->sub_credit_selection();
  	}
  }
// End - CREDIT CLASS Gift Voucher Contribution

if (is_array($buysafe_result) && $buysafe_result['IsBuySafeEnabled'] == 'true')
  {?>
    <tr><td colspan="4"><table>
    <?php
    $buysafe_module->draw_payment_page();
    ?>
    </td></tr></table>
   <?php
  }
//BOF Points/Rewards
  if ((USE_POINTS_SYSTEM == 'true') && (USE_REDEEM_SYSTEM == 'true')) {
    echo "<tr><td colspan=\"4\"><table border=\"0\">";
	  echo points_selection();
	  if (tep_not_null(USE_REFERRAL_SYSTEM) && (tep_count_customer_orders() == 0)) {
		  echo referral_input();
	  }
    echo "</table></td></tr>";
  }
//EOF Points/Rewards
?>
</table>