<div class="schedule"><?php echo TEXT_SCHEDULE; ?></div>
<form name="quick_search" method="GET" action="<?php echo tep_href_link(FILENAME_DEFAULT); ?>">
    <input type="hidden" name="token" value="<?php echo tep_escape(\EShopmakers\Security\CSRFToken::getToken()); ?>" />
    <div class="input clearfix">
        <input type="text" name="keywords" placeholder="<?php echo BOX_HEADING_SEARCH; ?>" class="form-control" />
        <button type="submit"></button>
    </div>
    <div class="results"></div>
</form>