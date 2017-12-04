<?php
/*
  $Id: configuration.php,v 1.2 2003/09/24 13:57:07 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- configuration //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CONFIGURATION,
                     'link'  => tep_href_link(FILENAME_CONFIGURATION, 'gID=1&selected_box=configuration'));

  if ($selected_box == 'configuration' || $menu_dhtml == true) {
    $cfg_groups = '';
    $configuration_groups_query = tep_db_query("select configuration_group_id as cgID, configuration_group_key as cgKey, configuration_group_title as cgTitle from " . TABLE_CONFIGURATION_GROUP . " where visible = '1' order by sort_order");
    while ($configuration_groups = tep_db_fetch_array($configuration_groups_query)) {
      if ($configuration_groups['cgID'] == '902') {
        if (SMSINFORM_MODULE_ENABLED != 'true') {
          continue;
        }
      }
      if ($configuration_groups['cgID'] == '26230') {
        if(XML_MODULE_ENABLED != 'true'){
          continue;
        }
      }
      $cfg_groups .= '<a href="' . tep_href_link(FILENAME_CONFIGURATION, 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '" class="menuBoxContentLink">' . constant(strtoupper($configuration_groups['cgKey'])) . '</a><br>';
    }

    $contents[] = array('text'  => $cfg_groups);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>

