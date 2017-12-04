<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
<?php 
// Set number of columns in listing
define ('NR_COLUMNS', 2);?>
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr> 
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
          <tr>
            <td class="pageHeading"><?php echo ARTICLE_SEARCH_TITLE; ?></td>
            <td align="right"><?php echo tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/content/specials.gif', ARTICLE_SEARCH_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td> 
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
$header_text = NAVBAR_TITLE_2;
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td>
<?php
  if ($akeywords == ""){
?>  
	  <tr>
	    <td class="main"><?php echo ERROR_INPUT; ?></td>
	  </tr>
<?php
    } else {		 
	
  if (isset($_GET['description'])) {
    $search_query = tep_db_query("select ad.articles_name, a.articles_id, ad.articles_description from " . TABLE_ARTICLES_DESCRIPTION . " ad inner join " . TABLE_ARTICLES . " a on ad.articles_id = a.articles_id where a.articles_status = '1' and ad.language_id = '" . (int)$languages_id . "' and (ad.articles_name like '%" . $akeywords . "%' or ad.articles_description like '%" . $akeywords . "%' or ad.articles_head_desc_tag like '%" . $akeywords . "%' or ad.articles_head_keywords_tag like '%" . $akeywords . "%' or ad.articles_head_title_tag like '%" . $akeywords . "%') order by ad.articles_name ASC");
  }  else {
    $search_query = tep_db_query("select ad.articles_name, a.articles_id, ad.articles_description from " . TABLE_ARTICLES_DESCRIPTION . " ad inner join " . TABLE_ARTICLES . " a on ad.articles_id = a.articles_id where a.articles_status='1' and ad.language_id = '" . (int)$languages_id . "' and (ad.articles_name like '%" . $akeywords . "%' or ad.articles_head_desc_tag like '%" . $akeywords . "%' or ad.articles_head_keywords_tag like '%" . $akeywords . "%' or ad.articles_head_title_tag like '%" . $akeywords . "%') order by ad.articles_name ASC");
  }    
    $count=0;
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
				  <tr>
	                <td valign="middle" align="center" width="33%"class="productListing-heading"><?php echo TEXT_ARTICLE_NAME; ?></td>
	                <td valign="middle" align="center" class="productListing-heading"><?php echo TEXT_ARTICLE_EXCERPT; ?></td>
	                <td valign="middle" align="center" class="productListing-heading"><?php echo TEXT_ARTICLE_LINK; ?></td>
	              </tr>
      <tr>
        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>

<?php		
    while($results = tep_db_fetch_array($search_query)){
	  $article_ex = substr($results['articles_description'], 0, 300);
	  
?>
	               <tr>
	                 <td valign="middle" align="center" valign="top" width="33%"class="main"><a href="<?php echo FILENAME_ARTICLE_INFO; ?>?articles_id=<?php echo $results['articles_id'] ?>"><?php echo $results['articles_name'] ?></a></td>
	                 <td valign="middle" align="center" valign="top" class="main"><?php echo $article_ex; ?> ...</td>
	                 <td valign="middle" align="center" valign="top" class="main"><a href="<?php echo FILENAME_ARTICLE_INFO; ?>?articles_id=<?php echo $results['articles_id'] ?>">Перейти к данной статье</a></td>
		           </tr>

		  
<?php
    $count++;
	} 
	if ($count == 0){
?>	

      <tr>
        <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
				   <tr>
		            <td class="main" colspan="3" align="center"><?php echo TEXT_NO_ARTICLES ?></td>
		           </tr>
<?php	
	}  
?>
                 </table></td>
			   </tr>
		    </table></td>
		</tr>			 		
<?php
  }
?>
<!-- body_text_smend //-->
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
   </table>
