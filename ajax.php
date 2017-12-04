<?php

include_once __DIR__ . '/includes/application_top.php';

if(!empty($_POST['new_phone_number']) && !empty($_POST['customer'])){
    tep_db_query("UPDATE `customers` SET `customers_telephone`='{$_POST['new_phone_number']}' WHERE `customers_id`='{$_POST['customer']}'");
}

return false;