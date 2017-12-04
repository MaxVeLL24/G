<?php

class currencies
{
    public static $customer_discount;
    public $currencies;
    public $enableCurrencies;
    public $taxWrapper;

    public function __construct()
    {
        $this->taxWrapper = 'span';
        $this->enableCurrencies = true;
        $this->currencies = array();
        $currencies_query = tep_db_query("select code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value from " . TABLE_CURRENCIES);
        while($currencies = tep_db_fetch_array($currencies_query))
        {
            $this->currencies[$currencies['code']] = array(
                'title' => $currencies['title'],
                'symbol_left' => $currencies['symbol_left'],
                'symbol_right' => $currencies['symbol_right'],
                'decimal_point' => $currencies['decimal_point'],
                'thousands_point' => $currencies['thousands_point'],
                'decimal_places' => $currencies['decimal_places'],
                'value' => $currencies['value']
            );
        }
    }
    
    public function __get($name)
    {
        if($name === 'currency')
        {
            if(empty($_SESSION['currency']))
            {
                $_SESSION['currency'] = DEFAULT_CURRENCY;
            }
            return $_SESSION['currency'];
        }
    }

    public function getSymbolRight()
    {
        global $currency;
        return $this->currencies[$currency]['symbol_right'];
    }
    
    public function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '')
    {
        switch($this->taxWrapper)
        {
            case 'span':
                $prefix_html = '<span class="currency">';
                $sufix_html = '</span>';
                break;
            case false:
                $prefix_html = '';
                $sufix_html = '';
                break;
            default:
                # code...
                break;
        }
        global $currency;

        if(empty($currency_type))
        {
            $currency_type = $currency;
        }
        if($calculate_currency_value == true)
        {
            if($this->enableCurrencies)
            {
                $right_symbol = $prefix_html . $this->currencies[$currency_type]['symbol_right'] . $sufix_html;
            }

            $rate = (tep_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
            $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . '' . $right_symbol;

            if((DEFAULT_CURRENCY == 'EUR') && ($currency_type == 'DEM' || $currency_type == 'BEF' || $currency_type == 'LUF' || $currency_type == 'ESP' || $currency_type == 'FRF' || $currency_type == 'IEP' || $currency_type == 'ITL' || $currency_type == 'NLG' || $currency_type == 'ATS' || $currency_type == 'PTE' || $currency_type == 'FIM' || $currency_type == 'GRD'))
            {
                $format_string .= ' <small>[' . $this->format($number, true, 'EUR') . ']</small>';
            }
        }
        else
        {
            $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(tep_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $prefix_html . $this->currencies[$currency_type]['symbol_right'] . $sufix_html;
        }
        return $format_string;
    }

    public function is_set($code)
    {
        if(isset($this->currencies[$code]) && tep_not_null($this->currencies[$code]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_value($code)
    {
        return $this->currencies[$code]['value'];
    }

    public function get_decimal_places($code)
    {
        return $this->currencies[$code]['decimal_places'];
    }
    
    public function setTaxwrapper($wrapper)
    {
        $this->taxWrapper = $wrapper;
    }

    public function display_price($products_price, $products_tax, $quantity = 1)
    {
        if(((constant('ALLOW_GUEST_TO_SEE_PRICES') == 'true') && !(tep_session_is_registered('customer_id'))) || ((tep_session_is_registered('customer_id'))))
        {
            return $this->format($this->get_price($products_price, $products_tax, $quantity), false);
        }
        else
        {
            return PRICES_LOGGED_IN_TEXT;
        }
    }

    public function display_price_nodiscount($products_price, $products_tax, $quantity = 1)
    {
        if(((constant('ALLOW_GUEST_TO_SEE_PRICES') == 'true') && !(tep_session_is_registered('customer_id'))) || ((tep_session_is_registered('customer_id'))))
        {
            return $this->format($this->get_price_nodiscount($products_price, $products_tax, $quantity), false);
        }
        else
        {
            return PRICES_LOGGED_IN_TEXT;
        }
    }
    
    /**
     * Вычисляет значение цены в валюте, выбранной покупателем. Данный метод
     * НЕ учитывает персональную и груповую скидку покупателя.
     * 
     * @param float $products_price Цена
     * @param float $products_tax Налоговая процентная ставка
     * @param float $quantity Количество товара
     * @return float
     */
    public function get_price_nodiscount($products_price, $products_tax, $quantity = 1)
    {
        global $currency;
        if(empty($currency))
        {
            $currency = DEFAULT_CURRENCY;
        }
        $tax = 0;
        if(constant('DISPLAY_PRICE_WITH_TAX') == 'true')
        {
            $tax = ($products_price * $products_tax / 100);
        }
        return round(($products_price + $tax) * $quantity * $this->currencies[$currency]['value'], $this->currencies[$currency]['decimal_places']);
    }
    
    /**
     * Вычисляет значение цены в валюте, выбранной покупателем. Данный метод
     * учитывает персональную и груповую скидку покупателя.
     * 
     * @param float $products_price Цена
     * @param float $products_tax Налоговая процентная ставка
     * @param float $quantity Количество товара
     * @return float
     */
    public function get_price($products_price, $products_tax, $quantity = 1)
    {
        return $this->get_price_nodiscount($products_price + $this->getCustomerDiscount(), $products_tax, $quantity);
    }
    
    /**
     * Возвращает процентную скидку покупателя
     * 
     * @return float
     */
    public function getCustomerDiscount()
    {
        if(!isset(self::$customer_discount))
        {
            if((constant('ALLOW_GUEST_TO_SEE_PRICES') == 'true') && empty($_SESSION['customer_id']))
            {
                self::$customer_discount = GUEST_DISCOUNT;
            }
            elseif(!empty($_SESSION['customer_id']))
            {
                $query = tep_db_query("select g.customers_groups_discount from " . TABLE_CUSTOMERS_GROUPS . " g inner join  " . TABLE_CUSTOMERS . " c on g.customers_groups_id = c.customers_groups_id and c.customers_id = '" . $_SESSION['customer_id'] . "'");
                $query_result = tep_db_fetch_array($query);
                $customers_groups_discount = $query_result['customers_groups_discount'];
                $query = tep_db_query("select customers_discount from " . TABLE_CUSTOMERS . " where customers_id =  '" . $_SESSION['customer_id'] . "'");
                $query_result = tep_db_fetch_array($query);
                self::$customer_discount = $query_result['customers_discount'];
                self::$customer_discount = self::$customer_discount + $customers_groups_discount;
            }
            else
            {
                self::$customer_discount = 0;
            }
        }
        return self::$customer_discount;
    }
}