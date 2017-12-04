<?php
/*
  $Id: information.php,v 1.20 2003/02/07 21:46:49 dgw_ Exp $

  Author: Xander Witteveen (xanderwitteveen@hotmail.com)

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

?>
<!-- information //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('align' => 'left',
                               'text'  => BOX_HEADING_INFORMATION,
                               'link'  => tep_href_link(FILENAME_INFORMATION, 'selected_box=information')
                              );
  if ($selected_box == 'information' || $menu_dhtml == true) {
    if (ARTICLES_MODULE_ENABLED == 'true') {
      $articles_link = '<a href="' . tep_href_link(FILENAME_ARTICLES, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_TOPICS_ARTICLES . '</a>';
      $product_articles = '<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_ARTICLES_XSELL . '</a>';
    }
    if (FAQ_MODULE_ENABLED == 'true') {
      $faq = tep_admin_files_boxes(FILENAME_FAQDESK, BOX_FAQDESK);
    }



    $contents[] = array('text'  => '
                                    <a href="' . tep_href_link('telephone.php', '', 'NONSSL') . '" class="menuBoxContentLink">'.CHANGE_TELEPHONE.'</a>
									<a href="' . tep_href_link('define_mainpage.php', '', 'NONSSL') . '" class="menuBoxContentLink">'.CHANGE_MAINPAGE.'</a>
									<a href="' . tep_href_link('slider.php', '', 'NONSSL') . '" class="menuBoxContentLink">'.CHANGE_SLIDER.'</a>
									<a href="' . tep_href_link(FILENAME_INFORMATION) . '" class="menuBoxContentLink">' . BOX_INFORMATION . '</a>'.
                                   '<a href="' . tep_href_link('newsdesk.php', '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_NEWSDESK . '</a>' .
                                    $faq.
                                    $articles_link.$product_articles
                                   );
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- information_smend //-->
