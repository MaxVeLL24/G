<?php

/*
  $Id: language.php,v 1.1.1.1 2003/09/18 19:05:15 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  browser language detection logic Copyright phpMyAdmin (select_lang.lib.php3 v1.24 04/19/2002)
  Copyright Stephane Garin <sgarin@sgarin.com> (detect_language.php v0.1 04/02/2002)
 */

/**
 * @property-read array $catalog_languages Перечень всех языков, установленных на сайте
 */
class language
{
    /**
     * Выбранный код языка
     * 
     * @var type 
     */
    public $language;
    
    /**
     * Перечень всех языков, установленных на сайте
     * 
     * @var array
     */
    private static $_catalog_languages;
    
    public function __get($name)
    {
        if($name === 'catalog_languages')
        {
            return self::$_catalog_languages;
        }
    }
    
    /**
     * Единожды выгрузить все языки, которые установлены на сайте
     */
    private static function loadLanguages()
    {
        if(!isset(self::$_catalog_languages))
        {
            self::$_catalog_languages = array();
            $languages_query = tep_db_query("select languages_id, name, code, image, directory from " . TABLE_LANGUAGES . " order by sort_order");
            while($languages = tep_db_fetch_array($languages_query))
            {
                self::$_catalog_languages[$languages['code']] = array(
                    'id' => $languages['languages_id'],
                    'name' => $languages['name'],
                    'image' => $languages['image'],
                    'directory' => $languages['directory']
                );
            }
        }
    }
    
    /**
     * Найти код языка по его ID в БД
     * 
     * @param int $id ID языка в БД
     * @return string Возвращает код языка
     */
    public static function getCodeByID($id)
    {
        self::loadLanguages();
        foreach(self::$_catalog_languages as $code => $language)
        {
            if($language['id'] == $id)
            {
                return $code;
            }
        }
        return null;
    }
    
    /**
     * Найти ID языка по его коду
     * 
     * @param string $code Код языка
     * @return int Возвращает ID языка
     */
    public static function getIDByCode($code)
    {
        self::loadLanguages();
        return isset(self::$_catalog_languages[$code]) ? self::$_catalog_languages[$code]['id'] : null;
    }

    /**
     * Конструктор
     * 
     * @param string $lng Код языка, который будет выбран
     */
    public function __construct($lng = '')
    {
        self::loadLanguages();
        $this->set_language($lng);
    }

    /**
     * Установить язык по его коду
     * 
     * @param string $language Код языка
     */
    public function set_language($language)
    {
        if(!empty($language) && (isset($this->catalog_languages[$language])))
        {
            $this->language = $this->catalog_languages[$language];
        }
        else
        {
            $this->language = $this->catalog_languages[DEFAULT_LANGUAGE];
        }
    }
    
    /**
     * Установить язык пое его идентификатору в ДБ
     * 
     * @param int $language_id ID языка в БД
     */
    public function set_language_by_id($language_id)
    {
        $_language = null;
        foreach($this->catalog_languages as $code => $language)
        {
            if($language['id'] == $language_id)
            {
                $_language = $code;
                break;
            }
        }
        $this->set_language($_language);
    }

    /**
     * Установить язык исходя из заголовка Accept-Language, переданного пользователем
     */
    public function get_browser_language()
    {
        $found_lang_code = null;
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            $parsed = array();
            $parts = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach($parts as $part)
            {
                $part = explode(';', trim($part)); // 'en_US; q=0.9' -> array('en_US', 'q=0.9')
                if($part)
                {
                    $part[0] = array_shift(explode('_', trim($part[0]))); // 'en_US' -> array('en', 'US') -> 'en'
                    if(!empty($part[1]))
                    {
                        $part[1] = floatval(substr(trim($part[1]), 2)); // 'q=0.9' -> '0.9' -> 0.9
                        if(!$part[1])
                        {
                            $part[1] = 0;
                        }
                    }
                    else
                    {
                        $part[1] = 1;
                    }
                    $parsed[$part[0]] = $part[1];
                }
            }
            arsort($parsed);
            if($parsed)
            {
                foreach(array_keys($parsed) as $code)
                {
                    if(array_key_exists($code, $this->catalog_languages))
                    {
                        $found_lang_code = $code;
                        break;
                    }
                    else
                    {
                        $code = substr($code, 0, 2);
                        if($code && array_key_exists($code, $this->catalog_languages))
                        {
                            $found_lang_code = $code;
                            break;
                        }
                    }
                }
            }
        }
        $this->set_language($found_lang_code);
    }

}