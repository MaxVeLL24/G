<?php

/**
 * Библиотека для выпуска и валидации CSRF токенов
 * 
 * @author Сергей Яруничев <xj5e34gnku22iiwi7q5d761zs2mkqx@gmail.com>
 */

namespace EShopmakers\Security;

/**
 * Класс для выпуска и валидации CSRF токенов
 */
abstract class CSRFToken
{
    /**
     * Массив символов для построения случайной строки
     * @var array
     */
    protected static $dictionary = array(
        '`', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', '-', '=', '~',
        '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+', 'q',
        'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p', '[', ']', '{', '}', '\\',
        '|', 'a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l', ';', ':', '\'', '"',
        'z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '<', '.', '>', '/', '?'
    );
    
    /**
     * Возвращает секретную часть токена
     * 
     * Генерирует и возвращает секретную часть ключа, которая используется для
     * выпуска новых и валидации уже существующих токенов. Секретная часть ключа
     * постоянна, привязана и хранится в сессии.
     * 
     * @return string
     */
    public static function getSecret()
    {
        if(empty($_SESSION['csrf_secret']))
        {
            $_SESSION['csrf_secret'] = md5(self::getRandomString());
        }
        return $_SESSION['csrf_secret'];
    }
    
    /**
     * Возвращает строку, содержащую случайную последовательность симовлов
     * 
     * @return string
     */
    public static function getRandomString()
    {
        $random_string = '';
        for($i = 0; $i < rand(32, 64); $i++)
        {
            $random_string .= self::$dictionary[array_rand(self::$dictionary)];
        }
        return $random_string;
    }
    
    /**
     * Выпускает новый токен
     * 
     * @return string
     */
    public static function getToken()
    {
        $salt = md5(self::getRandomString());
        return $salt . ':' . md5($salt . self::getSecret());
    }
    
    /**
     * Проверяет, действителен ли указанный токен
     * 
     * @param string $token Токен для проверки
     * @return boolean
     */
    public static function validateToken($token)
    {
        if($token)
        {
            $token = explode(':', $token);
            if($token && is_array($token) && count($token) === 2 && !empty($token[0]) && !empty($token[1]))
            {
                return md5($token[0] . self::getSecret()) === $token[1];
            }
        }
        return false;
    }
    
    /**
     * Сбрасывает (отзывает) все токены, выпущеные для текущего пользователя
     * 
     * Сбрасывает секретную часть ключа, при помощи которой были выпущены все
     * токены в сессии текущего пользователя, что автоматически делает их невалидными.
     */
    public static function resetAll()
    {
        unset($_SESSION['csrf_secret']);
    }
    
    /**
     * Пытается отыскать токен среди POST, GET или заголовков запроса
     * 
     * @return string|null Возвращает токен, если такой удалось отыскать, в противном случае возвращает NULL
     */
    public static function seekForTokenInRequest()
    {
        return empty($_POST['token']) ? empty($_GET['token']) ? empty($_SERVER['HTTP_X_CSRF_TOKEN']) ? null : $_SERVER['HTTP_X_CSRF_TOKEN'] : $_GET['token'] : $_POST['token'];
    }
    
    /**
     * Пытается отыскать токен среди POST, GET или заголовков запроса и произвести его валидацию
     * 
     * @return boolean Возвращает TRUE, если токен был найден и является валидным, в противном случае возвращает FALSE
     */
    public static function seekForTokenInRequestAndValidate()
    {
        return self::validateToken(self::seekForTokenInRequest());
    }
    
    /**
     * Выпускает новый токен и добавляет его в куки, если существующий устарел или не существует
     */
    public static function addToCookie()
    {
        // Выпустить новый токен
        if(empty($_COOKIE['csrf_token']) || !self::validateToken($_COOKIE['csrf_token']) && !headers_sent())
        {
            setcookie('csrf_token', self::getToken());
        }
    }
}