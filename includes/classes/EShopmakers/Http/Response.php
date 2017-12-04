<?php

/**
 * Библиотека, котороая содержит методы для управления ответом
 * 
 * @author Сергей Яруничев <xj5e34gnku22iiwi7q5d761zs2mkqx@gmail.com>
 */

namespace EShopmakers\Http;

/**
 * Класс содержит методы для управления ответом
 */
abstract class Response
{
    /**
     * Установить заголов X-Robots-Tag: noindex, follow
     */
    public static function noIndexFollow()
    {
        header('X-Robots-Tag: noindex, follow', true);
    }
    
    /**
     * Установить заголов X-Robots-Tag: noindex, nofollow
     */
    public static function noIndexNoFollow()
    {
        header('X-Robots-Tag: noindex, nofollow', true);
    }
    
    /**
     * Установить заголовки, запрещающие кэширование ответа
     */
    public static function noCache()
    {
        header('Cache-Control: no-cache, no-store, must-revalidate', true);
        header('Pragma: no-cache', true);
        header('Expires: 0', true);
    }
    
    /**
     * Отправить в ответ данные в формате JSON
     * 
     * @param mixed $data
     */
    public static function sendJSON($data)
    {
        while(ob_get_level() > 1)
        {
            ob_end_clean();
        }
        if(ob_get_level())
        {
            ob_clean();
        }
        self::noCache();
        self::noIndexFollow();
        header('Content-Type: application/json; charset=UTF-8', true, 200);
        exit(json_encode($data));
    }
    
    /**
     * Кинуть перманентный редирект (301)
     * @param string $link
     */
    public static function permanentRedirect($link)
    {
        ob_clean();
        header('Location: ' . $link, true, 301);
        tep_exit();
    }
}