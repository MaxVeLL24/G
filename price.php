<?php

/*
  $Id: allprods.php,v 1.7 2002/12/02

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  Copyright (c) 2002 HMCservices

  Released under the GNU General Public License
 */

include_once __DIR__ . '/includes/application_top.php';
include_once DIR_WS_LANGUAGES . $language . '/' . FILENAME_PRICE_HTML;

$breadcrumb->add(HEADING_TITLE);
$page_title = TITLE_PRICE .' '. HEAD_TITLE_TAG_DEFAULT;
$page_meta_description = TITLE_PRICE .' '. HEAD_DESC_TAG_DEFAULT;
$content = CONTENT_PRICE_HTML;

require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
require(DIR_WS_INCLUDES . 'application_bottom.php');