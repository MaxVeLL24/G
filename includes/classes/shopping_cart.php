<?php

/*
  $Id: shopping_cart.php,v 1.1.1.1 2003/09/18 19:05:12 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

class shoppingCart
{

    var $contents, $total, $weight, $cartID;

    function shoppingCart()
    {
        $this->reset();
    }

    function restore_contents()
    {
        //ICW replace line
        global $customer_id, $gv_id, $REMOTE_ADDR;

        if(!tep_session_is_registered('customer_id'))
        {
            return false;
        }

        // insert current cart contents in database
        if(is_array($this->contents))
        {
            reset($this->contents);
            while(list($products_id, ) = each($this->contents))
            {
                $qty = $this->contents[$products_id]['qty'];
                $product_query = tep_db_query("select products_id from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
                if(!tep_db_num_rows($product_query))
                {
                    tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int) $customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
                    if(isset($this->contents[$products_id]['attributes']))
                    {
                        reset($this->contents[$products_id]['attributes']);
                        while(list($option, $value) = each($this->contents[$products_id]['attributes']))
                        {
                            // otf 1.71 Update query to include attribute value. This is needed for text attributes.
                            $attr_value = $this->contents[$products_id]['attributes_values'][$option];
                            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int) $customer_id . "', '" . tep_db_input($products_id) . "', '" . (int) $option . "', '" . (int) $value . "', '" . tep_db_input($attr_value) . "')");
                        }
                    }
                }
                else
                {
                    tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $qty . "' where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
                }
            }
            //ICW ADDDED FOR CREDIT CLASS GV - START
            if(tep_session_is_registered('gv_id'))
            {
                $gv_query = tep_db_query("insert into  " . TABLE_COUPON_REDEEM_TRACK . " (coupon_id, customer_id, redeem_date, redeem_ip) values ('" . $gv_id . "', '" . (int) $customer_id . "', now(),'" . $REMOTE_ADDR . "')");
                $gv_update = tep_db_query("update " . TABLE_COUPONS . " set coupon_active = 'N' where coupon_id = '" . $gv_id . "'");
                tep_gv_account_update($customer_id, $gv_id);
                tep_session_unregister('gv_id');
            }
            //ICW ADDDED FOR CREDIT CLASS GV - END
        }

        // reset per-session cart contents, but not the database contents
        $this->reset(false);

        $products_query = tep_db_query("select products_id, customers_basket_quantity from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int) $customer_id . "'");
        while($products = tep_db_fetch_array($products_query))
        {
            $this->contents[$products['products_id']] = array('qty' => $products['customers_basket_quantity']);
            // attributes
            // otf 1.71Update query to pull attribute value_text. This is needed for text attributes.
            $attributes_query = tep_db_query("select products_options_id, products_options_value_id, products_options_value_text from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products['products_id']) . "'");
            while($attributes = tep_db_fetch_array($attributes_query))
            {
                $this->contents[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
                // If text attribute, then set additional information
                if($attributes['products_options_value_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID)
                {
                    $this->contents[$products['products_id']]['attributes_values'][$attributes['products_options_id']] = $attributes['products_options_value_text'];
                }
            }
        }

        $this->cleanup();
    }

    function reset($reset_database = false)
    {
        global $customer_id;

        $this->contents = array();
        $this->total = 0;
        $this->weight = 0;

        if(tep_session_is_registered('customer_id') && ($reset_database == true))
        {
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int) $customer_id . "'");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int) $customer_id . "'");
        }

        unset($this->cartID);
        if(tep_session_is_registered('cartID'))
        {
            tep_session_unregister('cartID');
        }
    }

    function add_cart($products_id, $qty = '1', $attributes = '', $notify = true)
    {
        global $new_products_id_in_cart, $customer_id;

        $products_id = tep_get_uprid($products_id, $attributes);
        if($notify == true)
        {
            $new_products_id_in_cart = $products_id;
            tep_session_register('new_products_id_in_cart');
        }

        if($this->in_cart($products_id))
        {
            $this->update_quantity($products_id, $qty, $attributes);
        }
        else
        {
            $this->contents[] = array($products_id);
            $this->contents[$products_id] = array('qty' => $qty);
            // insert into database
            if(tep_session_is_registered('customer_id'))
            {
                tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET . " (customers_id, products_id, customers_basket_quantity, customers_basket_date_added) values ('" . (int) $customer_id . "', '" . tep_db_input($products_id) . "', '" . $qty . "', '" . date('Ymd') . "')");
            }

            if(is_array($attributes))
            {
                reset($attributes);
                while(list($option, $value) = each($attributes))
                {
                    // otf 1.71 Check if input was from text box.  If so, store additional attribute information
                    // Check if text input is blank, if so do not add to attribute lists
                    // Add htmlspecialchars processing.  This handles quotes and other special chars in the user input.
                    $attr_value = NULL;
                    $blank_value = FALSE;
                    if(strstr($option, TEXT_PREFIX))
                    {
                        if(trim($value) == NULL)
                        {
                            $blank_value = TRUE;
                        }
                        else
                        {
                            $option = substr($option, strlen(TEXT_PREFIX));
                            $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
                            $value = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
                            $this->contents[$products_id]['attributes_values'][$option] = $attr_value;
                        }
                    }
                    if(!$blank_value)
                    {
                        $this->contents[$products_id]['attributes'][$option] = $value;
                        // insert into database
                        // otf 1.71 Update db insert to include attribute value_text. This is needed for text attributes.
                        // Add tep_db_input() processing
                        if(tep_session_is_registered('customer_id'))
                            tep_db_query("insert into " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " (customers_id, products_id, products_options_id, products_options_value_id, products_options_value_text) values ('" . (int) $customer_id . "', '" . tep_db_input($products_id) . "', '" . (int) $option . "', '" . (int) $value . "', '" . tep_db_input($attr_value) . "')");
                    }
                }
            }
        }
        $this->cleanup();

        // assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
        $this->cartID = $this->generate_cart_id();
    }

    function update_quantity($products_id, $quantity = '', $attributes = '')
    {
        global $customer_id;

        if(empty($quantity))
            return true; // nothing needs to be updated if theres no quantity, so we return true..

        $this->contents[$products_id] = array('qty' => $quantity);
        // update database
        if(tep_session_is_registered('customer_id'))
            tep_db_query("update " . TABLE_CUSTOMERS_BASKET . " set customers_basket_quantity = '" . $quantity . "' where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");

        if(is_array($attributes))
        {
            reset($attributes);
            while(list($option, $value) = each($attributes))
            {
                // otf 1.71 Check if input was from text box.  If so, store additional attribute information
                // Check if text input is blank, if so do not update attribute lists
                // Add htmlspecialchars processing.  This handles quotes and other special chars in the user input.
                $attr_value = NULL;
                $blank_value = FALSE;
                if(strstr($option, TEXT_PREFIX))
                {
                    if(trim($value) == NULL)
                    {
                        $blank_value = TRUE;
                    }
                    else
                    {
                        $option = substr($option, strlen(TEXT_PREFIX));
                        $attr_value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
                        $value = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
                        $this->contents[$products_id]['attributes_values'][$option] = $attr_value;
                    }
                }

                if(!$blank_value)
                {
                    $this->contents[$products_id]['attributes'][$option] = $value;
                    // update database
                    // Update db insert to include attribute value_text. This is needed for text attributes.
                    // Add tep_db_input() processing
                    if(tep_session_is_registered('customer_id'))
                        tep_db_query("update " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " set products_options_value_id = '" . (int) $value . "', products_options_value_text = '" . tep_db_input($attr_value) . "' where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products_id) . "' and products_options_id = '" . (int) $option . "'");
                }
            }
        }
    }

    function cleanup()
    {
        global $customer_id;

        reset($this->contents);
        while(list($key, ) = each($this->contents))
        {
            if($this->contents[$key]['qty'] < 1)
            {
                unset($this->contents[$key]);
                // remove from database
                if(tep_session_is_registered('customer_id'))
                {
                    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($key) . "'");
                    tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($key) . "'");
                }
            }
        }
    }

    function count_contents()
    {
        // get total number of items in cart 
        $total_items = 0;
        if(is_array($this->contents))
        {
            reset($this->contents);
            while(list($products_id, ) = each($this->contents))
            {
                $total_items += $this->get_quantity($products_id);
            }
        }

        return $total_items;
    }

    function get_quantity($products_id)
    {
        if(isset($this->contents[$products_id]))
        {
            return $this->contents[$products_id]['qty'];
        }
        else
        {
            return 0;
        }
    }

    function in_cart($products_id)
    {
        if(isset($this->contents[$products_id]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function remove($products_id)
    {
        global $customer_id;

        // otf 1.71 Add call tep_get_uprid to correctly format product ids containing quotes
        $products_id = tep_get_uprid($products_id, $attributes);

        unset($this->contents[$products_id]);
        // remove from database
        if(tep_session_is_registered('customer_id'))
        {
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
            tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int) $customer_id . "' and products_id = '" . tep_db_input($products_id) . "'");
        }

        // assign a temporary unique ID to the order contents to prevent hack attempts during the checkout procedure
        $this->cartID = $this->generate_cart_id();
    }

    function remove_all()
    {
        $this->reset();
    }

    function get_product_id_list()
    {
        $product_id_list = '';
        if(is_array($this->contents))
        {
            reset($this->contents);
            while(list($products_id, ) = each($this->contents))
            {
                $product_id_list .= ', ' . $products_id;
            }
        }

        return substr($product_id_list, 2);
    }

    function calculate()
    {
        $this->total = 0;
        $this->weight = 0;
        if(!is_array($this->contents))
            return 0;

        reset($this->contents);
        while(list($products_id, ) = each($this->contents))
        {
            $qty = $this->contents[$products_id]['qty'];

            // products price
            $product_query = tep_db_query("select products_id, products_price, products_tax_class_id, products_weight from " . TABLE_PRODUCTS . " where products_id = '" . (int) $products_id . "'");
            if($product = tep_db_fetch_array($product_query))
            {

                $prid = $product['products_id'];
                $products_tax = tep_get_tax_rate($product['products_tax_class_id']);
                $products_price = tep_xppp_getproductprice($product['products_id']);
                $products_weight = $product['products_weight'];

                global $customer_id;
                $customer_discount = $this->r_get_customer_discount($customer_id, $prid);

                if($customer_discount >= 0)
                {
                    $products_price = $products_price + $products_price * abs($customer_discount) / 100;
                }
                else
                {
                    $products_price = $products_price - $products_price * abs($customer_discount) / 100;
                }

                if($special_price = tep_get_products_special_price($prid))
                {
                    $products_price = $special_price;
                }

                $this->total += tep_add_tax($products_price, $products_tax) * $qty;
                $this->weight += ($qty * $products_weight);
            }

            // attributes price


            if(isset($this->contents[$products_id]['attributes']))
            {
                reset($this->contents[$products_id]['attributes']);
                while(list($option, $value) = each($this->contents[$products_id]['attributes']))
                {
                    $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int) $prid . "' and options_id = '" . (int) $option . "' and options_values_id = '" . (int) $value . "'");
                    $attribute_price = tep_db_fetch_array($attribute_price_query);
                    
                    if($customer_discount >= 0)
                    {
                        $attribute_price['options_values_price'] = $attribute_price['options_values_price'] + $attribute_price['options_values_price'] * abs($customer_discount) / 100;
                    }
                    else
                    {
                        $attribute_price['options_values_price'] = $attribute_price['options_values_price'] - $attribute_price['options_values_price'] * abs($customer_discount) / 100;
                    }

                    if($attribute_price['price_prefix'] == '+')
                    {
                        $this->total += $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
                    }
                    elseif($attribute_price['price_prefix'] == '-')
                    {
                        $this->total -= $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
                    } if($attribute_price['price_prefix'] == '=')
                    {
                        $this->total = $qty * tep_add_tax($attribute_price['options_values_price'], $products_tax);
                    }
                }
            }
        }
    }

    function attributes_price($products_id)
    {
        $attributes_price = 0;

        if(isset($this->contents[$products_id]['attributes']))
        {
            reset($this->contents[$products_id]['attributes']);
            while(list($option, $value) = each($this->contents[$products_id]['attributes']))
            {
                $attribute_price_query = tep_db_query("select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int) $products_id . "' and options_id = '" . (int) $option . "' and options_values_id = '" . (int) $value . "'");
                $attribute_price = tep_db_fetch_array($attribute_price_query);

                $prid = $products_id;
                global $customer_id;

                $customer_discount = $this->r_get_customer_discount($customer_id, $prid);

                if($customer_discount >= 0)
                {
                    $attribute_price['options_values_price'] = $attribute_price['options_values_price'] + $attribute_price['options_values_price'] * abs($customer_discount) / 100;
                }
                else
                {
                    $attribute_price['options_values_price'] = $attribute_price['options_values_price'] - $attribute_price['options_values_price'] * abs($customer_discount) / 100;
                }


                if($attribute_price['price_prefix'] == '+')
                {
                    $attributes_price += $attribute_price['options_values_price'];
                }
                elseif($attribute_price['price_prefix'] == '-')
                {
                    $attributes_price -= $attribute_price['options_values_price'];
                }
                elseif($attribute_price['price_prefix'] == '=')
                {
                    $attributes_price = $attribute_price['options_values_price'];
                }

                if($attribute_price['price_prefix'] == '=')
                    $this->contents[$products_id]['attributes_prefix'] = $attribute_price['price_prefix'];
            }
        }
        return $attributes_price;
    }

    function get_products()
    {
        global $languages_id;

        if(!is_array($this->contents))
        {
            return false;
        }

        $products_array = array();
        reset($this->contents);
        while(list($products_id, ) = each($this->contents))
        {
            $products_query = tep_db_query("select p.products_id, pd.products_name, p.products_model, p.products_images, p.products_price, p.products_weight, p.products_tax_class_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where p.products_id = '" . (int) $products_id . "' and pd.products_id = p.products_id and pd.language_id = '" . (int) $languages_id . "'");
            if($products = tep_db_fetch_array($products_query))
            {
                $prid = $products['products_id'];

                $products_price = tep_xppp_getproductprice($products['products_id']);

                global $customer_id;
                $customer_discount = $this->r_get_customer_discount($customer_id, $prid);


                if($customer_discount >= 0)
                {
                    $products_price = $products_price + $products_price * abs($customer_discount) / 100;
                }
                else
                {
                    $products_price = $products_price - $products_price * abs($customer_discount) / 100;
                }

                if($special_price = tep_get_products_special_price($prid))
                {
                    $products_price = $special_price;
                }
                
                $image = '';
                if($products['products_images'])
                {
                    $images = explode(';', $products['products_images']);
                    if($images)
                    {
                        $image = $images[0];
                    }
                }

                $products_array[] = array(
                    'id' => $products_id,
                    'name' => $products['products_name'],
                    'model' => $products['products_model'],
                    'image' => $image,
                    'price' => $products_price,
                    'quantity' => $this->contents[$products_id]['qty'],
                    'weight' => $products['products_weight'],
                    'final_price' => ($this->contents[$products_id]['attributes_prefix'] == '=') ? $this->attributes_price($products_id) : ($products_price + $this->attributes_price($products_id)),
                    'tax_class_id' => $products['products_tax_class_id'],
                    'attributes' => (isset($this->contents[$products_id]['attributes']) ? $this->contents[$products_id]['attributes'] : ''),
                    'attributes_values' => (isset($this->contents[$products_id]['attributes_values']) ? $this->contents[$products_id]['attributes_values'] : '')
                );
            }
        }

        return $products_array;
    }

    function r_get_customer_discount($customer_id, $prid)
    {
        $query_price_to_guest_result = ALLOW_GUEST_TO_SEE_PRICES;
        if(($query_price_to_guest_result == 'true') && !(tep_session_is_registered('customer_id')))
        {
            $customer_discount = GUEST_DISCOUNT;
        }
        elseif(tep_session_is_registered('customer_id'))
        {
            $query_A = tep_db_query("select m.manudiscount_discount from " . TABLE_MANUDISCOUNT . " m, " . TABLE_PRODUCTS . " p where m.manudiscount_groups_id = 0 and m.manudiscount_customers_id = '" . $customer_id . "' and p.products_id = '" . $prid . "' and p.manufacturers_id = m.manudiscount_manufacturers_id");
            $query_B = tep_db_query("select m.manudiscount_discount from " . TABLE_CUSTOMERS . " c, " . TABLE_MANUDISCOUNT . " m, " . TABLE_PRODUCTS . " p where m.manudiscount_groups_id = c.customers_groups_id  and m.manudiscount_customers_id = 0 and c.customers_id = '" . $customer_id . "' and p.products_id = '" . $prid . "' and p.manufacturers_id = m.manudiscount_manufacturers_id");
            $query_C = tep_db_query("select m.manudiscount_discount from " . TABLE_MANUDISCOUNT . " m, " . TABLE_PRODUCTS . " p where m.manudiscount_groups_id = 0 and m.manudiscount_customers_id = 0 and p.products_id = '" . $prid . "' and p.manufacturers_id = m.manudiscount_manufacturers_id");

            if($query_result = tep_db_fetch_array($query_A))
            {
                $customer_discount = $query_result['manudiscount_discount'];
            }
            else if($query_result = tep_db_fetch_array($query_B))
            {
                $customer_discount = $query_result['manudiscount_discount'];
            }
            else if($query_result = tep_db_fetch_array($query_C))
            {
                $customer_discount = $query_result['manudiscount_discount'];
            }
            else
            {
                // скидка для групп:
                $query_group = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . $customer_id . "'");
                $query_group_result = tep_db_fetch_array($query_group);
                $customers_groups_discount = $query_group_result['customers_groups_discount'];
                // скидка для покупателя:     
                $query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . $customer_id . "'");
                $query_result = tep_db_fetch_array($query);
                $customer_discount = $query_result['customers_discount'];

                $customer_discount = $customer_discount + $customers_groups_discount;
            }
        }
        return $customer_discount;
    }

    function attr_prefix($products_id)
    {
        return $this->contents[$products_id]['attributes_prefix'];
    }

    function show_total()
    {
        $this->calculate();

        return $this->total;
    }

    function show_weight()
    {
        $this->calculate();

        return $this->weight;
    }

    function generate_cart_id($length = 5)
    {
        return tep_create_random_value($length, 'digits');
    }

    function unserialize($broken)
    {
        for(reset($broken); $kv = each($broken);)
        {
            $key = $kv['key'];
            if(gettype($this->$key) != "user function")
            {
                $this->$key = $kv['value'];
            }
        }
    }
}