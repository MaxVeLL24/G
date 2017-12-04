<?php

/*
  $Id: wishlist.php,v 3.0  2005/08/24 Dennis Blake
  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
 */

class wishlist {
    public $wishID = array();

    public static function getInstance() {
        if (empty($_SESSION['wishList']) || !($_SESSION['wishList'] instanceof self)) {
            $_SESSION['wishList'] = new self();
        }
        return $_SESSION['wishList'];
    }

    public function __construct() {
        $this->reset();
    }

    public function restore_wishlist() {
        if (empty($_SESSION['customer_id'])) {
            return false;
        }

        // merge current wishlist items in database
        if (is_array($this->wishID)) {
            reset($this->wishID);

            while (list($wishlist_id, ) = each($this->wishID)) {
                $wishlist_query = tep_db_query("select products_id from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $wishlist_id . "'");
                if (!tep_db_num_rows($wishlist_query)) {
                    tep_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id) values ('" . $_SESSION['customer_id'] . "', '" . $wishlist_id . "')");
                    if (isset($this->wishID[$wishlist_id]['attributes'])) {
                        reset($this->wishID[$wishlist_id]['attributes']);
                        while (list($option, $value) = each($this->wishID[$wishlist_id]['attributes'])) {
                            tep_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . $_SESSION['customer_id'] . "', '" . $wishlist_id . "', '" . $option . "', '" . $value . "' )");
                        }
                    }
                }
            }
        }

        // reset session contents
        $this->wishID = array();

        $wishlist_session = tep_db_query("select products_id from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "'");
        while ($wishlist = tep_db_fetch_array($wishlist_session)) {
            $this->wishID[$wishlist['products_id']] = array($wishlist['products_id']);
            // attributes
            $attributes_query = tep_db_query("select products_options_id, products_options_value_id from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "' and products_id = '" . $wishlist['products_id'] . "'");
            while ($attributes = tep_db_fetch_array($attributes_query)) {
                $this->wishID[$wishlist['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
            }
        }
    }

    public function add_wishlist($wishlist_id, $attributes_id) {
        if (!$this->in_wishlist($wishlist_id)) {

            $wishlist_id = tep_get_uprid($wishlist_id, $attributes_id);
            // Insert into session
            $this->wishID[$wishlist_id] = array($wishlist_id);

            if (!empty($_SESSION['customer_id'])) {
                // Insert into database
                tep_db_query("insert into " . TABLE_WISHLIST . " (customers_id, products_id) values ('" . $_SESSION['customer_id'] . "', '" . $wishlist_id . "')");
            }

            // Read array of options and values for attributes in id[]
            if (is_array($attributes_id)) {
                reset($attributes_id);
                while (list($option, $value) = each($attributes_id)) {
                    $this->wishID[$wishlist_id]['attributes'][$option] = $value;
                    // Add to customers_wishlist_attributes table
                    if (!empty($_SESSION['customer_id'])) {
                        tep_db_query("insert into " . TABLE_WISHLIST_ATTRIBUTES . " (customers_id, products_id, products_options_id , products_options_value_id) values ('" . $_SESSION['customer_id'] . "', '" . $wishlist_id . "', '" . $option . "', '" . $value . "' )");
                    }
                }
                tep_session_unregister('attributes_id');
            }
        }
    }

    public function remove($wishlist_id) {
        // Remove from session
        // выделяем чистый айди товара:
        $wishlist_id = tep_get_prid($wishlist_id);
        unset($this->wishID[$wishlist_id]);

        //remove from database
        if (!empty($_SESSION['customer_id'])) {
            tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $wishlist_id . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
            tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = '" . $wishlist_id . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
        }
    }

    public function clear() {
        // Remove all from database
        if (!empty($_SESSION['customer_id'])) {
            $wishlist_products_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . $_SESSION['customer_id'] . "'");
            while ($wishlist_products = tep_db_fetch_array($wishlist_products_query)) {
                // выделяем чистый айди товара:
                $wishlist_products['products_id'] = tep_get_prid($wishlist_products['products_id']);

                unset($this->wishID[$wishlist_products['products_id']]);

                tep_db_query("delete from " . TABLE_WISHLIST . " where products_id = '" . $wishlist_products['products_id'] . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
                tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where products_id = '" . $wishlist_products['products_id'] . "' and customers_id = '" . $_SESSION['customer_id'] . "'");
            }
        }
    }

    public function reset($reset_database = false) {
        // Remove all from database
        if (!empty($_SESSION['customer_id']) && ($reset_database == true)) {
            tep_db_query("delete from " . TABLE_WISHLIST . " where customers_id = '" . $_SESSION['customer_id'] . "'");
            tep_db_query("delete from " . TABLE_WISHLIST_ATTRIBUTES . " where customers_id = '" . $_SESSION['customer_id'] . "'");
        }

        // reset session contents
        $this->wishID = array();
    }

    public function in_wishlist($wishlist_id) {
        return isset($this->wishID[$wishlist_id]);
    }
}