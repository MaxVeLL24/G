<div><?php
    if(tep_session_is_registered('customer_id') && ONEPAGE_CHECKOUT_SHOW_ADDRESS_INPUT_FIELDS == 'False')
    {
        echo tep_address_label($customer_id, $billto, true, ' ', '<br>');

        $address_query = tep_db_query("select entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $customer_id . "' and address_book_id = '" . (int) $billto . "'");
        $address = tep_db_fetch_array($address_query);

        echo '<input type="hidden" name="billing_firstname" value="' . $address['firstname'] . '" />';
        echo '<input type="hidden" name="billing_lastname" value="' . $address['lastname'] . '" />';
//   echo '<input type="hidden" name="billing_email_address" value="'.$address['firstname'].'" />';
//   echo '<input type="hidden" name="billing_telephone" value="'.$address['firstname'].'" />';
        echo '<input type="hidden" name="billing_country" value="' . $address['country_id'] . '" />';
        echo '<input type="hidden" name="billing_city" value="' . $address['city'] . '" />';
        echo '<input type="hidden" name="billing_street_address" value="' . $address['street_address'] . '" />';
    }
    else
    {
        if(tep_session_is_registered('onepage'))
        {
            $billingAddress = $onepage['billing'];
            $customerAddress = $onepage['customer'];
        }
        ?>
    <div class="form-group">
        <label for="checkout-form-billing-firstname-input"><?php echo ENTRY_FIRST_NAME; ?></label>
        <input
            type="text"
            id="checkout-form-billing-firstname-input"
            name="billing_firstname"
            class="form-control"
            placeholder="<?php echo ENTRY_FIRST_NAME; ?>"
            required
            <?php if(!empty($billingAddress['firstname'])) : ?>value="<?php echo tep_escape($billingAddress['firstname']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <div class="form-group">
        <label for="checkout-form-billing-lastname-input"><?php echo ENTRY_LAST_NAME; ?></label>
        <input
            type="text"
            id="checkout-form-billing-lastname-input"
            name="billing_lastname"
            class="form-control"
            placeholder="<?php echo ENTRY_LAST_NAME; ?>"
            required
            <?php if(!empty($billingAddress['lastname'])) : ?>value="<?php echo tep_escape($billingAddress['lastname']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <?php if(empty($_SESSION['customer_id'])) : ?>
    <div class="form-group">
        <label for="checkout-form-billing-email-address-input"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
        <input
            type="email"
            id="checkout-form-billing-email-address-input"
            name="billing_email_address"
            class="form-control"
            placeholder="<?php echo ENTRY_EMAIL_ADDRESS; ?>"
            required
            <?php if(!empty($customerAddress['email_address'])) : ?>value="<?php echo tep_escape($customerAddress['email_address']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <label for="checkout-form-billing-telephone-input"><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
        <input
            type="text"
            id="checkout-form-billing-telephone-input"
            <?php if($customer_id) : ?>
            data-customer="<?=$customer_id?>"
            <?php endif; ?>
            name="billing_telephone"
            class="form-control"
            placeholder="<?php echo ENTRY_TELEPHONE_NUMBER; ?>"
            required
            <?php if(!empty($customerAddress['telephone'])) : ?>value="<?php echo tep_escape($customerAddress['telephone']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <input
        type="hidden"
        name="country_id"
        value="<?php echo empty($billingAddress['country_id']) ? 220 : $billingAddress['country_id']; ?>"
        />
    <div class="form-group">
        <label for="checkout-form-billing-city-input"><?php echo ENTRY_CITY; ?></label>
        <input
            type="text"
            id="checkout-form-billing-city-input"
            name="billing_city"
            class="form-control"
            placeholder="<?php echo ENTRY_CITY; ?>"
            required
            <?php if(!empty($billingAddress['city'])) : ?>value="<?php echo tep_escape($billingAddress['city']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <?php if(ONEPAGE_ZIP_BELOW == 'True') : ?>
    <div class="form-group">
        <label for="checkout-form-billing-postcode-input"><?php echo ENTRY_POST_CODE; ?></label>
        <input
            type="text"
            id="checkout-form-billing-postcode-input"
            name="billing_zipcode"
            class="form-control"
            placeholder="<?php echo ENTRY_POST_CODE; ?>"
            required
            <?php if(!empty($billingAddress['postcode'])) : ?>value="<?php echo tep_escape($billingAddress['postcode']); ?>"<?php endif; ?>
            />
        <div class="help-block"><?php echo CHECKOUT_REQUIRED_FIELD; ?></div>
    </div>
    <?php endif; ?>
    <?php if(empty($_SESSION['customer_id'])) : ?>
    <input
        type="hidden"
        name="password"
        />
    <input
        type="hidden"
        name="confirmation"
        />
    <input
        type="hidden"
        name="billing_newsletter"
        value="<?php echo isset($customerAddress['newsletter']) ? $customerAddress['newsletter'] : 1; ?>"
        />
    <?php endif; ?>
        <?php
    }
    ?></div>