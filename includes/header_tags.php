<?php

// /catalog/includes/header_tags.php
// WebMakers.com Added: Header Tags Generator v2.0
// Add META TAGS and Modify TITLE
//
// NOTE: Globally replace all fields in products table with current product name just to get things started:
// In phpMyAdmin use: UPDATE products_description set PRODUCTS_HEAD_TITLE_TAG = PRODUCTS_NAME
//

// Define specific settings per page:  

switch (true) {
    case (strstr($_SERVER['PHP_SELF'], FILENAME_DEFAULT) or strstr($PHP_SELF, FILENAME_DEFAULT) ):
        $the_category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int) $current_category_id . "' and cd.categories_id = '" . (int) $current_category_id . "' and cd.language_id = '" . (int) $languages_id . "'");
        $the_category = tep_db_fetch_array($the_category_query);

        $metaCategoryArray = explode("_", $cPath);
        if (strpos($cPath, '_')) {
            $metaCategoryArray = array_reverse($metaCategoryArray);
        }
        $metaCategory = $metaCategoryArray[0];

        $category_query = tep_db_query("select categories_meta_title, categories_meta_description, categories_meta_keywords from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $metaCategory . "' and language_id = '" . (int) $languages_id . "'");

        $metaData = tep_db_fetch_array($category_query);

        if ($_SERVER['REQUEST_URI'] == '/') {
            $page_meta_description = HEAD_DESC_TAG_DEFAULT;
        } else {
            if (empty($metaData['categories_meta_description'])) {
                $page_meta_description = HEAD_DESC_TAG_DEFAULT;
            } else {
                if (isset($cPath) && tep_not_null($cPath)) {
                    $page_meta_description = $metaData['categories_meta_description'];
                } else {
                    $page_meta_description = HEAD_DESC_TAG_DEFAULT;
                }
            }
        }

        if (empty($metaData['categories_meta_keywords'])) {
            $page_meta_keywords = HEAD_KEY_TAG_DEFAULT;
        } else {
            if (isset($cPath) && tep_not_null($cPath)) {
                $page_meta_keywords = $metaData['categories_meta_keywords'] . ' ' . HEAD_KEY_TAG_DEFAULT;
            } else {
                $page_meta_keywords = HEAD_KEY_TAG_DEFAULT;
            }
        }


        if (empty($the_category['categories_name'])) {
            $page_title = HEAD_TITLE_TAG_ALL . '' . HEAD_TITLE_TAG_DEFAULT . $the_category['categories_name'];
        } else {
            if (HTTA_DEFAULT_ON == '1' and empty($metaData['categories_meta_title'])) {
                $page_title = $the_category['categories_name'] . ' - ' . HEAD_TITLE_TAG_ALL;
            } else {
                $page_title = $metaData['categories_meta_title'] . '' . HEAD_TITLE_TAG_ALL;
            }
        }
        break;

    // PRODUCT_INFO.PHP
    case ( strstr($_SERVER['PHP_SELF'], 'product_info.php') or strstr($PHP_SELF, 'product_info.php') ):
        $the_product_info_query = tep_db_query("select pd.language_id, p.products_id, pd.products_name, pd.products_description, pd.products_head_title_tag, pd.products_head_keywords_tag, pd.products_head_desc_tag, p.products_model, p.products_quantity, p.products_image, pd.products_url, p.products_price, p.products_tax_class_id, p.products_date_added, p.products_date_available, p.manufacturers_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int) $_GET['products_id'] . "' and pd.products_id = '" . (int) $_GET['products_id'] . "'" . " and pd.language_id ='" . (int) $languages_id . "'");
        $the_product_info = tep_db_fetch_array($the_product_info_query);

        if (empty($the_product_info['products_head_desc_tag'])) {
            $page_meta_description = HEAD_DESC_TAG_ALL;
        } else {
            if (HTDA_PRODUCT_INFO_ON == '1') {
                $page_meta_description = $the_product_info['products_head_desc_tag'] . ' ' . HEAD_DESC_TAG_ALL;
            } else {
                $page_meta_description = $the_product_info['products_head_desc_tag'];
            }
        }

        if (empty($the_product_info['products_head_keywords_tag'])) {
            $page_meta_keywords = HEAD_KEY_TAG_ALL;
        } else {
            if (HTKA_PRODUCT_INFO_ON == '1') {
                $page_meta_keywords = $the_product_info['products_head_keywords_tag'] . ' ' . HEAD_KEY_TAG_ALL;
            } else {
                $page_meta_keywords = $the_product_info['products_head_keywords_tag'];
            }
        }

        $the_category_query = tep_db_query("select cd.categories_name from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int) $current_category_id . "' and cd.categories_id = '" . (int) $current_category_id . "' and cd.language_id = '" . (int) $languages_id . "'");
        $the_category = tep_db_fetch_array($the_category_query);

        $metaCategoryArray = explode("_", $cPath);
        if (strpos($cPath, '_')) {
            $metaCategoryArray = array_reverse($metaCategoryArray);
        }
        $metaCategory = $metaCategoryArray[0];

        $category_query = tep_db_query("select categories_meta_title, categories_meta_description, categories_meta_keywords from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id = '" . $metaCategory . "' and language_id = '" . (int) $languages_id . "'");

        $metaData = tep_db_fetch_array($category_query);

        if (empty($the_product_info['products_head_title_tag'])) {
            $page_title = $the_product_info['products_name'] . ' - ' . $the_category['categories_name'] . ' - ' . HEAD_TITLE_TAG_ALL;
        } else {
            if (HTTA_PRODUCT_INFO_ON == '1') {
                $page_title = clean_html_comments($the_product_info['products_head_title_tag']) . ' - ' . $the_category['categories_name'] . ' - ' . HEAD_TITLE_TAG_ALL;
            } else {
                $page_title = clean_html_comments($the_product_info['products_head_title_tag']);
            }
        }

        break;

    // PRODUCTS_NEW.PHP 
   
    case ( strstr($_SERVER['PHP_SELF'], 'sort=new') or strstr($PHP_SELF, 'sort=new') ):
        if (HEAD_DESC_TAG_WHATS_NEW != '') {
            if (HTDA_WHATS_NEW_ON == '1') {
                $page_meta_description = HEAD_DESC_TAG_WHATS_NEW . ' ' . HEAD_DESC_TAG_ALL;
            } else {
                $page_meta_description = HEAD_DESC_TAG_WHATS_NEW;
            }
        } else {
            $page_meta_description = HEAD_DESC_TAG_DEFAULT;
        }

        if (HEAD_KEY_TAG_WHATS_NEW != '') {
            if (HTKA_WHATS_NEW_ON == '1') {
                $page_meta_keywords = HEAD_KEY_TAG_WHATS_NEW . ' ' . HEAD_KEY_TAG_ALL;
            } else {
                $page_meta_keywords = HEAD_KEY_TAG_WHATS_NEW;
            }
        } else {
            $page_meta_keywords = HEAD_KEY_TAG_ALL;
        }

        if (HEAD_TITLE_TAG_WHATS_NEW != '') {
            if (HTTA_WHATS_NEW_ON == '1') {
                $page_title = HEAD_TITLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_WHATS_NEW;
            } else {
                $page_title = HEAD_TITLE_TAG_WHATS_NEW;
            }
        } else {
            $page_title = HEAD_TITLE_TAG_ALL;
        }

        break;

    // SPECIALS.PHP
    case ( strstr($_SERVER['PHP_SELF'], 'specials.php') or strstr($PHP_SELF, 'specials.php') ):
        if (HEAD_DESC_TAG_SPECIALS != '') {
            if (HTDA_SPECIALS_ON == '1') {
                $page_meta_description = HEAD_DESC_TAG_SPECIALS . ' ' . HEAD_DESC_TAG_ALL;
            } else {
                $page_meta_description = HEAD_DESC_TAG_SPECIALS;
            }
        } else {
            $page_meta_description = HEAD_DESC_TAG_ALL;
        }

        if (HEAD_KEY_TAG_SPECIALS == '') {
            // Build a list of ALL specials product names to put in keywords
            $new = tep_db_query("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and s.products_id = p.products_id and p.products_id = pd.products_id and pd.language_id = '" . (int) $languages_id . "' and s.status = '1' order by s.specials_date_added DESC ");
            $row = 0;
            $the_specials = '';
            while ($new_values = tep_db_fetch_array($new)) {
                $the_specials .= clean_html_comments($new_values['products_name']) . ', ';
            }
            if (HTKA_SPECIALS_ON == '1') {
                $page_meta_keywords = $the_specials . ' ' . HEAD_KEY_TAG_ALL;
            } else {
                $page_meta_keywords = $the_specials;
            }
        } else {
            $page_meta_keywords = HEAD_KEY_TAG_SPECIALS . ' ' . HEAD_KEY_TAG_ALL;
        }

        if (HEAD_TITLE_TAG_SPECIALS != '') {
            if (HTTA_SPECIALS_ON == '1') {
                $page_title = HEAD_TITLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_SPECIALS;
            } else {
                $page_title = HEAD_TITLE_TAG_SPECIALS;
            }
        } else {
            $page_title = HEAD_TITLE_TAG_ALL;
        }

        break;
    case ( strstr($_SERVER['PHP_SELF'],'account_history_info.php') or strstr($PHP_SELF,'account_history_info.php') ):
        $the_title = sprintf(ACCOUNT_HISTORY_INFO_META_TITLE, $_GET['order_id']);
        break;
    case ( strstr($_SERVER['PHP_SELF'],'shopping_cart.php') or strstr($PHP_SELF,'shopping_cart.php') ):
        $the_title= SHOPPING_CART_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'checkout.php') or strstr($PHP_SELF,'checkout.php') ):
        $the_title= CHECKOUT_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'checkout_success.php') or strstr($PHP_SELF,'checkout_success.php') ):
        $the_title= CHECKOUT_SUCCESS_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'account.php') or strstr($PHP_SELF,'account.php') ):
        $the_title= ACCOUNT_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'account_history.php') or strstr($PHP_SELF,'account_history.php') ):
        $the_title= ACCOUNT_HISTORY_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'account_edit.php') or strstr($PHP_SELF,'account_edit.php') ):
        $the_title= ACCOUNT_EDIT_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'address_book.php') or strstr($PHP_SELF,'address_book.php') ):
        $the_title= ADDRESS_BOOK_META_TITLE;
        break;
    case ( strstr($_SERVER['PHP_SELF'],'account_password.php') or strstr($PHP_SELF,'account_password.php') ):
        $the_title= ACCOUNT_PASSWORD_META_TITLE;
        break;

    // ARTICLES_NEW.PHP
    case ( strstr($_SERVER['PHP_SELF'], 'articles_new.php') or strstr($PHP_SELF, 'articles_new.php') ):
        if (HEAD_DESC_TAG_ARTICLES_NEW != '') {
            if (HTDA_ARTICLES_NEW_ON == '1') {
                $page_meta_description = HEAD_DESC_TAG_ARTICLES_NEW . '. ' . HEAD_DESC_ARTICLE_TAG_ALL;
            } else {
                $page_meta_description = HEAD_DESC_TAG_ARTICLES_NEW;
            }
        } else {
            $page_meta_description = NAVBAR_TITLE . '. ' . HEAD_DESC_ARTICLE_TAG_ALL;
        }

        if (HEAD_KEY_TAG_ARTICLES_NEW == '') {
            // Build a list of ALL new article names to put in keywords
            $articles_new_array = array();
            $articles_new_query_raw = "select ad.articles_name from " . TABLE_ARTICLES . " a " . TABLE_ARTICLES_DESCRIPTION . " ad where a.articles_status = '1' and a.articles_id = ad.articles_id and ad.language_id = '" . (int) $languages_id . "' order by a.articles_date_added DESC, ad.articles_name";
            $articles_new_split = new splitPageResults($articles_new_query_raw, MAX_NEW_ARTICLES_PER_PAGE);
            $articles_new_query = tep_db_query($articles_new_split->sql_query);

            $row = 0;
            $the_new_articles = '';
            while ($articles_new = tep_db_fetch_array($articles_new_query)) {
                $the_new_articles .= clean_html_comments($articles_new['articles_name']) . ', ';
            }
            if (HTKA_ARTICLES_NEW_ON == '1') {
                $page_meta_keywords = NAVBAR_TITLE . ', ' . $the_new_articles . ', ' . HEAD_KEY_ARTICLE_TAG_ALL;
            } else {
                $page_meta_keywords = NAVBAR_TITLE . ', ' . $the_new_articles;
            }
        } else {
            $page_meta_keywords = HEAD_KEY_TAG_ARTICLES_NEW . ', ' . HEAD_KEY_ARTICLE_TAG_ALL;
        }

        if (HEAD_TITLE_TAG_ARTICLES_NEW != '') {
            if (HTTA_ARTICLES_NEW_ON == '1') {
                $page_title = HEAD_TITLE_ARTICLE_TAG_ALL . ' - ' . HEAD_TITLE_TAG_ARTICLES_NEW;
            } else {
                $page_title = HEAD_TITLE_TAG_ARTICLES_NEW;
            }
        } else {
            $page_title = HEAD_TITLE_ARTICLE_TAG_ALL . ' - ' . NAVBAR_TITLE;
        }
        break;
        
    // ALL OTHER PAGES NOT DEFINED ABOVE
    default:
        $page_meta_description = HEAD_DESC_TAG_ALL;
        $page_meta_keywords = HEAD_KEY_TAG_ALL;
        $page_title = HEAD_TITLE_TAG_ALL;
        break;
}