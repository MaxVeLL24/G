<h1><?php echo HEADING_TITLE; ?></h1>
<?php $messageStack->render('password_forgotten'); ?>
<p><?php echo TEXT_MAIN; ?></p>
<form
    name="password_forgotten"
    method="POST"
    action="<?php echo tep_href_link(FILENAME_PASSWORD_FORGOTTEN, 'action=process', 'SSL'); ?>"
    class="middle-width-form"
    >
    <div class="form-group">
        <label
            for="password-forgotten-form-email-address-input"
            class="conreol-label"
            ><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
        <input
            type="text"
            id="password-forgotten-form-email-address-input"
            name="email_address"
            class="form-control"
            required
            />
    </div>
    <div class="buttons-block clearfix">
        <a href="<?php echo tep_href_link(FILENAME_LOGIN, '', 'SSL'); ?>" class="button button-red float-left"><?php echo IMAGE_BUTTON_BACK; ?></a>
        <button type="submit" class="button float-right"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
    </div>
</form>