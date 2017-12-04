<?php

/**
 * Шаблон страницы оформления подписки на новостную рассылку
 */

/* @var $messageStack \messageStack */

?>
<h1><?php echo SUBSCRIBE_PAGE_HEADER; ?></h1>
<?php echo $messageStack->output(CONTENT_SUBSCRIBE); ?>
<?php echo SUBSCRIBE_DESCRIPTION; ?>
<form
    name="subscribe"
    id="subscribe-form"
    method="POST"
    action="<?php echo tep_href_link(FILENAME_SUBSCRIBE); ?>"
    >
    <input
        type="hidden"
        name="token"
        value="<?php echo tep_escape(\EShopmakers\Security\CSRFToken::getToken()); ?>"
        />
    <div class="form-group">
        <label
            for="subscribe-form-email-input"
            class="control-label"
            ><?php echo SUBSCRIBE_EMAIL_LABEL; ?></label>
        <input
            type="email"
            name="email"
            id="subscribe-form-email-input"
            class="form-control"
            <?php if($form_data['email']) : ?>value="<?php echo tep_href_link($form_data['email']); ?>"<?php endif; ?>
            required
            style="width: 300px;"
            />
    </div>
    <div class="text-right">
        <button
            type="submit"
            class="button"
            ><?php echo SUBSCRIBE_SUBMIT_BUTTON; ?></button>
    </div>
</form>