<h1><?php echo HEADING_TITLE_REG_CLIENT; ?></h1>
<?php $messageStack->render('create_account'); ?>
<form
    id="create-account-form"
    name="create_account"
    action="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>"
    method="POST"
    >
    <input type="hidden" name="action" value="process" />
    <input type="hidden" name="guest_account" value="<?php echo $guest_account; ?>" />
    <div class="form-group">
        <label
            for="create-account-form-first-name-input"
            class="control-label"
            ><?php echo ENTRY_FIRST_NAME; ?></label>
        <input
            type="text"
            id="create-account-form-first-name-input"
            name="firstname"
            class="form-control"
            <?php if(ENTRY_FIRST_NAME_TEXT) : ?>required<?php endif; ?>
            <?php if(!empty($firstname)) : ?>value="<?php echo tep_escape($firstname); ?>"<?php endif; ?>
            />
    </div>
    <div class="form-group">
        <label
            for="create-account-form-last-name-input"
            class="control-label"
            ><?php echo ENTRY_LAST_NAME; ?></label>
        <input
            type="text"
            id="create-account-form-last-name-input"
            name="lastname"
            class="form-control"
            <?php if(ENTRY_LAST_NAME_TEXT) : ?>required<?php endif; ?>
            <?php if(!empty($lastname)) : ?>value="<?php echo tep_escape($lastname); ?>"<?php endif; ?>
            />
    </div>
    <div class="form-group">
        <label
            for="create-account-form-email-address-input"
            class="control-label"
            ><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
        <input
            type="email"
            id="create-account-form-email-address-input"
            name="email_address"
            class="form-control"
            <?php if(ENTRY_EMAIL_ADDRESS_TEXT) : ?>required<?php endif; ?>
            <?php if(!empty($email_address)) : ?>value="<?php echo tep_escape($email_address); ?>"<?php endif; ?>
            />
    </div>
    <?php if(ACCOUNT_STREET_ADDRESS == 'true') : ?>
    <div class="form-group">
        <label
            for="create-account-form-street-address-input"
            class="control-label"
            ><?php echo ENTRY_STREET_ADDRESS; ?></label>
        <input
            type="text"
            id="create-account-form-street-address-input"
            name="street_address"
            class="form-control"
            <?php if(!empty($street_address)) : ?>value="<?php echo tep_escape($street_address); ?>"<?php endif; ?>
            />
    </div>
    <?php endif; ?>
    <?php if(ACCOUNT_POSTCODE == 'true') : ?>
    <div class="form-group">
        <label
            for="create-account-form-postcode-input"
            class="control-label"
            ><?php echo ENTRY_POST_CODE; ?></label>
        <input
            type="text"
            id="create-account-form-postcode-input"
            name="postcode"
            class="form-control"
            <?php if(ENTRY_POST_CODE_TEXT) : ?>required<?php endif; ?>
            <?php if(!empty($postcode)) : ?>value="<?php echo tep_escape($postcode); ?>"<?php endif; ?>
            />
    </div>
    <?php endif; ?>
    <?php if(ACCOUNT_CITY == 'true') : ?>
    <div class="form-group">
        <label
            for="create-account-form-city-input"
            class="control-label"
            ><?php echo ENTRY_CITY; ?></label>
        <input
            type="text"
            id="create-account-form-city-input"
            name="city"
            class="form-control"
            <?php if(ENTRY_CITY_TEXT) : ?>required<?php endif; ?>
            <?php if(!empty($city)) : ?>value="<?php echo tep_escape($city); ?>"<?php endif; ?>
            />
    </div>
    <?php if(ACCOUNT_COUNTRY == 'true') : ?>
    <input type="hidden" name="country" value="<?php echo $country; ?>" />
    <?php endif; ?>
    <?php endif; ?>
    <?php if(ACCOUNT_TELE == 'true') : ?>
    <div class="form-group">
        <label
            for="create-account-form-telephone-input"
            class="control-label"
            ><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
        <input
            type="text"
            id="create-account-form-telephone-input"
            name="telephone"
            class="form-control"
            <?php if(ENTRY_TELEPHONE_NUMBER_TEXT) : ?>required<?php endif; ?>
            <?php if(!empty($telephone)) : ?>value="<?php echo tep_escape($telephone); ?>"<?php endif; ?>
            />
    </div>
    <?php \EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
    <script>
        $(document).ready(function(){
            $('#create-account-form-telephone-input').mask('+380 (99) 999-99-99', {
                translation: {
                    '9': {
                        pattern: /[0-9]/
                    }
                }
            });
        });
    </script>
    <?php \EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>
    <?php endif; ?>
    <?php if($guest_account == false) : ?>
    <div class="form-group">
        <label
            for="create-account-form-password-input"
            class="control-label"
            ><?php echo ENTRY_PASSWORD; ?></label>
        <input
            type="password"
            id="create-account-form-password-input"
            name="password"
            class="form-control"
            <?php if(ENTRY_PASSWORD_TEXT) : ?>required<?php endif; ?>
            />
    </div>
    <div class="form-group">
        <label
            for="create-account-form-confirmation-input"
            class="control-label"
            ><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
        <input
            type="password"
            id="create-account-form-confirmation-input"
            name="confirmation"
            class="form-control"
            <?php if(ENTRY_PASSWORD_CONFIRMATION_TEXT) : ?>required<?php endif; ?>
            />
    </div>
    <?php endif; ?>
    <div class="buttons-block align-right">
        <button type="submit" class="button"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
    </div>
</form>
<?php return; ?>
<div class="size1of3" style="margin:0 auto;">
    <?php echo tep_draw_form('create_account', tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'), 'post', 'onSubmit="return check_form(create_account);"') . tep_draw_hidden_field('action', 'process') . tep_draw_hidden_field('guest_account', $guest_account); ?>

    <?php
// BOF: Lango Added for template MOD
    if(SHOW_HEADING_TITLE_ORIGINAL == 'yes')
    {
        $header_text = '&nbsp;'
//EOF: Lango Added for template MOD
        ?>

        <?php
// BOF: Lango Added for template MOD
    }
    else
    {
        $header_text = HEADING_TITLE;
    }
// EOF: Lango Added for template MOD

    echo '<h1>' . HEADING_TITLE_REG_CLIENT . '</h1>';
    ?>
    <div style="padding-left:2px;"><br />
        <?php echo '<a href="' . tep_href_link(FILENAME_LOGIN, tep_get_all_get_params(), 'SSL') . '">' . HEADING_TITLE_REG_LOGIN; ?>
        <br /><br />
    </div>
    <?php $messageStack->render('create_account', 'tr'); ?>
    <div class="reg_allpage">
        <table border="0" cellspacing="0" cellpadding="2">

            <tr>
                <td class="main" width="140"><?php echo ENTRY_FIRST_NAME; ?></td>
                <td class="main"><?php echo tep_draw_input_field('firstname', '', 'class="reg_input" style="width:120px;"') . '&nbsp;' . (tep_not_null(ENTRY_FIRST_NAME_TEXT) ? '<span class="inputRequirement">' . ENTRY_FIRST_NAME_TEXT . '</span>' : ''); ?></td>
            </tr>

            <tr>
                <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
                <td class="main"><?php echo tep_draw_input_field('email_address', '', 'class="reg_input" style="width:120px;"') . '&nbsp;' . (tep_not_null(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="inputRequirement">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' : ''); ?></td>
            </tr>
        </table>
    </div>

    <div class="reg_allpage">
        <table border="0" cellspacing="0" cellpadding="2">
            <?php
            if(ACCOUNT_STREET_ADDRESS == 'true')
            {
                ?>
                <tr>
                    <td class="main" width="140"><?php echo ENTRY_STREET_ADDRESS; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('street_address', '', 'class="reg_input"'); ?></td>
                </tr>
                <?php
            }
            ?>

            <?php
            if(ACCOUNT_POSTCODE == 'true')
            {
                ?>
                <tr>
                    <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('postcode') . '&nbsp;' . (tep_not_null(ENTRY_POST_CODE_TEXT) ? '<span class="inputRequirement">' . ENTRY_POST_CODE_TEXT . '</span>' : ''); ?></td>
                </tr>
                <?php
            }
            ?>
            <?php
            if(ACCOUNT_CITY == 'true')
            {
                ?>
                <tr>
                    <td class="main"><?php echo ENTRY_CITY; ?><?php if(ACCOUNT_COUNTRY == 'true') echo tep_draw_hidden_field('country', STORE_COUNTRY, ''); ?></td>
                    <td class="main"><?php echo tep_draw_input_field('city', '', 'class="reg_input" style="width:120px;"') . '&nbsp;' . (tep_not_null(ENTRY_CITY_TEXT) ? '<span class="inputRequirement">' . ENTRY_CITY_TEXT . '</span>' : ''); ?>
                        &nbsp;&nbsp;<?php
                        if(ACCOUNT_STATE == 'true')
                        {
                            ?>
                            <script language="javascript">
                                <!--
                                function changeselect(reg) {
                                    //clear select
                                    document.create_account.state.length = 0;
                                    var j = 0;
                                    for (var i = 0; i < zones.length; i++) {
                                        if (zones[i][0] == document.create_account.country.value) {
                                            document.create_account.state.options[j] = new Option(zones[i][1], zones[i][1]);
                                            j++;
                                        }
                                    }
                                    if (j == 0) {
                                        document.create_account.state.options[0] = new Option('-', '-');
                                    }
                                    if (reg) {
                                        document.create_account.state.value = reg;
                                    }
                                }
                                var zones = new Array(
        <?php
        $zones_query = tep_db_query("select zone_country_id,zone_name from " . TABLE_ZONES . " order by zone_id asc");
        $mas = array();
        while($zones_values = tep_db_fetch_array($zones_query))
        {
            $zones[] = 'new Array(' . $zones_values['zone_country_id'] . ',"' . $zones_values['zone_name'] . '")';
        }
        echo implode(',', $zones);
        ?>
                                );
                                document.write('<SELECT NAME="state" class="reg_input" style="width:120px;padding:1px;">');
                                document.write('</SELECT>');
                                changeselect("<?php echo tep_db_prepare_input($_POST['state']); ?>");
        -->
                            </script>
                        <?php } ?>
                    </td>
                </tr>
                <?php
            }
            ?>    
            <?php
            if(ACCOUNT_TELE == 'true')
            {
                ?>
                <tr>
                    <td class="main" width="140"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
                    <td class="main"><?php echo tep_draw_input_field('telephone', '+380', 'class="reg_input" style="width:120px;"') . '&nbsp;' . (tep_not_null(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="inputRequirement">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>' : ''); ?></td>
                </tr>
                <?php
            }
            ?>         
        </table>
    </div>
    <?php
    if($guest_account == false)
    { // Not a Guest Account
        ?>
        <div class="reg_allpage">
            <table border="0" cellspacing="0" cellpadding="2">
                <tr>
                    <td class="main" width="140"><?php echo ENTRY_PASSWORD; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('password', '', 'class="reg_input" style="width:120px;"') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_TEXT . '</span>' : ''); ?></td>
                </tr>
                <tr>
                    <td class="main"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></td>
                    <td class="main"><?php echo tep_draw_password_field('confirmation', '', 'class="reg_input" style="width:120px;"') . '&nbsp;' . (tep_not_null(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="inputRequirement">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>' : ''); ?></td>
                </tr>
            </table>
        </div>

        <?php
    } // Guest Account end
    ?>
    <div>
        <table border="0" width="290px" cellspacing="0" cellpadding="2">
            <tr>               
                <td align="center"><input type="submit" class="submit btn" value="<?php echo IMAGE_BUTTON_CONTINUE; ?>"></td>
            </tr>
        </table>
    </div>
</form>   
</div>