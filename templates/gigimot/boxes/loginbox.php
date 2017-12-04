<?php

/**
 * Авторизация и кабинет пользователя
 */

?>
<span class="customer-cp">
    <?php /* Пользователя аутентифицирован? */ ?>
    <?php if(tep_session_is_registered('customer_id')): ?>
    <a href="<?php echo tep_href_link(FILENAME_ACCOUNT_HISTORY); ?>"><?php echo LOGIN_BOX_MY_CABINET; ?></strong></a>
    <a href="<?php echo tep_href_link(FILENAME_LOGOFF); ?>"><?php echo LOGIN_BOX_LOGOFF; ?></a>
    <?php else: ?>
        <a class="log-in" href="<?php echo tep_href_link(FILENAME_LOGIN); ?>"><?= LOGIN_FROM_SITE; ?></a>
        <a class="register" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT); ?>"><?= HEADER_TITLE_CREATE_ACCOUNT; ?></a>
    <?php endif; ?>
</span>