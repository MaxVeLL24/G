<?php
/*
  $Id: article_listing.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$listing_split = new splitPageResults($listing_sql, MAX_ARTICLES_PER_PAGE);

?>
<?php
  if ($listing_split->number_of_rows > 0) {
    $articles_listing_query = tep_db_query($listing_split->sql_query);

    while ($articles_listing = tep_db_fetch_array($articles_listing_query)) {
?>
      <tr>
        <td><table id="articles_listing" width="100%" cellspacing="0" cellpadding="0"><br>
          <tr>
             <td  valign="top" class="articles" width="100%">
<?php
  echo '<h1><a href="' . tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $articles_listing['articles_id']) . '">' . $articles_listing['articles_name'] . '</a> </h1>';
  if (DISPLAY_AUTHOR_ARTICLE_LISTING == 'true' && tep_not_null($articles_listing['authors_name'])) {
   echo TEXT_BY . ' ' . '<h1><a href="' . tep_href_link(FILENAME_ARTICLES, 'authors_id=' . $articles_listing['authors_id']) . '"> ' . $articles_listing['authors_name'] . '</a></h1>';
  }
?>
<div class="date"><?php echo date('d.m.Y',strtotime($articles_listing['articles_date_added'])); ?></div>
<div class="newsdesk_article_shorttext">
    <?php if ($articles_listing['articles_head_desc_tag']): ?>
      <?php echo $articles_listing['articles_head_desc_tag']; ?>
    <?php else: ?>
      <?php echo truncateHtml(strip_tags($articles_listing['articles_description'],'<p>'),600); ?>
    <?php endif ?>
</div>
  </td>
  </tr>
  </table>
  </td>
</tr >

<?php } ?>
<?php

  }
?>

<?php
  if (($listing_split->number_of_rows > 0) && ((ARTICLE_PREV_NEXT_BAR_LOCATION == 'bottom') || (ARTICLE_PREV_NEXT_BAR_LOCATION == 'both'))) {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
           <tr>
            <td style="padding-left:2px" class="smallText">Всего статей: <b><?php echo $listing_split->number_of_rows; ?></b></td>
            <td align="right" class="smallText countPages "><?php echo TEXT_RESULT_PAGE . ' ' . $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
