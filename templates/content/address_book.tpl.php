<?php

include_once 'includes/languages/' . $language . '/account.php';
$addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int) $customer_id . "' order by firstname, lastname");
$addresses = array();
if(tep_db_num_rows($addresses_query))
{
    while(($row = tep_db_fetch_array($addresses_query)) !== false)
    {
        $addresses[] = $row;
    }
}

// PRIMARY_ADDRESS

?>
<h1><?php echo HEADING_TITLE; ?></h1>
<?php $messageStack->render('addressbook'); ?>
<div class="account-grid clearfix">
    <div class="block-menu">
        <?php require __DIR__ . '/account.tpl.php'; ?>
    </div>
    <div class="block-content">
        <div class="tab-content common-styled-block">
            <div><?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></div>
            <div><?php echo tep_address_label($customer_id, $customer_default_address_id, true, ' ', '<br>'); ?></div>
            <h2><?php echo ADDRESS_BOOK_TITLE; ?></h2>
            <div class="address-book-entries">
                <?php foreach($addresses as $addresses) : ?>
                    <div class="address-book-entry<?php if($addresses['address_book_id'] == $customer_default_address_id) : ?> primary-entry<?php endif; ?> clearfix">
                        <div class="buttons">
                            <a class="button button-block button-red" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL'); ?>"><?php echo IMAGE_BUTTON_DELETE; ?></a>
                            <a class="button button-block button-blue" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>"><?php echo SMALL_IMAGE_BUTTON_EDIT; ?></a>
                        </div>
                        <div class="content">
                            <div><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']) . ($addresses['address_book_id'] == $customer_default_address_id ? ' <b>' . PRIMARY_ADDRESS . '</b>' : ''); ?></div>
                            <div><?php echo tep_address_format(tep_get_address_format_id($addresses['country_id']), $addresses, true, ' ', '<br>'); ?></div>
                        </div>
                    </div>
                <?php endforeach;  ?>
            </div>
            <?php if(tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) : ?>
            <div class="buttons-block align-right">
                <a class="button" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL'); ?>"><?php echo IMAGE_BUTTON_ADD_ADDRESS; ?></a>
            </div>
            <?php endif; ?>
            <br />
            <p><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>
        </div>
    </div>
</div>
