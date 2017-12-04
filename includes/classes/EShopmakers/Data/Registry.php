<?php

namespace EShopmakers\Data;

class Registry extends Instance
{
    protected $_data;
    protected $_modified = false;
    public function __get($name)
    {
        if(isset($this->_data[$name]))
        {
            return $this->_data[$name];
        }
    }
    public function __set($name, $value)
    {
        $this->_data[$name] = $value;
    }
    public function __isset($name)
    {
        $this->_modified = true;
        return isset($this->_data[$name]);
    }
    public function __unset($name)
    {
        $this->_modified = true;
        unset($this->_data[$name]);
    }
}