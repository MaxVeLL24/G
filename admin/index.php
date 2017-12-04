<?php
/*
  $Id: index.php,v 1.2 2003/09/24 15:18:15 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  include_once __DIR__ . '/includes/application_top.php';

// BOF: KategorienAdmin / OLISWISS
  if ($login_groups_id != 1) {
    tep_redirect(tep_href_link(FILENAME_CATEGORIES, ''));
  }
// BOF: KategorienAdmin / OLISWISS

	    $template_id_select_query = tep_db_query("select template_id from " . TABLE_TEMPLATE . "  where template_name = '" . DEFAULT_TEMPLATE . "'");
$template_id_select =  tep_db_fetch_array($template_id_select_query);

  $cat = array(array('title' => BOX_HEADING_CONFIGURATION,
//Admin begin
                     'access' => tep_admin_check_boxes('configuration.php'),
//Admin end
                     'image' => 'configuration.gif',
                     'href' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1'),
                     'children' => array(array('title' => BOX_CONFIGURATION_MYSTORE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=1')),
                                         array('title' => BOX_CONFIGURATION_LOGGING, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=10')),
                                         array('title' => BOX_CONFIGURATION_CACHE, 'link' => tep_href_link(FILENAME_CONFIGURATION, 'selected_box=configuration&gID=11')))),
               array('title' => BOX_HEADING_MODULES,
//Admin begin
                     'access' => tep_admin_check_boxes('modules.php'),
//Admin end
                     'image' => 'modules.gif',
                     'href' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment'),
                     'children' => array(array('title' => BOX_MODULES_PAYMENT, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=payment')),
                                         array('title' => BOX_MODULES_SHIPPING, 'link' => tep_href_link(FILENAME_MODULES, 'selected_box=modules&set=shipping')))),
               array('title' => BOX_HEADING_CATALOG,
//Admin begin
                     'access' => tep_admin_check_boxes('catalog.php'),
//Admin end
                     'image' => 'catalog.gif',
                     'href' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog'),
                     'children' => array(array('title' => CATALOG_CONTENTS, 'link' => tep_href_link(FILENAME_CATEGORIES, 'selected_box=catalog')),
                                         array('title' => BOX_CATALOG_MANUFACTURERS, 'link' => tep_href_link(FILENAME_MANUFACTURERS, 'selected_box=catalog')))),
               array('title' => BOX_HEADING_LOCATION_AND_TAXES,
//Admin begin
                     'access' => tep_admin_check_boxes('taxes.php'),
//Admin end
                     'image' => 'location.gif',
                     'href' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes'),
                     'children' => array(array('title' => BOX_TAXES_COUNTRIES, 'link' => tep_href_link(FILENAME_COUNTRIES, 'selected_box=taxes')),
                                         array('title' => BOX_TAXES_GEO_ZONES, 'link' => tep_href_link(FILENAME_GEO_ZONES, 'selected_box=taxes')))),
               array('title' => BOX_HEADING_CUSTOMERS,
//Admin begin
                     'access' => tep_admin_check_boxes('customers.php'),
//Admin end
                     'image' => 'customers.gif',
                     'href' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers'),
                     'children' => array(array('title' => BOX_CUSTOMERS_CUSTOMERS, 'link' => tep_href_link(FILENAME_CUSTOMERS, 'selected_box=customers')),
                                         array('title' => BOX_CUSTOMERS_ORDERS, 'link' => tep_href_link(FILENAME_ORDERS, 'selected_box=customers')))),
               array('title' => BOX_HEADING_LOCALIZATION,
//Admin begin
                     'access' => tep_admin_check_boxes('localization.php'),
//Admin end
                     'image' => 'localization.gif',
                     'href' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization'),
                     'children' => array(array('title' => BOX_LOCALIZATION_CURRENCIES, 'link' => tep_href_link(FILENAME_CURRENCIES, 'selected_box=localization')),
                                         array('title' => BOX_LOCALIZATION_LANGUAGES, 'link' => tep_href_link(FILENAME_LANGUAGES, 'selected_box=localization')))),
               array('title' => BOX_HEADING_REPORTS,
//Admin begin
                     'access' => tep_admin_check_boxes('reports.php'),
//Admin end
                     'image' => 'reports.gif',
                     'href' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports'),
                     'children' => array(array('title' => REPORTS_PRODUCTS, 'link' => tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED, 'selected_box=reports')),
                                         array('title' => REPORTS_ORDERS, 'link' => tep_href_link(FILENAME_STATS_CUSTOMERS, 'selected_box=reports')))),


// added for support
/*
	array(
		'title' => BOX_SUPPORT_HEADING,
//Admin begin
		'access' => tep_admin_check_boxes('support.php'),
//Admin end
		'image' => 'helpdesk.gif',
		'href' => tep_href_link(FILENAME_SUPPORT_TICKETS, 'selected_box=support'),
		'children' => array(array(
				'title' => BOX_TICKET_STATUS,
				'link' => tep_href_link(FILENAME_SUPPORT_STATUS, 'selected_box=support')
			),
			array(
				'title' => BOX_TICKET_ADMINS,
				'link' => tep_href_link(FILENAME_SUPPORT_ADMIN, 'selected_box=support'))
			)
	),
*/
//end of support


// added for newsdesk
	array(
		'title' => BOX_HEADING_NEWSDESK,
//Admin begin
		'access' => tep_admin_check_boxes('newsdesk.php'),
//Admin end
		'image' => 'news.gif',
		'href' => tep_href_link(FILENAME_NEWSDESK, 'selected_box=newsdesk'),
		'children' => array(array(
				'title' => NEWSDESK_ARTICLES,
				'link' => tep_href_link(FILENAME_NEWSDESK, 'selected_box=newsdesk')
			),
			array(
				'title' => NEWSDESK_REVIEWS,
				'link' => tep_href_link(FILENAME_NEWSDESK_REVIEWS, 'selected_box=newsdesk'))
			)
	),
//end of newsdesk


// added for faqdesk
	array(
		'title' => BOX_HEADING_FAQDESK,
//Admin begin
		'access' => tep_admin_check_boxes('faqdesk.php'),
//Admin end
		'image' => 'faq.gif',
		'href' => tep_href_link(FILENAME_FAQDESK, 'selected_box=faqdesk'),
		'children' => array(array(
				'title' => FAQDESK_ARTICLES,
				'link' => tep_href_link(FILENAME_FAQDESK, 'selected_box=faqdesk')
			),
			array(
				'title' => FAQDESK_REVIEWS,
				'link' => tep_href_link(FILENAME_FAQDESK_REVIEWS, 'selected_box=faqdesk'))
			)
	),
//end of faqdesk


//Admin begin
       	       array('title' => BOX_HEADING_MY_ACCOUNT,
                     'access' => 'true',
                     'image' => 'my_account.gif',
                     'href' => tep_href_link(FILENAME_ADMIN_ACCOUNT),
                     'children' => array(array('title' => HEADER_TITLE_ACCOUNT, 'link' => tep_href_link(FILENAME_ADMIN_ACCOUNT),
                                               'access' => 'true'),
                                         array('title' => HEADER_TITLE_LOGOFF, 'link' => tep_href_link(FILENAME_LOGOFF),
                                               'access' => 'true'))),
               array('title' => BOX_HEADING_ADMINISTRATOR,
                     'access' => tep_admin_check_boxes('administrator.php'),
                     'image' => 'administrator.gif',
                     'href' => tep_href_link(tep_selected_file('administrator.php'), 'selected_box=administrator'),
                     'children' => array(array('title' => BOX_ADMINISTRATOR_MEMBER, 'link' => tep_href_link(FILENAME_ADMIN_MEMBERS, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_MEMBERS, 'sub_boxes')),
                                         array('title' => BOX_ADMINISTRATOR_BOXES, 'link' => tep_href_link(FILENAME_ADMIN_FILES, 'selected_box=administrator'),
                                               'access' => tep_admin_check_boxes(FILENAME_ADMIN_FILES, 'sub_boxes')))),



               array('title' => BOX_HEADING_DESIGN_CONTROLS,
//Admin begin
                     'access' => tep_admin_check_boxes('design_controls.php'),
//Admin end
                     'image' => 'design_controls.gif',
                     'href' => tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $template_id_select[template_id] . '&selected_box=design_controls'),
                     'children' => array(array('title' => BOX_HEADING_TEMPLATE_CONFIGURATION, 'link' => tep_href_link(FILENAME_TEMPLATE_CONFIGURATION, 'cID=' . $template_id_select[template_id] . '&selected_box=design_controls')),
                                         array('title' => BOX_HEADING_BOXES, 'link' => tep_href_link(FILENAME_INFOBOX_CONFIGURATION, 'gID=' . $template_id_select[template_id] . '&selected_box=design_controls')))),


               array('title' => BOX_HEADING_ARTICLES,
//Admin begin
                     'access' => tep_admin_check_boxes('articles.php'),
//Admin end
                     'image' => 'articles.gif',
                     'href' => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles'),
                     'children' => array(array('title' => BOX_TOPICS_ARTICLES, 'link' => tep_href_link(FILENAME_ARTICLES, 'selected_box=articles')),
                                         array('title' => BOX_ARTICLES_CONFIG, 'link' => tep_href_link(FILENAME_ARTICLES_CONFIG, 'selected_box=articles')))),

               array('title' => BOX_HEADING_INFORMATION,
//Admin begin
                     'access' => tep_admin_check_boxes('information.php'),
//Admin end
                     'image' => 'info.gif',
                     'href' => tep_href_link(FILENAME_INFORMATION, 'selected_box=information'),
                     'children' => array(array('title' => BOX_INFORMATION, 'link' => tep_href_link(FILENAME_INFORMATION, 'selected_box=information')))),

               array('title' => BOX_INDEX_GIFTVOUCHERS,
//Admin begin
                     'access' => tep_admin_check_boxes('gv_admin.php'),
//Admin end
                     'image' => 'gift.gif',
                     'href' => tep_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_admin'),
                     'children' => array(array('title' => BOX_COUPON_ADMIN, 'link' => tep_href_link(FILENAME_COUPON_ADMIN, 'selected_box=gv_admin')),
                                         array('title' => BOX_GV_ADMIN_QUEUE, 'link' => tep_href_link(FILENAME_GV_QUEUE, 'selected_box=gv_admin')))),

               array('title' => BOX_HEADING_POLLS,
//Admin begin
                     'access' => tep_admin_check_boxes('polls.php'),
//Admin end
                     'image' => 'oprosu.gif',
                     'href' => tep_href_link(FILENAME_POLLS, 'selected_box=polls'),
                     'children' => array(array('title' => BOX_POLLS_CONFIG, 'link' => tep_href_link(FILENAME_POLLS, 'action=config&selected_box=polls')),
                                         array('title' => BOX_POLLS_POLLS, 'link' => tep_href_link(FILENAME_POLLS, 'selected_box=polls')))),

               array('title' => BOX_HEADING_LINKS,
//Admin begin
                     'access' => tep_admin_check_boxes('links.php'),
//Admin end
                     'image' => 'links.gif',
                     'href' => tep_href_link(FILENAME_LINKS, 'selected_box=links'),
                     'children' => array(array('title' => BOX_LINKS_LINKS, 'link' => tep_href_link(FILENAME_LINKS, 'selected_box=links')),
                                         array('title' => BOX_LINKS_LINK_CATEGORIES, 'link' => tep_href_link(FILENAME_LINK_CATEGORIES, 'selected_box=links')))),

//               array('title' => BOX_KEYWORD_SHOW,
//Admin begin
//                     'access' => tep_admin_check_boxes('keyword_show.php'),
//Admin end
//                     'image' => 'reports.gif',
//                     'href' => tep_href_link(FILENAME_KEYWORD_SHOW_CONFIG, 'selected_box=keyword_show'),
//                     'children' => array(array('title' => TEXT_KEYWORD_SHOW_CONFIG, 'link' => tep_href_link(FILENAME_KEYWORD_SHOW_CONFIG, 'selected_box=keyword_show')),
//                                         array('title' => BOX_KEYWORD_SHOW, 'link' => tep_href_link(FILENAME_KEYWORD_SHOW, 'selected_box=keyword_show')))),



//Admin end
               array('title' => BOX_HEADING_TOOLS,
//Admin begin
                     'access' => tep_admin_check_boxes('tools.php'),
//Admin end
                     'image' => 'tools.gif',
                     'href' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools'),
                     'children' => array(array('title' => TOOLS_BACKUP, 'link' => tep_href_link(FILENAME_BACKUP, 'selected_box=tools')),
                                         array('title' => TOOLS_BANNERS, 'link' => tep_href_link(FILENAME_BANNER_MANAGER, 'selected_box=tools')),
                                         array('title' => BOX_TOOLS_RECOVER_CART, 'link' => tep_href_link(FILENAME_RECOVER_CART_SALES, 'selected_box=tools')))));

  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>

<?php if (MENU_DHTML == 'true') echo '<link rel="stylesheet" type="text/css" href="includes/menu.css">'; ?>
<style type="text/css"><!--
a { color: #000000; text-decoration:none; }
a:hover { color:#000000; text-decoration:underline; }
a.text:link, a.text:visited { color: #000000; text-decoration: none; }
a:text:hover { color: #000000; text-decoration: underline; }
a.main:link, a.main:visited { color: #000000; text-decoration: none;font-size:17px; }
A.main:hover { color: #000000; text-decoration: underline; }
a.sub:link, a.sub:visited { color: #6a6a6a; text-decoration: none; }
A.sub:hover { color: #000000; text-decoration: underline; }
a:link.headerLink { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; font-weight: bold; text-decoration: none; }
a:visited.headerLink { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; font-weight: bold; text-decoration: none }
a:active.headerLink { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; font-weight: bold; text-decoration: none; }
a:hover.headerLink { font-family: Verdana, Arial, sans-serif; font-size: 10px; color: #000000; font-weight: bold; text-decoration: underline; }

.heading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 22px;  line-height: 1.5; color: #6a6a6a; }
.main { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 17px;  line-height: 1.5; color: #000000; }
.sub { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; line-height: 1.5; color: #dddddd; }
.text { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; font-weight: bold; line-height: 1.0; color: #000000; }
.menuBoxHeading { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #ffffff; font-weight: bold; background-color: #FF5353; }
.infoBox { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10px; color: #080381; background-color: #ffffff; border-color: #967676;  }
.smallText { font-family: Verdana, Arial, sans-serif; font-size: 10px; }
//--></style>

    <link type="text/css" href="../includes/javascript/ui/css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
		<script type="text/javascript" src="../includes/javascript/lib/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="includes/javascript/jquery-ui-1.7.2.custom.min.js"></script>
    <script type="text/javascript">
			$(function(){
				$('#tabs').tabs({ fx: { opacity: 'toggle', duration: 'fast' } });
			});
		</script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php
        	// #CP - point logos to come from selected template's images directory
		    $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  			$template = tep_db_fetch_array($template_query);
  			$CURR_TEMPLATE = $template['configuration_value'] . '/';


        ?>
<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0" align="center" valign="top">
  <tr valign="top">
    <td><table border="0" width="100%" height="440" cellspacing="0" cellpadding="0" align="center" valign="top">
      <tr valign="top">
        <td><table border="0" width="100%" height="440" cellspacing="0" cellpadding="0">
<tr>
<td>
<div>
  <div style="float:left;padding:15px 0 0 15px;"><a href="/admin"><img src="images/logo.jpg" / border="0"></a></div>
  <div style="float:left;padding: 40px 6px 0 6px;"><span><?php echo INDEX_HOLA; ?>, <b><?php echo $_SESSION['login_first_name']; ?></b>.</span>
  <a href="<?php echo tep_href_link(FILENAME_LOGOFF, '', 'NONSSL');?>" ><?php echo HEADER_TITLE_LOGOFF; ?></a>
  </div>
	<div style="float:right;">

     <?php
	  $heading = array();
	  $contents = array();
	  echo '<div style="line-height:2px;">';
	  $orders_contents = '';
	  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
	  while ($orders_status = tep_db_fetch_array($orders_status_query)) {
	    $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
	    $orders_pending = tep_db_fetch_array($orders_pending_query);
	//Admin begin
	//    $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . '<br>';
	    if (tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes') == true) {
	      $orders_contents .= '<a href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a>: ' . $orders_pending['count'] . ' | ';
	    } else {
	      $orders_contents .= '' . $orders_status['orders_status_name'] . ': ' . $orders_pending['count'] . '<br>';
	    }
	//Admin end
	  }

	  $orders_contents = substr($orders_contents, 0, -2);

	  $heading = array();
	  $contents = array();

	  $heading[] = array('params' => 'class="menuBoxHeading"',
	                     'text'  => BOX_TITLE_ORDERS);

	  $contents[] = array('params' => 'class="infoBox"',
	                      'text'  => $orders_contents);

	  $box = new box;
	  echo $box->infoBox($heading, $contents);

	  echo '</div><div style="padding-top:10px; line-height:2px;">';

	  $customers_query = tep_db_query("select count(*) as count from " . TABLE_CUSTOMERS);
	  $customers = tep_db_fetch_array($customers_query);
	  $products_query = tep_db_query("select count(*) as count from " . TABLE_PRODUCTS . " where products_status = '1'");
	  $products = tep_db_fetch_array($products_query);
//	  $reviews_query = tep_db_query("select count(*) as count from " . TABLE_REVIEWS);
//	  $reviews = tep_db_fetch_array($reviews_query);

	  $heading = array();
	  $contents = array();

	  $heading[] = array('params' => 'class="menuBoxHeading"',
	                     'text'  => BOX_TITLE_STATISTICS);

	  $contents[] = array('params' => 'class="infoBox"',
	                      'text'  => BOX_ENTRY_CUSTOMERS . ' ' . $customers['count'] . ' | ' .
	                                 BOX_ENTRY_PRODUCTS . ' ' . $products['count']);

	  $box = new box;
	  echo $box->infoBox($heading, $contents);
	  echo '</div>';
	?>
  </div>
  <div style="clear:both;"></div>
</div>
<script language="javascript" src="includes/menu.js"></script>

<?php if (MENU_DHTML == 'true') require(DIR_WS_INCLUDES . 'header_navigation.php'); ?>
</td>
</tr>

          <tr bgcolor="#ffffff">
            <td colspan="2"><table border="0" width="100%" height="390" cellspacing="0" cellpadding="2">
                  <tr>
                    <td colspan="4" style="background:#E9F9FF;"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                      <tr>
                        <td class="heading"><?php //echo HEADING_TITLE; ?></td>
                <td class="smallText" align="left">
                  <div style="float:left;">
<?php

    echo tep_draw_form('goto', FILENAME_CATEGORIES, '', 'get');
    echo GOTO2 . ': ' . tep_draw_pull_down_menu('cPath', tep_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '</form>&nbsp;&nbsp;';
?>
                  </div>
                  <div style="float:left;">
<?php
    echo tep_draw_form('search', FILENAME_CATEGORIES, '', 'get');
    echo '<div style="float:left;">'. SEARCH2 .': ' . tep_draw_input_field('search').'</div>';
    echo '<div style="float:left;padding:0 3px;">'.tep_image_submit('button_search.gif', IMAGE_SEARCH).'</div>';
    echo '<div style="float:left;">'.tep_draw_checkbox_field('search_model_key').'</div>';
    echo '<div style="float:left;line-height:0.9;padding:0 3px;">'. CODE1 .'<br>'. CODE2 .'</div>';
    echo '<div style="clear:both;"></div>';
    echo '</form>';
?>
                  </div>
                  <div style="clear:both;"></div>
                </td>
                        <td align="right">
												  <?php echo tep_draw_form('languages', 'index.php', '', 'get'); ?>
													  <?php echo tep_draw_pull_down_menu('language', $languages_array, $languages_selected, 'onChange="this.form.submit();"'); ?>
												  </form>
												</td>
                      </tr>
                    </table></td>
			  </tr>
				<tr>
				<td align="left" width="100%" valign="top">

<div id="tabs">

			<ul>
				<li><a href="#orders"><?php echo TEXT_SUMMARY_ORDERS; ?></a></li>
			  <li><a href="#stat"><?php echo TEXT_SUMMARY_STAT; ?></a></li>
				<li><a href="#customers"><?php echo TEXT_SUMMARY_CUSTOMERS; ?></a></li>
				<li><a href="#products"><?php echo TEXT_SUMMARY_PRODUCTS; ?></a></li>
			</ul>
<?php
include(DIR_WS_CLASSES . 'ofc-library/open_flash_chart_object.php');
?>
<div id="orders">
<table border="0" width="100%">
<?php include(DIR_WS_MODULES . 'summary/orders.php'); ?>
</table>
</div>

<div id="stat">
<table border="0" width="100%">
<?php include(DIR_WS_MODULES . 'summary/statistics.php'); ?>
</table>
</div>

<div id="customers">
<table border="0" width="100%">
<?php include(DIR_WS_MODULES . 'summary/customers.php'); ?>
</table>
</div>

<div id="products">
<table border="0" width="100%">
<?php include(DIR_WS_MODULES . 'summary/products.php'); ?>
</table>
</div>

</div>

				</td>
				</tr>
              <tr valign="top">

                <td width="100%" align="center"><table border="0" width="100%" height="390" cellspacing="0" cellpadding="2">
<?php
  $col = 2;
  $counter = 0;
  for ($i = 0, $n = sizeof($cat); $i < $n; $i++) {
    $counter++;
    if ($counter < $col) {
      echo '                  <tr>' . "\n";
    }

    echo '                    <td style="width:25%"><table border="0" cellspacing="0" cellpadding="2" >' . "\n" .
         '                      <tr>' . "\n" .
         '                        <td class="main"><a href="' . $cat[$i]['href'] . '">' . tep_image(DIR_WS_IMAGES . 'categories/' . $cat[$i]['image'], $cat[$i]['title'], '64', '64') . '</a></td>' . "\n" .
         '                        <td><table border="0" cellspacing="0" cellpadding="2">' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="main"><a href="' . $cat[$i]['href'] . '" class="main">' . $cat[$i]['title'] . '</a></td>' . "\n" .
         '                          </tr>' . "\n" .
         '                          <tr>' . "\n" .
         '                            <td class="sub">';

    $children = '';
    for ($j = 0, $k = sizeof($cat[$i]['children']); $j < $k; $j++) {
      $children .= '<a href="' . $cat[$i]['children'][$j]['link'] . '" class="sub">' . $cat[$i]['children'][$j]['title'] . '</a>, ';
    }
    echo substr($children, 0, -2);

    echo '</td> ' . "\n" .
         '                          </tr>' . "\n" .
         '                        </table></td>' . "\n" .
         '                      </tr>' . "\n" .
         '                    </table></td>' . "\n";

    if ($counter >= 4) {
      echo '                  </tr>' . "\n";
      $counter = 0;
    }
  }
?>
                </table></td>
              </tr>

            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php require(DIR_WS_INCLUDES . 'footer.php'); ?></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>

</html>
