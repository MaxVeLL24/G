<?php

namespace EShopmakers\Html;

/**
 * Класс для захвата содержимого, отправляемого в вывод
 */
class Capture
{
    private static $instances = array();
    public $captured_items = array();
    
    /**
     * Начинает захват вывода
     * 
     * @return \EShopmakers\Html\Capture
     */
    public function startCapture()
    {
        ob_start();
        return $this;
    }
    
    /**
     * Останавливает захват вывода, сохраняет захваченное содержимое во внутренний буфер
     * 
     * @return \EShopmakers\Html\Capture
     */
    public function stopCapture()
    {
        $this->captured_items[] = ob_get_contents();
        ob_end_clean();
        return $this;
    }
    
    /**
     * Выводит содержимое внутреннего буфера
     * 
     * @return \EShopmakers\Html\Capture
     */
    public function display()
    {
        foreach($this->captured_items as $item)
        {
            echo $item;
        }
        return $this;
    }
    
    /**
     * Очищает внутренний буфер
     * 
     * @return \EShopmakers\Html\Capture
     */
    public function reset()
    {
        $this->captured_items = array();
        return $this;
    }
    
    /**
     * Возвращает весь внутренний буфер
     * 
     * @return array
     */
    public function get()
    {
        return $this->captured_items;
    }
    
    /**
     * Возвращает последнее захваченое содержимое или NULL, если внутренний буфер пуст
     * 
     * @return string|null
     */
    public function getLast()
    {
        return end($this->captured_items);
    }
    
    /**
     * Возвращает указанную сущность (объект данного класса) из хранилища сущностей
     * 
     * @param string $instance_name Имя сущности
     * @return \EShopmakers\Html\Capture Сущность
     * @throws Exception
     */
    public static function getInstance($instance_name)
    {
        if(!is_string($instance_name))
        {
            throw new Exception('The name of the copy can not be empty');
        }
        if(empty(self::$instances[$instance_name]))
        {
            self::$instances[$instance_name] = new self;
        }
        return self::$instances[$instance_name];
    }
    
    /**
     * Удаляет указанную сущность из хранилища
     * 
     * @param string $instance_name Имя сущности
     * @return boolean Возвращает TRUE, если сущность с указанным именем существовала
     * в хранилище и была удалена. В противном случае возвращает FALSE.
     */
    public static function deleteInstance($instance_name)
    {
        if(!self::instanceExists($instance_name))
        {
            return false;
        }
        unset(self::$instances[$instance_name]);
        return true;
    }
    
    /**
     * Проверяет, находится ли указанная сущносить в хранилище
     * 
     * @param string $instance_name Имя сущности
     * @return boolean  Возвращает TRUE, если сущность с указанным именем существует
     * в хранилище. В противном случае возвращает FALSE.
     */
    public static function instanceExists($instance_name)
    {
        return array_key_exists($instance_name, self::$instances);
    }
}
