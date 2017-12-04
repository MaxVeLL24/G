<?php

/**
 * Шаблон страницы редактирования записи в адресной книге покупателя
 */

include_once 'includes/languages/russian/account.php';

// Заголовок страницы
if(isset($_GET['edit']))
{
    $page_header = HEADING_TITLE_MODIFY_ENTRY;
}
elseif(isset($_GET['delete']))
{
    $page_header = HEADING_TITLE_DELETE_ENTRY;
}
else
{
    $page_header = HEADING_TITLE_ADD_ENTRY;
}

$page_title = $page_header;
$page_robots_tag = 'noindex, follow';

?>
<h1><?php echo $page_header; ?></h1>
<?php $messageStack->render('addressbook'); ?>
<div class="account-grid clearfix">
    <div class="block-menu">
        <?php require __DIR__ . '/account.tpl.php'; ?>
    </div>
    <div class="block-content">
        <div class="tab-content common-styled-block">
            <?php if(!empty($_GET['delete'])) : ?>
            <p><?php echo DELETE_ADDRESS_DESCRIPTION; ?></p>
            <div><?php echo tep_address_label($customer_id, $_GET['delete'], true, ' ', '<br>'); ?></div>
            <div class="buttons-block align-right">
                <a class="button" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>"><?php echo IMAGE_BUTTON_BACK; ?></a>
                <a class="button button-red" href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $_GET['delete'] . '&action=deleteconfirm', 'SSL'); ?>"><?php echo IMAGE_BUTTON_DELETE; ?></a>
            </div>
            <?php else : ?>
            <form
                name="addressbook"
                method="POST"
                action="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, (isset($_GET['edit']) ? 'edit=' . $_GET['edit'] : ''), 'SSL'); ?>"
                class="middle-width-form"
                >
                <?php if(ACCOUNT_GENDER == 'true') : ?>
                <div class="form-group">
                    <div class="control-label"><?php echo ENTRY_GENDER; ?></div>
                    <div class="radiobox-group">
                        <span class="custom-radiobox">
                            <input
                                type="radio"
                                id="addressbook-form-gender-input-male"
                                name="gender"
                                value="m"
                                <?php if(empty($entry['entry_gender']) || $entry['entry_gender'] === 'm') : ?>checked<?php endif; ?>
                                />
                            <label for="addressbook-form-gender-input-male"></label>
                        </span>
                        <label for="addressbook-form-gender-input-male"><?php echo MALE; ?></label>
                    </div>
                    <div class="radiobox-group">
                        <span class="custom-radiobox">
                            <input
                                type="radio"
                                id="addressbook-form-gender-input-female"
                                name="gender"
                                value="m"
                                <?php if(!empty($entry['entry_gender']) && $entry['entry_gender'] === 'f') : ?>checked<?php endif; ?>
                                />
                            <label for="addressbook-form-gender-input-female"></label>
                        </span>
                        <label for="addressbook-form-gender-input-female"><?php echo FEMALE; ?></label>
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-first-name-input"
                        class="control-label"
                        ><?php echo ENTRY_FIRST_NAME; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-first-name-input"
                        name="firstname"
                        class="form-control"
                        <?php if(ENTRY_FIRST_NAME_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_firstname'])) : ?>value="<?php echo tep_escape($entry['entry_firstname']); ?>"<?php endif; ?>
                        />
                </div>
                <div class="form-group">
                    <label
                        for="addressbook-form-last-name-input"
                        class="control-label"
                        ><?php echo ENTRY_LAST_NAME; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-last-name-input"
                        name="lastname"
                        class="form-control"
                        <?php if(ENTRY_LAST_NAME_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_lastname'])) : ?>value="<?php echo tep_escape($entry['entry_lastname']); ?>"<?php endif; ?>
                        />
                </div>
                <?php if(ACCOUNT_COMPANY == 'true') : ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-company-input"
                        class="control-label"
                        ><?php echo ENTRY_COMPANY; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-company-input"
                        name="company"
                        class="form-control"
                        <?php if(ENTRY_LAST_NAME_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_company'])) : ?>value="<?php echo tep_escape($entry['entry_company']); ?>"<?php endif; ?>
                        />
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-street-address-input"
                        class="control-label"
                        ><?php echo ENTRY_STREET_ADDRESS; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-street-address-input"
                        name="street_address"
                        class="form-control"
                        <?php if(ENTRY_STREET_ADDRESS_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_street_address'])) : ?>value="<?php echo tep_escape($entry['entry_street_address']); ?>"<?php endif; ?>
                        />
                </div>
                <?php if(ACCOUNT_SUBURB == 'true') : ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-suburb-input"
                        class="control-label"
                        ><?php echo ENTRY_SUBURB; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-suburb-input"
                        name="suburb"
                        class="form-control"
                        <?php if(ENTRY_SUBURB_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_suburb'])) : ?>value="<?php echo tep_escape($entry['entry_suburb']); ?>"<?php endif; ?>
                        />
                </div>
                <?php endif; ?>
                <?php if(ACCOUNT_POSTCODE == 'true') : ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-postcode-input"
                        class="control-label"
                        ><?php echo ENTRY_POST_CODE; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-postcode-input"
                        name="postcode"
                        class="form-control"
                        <?php if(ENTRY_POST_CODE_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_postcode'])) : ?>value="<?php echo tep_escape($entry['entry_postcode']); ?>"<?php endif; ?>
                        />
                </div>
                <?php endif; ?>
                <?php if(ACCOUNT_CITY == 'true') : ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-city-input"
                        class="control-label"
                        ><?php echo ENTRY_CITY; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-city-input"
                        name="city"
                        class="form-control"
                        <?php if(ENTRY_CITY_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_city'])) : ?>value="<?php echo tep_escape($entry['entry_city']); ?>"<?php endif; ?>
                        />
                </div>
                <?php endif; ?>
                <?php if(ACCOUNT_STATE == 'true') : ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-state-input"
                        class="control-label"
                        ><?php echo ENTRY_STATE; ?></label>
                    <input
                        type="text"
                        id="addressbook-form-state-input"
                        name="state"
                        class="form-control"
                        <?php if(ENTRY_STATE_TEXT) : ?>required<?php endif; ?>
                        <?php if(!empty($entry['entry_state'])) : ?>value="<?php echo tep_escape($entry['entry_state']); ?>"<?php endif; ?>
                        />
                </div>
                <?php endif; ?>
                <?php if(ACCOUNT_COUNTRY == 'true') : ?>
                <div class="form-group">
                    <label
                        for="addressbook-form-country-input"
                        class="control-label"
                        ><?php echo ENTRY_COUNTRY; ?></label>
                    <?php

                    // Выбрать существующие страны
                    $query = tep_db_query("SELECT countries_id, countries_name FROM countries ORDER BY countries_name ASC");
                    $countries = array();
                    if(tep_db_num_rows($query))
                    {
                        while(($row = tep_db_fetch_array($query)) !== false)
                        {
                            $countries[$row['countries_id']] = $row['countries_name'];
                        }
                    }
                    // Страна по-умолчанию, если не выбрана пользователем
                    if(empty($entry['entry_country_id']))
                    {
                        $entry['entry_country_id'] = DEFAULT_COUNTRY;
                    }

                    ?>
                    <select
                        class="custom-select form-control"
                        name="country"
                        >
                        <?php foreach($countries as $country_id => $country_name) : ?>
                        <option
                            value="<?php echo $country_id; ?>"
                            <?php if($country_id == $entry['entry_country_id']) : ?>selected<?php endif; ?>
                            ><?php echo tep_escape($country_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="buttons-block align-right clearfix">
                    <?php if(isset($_GET['edit']) && $_GET['edit'] != $customer_default_address_id) : ?>
                    <span class="float-left">
                        <span class="custom-checkbox">
                            <input type="checkbox" id="set-as-primary" name="primary" value="on" />
                            <label for="set-as-primary"></label>
                        </span>
                        <label for="set-as-primary"><?php echo SET_AS_PRIMARY; ?></label>
                    </span>
                    <?php endif; ?>
                    <?php if(!empty($_GET['edit'])) : ?>
                    <input type="hidden" name="edit" value="<?php echo $_GET['edit']; ?>" />
                    <input type="hidden" name="action" value="update" />
                    <?php else : ?>
                    <input type="hidden" name="action" value="process" />
                    <?php endif; ?>
                    <button type="submit" class="button"><?php echo IMAGE_BUTTON_UPDATE; ?></button>
                    <a href="<?php echo tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL'); ?>" class="button button-red"><?php echo IMAGE_BUTTON_BACK; ?></a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>
