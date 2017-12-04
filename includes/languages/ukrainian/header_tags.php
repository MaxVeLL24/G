<?php
// /catalog/includes/languages/english/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.0
// Add META TAGS and Modify TITLE
//
// DEFINITIONS FOR /includes/languages/english/header_tags.php

// Define your email address to appear on all pages
define('HEAD_REPLY_TAG_ALL','');

// For all pages not defined or left blank, and for products not defined
// These are included unless you set the toggle switch in each section below to OFF ( '0' )
// The HEAD_TITLE_TAG_ALL is included BEFORE the specific one for the page
// The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page
// The HEAD_KEY_TAG_ALL is included BEFORE the specific one for the page
define('HEAD_TITLE_TAG_ALL','');
define('HEAD_DESC_TAG_ALL','Інтернет-магазин іграшок');
define('HEAD_KEY_TAG_ALL','Інтернет-магазин іграшок');

// DEFINE TAGS FOR INDIVIDUAL PAGES

// index.php
define('HTTA_DEFAULT_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_DEFAULT_ON','1'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_DEFAULT_ON','0'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_DEFAULT','Інтернет-магазин іграшок, купити дитячі іграшки в Києві, Україні | Gigimot (Гігімот)');
define('HEAD_DESC_TAG_DEFAULT','Великий вибір іграшок для вашої дитини. ✓ Акції $ Знижки ✈ Доставка в будь-який куточок Україини. Вибирайте швидко та легко в магазині ☆ Gigimot.сom.ua ☆');
define('HEAD_KEY_TAG_DEFAULT','Інтернет-магазин іграшок');

// product_info.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_INFO_ON','1');
define('HTKA_PRODUCT_INFO_ON','1');
define('HTDA_PRODUCT_INFO_ON','0');
define('HEAD_TITLE_TAG_PRODUCT_INFO','%s купити, ціна, відгуки, продаж Київ, Україна | Інтернет-магазин Gigimot.com.ua');
define('HEAD_DESC_TAG_PRODUCT_INFO','Купити %s. $ Краща ціна ✈ Оперативна доставка ☑ Гарантія якості в інтернет-магазині ☆ Gigimot.com.ua ☆');
define('HEAD_KEY_TAG_PRODUCT_INFO','Інтернет-магазин іграшок');

// products_new.php - whats_new
define('HTTA_WHATS_NEW_ON','1');
define('HTKA_WHATS_NEW_ON','1');
define('HTDA_WHATS_NEW_ON','1');
define('HEAD_TITLE_TAG_WHATS_NEW','Новинки');
define('HEAD_DESC_TAG_WHATS_NEW','новинки');
define('HEAD_KEY_TAG_WHATS_NEW','новинки');

// specials.php
// If HEAD_KEY_TAG_SPECIALS is left blank, it will build the keywords from the products_names of all products on special
define('HTTA_SPECIALS_ON','1');
define('HTKA_SPECIALS_ON','1');
define('HTDA_SPECIALS_ON','1');
define('HEAD_TITLE_TAG_SPECIALS','Знижки');
define('HEAD_DESC_TAG_SPECIALS','Знижки');
define('HEAD_KEY_TAG_SPECIALS','Знижки');

// featured.php

define('HTTA_FEATURED_ON', '1');
define('HTKA_FEATURED_ON', '1');
define('HTDA_FEATURED_ON', '1');
define('HEAD_TITLE_TAG_FEATURED', 'Ми рекомендуємо');
define('HEAD_DESC_TAG_FEATURED', 'Ми рекомендуємо');
define('HEAD_KEY_TAG_FEATURED', 'Ми рекомендуємо');

// product_reviews_info.php and product_reviews.php - if left blank in products_description table these values will be used
define('HTTA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTKA_PRODUCT_REVIEWS_INFO_ON','1');
define('HTDA_PRODUCT_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_PRODUCT_REVIEWS_INFO','Відгук про товар');
define('HEAD_DESC_TAG_PRODUCT_REVIEWS_INFO','Відгуки');
define('HEAD_KEY_TAG_PRODUCT_REVIEWS_INFO','Відгуки');


// Управление тэгами статей

   // For all pages not defined or left blank, and for articles not defined
   // These are included unless you set the toggle switch in each section below to OFF ( '0' )
   // The HEAD_TITLE_TAG_ALL is included BEFORE the specific one for the page
   // The HEAD_DESC_TAG_ALL is included AFTER the specific one for the page
   // The HEAD_KEY_TAG_ALL is included AFTER the specific one for the page
   define('HEAD_TITLE_ARTICLE_TAG_ALL','');
   define('HEAD_DESC_ARTICLE_TAG_ALL','');
   define('HEAD_KEY_ARTICLE_TAG_ALL','');

/* End of Indented Section */

// DEFINE TAGS FOR INDIVIDUAL PAGES

// articles.php
define('HTTA_ARTICLES_ON','1'); // Include HEAD_TITLE_TAG_ALL in Title
define('HTKA_ARTICLES_ON','0'); // Include HEAD_KEY_TAG_ALL in Keywords
define('HTDA_ARTICLES_ON','1'); // Include HEAD_DESC_TAG_ALL in Description
define('HEAD_TITLE_TAG_ARTICLES','Цікаві статті, акції');
define('HEAD_DESC_TAG_ARTICLES','Статті');
define('HEAD_KEY_TAG_ARTICLES','');

// article_info.php - if left blank in articles_description table these values will be used
define('HTTA_ARTICLE_INFO_ON','0');
define('HTKA_ARTICLE_INFO_ON','0');
define('HTDA_ARTICLE_INFO_ON','0');
define('HEAD_TITLE_TAG_ARTICLE_INFO','Статті');
define('HEAD_DESC_TAG_ARTICLE_INFO','Статті');
define('HEAD_KEY_TAG_ARTICLE_INFO','Статті');

// articles_new.php - new articles
// If HEAD_KEY_TAG_ARTICLES_NEW is left blank, it will build the keywords from the articles_names of all new articles
define('HTTA_ARTICLES_NEW_ON','1');
define('HTKA_ARTICLES_NEW_ON','1');
define('HTDA_ARTICLES_NEW_ON','1');
define('HEAD_TITLE_TAG_ARTICLES_NEW','Нові статті');
define('HEAD_DESC_TAG_ARTICLES_NEW','Нові статті');
define('HEAD_KEY_TAG_ARTICLES_NEW','Нові статті');

// article_reviews_info.php and article_reviews.php - if left blank in articles_description table these values will be used
define('HTTA_ARTICLE_REVIEWS_INFO_ON','1');
define('HTKA_ARTICLE_REVIEWS_INFO_ON','1');
define('HTDA_ARTICLE_REVIEWS_INFO_ON','1');
define('HEAD_TITLE_TAG_ARTICLE_REVIEWS_INFO','Відгуки статті');
define('HEAD_DESC_TAG_ARTICLE_REVIEWS_INFO','Відгуки статті');
define('HEAD_KEY_TAG_ARTICLE_REVIEWS_INFO','Відгуки статті');

define('HEAD_TITLE_TAG_ALL_PRODUCTS', 'Усі товари');
define('HEAD_DESC_TAG_ALL_PRODUCTS', 'Усі товари');
define('HEAD_KEY_TAG_ALL_PRODUCTS', 'Усі товари');

define('ACCOUNT_HISTORY_INFO_META_TITLE', 'Історія замовлення #%d');
define('SHOPPING_CART_META_TITLE', 'Кошик');
define('CHECKOUT_META_TITLE', 'оформлення замовлення');
define('CHECKOUT_SUCCESS_META_TITLE', 'Ваше замовлення успішно оформлене!');
define('ACCOUNT_META_TITLE', 'Ваш кабінет');
define('ACCOUNT_HISTORY_META_TITLE', 'Мої замовлення');
define('ACCOUNT_EDIT_META_TITLE', 'Перегляд і редагування данних');
define('ADDRESS_BOOK_META_TITLE', 'Адресна книга');
define('ACCOUNT_PASSWORD_META_TITLE', 'Змінити пароль');