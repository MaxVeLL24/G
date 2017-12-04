<?php
include_once __DIR__ . '/includes/application_top.php';
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CONTACT_US);

if(tep_session_is_registered('customer_id'))
{
    $customer_query_raw = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id='" . $customer_id . "'";
    $customer_query = tep_db_query($customer_query_raw);
    $customer_array = tep_db_fetch_array($customer_query);
}
?>
<?php if(isset($_GET['action']) && ($_GET['action'] == 'success')) : ?>
    <div class="section-title align-center"><?php echo MESS; ?></div>
<?php else : ?>
    <div class="section-title align-center"><?php echo HEADING_PEREZVONIM; ?></div>
    <form
        name="contact_us"
        method="POST"
        action="<?php echo tep_href_link(FILENAME_CONTACT_US, 'action=send'); ?>"
        >
        <input type="hidden" name="send_to" value="<?php echo CONTACT_US_LIST; ?>" />
        <div class="form-group">
            <label
                class="control-label"
                for="contact-us-form-name-input"
                ><?php echo ENTRY_NAME; ?></label>
            <input
                type="text"
                id="contact-us-form-name-input"
                name="name"
                class="form-control"
                />
        </div>
        <div class="form-group">
            <label
                class="control-label"
                for="contact-us-form-email-input"
                ><?php echo ENTRY_EMAIL; ?></label>
            <input
                type="email"
                id="contact-us-form-email-input"
                name="email"
                class="form-control"
                />
        </div>
        <div class="form-group">
            <label
                class="control-label"
                for="contact-us-form-phone-input"
                ><?php echo ENTRY_TELEPHONE_NUMBER; ?></label>
            <input
                type="text"
                id="contact-us-form-phone-input"
                name="phone"
                class="form-control"
                />
        </div>
        <div class="form-group">
            <label
                class="control-label"
                for="contact-us-form-enquiry-input"
                ><?php echo ENTRY_ENQUIRY; ?></label>
            <textarea
                id="contact-us-form-enquiry-input"
                name="enquiry"
                wrap="soft"
                class="form-control"
                ></textarea>
        </div>
        <div class="buttons-block align-right">
            <button type="submit" class="button"><?php echo IMAGE_BUTTON_SEND; ?></button>
            <button type="button" class="button button-red close-popup"><?php echo IMAGE_BUTTON_BACK; ?></button>
        </div>
    </form>
    <script>
        (function($){
            $(document).ready(function(){
                $('#contact-us-form-phone-input').mask('+380 (99) 999-99-99', {
                    'translation': {
                        '9': {
                            'pattern': /[0-9]/
                        }
                    }
                });
            });
        })(window.jQuery || window.Zepto);
    </script>
<?php endif; ?>