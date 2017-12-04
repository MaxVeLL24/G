<?php

/*
  $Id: shipping.php,v 1.1.1.1 2003/09/18 19:05:13 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

class shipping
{

    var $modules;
    public static $shipping_classes_objects = array();

    /**
     * Возвращает объект указанного класса доставки
     * @param string $shipping_class_name
     * @return object|null
     */
    public static function getInstanceOf($shipping_class_name)
    {
        if(!class_exists($shipping_class_name))
        {
            if(is_file(DIR_WS_MODULES . 'shipping/' . $shipping_class_name . '.php'))
            {
                include DIR_WS_MODULES . 'shipping/' . $shipping_class_name . '.php';
                if(!class_exists($shipping_class_name))
                {
                    return null;
                }
            }
            else
            {
                return null;
            }
            if(is_file(DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/shipping/' . $shipping_class_name . '.php'))
            {
                include DIR_WS_LANGUAGES . $_SESSION['language'] . '/modules/shipping/' . $shipping_class_name . '.php';
            }
        }
        if(empty(self::$shipping_classes_objects[$shipping_class_name]))
        {
            self::$shipping_classes_objects[$shipping_class_name] = new $shipping_class_name();
        }
        return self::$shipping_classes_objects[$shipping_class_name];
    }

// class constructor
    function shipping($module = '')
    {
// BOF: WebMakers.com Added: Downloads Controller
        global $language, $PHP_SELF, $cart;
// EOF: WebMakers.com Added: Downloads Controller

        if(defined('MODULE_SHIPPING_INSTALLED') && tep_not_null(MODULE_SHIPPING_INSTALLED))
        {

//add for SPPC shipment and payment module start 
            //   $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
            global $sppc_customers_groups_id, $customer_id;
            if(!tep_session_is_registered('sppc_customers_groups_id'))
            {
                $customers_groups_id = '0';
            }
            else
            {

                $query_shipping = tep_db_query("select g.customers_groups_id from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . $customer_id . "'");
                $query_shipping_result = tep_db_fetch_array($query_shipping);

                $customers_groups_id = $query_shipping_result['customers_groups_id'];
//      $customers_groups_id = $sppc_customers_groups_id;
            }
            $customer_shipment_query = tep_db_query("select IF(c.customers_shipment_allowed <> '', c.customers_shipment_allowed, cg.group_shipment_allowed) as shipment_allowed from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_GROUPS . " cg where c.customers_id = '" . $customer_id . "' and cg.customers_groups_id =  '" . $customers_groups_id . "'");
            if($customer_shipment = tep_db_fetch_array($customer_shipment_query))
            {
                if(tep_not_null($customer_shipment['shipment_allowed']))
                {
                    $temp_shipment_array = explode(';', $customer_shipment['shipment_allowed']);
                    $installed_modules = explode(';', MODULE_SHIPPING_INSTALLED);
                    for($n = 0; $n < sizeof($installed_modules); $n++)
                    {
                        // check to see if a shipping module is not de-installed
                        if(in_array($installed_modules[$n], $temp_shipment_array))
                        {
                            $shipment_array[] = $installed_modules[$n];
                        }
                    } // end for loop
                    $this->modules = $shipment_array;
                }
                else
                {
                    $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
                }
            }
            else
            { // default
                $this->modules = explode(';', MODULE_SHIPPING_INSTALLED);
            }
//add for SPPC shipment and payment module end
            $include_modules = array();

            if((tep_not_null($module)) && (in_array(substr($module['id'], 0, strpos($module['id'], '_')) . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.') + 1)), $this->modules)))
            {
                $include_modules[] = array('class' => substr($module['id'], 0, strpos($module['id'], '_')), 'file' => substr($module['id'], 0, strpos($module['id'], '_')) . '.' . substr($PHP_SELF, (strrpos($PHP_SELF, '.') + 1)));
            }
            else
            {
                reset($this->modules);
// BOF: WebMakers.com Added: Downloads Controller - Free Shipping and Payments
// Show either normal shipping modules or free shipping module when Free Shipping Module is On
                // Free Shipping Only
                if((tep_get_configuration_key_value('MODULE_SHIPPING_FREESHIPPER_STATUS') == '1' and $cart->show_weight() == 0))
                {
                    $include_modules[] = array('class' => 'freeshipper', 'file' => 'freeshipper.php');
                }
                else
                {
                    // All Other Shipping Modules
                    while(list(, $value) = each($this->modules))
                    {
                        $class = substr($value, 0, strrpos($value, '.'));
                        // Don't show Free Shipping Module
                        if($class != 'freeshipper')
                        {
                            $include_modules[] = array('class' => $class, 'file' => $value);
                        }
                    }
                }
// EOF: WebMakers.com Added: Downloads Controller - Free Shipping and Payments
            }
        }
    }

    function quote($method = '', $module = '')
    {
        global $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes;

        $quotes_array = array();

        if(is_array($this->modules))
        {
            $shipping_quoted = '';
            $shipping_num_boxes = 1;
            $shipping_weight = $total_weight;

            if(SHIPPING_BOX_WEIGHT >= $shipping_weight * SHIPPING_BOX_PADDING / 100)
            {
                $shipping_weight = $shipping_weight + SHIPPING_BOX_WEIGHT;
            }
            else
            {
                $shipping_weight = $shipping_weight + ($shipping_weight * SHIPPING_BOX_PADDING / 100);
            }

            if($shipping_weight > SHIPPING_MAX_WEIGHT)
            { // Split into many boxes
                $shipping_num_boxes = ceil($shipping_weight / SHIPPING_MAX_WEIGHT);
                $shipping_weight = $shipping_weight / $shipping_num_boxes;
            }
            
            if($module)
            {
                if(in_array($module . '.php', $this->modules))
                {
                    if(($_module = self::getInstanceOf($module)) && ($_module->enabled))
                    {
                        $quotes_array[] = $_module->quote($method);
                    }
                }
            }
            else
            {
                foreach($this->modules as $value)
                {
                    $class = substr($value, 0, strrpos($value, '.'));
                    if(($_module = self::getInstanceOf($class)) && ($_module->enabled))
                    {
                        $quotes_array[] = $_module->quote($method);
                    }
                }
            }
        }
        return $quotes_array;
    }

    function cheapest()
    {
        if(is_array($this->modules))
        {
            $rates = array();

            reset($this->modules);
            while(list(, $value) = each($this->modules))
            {
                $class = substr($value, 0, strrpos($value, '.'));
                if(($_module = self::getInstanceOf($class)) && ($_module->enabled))
                {
                    $quotes = $_module->quotes;
                    for($i = 0, $n = sizeof($quotes['methods']); $i < $n; $i++)
                    {
                        if(isset($quotes['methods'][$i]['cost']) && tep_not_null($quotes['methods'][$i]['cost']))
                        {
                            $rates[] = array('id' => $quotes['id'] . '_' . $quotes['methods'][$i]['id'],
                                'title' => $quotes['module'] . ' (' . $quotes['methods'][$i]['title'] . ')',
                                'cost' => $quotes['methods'][$i]['cost']);
                        }
                    }
                }
            }

            $cheapest = false;
            for($i = 0, $n = sizeof($rates); $i < $n; $i++)
            {
                if(is_array($cheapest))
                {
                    if($rates[$i]['cost'] < $cheapest['cost'])
                    {
                        $cheapest = $rates[$i];
                    }
                }
                else
                {
                    $cheapest = $rates[$i];
                }
            }

            return $cheapest;
        }
    }

}