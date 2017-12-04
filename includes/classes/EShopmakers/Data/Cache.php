<?php

namespace EShopmakers\Data;

abstract class Cache extends Registry
{
    /**
     * Указывает на то, стоит ли автоматически сохранить данные кэша при вызове деструктора
     * 
     * @var boolean
     */
    protected $_autosave = true;
    
    protected $_valid_thru;
    
    public function __destruct()
    {
        if($this->_autosave)
        {
            $this->save();
        }
    }
    
    /**
     * Установить и/или получить значение флага автосохранения
     * 
     * @param boolean $status Устанавливаемое значение флага автосохранения. Необязательный параметр.
     * @return boolean Возвращает TRUE, если автосохранение включено, в противном случае возвращает FALSE
     */
    public function autosave($status)
    {
        if(func_num_args())
        {
            $this->_autosave = (bool)$status;
        }
        return $this->_autosave;
    }
    
    /**
     * Установить и/или получить значение срока действия кэша
     * @param int $timestamt Устанавливаемое значение срока действия кэша - временная метка UNIX. Необязательный параметр.
     * @return int Срока действия кэша - временная метка UNIX
     */
    public function validThru($timestamt)
    {
        if(func_num_args() && is_int($timestamt) && $this->_valid_thru !== $timestamt)
        {
            $this->_valid_thru = $timestamt;
            $this->_modified = true;
        }
        return $this->_valid_thru;
    }
    
    /**
     * Сохраняет данные кэша
     */
    abstract public function save();
}