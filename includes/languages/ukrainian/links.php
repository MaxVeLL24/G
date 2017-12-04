<?php
/*
  $Id: links.php,v 1.00 2003/10/03 Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  define('NAVBAR_TITLE', 'Посилання');

if ($display_mode == 'links') {
     define('HEADING_TITLE', 'Посилання');
     define('TABLE_HEADING_LINKS_IMAGE', '');
     define('TABLE_HEADING_LINKS_TITLE', 'Назва');
     define('TABLE_HEADING_LINKS_URL', 'URL Адреса');
     define('TABLE_HEADING_LINKS_DESCRIPTION', 'Опис');
     define('TABLE_HEADING_LINKS_COUNT', 'Кліки');
     define('TEXT_NO_LINKS', 'В даному розділі немає жодного посилання.');
} elseif ($display_mode == 'categories') {
     define('HEADING_TITLE', 'Розділи');
     define('TEXT_NO_CATEGORIES', 'Немає жодного розділу.');
}

// VJ todo - move to common language file
  define('TEXT_DISPLAY_NUMBER_OF_LINKS', 'Показано <b>%d</b> - <b>%d</b> (всього <b>%d</b> посилань)');

  define('IMAGE_BUTTON_SUBMIT_LINK', 'Додати посилання');
?>
