<?php

namespace EShopmakers\Data;

class DatabaseCache extends Cache
{
    /**
     * Идентификатор кэша
     * @var int
     */
    private $_cache_id;
    
    public function __construct($instance_name)
    {
        parent::__construct($instance_name);
        
        // Выгрузить ранее сохранённые данные кэша из БД, если они там есть
        $query = tep_db_query("SELECT `cache_id`, `cache_data`, `cache_valid_thru` FROM `cache` WHERE `cache_name` = '" . tep_db_input($instance_name) . "' LIMIT 1");
        if(tep_db_num_rows($query))
        {
            $result = tep_db_fetch_array($query);
            // Валидация кэша
            if($result['cache_valid_thru'] && $result['cache_valid_thru'] < time())
            {
                tep_db_query("DELETE FROM `cache` WHERE `cache_id` = " . $result['cache_id']);
            }
            else
            {
                $this->_cache_id = $result['cache_id'];
                if(($this->_data = @unserialize($result['cache_data'])) === false)
                {
                    $this->_data = array();
                }
                $this->_valid_thru = (int)$result['cache_valid_thru'];
                if(!is_array($this->_data))
                {
                    $this->_data = array();
                }
            }
        }
    }
    
    public function save()
    {
        // Если не было измеений в данных, то не делаем запроса 
        if(!$this->_modified)
        {
            return;
        }
        if($this->_cache_id)
        {
            tep_db_query("UPDATE `cache` SET `cache_data` = '" . tep_db_input(serialize($this->_data)) . "', `cache_valid_thru` = " . ($this->_valid_thru ? $this->_valid_thru : 'NULL') . " WHERE `cache_id` = " . $this->_cache_id);
        }
        else
        {
            tep_db_query("INSERT INTO `cache` SET `cache_name` = '" . tep_db_input($this->_instance_name) . "',  `cache_data` = '" . tep_db_input(serialize($this->_data)) . "', `cache_valid_thru` = " . ($this->_valid_thru ? $this->_valid_thru : 'NULL'));
            $this->_cache_id = tep_db_insert_id();
        }
    }
}