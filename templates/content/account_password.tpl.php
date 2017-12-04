<?php include_once 'includes/languages/russian/account.php'; ?>
<h1><?php echo MY_PASSWORD_TITLE; ?></h1>
<?php $messageStack->render('account_password'); ?>
<div class="account-grid clearfix">
    <div class="block-menu">
        <?php require __DIR__ . '/account.tpl.php'; ?>
    </div>
    <div class="block-content">
        <div class="tab-content common-styled-block">
            <form
                name="account_password"
                action="<?php echo tep_href_link(FILENAME_ACCOUNT_PASSWORD, '', 'SSL'); ?>"
                method="POST"
                class="middle-width-form"
                >
                <input type="hidden" name="action" value="process" />
                <div class="form-group">
                    <label
                        for="account-password-form-password-current-input"
                        class="control-label"
                        ><?php echo ENTRY_PASSWORD_CURRENT; ?></label>
                    <input
                        type="password"
                        id="account-password-form-password-current-input"
                        name="password_current"
                        class="form-control"
                        required
                        />
                </div>
                <div class="form-group">
                    <label
                        for="account-password-form-password-new-input"
                        class="control-label"
                        ><?php echo ENTRY_PASSWORD_NEW; ?></label>
                    <input
                        type="password"
                        id="account-password-form-password-new-input"
                        name="password_new"
                        class="form-control"
                        required
                        />
                </div>
                <div class="form-group">
                    <label
                        for="account-password-form-password-confirmation-input"
                        class="control-label"
                        ><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
                    <input
                        type="password"
                        id="account-password-form-password-confirmation-input"
                        name="password_confirmation"
                        class="form-control"
                        required
                        />
                </div>
                <div class="buttons-block">
                    <button type="submit" class="button"><?php echo IMAGE_BUTTON_CONTINUE; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php \EShopmakers\Html\Capture::getInstance('footer')->startCapture(); ?>
<script>
    (function($){
        'use strict';
        $(document).ready(function(){
            var form                             = $(document.forms.account_password),
                submit_button                    = form.find('button[type="submit"]'),
                password_current                 = $(document.forms.account_password.elements.password_current),
                password_current_parent          = password_current.parent(),
                password_new                     = $(document.forms.account_password.elements.password_new),
                password_new_parent              = password_new.parent(),
                password_confirmation            = $(document.forms.account_password.elements.password_confirmation),
                password_confirmation_parent     = password_confirmation.parent(),
                lang_new_password_error          = '<?php echo addslashes(ERROR_CURRENT_PASSWORD_AND_NEW_PASSWORD_ARE_THE_SAME); ?>',
                lang_password_confirmation_error = '<?php echo addslashes(ERROR_NEW_PASSWORD_AND_CONFIRMATION_PASSWORD_DO_NOT_MATCH); ?>',
                append_help_block                = function(element, text){
                    var help_block = element.children('.help-block');
                    if(help_block.length)
                    {
                        return;
                    }
                    element.append('<div class="help-block">' + text + '</div>');
                },
                validate_form                    = function(){
                    var _password_current      = password_current.val().trim(),
                        _password_new          = password_new.val().trim(),
                        _password_confirmation = password_confirmation.val().trim(),
                        has_errors             = false;
                
                    // Удаляем начальные и конечные пробельные символы в строках
                    password_current.val(_password_current);
                    password_new.val(_password_new)
                    password_confirmation.val(_password_confirmation)
                    
                    // Если все поля пустые, то прячем сообщения об ошибках и делаем кнопку
                    // отправки формы неактивной
                    if(!_password_current && !_password_new && !_password_confirmation)
                    {
                        password_current_parent.removeClass('has-error').find('.help-block').remove();
                        password_new_parent.removeClass('has-error').find('.help-block').remove();
                        password_confirmation_parent.removeClass('has-error').find('.help-block').remove();
                        has_errors = true;
                    }
                    
                    // Если новый пароль совпадает со старым, то показываем сообщение
                    // об ошибка рядом с полем для ввода нового пароля
                    if(_password_current && _password_current === _password_new)
                    {
                        password_new_parent.addClass('has-error');
                        append_help_block(password_new_parent, lang_new_password_error);
                        has_errors = true;
                    }
                    // В противном случаем убираем сообщение об ошибке
                    else
                    {
                        password_new_parent.removeClass('has-error').find('.help-block').remove();
                    }
                    
                    // Если поля для нового пароля и подтвержения пароля не пустые и новый пароль
                    // не соответствует подтверждению, то отображаем ошибку рядом с полем
                    // для ввода подтверждения, делаем кнопку отправки формы неактивной
                    if(_password_new && _password_confirmation && _password_new !== _password_confirmation)
                    {
                        password_confirmation_parent.addClass('has-error');
                        append_help_block(password_confirmation_parent, lang_password_confirmation_error);
                        has_errors = true;
                    }
                    // В противном случаем убираем сообщение об ошибке
                    else
                    {
                        password_confirmation_parent.removeClass('has-error').find('.help-block').remove();
                    }
                    
                    // Если хотя бы одно из полей не заполнено, делаем кнопку отправки формы неактивной
                    if(!_password_current || !_password_new || !_password_confirmation)
                    {
                        has_errors = true;
                    }
                    
                    if(has_errors)
                    {
                        submit_button.attr('disabled', '');
                    }
                    else
                    {
                        submit_button.removeAttr('disabled');
                    }
                    return !has_errors;
                };
            form.submit(validate_form);
            password_current.change(validate_form);
            password_new.change(validate_form);
            password_confirmation.change(validate_form);
            submit_button.attr('disabled', '');
        });
    })(window.jQuery || window.Zepto)
</script>
<?php \EShopmakers\Html\Capture::getInstance('footer')->stopCapture(); ?>