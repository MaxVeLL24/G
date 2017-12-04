<?php include_once 'includes/languages/' . $language . '/account.php'; ?>
<h1><?php echo MY_ACCOUNT_TITLE; ?></h1>
<?php $messageStack->render(CONTENT_ACCOUNT_EDIT); ?>
<div class="account-grid clearfix">
    <div class="block-menu">
        <?php require __DIR__ . '/account.tpl.php'; ?>
    </div>
    <div class="block-content">
        <div class="tab-content common-styled-block">
            <form
                name="account_edit"
                action="<?php echo tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL'); ?>"
                method="POST"
                class="middle-width-form"
                >
                <input type="hidden" name="action" value="process" />
                <div class="form-group">
                    <label
                        for="account-edit-form-firstname-input"
                        class="control-label"
                        ><?php echo ENTRY_FIRST_NAME; ?></label>
                    <input
                        type="text"
                        id="account-edit-form-firstname-input"
                        name="firstname"
                        class="form-control"
                        <?php if(!empty($account['customers_firstname'])) : ?>value="<?php echo tep_escape($account['customers_firstname']); ?>"<?php endif; ?>
                        <?php if(ENTRY_FIRST_NAME_TEXT) : ?>required<?php endif; ?>
                        />
                </div>
                <div class="form-group">
                    <label
                        for="account-edit-form-lastname-input"
                        class="control-label"
                        ><?php echo ENTRY_LAST_NAME; ?></label>
                    <input
                        type="text"
                        id="account-edit-form-lastname-input"
                        name="lastname"
                        class="form-control"
                        <?php if(!empty($account['customers_lastname'])) : ?>value="<?php echo tep_escape($account['customers_lastname']); ?>"<?php endif; ?>
                        <?php if(ENTRY_LAST_NAME_TEXT) : ?>required<?php endif; ?>
                        />
                </div>
                <div class="form-group">
                    <label
                        for="account-edit-email-address-input"
                        class="control-label"
                        ><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
                    <input
                        type="email"
                        id="account-edit-form-email-address-input"
                        name="email_address"
                        class="form-control"
                        <?php if(!empty($account['customers_email_address'])) : ?>value="<?php echo tep_escape($account['customers_email_address']); ?>"<?php endif; ?>
                        <?php if(ENTRY_EMAIL_ADDRESS_TEXT) : ?>required<?php endif; ?>
                        />
                </div>
                <div class="form-group">
                    <label
                        for="account-edit-form-telephone-input"
                        class="control-label"
                        ><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
                    <input
                        type="text"
                        id="account-edit-form-telephone-input"
                        name="telephone"
                        class="form-control"
                        <?php if(!empty($account['customers_telephone'])) : ?>value="<?php echo tep_escape($account['customers_telephone']); ?>"<?php endif; ?>
                        <?php if(ENTRY_TELEPHONE_NUMBER_TEXT) : ?>required<?php endif; ?>
                        />
                </div>
                <div class="buttons-block">
                    <button type="submit" class="button"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
