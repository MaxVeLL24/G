    <?php echo tep_draw_form('contact_us', tep_href_link(FILENAME_CONTACT_US, 'action=send')); ?><table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>

          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE;
}
// EOF: Lango Added for template MOD
?>

<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
$header_text = HEADING_TITLE;
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
<?php
  if ($messageStack->size('contact') > 0) {
?>
      <tr>
        <td><?php echo $messageStack->output('contact'); ?></td>
      </tr>
      <tr>
        <td></td>
      </tr>
<?php
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
?>

      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
                <td width="10"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>

<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <?php
          if (CONTACT_US_LIST !=''){
                      echo '<tr><td class="main">'. SEND_TO_TEXT. '</td></tr>'
                         .'<tr><td class="main">';
                 if(SEND_TO_TYPE=='radio'){
                 foreach(explode("," ,CONTACT_US_LIST) as $k => $v) {
                    if($k==0){
                    $checked=true;
                    }else{
                    $checked=false;
                    }
                    echo tep_draw_radio_field('send_to', "$k", $checked). " " .preg_replace('/\<[^*]*/', '', $v)."<br>\n";
                 }

               }else{
                   foreach(explode("," ,CONTACT_US_LIST) as $k => $v) {
                        $send_to_array[] = array('id' => $k, 'text' => preg_replace('/\<[^*]*/', '', $v));
                     }
                echo tep_draw_pull_down_menu('send_to',  $send_to_array);
               }

            echo "\n</td></tr>\n";

            }
             ?>

              <tr>
                <td class="main"><?php echo ENTRY_NAME; ?></td>
              </tr>
              <tr>
                <td class="main"><?php
                          // prefill first+last name and email address if customer is logged in
                          if (tep_session_is_registered('customer_id')) {
                          $customer_query_raw = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_id='" . $customer_id . "'";
                          $customer_query = tep_db_query($customer_query_raw);
                          $customer_array = tep_db_fetch_array($customer_query);
                          echo tep_draw_input_field('name', $customer_array['customers_firstname'] . " " . $customer_array['customers_lastname']);
                          echo "</td></tr><tr><td class=\"main\">" . ENTRY_EMAIL . "</td></tr><tr><td class=\"main\">";
                          echo tep_draw_input_field('email', $customer_array['customers_email_address']);
                            } else {
                          echo tep_draw_input_field('name');
                          echo "</td></tr><tr><td class=\"main\">" . ENTRY_EMAIL . "</td></tr><tr><td class=\"main\">";
                                echo tep_draw_input_field('email');
                            } ?></td>
              </tr>
              <tr><td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td></tr>
              <tr><td><?php echo tep_draw_input_field('phone','','class="bg_reg"'); ?></td></tr>
              <tr>
                <td class="main"><?php echo ENTRY_ENQUIRY; ?></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_textarea_field('enquiry', 'soft', 50, 15); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><br /><table border="0" width="365px" cellspacing="1" cellpadding="2" class="infoBox">
          <tr class="infoBoxContents">
            <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td width="10"></td>
                <td align="right"><button type="submit" class="btn yellow bold" style="cursor: pointer;">Отправить</button></td>
                <td width="10"></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></form>

