<?php

/**
 * Библиотека, котороая содержит методы для получения сведений о запросе
 * 
 * @author Сергей Яруничев <xj5e34gnku22iiwi7q5d761zs2mkqx@gmail.com>
 */

namespace EShopmakers\Http;

/**
 * Класс содержит методы для получения сведений о запросе
 */
abstract class Request
{
    /**
     * Проверяет, выполнен ли запрос AJAX-ом
     * 
     * @return boolean
     */
    public static function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Возвращает реальный удалённый IP-адрес клиента
     * 
     * @return string
     */
    public static function getIP()
    {
        if(!empty($_SERVER['HTTP_X_REAL_IP']))
        {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        if(!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}