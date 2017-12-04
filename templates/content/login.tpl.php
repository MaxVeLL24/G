<?php $messageStack->render('login'); ?>
<div class="login-columns clearfix">
    <div class="column column-login">
        <div class="column-padding">
            <h2><?php echo TEXT_RETURNING_CUSTOMER; ?></h2>
            <form
                name="login"
                id="login-form"
                action="<?php echo tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL'); ?>"
                method="POST"
                >
                <div class="form-group">
                    <label
                        for="login-form-email-address-input"
                        class="control-label"
                        ><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
                    <input
                        type="email"
                        id="login-form-email-address-input"
                        name="email_address"
                        required
                        class="form-control"
                        />
                </div>
                <div class="form-group">
                    <label
                        for="login-form-password-input"
                        class="control-label"
                        ><?php echo ENTRY_PASSWORD; ?></label>
                    <input
                        type="password"
                        id="login-form-password-input"
                        name="password"
                        required
                        class="form-control"
                        />
                </div>
                <div class="buttons-block">
                    <button type="submit" class="button"><?php echo IMAGE_BUTTON_SEND; ?></button>
                    <a href="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, '', 'SSL'); ?>"><?php echo TEXT_PASSWORD_FORGOTTEN; ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="column column-register">
        <div class="column-padding">
            <h2><?php echo TEXT_NEW_CUSTOMER; ?></h2>
            <?php echo TEXT_NEW_CUSTOMER_INTRODUCTION; ?>
            <div class="buttons-block">
                <a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT, '', 'SSL'); ?>" class="button"><?php echo IMAGE_BUTTON_CONTINUE; ?></a>
            </div>
        </div>
    </div>
</div>