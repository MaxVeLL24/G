<?php

namespace EShopmakers\Http;

class Rewrite extends \EShopmakers\Data\SingletonInstance
{
    /**
     * Код языка
     * @var string
     */
    private $language;
    
    /**
     * ID языка
     * @var int 
     */
    private $language_id;
    
    /**
     * Парсит ЧПУ
     * @param string $link Ссылка, которую нужно распарсить
     * @return array Возвращает индексный массив, первый элемент которого содержит имя файла, который предназначен
     * для обработки данного запроса, а второй - содержит массив параметров запроса
     */
    public function parse($link)
    {
        // Избавляемся от якоря
        $link = explode('#', $link);
        $parsed_path  = array();
        $parsed_query = array();
        $initial_query = array();
        parse_str(parse_url($link[0], PHP_URL_QUERY), $initial_query);
        
        // Парсим части пути
        $path = trim(parse_url($link[0], PHP_URL_PATH), '/');
        if($path)
        {
            $path = explode('/', $path);
        }
        else
        {
            $path = array();
        }
        foreach($path as $key => $part)
        {
            // Язык
            if(!$key)
            {
                $language_id = \language::getIDByCode($part);
                if($language_id !== null)
                {
                    $this->language_id = $language_id;
                    $this->language = \language::getCodeByID($this->language_id);
                    continue;
                }
            }
            
            $part = explode('-', $part);
            
            // Категория
            if(count($part) >= 2 && $part[0] === 'c' && preg_match('/^\d+(?:_\d+)*$/', $part[1]) && (!$parsed_query || (count($parsed_query) === 1 && isset($parsed_query['cPath']))))
            {
                if(!isset($parsed_query['cPath']))
                {
                    $parsed_query['cPath'] = array();
                }
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_DEFAULT;
                }
                foreach(explode('_', $part[1]) as $tmp)
                {
                    $parsed_query['cPath'][] = (int)$tmp;
                }
            }
            // Предыдущий вариант адреса категории (оставляем для своместимости, чтобы работал редирект)
            elseif(count($part) >= 2 && $part[0] === 'c' && is_numeric($part[1]) && (!$parsed_query || (count($parsed_query) === 1 && isset($parsed_query['cPath']))))
            {
                if(!isset($parsed_query['cPath']))
                {
                    $parsed_query['cPath'] = array();
                }
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_DEFAULT;
                }
                $parsed_query['cPath'][] = (int)$part[1];
            }
            // Производитель
            elseif(count($part) >= 2 && $part[0] === 'm' && is_numeric($part[1]) && !$parsed_query)
            {
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_DEFAULT;
                }
                $parsed_query['manufacturers_id'] = (int)$part[1];
            }
            // Фильтры категории
            elseif(count($part) >= 2 && $part[0] === 'f' && preg_match('/^f(?:-\d+(?:_\d+)+)+$/', $path[$key]) && (isset($parsed_query['cPath']) || isset($parsed_query['manufacturers_id'])))
            {
                for($i = 1; $i < count($part); $i++)
                {
                    $tmp = explode('_', $part[$i]);
                    $option_id = (int)$tmp[0];
                    $options_values_ids = array();
                    for($j = 1; $j < count($tmp); $j++)
                    {
                        $options_values_ids[] = (int)$tmp[$j];
                    }
                    $parsed_query[$option_id] = implode('-', $options_values_ids);
                }
            }
            // Предыдущий вариант фильтра категории (оставляем для своместимости, чтобы работал редирект)
            elseif(count($part) >= 3 && $part[0] === 'f' && is_numeric($part[1]) && is_numeric($part[2]) && (isset($parsed_query['cPath']) || isset($parsed_query['manufacturers_id'])))
            {
                $option_id = (int)$part[1];
                $options_values_ids = array();
                for($i = 2; $i < count($part); $i++)
                {
                    if(!is_numeric($part[$i]))
                    {
                        break;
                    }
                    $options_values_ids[] = (int)$part[$i];
                }
                $parsed_query[$option_id] = implode('-', $options_values_ids);
            }
            // Товар
            elseif(count($path) === ($this->language === $path[0] ? 2 : 1) && count($part) >= 2 && $part[0] === 'p' && is_numeric($part[1]) && !$parsed_query)
            {
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_PRODUCT_INFO;
                }
                $parsed_query['products_id'] = (int)$part[1];
                if(isset($initial_query['options']))
                {
                    $parsed_query['products_id'] .= $initial_query['options'];
                    unset($initial_query['options']);
                }
            }
            // Инфостраница
            elseif(count($path) === ($this->language === $path[0] ? 2 : 1) && count($part) >= 2 && $part[0] === 'i' && is_numeric($part[1]) && !$parsed_query)
            {
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_INFORMATION;
                }
                $parsed_query['pages_id'] = (int)$part[1];
            }
            // Статья
            elseif(count($path) === ($this->language === $path[0] ? 2 : 1) && count($part) >= 2 && $part[0] === 'a' && is_numeric($part[1]) && !$parsed_query)
            {
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_ARTICLE_INFO;
                }
                $parsed_query['articles_id'] = (int)$part[1];
            }
            // Категория статей
            elseif(count($part) >= 2 && $part[0] === 't' && is_numeric($part[1]) && (!$parsed_query || (count($parsed_query) === 1 && isset($parsed_query['tPath']))))
            {
                if(!isset($parsed_query['tPath']))
                {
                    $parsed_query['tPath'] = array();
                }
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_ARTICLES;
                }
                $parsed_query['tPath'][] = (int)$part[1];
            }
            // Новость
            elseif(count($path) === ($this->language === $path[0] ? 2 : 1) && count($part) >= 2 && $part[0] === 'n' && is_numeric($part[1]) && !$parsed_query)
            {
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_NEWSDESK_INFO;
                }
                $parsed_query['newsdesk_id'] = (int)$part[1];
            }
            // Категория новостей
            elseif(count($part) >= 2 && $part[0] === 'nc' && is_numeric($part[1]) && (!$parsed_query || (count($parsed_query) === 1 && isset($parsed_query['newsPath']))))
            {
                if(!isset($parsed_query['newsPath']))
                {
                    $parsed_query['newsPath'] = array();
                }
                if(!$parsed_path)
                {
                    $parsed_path[] = FILENAME_NEWSDESK_INDEX;
                }
                $parsed_query['newsPath'][] = (int)$part[1];
            }
            // Всё остальное, что не подпадает под ЧПУ
            else
            {
                if($parsed_query)
                {
                    return array(
                        FILENAME_NOT_FOUND,
                        array()
                    );
                }
                $parsed_path[] = $path[$key];
            }
        }

        // Если финальный путь у нас пустой, то запрос ведёт к index.php
        if(!$parsed_path)
        {
            $parsed_path[] = FILENAME_DEFAULT;
        }
        
        // Собрать воедино ID-шники категорий
        if(isset($parsed_query['cPath']))
        {
            $parsed_query['cPath'] = implode('_', $parsed_query['cPath']);
        }
        // Собрать воедино ID-шники статей
        if(isset($parsed_query['tPath']))
        {
            $parsed_query['tPath'] = implode('_', $parsed_query['tPath']);
        }
        // Собрать воедино ID-шники новостей
        if(isset($parsed_query['newsPath']))
        {
            $parsed_query['newsPath'] = implode('_', $parsed_query['newsPath']);
        }
        
        // Собрать воедино путь запроса
        $parsed_path = implode('/', $parsed_path);
        
        // Собрать воедино строку запроса
        foreach($initial_query as $key => $value)
        {
            if(!array_key_exists($key, $parsed_query))
            {
                $parsed_query[$key] = $value;
            }
        }
        
        // Язык
        if($this->language)
        {
            $parsed_query['language'] = $this->language;
        }
        elseif(isset($parsed_query['language']))
        {
            $this->language_id = \language::getIDByCode($parsed_query['language']);
            if($this->language_id)
            {
                $this->language = $parsed_query['language'];
            }
            else
            {
                $this->language = DEFAULT_LANGUAGE;
                $this->language_id = \language::getIDByCode($this->language);
            }
        }
        else
        {
            $this->language = DEFAULT_LANGUAGE;
            $this->language_id = \language::getIDByCode($this->language);
        }
        $parsed_query['language'] = $this->language;
        
        // Проверить корректность ссылки, но только если это не AJAX запрос
        if(!\EShopmakers\Http\Request::isAjax())
        {
            $check_link = $this->link($parsed_path, $parsed_query);
            if($check_link !== $link[0])
            {
                \EShopmakers\Http\Response::permanentRedirect($check_link . (empty($link[1]) ? '' : '#' . $link[1]));
            }
        }
        
        return array(
            $parsed_path,
            $parsed_query
        );
    }
    
    /**
     * Генерирует ЧПУ c указанным путём и параметрами запроса
     * @param string $path Путь URL
     * @param array|string $query Массив параметров или строка запроса
     * @return string
     */
    public function link($path, $query = array())
    {
        $_path = array();
        
        // Парсим строку запроса
        if(!is_array($query))
        {
            $tmp = array();
            parse_str(trim(str_replace('&amp;', '&', $query), '&'), $tmp);
            $query = $tmp;
            unset($tmp);
        }
        
        // Проверяем, не меняется ли язык в запросе
        if(isset($query['language']))
        {
            $this->language = $query['language'];
            $this->language_id = \language::getIDByCode($this->language);
            if(!$this->language_id)
            {
                $this->language_id = \language::getIDByCode(DEFAULT_LANGUAGE);
                $this->language = \language::getCodeByID($this->language_id);
            }
            unset($query['language']);
        }
        else
        {
            $this->language_id = empty($_SESSION['languages_id']) ? \language::getIDByCode(DEFAULT_LANGUAGE) : $_SESSION['languages_id'];
            $this->language = \language::getCodeByID($this->language_id);
        }
        
        // Добавляем код языка в ссылку, если он отличается от языка "по-умолчанию"
        if($this->language !== DEFAULT_LANGUAGE)
        {
            $_path[] = $this->language;
        }
        
        // Категория, производитель, главная страница
        if($path === FILENAME_DEFAULT)
        {
            // Категория или производитель
            if(array_key_exists('cPath', $query) || array_key_exists('manufacturers_id', $query))
            {
                // Категория
                if(array_key_exists('cPath', $query))
                {
                    $_path[] = 'c-' . $query['cPath'] . '-' . $this->getCategoryName(end(explode('_', $query['cPath'])));
                    unset($query['cPath']);
                }
                // Производитель
                elseif(array_key_exists('manufacturers_id', $query))
                {
                    $manufacturers_id = (int)$query['manufacturers_id'];
                    $_path[] = 'm-' . $manufacturers_id . '-' . $this->getManufacturerName($manufacturers_id);
                    unset($query['manufacturers_id']);
                }

                // Фильтры
                $filter_query_params = array_filter(array_keys($query), 'is_numeric');
                asort($filter_query_params);
                if($filter_query_params)
                {
                    $_path_part = 'f';
                    foreach($filter_query_params as $key)
                    {
                        $tmp = array_filter(explode('-', $query[$key]), 'is_numeric');
                        asort($tmp);
                        $_path_part .= '-' . $key . '_' . implode($tmp, '_');

                        // Удалить эту опцию из параметров запроса
                        unset($query[$key]);
                    }
                    $_path[] = $_path_part;
                }
            }
            // Для главной страницы ничего не добавляем в путь ссылки и таким образом избавляемся от index.php
        }
        // Товар
        elseif($path === FILENAME_PRODUCT_INFO && !empty($query['products_id']))
        {
            $options = explode('{', $query['products_id']);
            $products_id = (int)array_shift($options);
            $_path[] = 'p-' . $products_id . '-' . $this->getProductName($products_id);
            unset($query['products_id']);
            if($options)
            {
                $query['options'] = '{' . implode('{', $options);
            }
        }
        // Инфостраница
        elseif($path === FILENAME_INFORMATION && !empty($query['pages_id']))
        {
            $page_id = (int)$query['pages_id'];
            $_path[] = 'i-' . $page_id . '-' . $this->getPageName($page_id);
            unset($query['pages_id']);
        }
        // Статья
        elseif($path === FILENAME_ARTICLE_INFO && !empty($query['articles_id']))
        {
            $article_id = (int)$query['articles_id'];
            $_path[] = 'a-' . $article_id . '-' . $this->getArticleName($article_id);
            unset($query['articles_id']);
        }
        // Категория статей
        elseif($path === FILENAME_ARTICLES)
        {
            if(array_key_exists('tPath', $query))
            {
                $tPath = explode('_', $query['tPath']);
            }
            else
            {
                $tPath = array(0);
            }
            foreach($tPath as $topic_id)
            {
                $topic_id = (int)$topic_id;
                $_path[] = 't-' . $topic_id . '-' . $this->getTopicName($topic_id);
            }
            unset($query['tPath']);
        }
        // Новость
        elseif($path === FILENAME_NEWSDESK_INFO && !empty($query['newsdesk_id']))
        {
            $newsdesk_id = (int)$query['newsdesk_id'];
            $_path[] = 'n-' . $newsdesk_id . '-' . $this->getNewsarticleName($newsdesk_id);
            unset($query['newsdesk_id']);
        }
        // Категория новостей
        elseif($path === FILENAME_NEWSDESK_INDEX)
        {
            if(array_key_exists('tPath', $query))
            {
                $newsPath = explode('_', $query['newsPath']);
            }
            else
            {
                $newsPath = array(0);
            }
            foreach($newsPath as $newstopic_id)
            {
                $newstopic_id = (int)$newstopic_id;
                $_path[] = 'nc-' . $newstopic_id . '-' . $this->getNewstopicName($newstopic_id);
            }
            unset($query['newsPath']);
        }
        // Если для данного пути не предусмотрено ЧПУ, то сохраняем исходный путь
        else
        {
            $_path[] = $path;
        }
        
        // Строим результирующую строку URL
        return ($_SERVER['SERVER_PORT'] === 443 ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . implode('/', $_path) . ($query ? '?' . http_build_query_rfc_3986($query): '');
    }
    
    /**
     * Возвращает название категории по её ID
     * @param int $category_id ID категории
     * @return string ЧПУ имя категории
     */
    private function getCategoryName($category_id)
    {
        if(!$category_id)
        {
            return 'all';
        }
        $sef_categories_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.categories.' . $this->language);
        if(!isset($sef_categories_cache->{$category_id}))
        {
            $result = tep_db_query("SELECT categories_url, categories_name FROM categories_description WHERE categories_id = " . $category_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_fetch_array($result);
                if($result['categories_url'])
                {
                    $result = $result['categories_url'];
                }
                else
                {
                    $result = $result['categories_name'];
                }
            }
            else
            {
                $result = '';
            }
            $sef_categories_cache->{$category_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_categories_cache->{$category_id};
    }
    
    /**
     * Возвращает название производителя по его ID
     * @param int $manufacturers_id ID производителя
     * @return string ЧПУ имя производителя
     */
    private function getManufacturerName($manufacturers_id)
    {
        $sef_manufacturers_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.manufacturers.' . $this->language);
        if(!isset($sef_manufacturers_cache->{$manufacturers_id}))
        {
            $result = tep_db_query("SELECT manufacturers_name FROM manufacturers WHERE manufacturers_id = " . $manufacturers_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_result($result, 0);
            }
            else
            {
                $result = '';
            }
            $sef_manufacturers_cache->{$manufacturers_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_manufacturers_cache->{$manufacturers_id};
    }
    
    /**
     * Возвращает название товара по его ID
     * @param int $product_id ID товара
     * @return string ЧПУ имя товара
     */
    private function getProductName($product_id)
    {
        $sef_products_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.products.' . $this->language);
        if(!isset($sef_products_cache->{$product_id}))
        {
            $result = tep_db_query("SELECT products_url, products_name FROM products_description WHERE products_id = " . $product_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_fetch_array($result);
                if($result['products_url'])
                {
                    $result = $result['products_url'];
                }
                else
                {
                    $result = $result['products_name'];
                }
            }
            else
            {
                $result = '';
            }
            $sef_products_cache->{$product_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_products_cache->{$product_id};
    }
    
    /**
     * Возвращает название инфостраницы по её ID
     * @param int $page_id ID инфостраницы
     * @return string ЧПУ имя инфостраницы
     */
    private function getPageName($page_id)
    {
        $sef_pages_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.pages.' . $this->language);
        if(!isset($sef_pages_cache->{$page_id}))
        {
            $result = tep_db_query("SELECT pages_name FROM pages_description WHERE pages_id = " . $page_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_result($result, 0);
            }
            else
            {
                $result = '';
            }
            $sef_pages_cache->{$page_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_pages_cache->{$page_id};
    }
    
    /**
     * Возвращает название статьи по её ID
     * @param int $article_id ID статьи
     * @return string ЧПУ имя статьи
     */
    private function getArticleName($article_id)
    {
        $sef_articles_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.articles.' . $this->language);
        if(!isset($sef_articles_cache->{$article_id}))
        {
            $result = tep_db_query("SELECT articles_url, articles_name FROM articles_description WHERE articles_id = " . $article_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_fetch_array($result);
                if($result['articles_url'])
                {
                    $result = $result['articles_url'];
                }
                else
                {
                    $result = $result['articles_name'];
                }
            }
            else
            {
                $result = '';
            }
            $sef_articles_cache->{$article_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_articles_cache->{$article_id};
    }
    
    /**
     * Возвращает название статьи по её ID
     * @param int $topic_id ID статьи
     * @return string ЧПУ имя статьи
     */
    private function getTopicName($topic_id)
    {
        if(!$topic_id)
        {
            return 'all';
        }
        $sef_topics_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.topics.' . $this->language);
        if(!isset($sef_topics_cache->{$topic_id}))
        {
            $result = tep_db_query("SELECT topics_name FROM topics_description WHERE topics_id = " . $topic_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_result($result, 0);
            }
            else
            {
                $result = '';
            }
            $sef_topics_cache->{$topic_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_topics_cache->{$topic_id};
    }
    
    /**
     * Возвращает название новости по её ID
     * @param int $newsarticle_id ID новости
     * @return string ЧПУ имя новости
     */
    private function getNewsarticleName($newsarticle_id)
    {
        $sef_newsarticles_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.newsarticles.' . $this->language);
        if(!isset($sef_newsarticles_cache->{$newsarticle_id}))
        {
            $result = tep_db_query("SELECT newsdesk_article_url, newsdesk_article_name FROM newsdesk_description WHERE newsdesk_id = " . $newsarticle_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_fetch_array($result);
                if($result['newsdesk_article_url'])
                {
                    $result = $result['newsdesk_article_url'];
                }
                else
                {
                    $result = $result['newsdesk_article_name'];
                }
            }
            else
            {
                $result = '';
            }
            $sef_newsarticles_cache->{$newsarticle_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_newsarticles_cache->{$newsarticle_id};
    }
    
    /**
     * Возвращает название категории новостей по её ID
     * @param int $newstopic_id ID категории новостей
     * @return string ЧПУ имя категории новостей
     */
    private function getNewstopicName($newstopic_id)
    {
        if(!$newstopic_id)
        {
            return 'all';
        }
        $sef_newstopics_cache = \EShopmakers\Data\DatabaseCache::getInstance('sef.newstopics.' . $this->language);
        if(!isset($sef_newstopics_cache->{$newstopic_id}))
        {
            $result = tep_db_query("SELECT categories_name FROM newsdesk_categories_description WHERE categories_id = " . $newstopic_id . " AND language_id = " . $this->language_id);
            if(tep_db_num_rows($result))
            {
                $result = tep_db_result($result, 0);
            }
            else
            {
                $result = '';
            }
            $sef_newstopics_cache->{$newstopic_id} = tep_make_uri_friendly_string($result);
        }
        return $sef_newstopics_cache->{$newstopic_id};
    }
}