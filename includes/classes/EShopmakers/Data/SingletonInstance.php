<?php

namespace EShopmakers\Data;

/**
 * Абстрактный класс для сущностей, которые должны существовать в единственном числе
 */
abstract class SingletonInstance
{
    /**
     * Постоянная ссылка на существующий экземпляр класса
     * 
     * @var object
     */
    protected static $_instance;
    /**
     * Возвращает экземпляр класса
     * 
     * @return object
     */
    public static function getInstance()
    {
        if(!static::$_instance)
        {
            static::$_instance = new static;
        }
        return static::$_instance;
    }
    public function __construct()
    {
        if(static::$_instance)
        {
            throw new Exception('Can\'t create more then one instance of ' . get_class($this) . ' class!');
        }
    }
}