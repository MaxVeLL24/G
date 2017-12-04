<?php

namespace EShopmakers\Data;

abstract class Instance
{
    protected static $_instances = array();
    protected $_instance_name;
    public function __construct($instance_name)
    {
        $this->_instance_name = $instance_name;
    }
    public static function getInstance($instance_name)
    {
        if(!isset(static::$_instances[$instance_name]))
        {
            static::$_instances[$instance_name] = new static($instance_name);
            static::$_instances[$instance_name]->_instance_name = $instance_name;
        }
        return static::$_instances[$instance_name];
    }
    public static function hasInstance($instance_name)
    {
        return isset(static::$_instances[$instance_name]);
    }
    public static function deleteInstance($instance_name)
    {
        unset(static::$_instances[$instance_name]);
    }
}