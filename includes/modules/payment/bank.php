<?php

class bank
{

    var $code, $title, $description, $enabled;

// class constructor
    public function __construct()
    {
        $this->code = 'bank';
        $this->title = MODULE_PAYMENT_BANK_TEXT_TITLE;
        $this->description = MODULE_PAYMENT_BANK_TEXT_DESCRIPTION;
        $this->sort_order = MODULE_PAYMENT_BANK_SORT_ORDER;
        $this->email_footer = MODULE_PAYMENT_BANK_TEXT_EMAIL_FOOTER;
        $this->enabled = MODULE_PAYMENT_BANK_STATUS;
    }

// class methods
    public function javascript_validation()
    {
        return false;
    }

    public function selection()
    {
        return array(
            'id' => $this->code,
            'module' => $this->title
        );
    }

    public function pre_confirmation_check()
    {
        return false;
    }

    public function confirmation()
    {
        return array('title' => MODULE_PAYMENT_BANK_TEXT_DESCRIPTION);
    }

    public function process_button()
    {
        return false;
    }

    public function before_process()
    {
        return false;
    }

    public function after_process()
    {
        global $order, $onepage;
        
        $mailer = tep_get_mailer();
        $mailer->addAddress($order->customer['email_address'], trim($order->customer['firstname'] . ' ' . $order->customer['lastname']));
        $mailer->Subject = sprintf(MODULE_PAYMENT_BANK_TEXT_EMAIL_SUBJECT, $onepage['info']['order_id']);
        $mailer->Body = MODULE_PAYMENT_BANK_TEXT_EMAIL_BODY;
        $attachment_file = $this->getEmailAttachment();
        $mailer->addAttachment($attachment_file, MODULE_PAYMENT_BANK_INVOICE_ATTACHMENT_FILENAME, 'base64', 'text/html; charset=' . (defined('CHARSET') ? CHARSET : 'UTF-8'));
        $mailer->send();
        @unlink($attachment_file);
        
        return false;
    }
    
    private function getEmailAttachment()
    {
        global $onepage, $currencies;
        $orders_id = $onepage['info']['order_id'];
        $content = require FILENAME_INVOICE_HTML;
        $filename = tempnam(sys_get_temp_dir(), 'Tmp');
        file_put_contents($filename, $content);
        return $filename;
    }

    public function get_error()
    {
        return false;
    }

    public function check()
    {
        if(!isset($this->check))
        {
            $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_BANK_STATUS'");
            $this->check = tep_db_num_rows($check_query);
        }
        return $this->check;
    }

    public function install()
    {
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Предоплата на счёт', 'MODULE_PAYMENT_BANK_STATUS', '1', 'Вы хотите использовать модуль Предоплата на счёт? 1 - да, 0 - нет', '6', '0', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Порядок сортировки.', 'MODULE_PAYMENT_BANK_SORT_ORDER', '0', 'Порядок сортировки модуля.', '6', '1', now())");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Получатель платежа', 'MODULE_PAYMENT_BANK_PAYMENT_RECEIVER', '" . tep_db_input(defined('STORE_NAME') ? STORE_NAME : '') . "', 'Если не указан, то будет использовано название магазина', '6', '2', now());");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Счёт олучателя', 'MODULE_PAYMENT_BANK_ACCOUNT', '', 'Введите ваш номер счёта', '6', '3', now());");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ЕГРПОУ', 'MODULE_PAYMENT_BANK_VATIN', '', 'Ваш код ЕГРПОУ', '6', '4', now());");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Название банка', 'MODULE_PAYMENT_BANK_BANK_NAME', '', 'Название банка, в котором обслуживается ваш счёт', '6', '5', now());");
        tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Код банка', 'MODULE_PAYMENT_BANK_BANK_CODE', '', 'Код банка (МФО), в котором обслуживается ваш счёт', '6', '6', now());");
    }

    public function remove()
    {
        tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys()
    {
        return array(
            'MODULE_PAYMENT_BANK_STATUS',
            'MODULE_PAYMENT_BANK_SORT_ORDER',
            'MODULE_PAYMENT_BANK_PAYMENT_RECEIVER',
            'MODULE_PAYMENT_BANK_ACCOUNT',
            'MODULE_PAYMENT_BANK_VATIN',
            'MODULE_PAYMENT_BANK_BANK_NAME',
            'MODULE_PAYMENT_BANK_BANK_CODE'
        );
    }
}