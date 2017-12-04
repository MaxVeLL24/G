<?php
/*
  $Id: pollbooth.php,v 1.5 2003/04/06 21:45:33 wilt Exp $

  The Exchange Project - Community Made Shopping!
  http://www.theexchangeproject.org

  Copyright (c) 2000,2001 The Exchange Project

  Released under the GNU General Public License
*/
if (!isset($_GET['op'])) {
$_GET['Op'] = "list";
}
if ($_GET['op'] == 'results') {
     define('TOP_BAR_TITLE', 'Результати опитування');
     define('HEADING_TITLE', 'Результати опитування');
     define('SUB_BAR_TITLE', 'Результати опитування');
}
if ($_GET['op'] == 'list') {
     define('TOP_BAR_TITLE', 'Результати опитування');
     define('HEADING_TITLE', 'Результати опитування');
     define('SUB_BAR_TITLE', 'Інші опитування');
}
if ($_GET['op'] == 'vote') {
     define('TOP_BAR_TITLE', 'Результати опитування');
     define('HEADING_TITLE', 'Результати опитування');
     define('SUB_BAR_TITLE', 'Проголосуйте');
}
if ($_GET['op'] == 'comment') {
     define('HEADING_TITLE', 'Відгуки');
}
  define('_WARNING', 'Попередження:');
  define('_ALREADY_VOTED', 'Ви вже голосували.');
  define('_NO_VOTE_SELECTED', 'Ви не обрали відповідь для голосування.');
  define('_TOTALVOTES', 'Всього голосів');
  define('_OTHERPOLLS', 'Інші опитування');
  define('NAVBAR_TITLE_1', 'Результати опитування');
  define('_POLLRESULTS', 'Результати опитування');
  define('_VOTING', 'Голосувати');
  define('_RESULTS', 'Результати');
  define('_VOTES', 'Голосів');
  define('_VOTE', 'Голосувати');
  define('_COMMENT', 'Відгук');
  define('_COMMENTS', 'Відгуки');
  define('_COMMENTS_POSTED', 'Відгуки додані');
  define('_COMMENTS_BY', 'Відгук додав');
  define('_COMMENTS_ON', '');
  define('_YOURNAME', 'Ваше ім\'я:');
  define('_OTZYV', 'Відгук:');
  define('TEXT_CONTINUE', 'Додати відгук');
  define('_PUBLIC', 'Відкрите голосування');
  define('_PRIVATE', 'Закрите голосування');
  define('_POLLOPEN', 'Опитування відкрите');
  define('_POLLCLOSED', 'Опитування для зареєстрованих користувачів');
  define('_POLLPRIVATE', 'Опитування для зареєстрованих користувачів, увійдіть в магазин, опитування тільки для зареєстрованих користувачів');
  define('_ADD_COMMENTS', 'Додати відгук');
  define('TEXT_DISPLAY_NUMBER_OF_COMMENTS', 'Показано <b>%d</b> - <b>%d</b> (всього <b>%d</b>відгуків)');
?>
