<?php

final class messageStack
{
    private $messages = array();
    private $count_by_class = array();
    
    public static function getInstance()
    {
        static $instance;
        if(!isset($instance))
        {
            $instance = new self;
        }
        return $instance;
    }
    
    public function render($page_key = '')
    {
        if($this->size($page_key) > 0)
        {
            echo $this->output($page_key);
        }
    }
    
    public function __construct()
    {
        $this->reset();
        if(!empty($_SESSION['messageToStack']) && is_array($_SESSION['messageToStack']))
        {
            foreach($_SESSION['messageToStack'] as $messageToStack)
            {
                $this->add($messageToStack['class'], $messageToStack['text'], $messageToStack['type']);
            }
            unset($_SESSION['messageToStack']);
        }
    }
    
    public function add($class, $message, $type = 'error')
    {
        $type = $type === 'info' || $type === 'warning' || $type === 'success' || $type === 'error' ? $type : 'error';
        $this->messages[] = array(
            'class' => $class,
            'text'  => $message,
            'type'  => $type
        );
        if(empty($this->count_by_class[$class]))
        {
            $this->count_by_class[$class] = 1;
        }
        else
        {
            $this->count_by_class[$class] += 1;
        }
    }

    public function add_session($class, $message, $type = 'error')
    {
        if(empty($_SESSION['messageToStack']) || !is_array($_SESSION['messageToStack']))
        {
            $_SESSION['messageToStack'] = array();
        }
        $_SESSION['messageToStack'][] = array(
            'class' => $class,
            'text'  => $message,
            'type'  => $type
        );
    }

    function reset()
    {
        $this->messages = array();
    }

    function output($class)
    {
        $output = '';
        foreach($this->messages as $message)
        {
            if($message['class'] === $class)
            {
                $output .= '<div class="alert alert-' . $message['type'] . '" role="alert">' . $message['text'] . '</div>';
            }
        }
        return $output;
    }

    function size($class)
    {
        if(!empty($this->count_by_class[$class]))
        {
            return $this->count_by_class[$class];
        }
        return 0;
    }
}