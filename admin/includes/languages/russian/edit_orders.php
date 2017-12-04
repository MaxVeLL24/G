<?php
/*
  $Id: edit_orders.php,v 1.72 2003/08/07 00:28:44 jwh Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce
  
  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Редактирование заказа');
define('HEADING_TITLE_NUMBER', 'номер');
define('HEADING_TITLE_DATE', 'от');
define('HEADING_SUBTITLE', 'После изменения заказа не забудьте нажать кнопку "Обновить" для сохранения изменений.');
define('HEADING_TITLE_SEARCH', 'Код заказа:');
define('HEADING_TITLE_STATUS', 'Статус:');
define('ADDING_TITLE', 'Добавить товар к заказу');

define('HINT_UPDATE_TO_CC', '<font color="#FF0000">Совет: </font>Установите способ оплаты "Кредитная карточка" для получения дополнительных сведений о платеже.');
define('HINT_DELETE_POSITION', '<font color="#FF0000">Совет: </font>Чтобы удалить товар из заказа, установите количество "0" напротив нужного товара.');
define('HINT_TOTALS', '');
//define('HINT_TOTALS', '<font color="#FF0000">Hint: </font>Feel free to give discounts by adding negative amounts to the list.<br>Fields with "0" values are deleted when updating the order (exception: shipping).');
define('HINT_PRESS_UPDATE', 'Не забудьте нажать кнопку "Обновить" для сохранения изменений.');

define('TABLE_HEADING_COMMENTS', 'Комментарий');
define('TABLE_HEADING_CUSTOMERS', 'Покупатели');
define('TABLE_HEADING_ORDER_TOTAL', 'Всего');
define('TABLE_HEADING_DATE_PURCHASED', 'Дата заказа');
define('TABLE_HEADING_DELETE', 'Удалить');
define('TABLE_HEADING_STATUS', 'Новый статус');
define('TABLE_HEADING_ACTION', 'Действие');
define('TABLE_HEADING_QUANTITY', 'Количество');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Код');
define('TABLE_HEADING_PRODUCTS', 'Товар');
define('TABLE_HEADING_TAX', 'Налог %');
define('TABLE_HEADING_TOTAL', 'Всего');
define('TABLE_HEADING_UNIT_PRICE', 'Стоимость (без налога)');
define('TABLE_HEADING_UNIT_PRICE_TAXED', 'Стоимость (с налогом)');
define('TABLE_HEADING_TOTAL_PRICE', 'Всего (без налога)');
define('TABLE_HEADING_TOTAL_PRICE_TAXED', 'Всего (с налогом)');
define('TABLE_HEADING_TOTAL_MODULE', 'Общая стоимость заказа');
define('TABLE_HEADING_TOTAL_AMOUNT', 'Сумма');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Покупатель уведомлён');
define('TABLE_HEADING_DATE_ADDED', 'Дата');

define('ENTRY_CUSTOMER', 'Покупатель');
define('ENTRY_CUSTOMER_NAME', 'Имя');
define('ENTRY_CUSTOMER_COMPANY', 'Компания');
define('ENTRY_CUSTOMER_ADDRESS', 'Адрес покупателя');
define('ENTRY_ADDRESS', 'Адрес');
define('ENTRY_CUSTOMER_SUBURB', 'Район');
define('ENTRY_CUSTOMER_CITY', 'Город');
define('ENTRY_CUSTOMER_STATE', 'Регион');
define('ENTRY_CUSTOMER_POSTCODE', 'Почтовый индекс');
define('ENTRY_CUSTOMER_COUNTRY', 'Страна');
define('ENTRY_CUSTOMER_PHONE', 'Телефон');
define('ENTRY_CUSTOMER_FAX', 'Факс');
define('ENTRY_CUSTOMER_EMAIL', 'E-Mail');

define('ENTRY_SOLD_TO', 'Покупатель:');
define('ENTRY_DELIVERY_TO', 'Доставка:');
define('ENTRY_SHIP_TO', 'Адрес доставки:');
define('ENTRY_SHIPPING_ADDRESS', 'Адрес доставки');
define('ENTRY_SHIPPING_SUBURB', 'Отделение транспортной компании:');
define('ENTRY_BILLING_ADDRESS', 'Адрес покупателя');
define('ENTRY_PAYMENT_METHOD', 'Способ оплаты:');
define('ENTRY_CREDIT_CARD_TYPE', 'Тип карточки:');
define('ENTRY_CREDIT_CARD_OWNER', 'Владелец карточки:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Номер карточки:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Действительна до:');
define('ENTRY_SUB_TOTAL', 'Стоимость товара:');
define('ENTRY_TAX', 'Налог:');
define('ENTRY_SHIPPING', 'Доставка:');
define('ENTRY_TOTAL', 'Всего:');
define('ENTRY_DATE_PURCHASED', 'Дата покупки:');
define('ENTRY_STATUS', 'Статус заказа:');
define('ENTRY_DATE_LAST_UPDATED', 'последнее обновление:');
define('ENTRY_NOTIFY_CUSTOMER', 'Уведомить покупателя:');
define('ENTRY_NOTIFY_COMMENTS', 'Отправить комментарий:');
define('ENTRY_PRINTABLE', 'Распечатать счёт');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Удаление заказа');
define('TEXT_INFO_DELETE_INTRO', 'Вы действительно хотите удалить данный заказ?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Пересчитать количество');
define('TEXT_DATE_ORDER_CREATED', 'Дата создания заказа:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Последнее изменение:');
define('TEXT_DATE_ORDER_ADDNEW', 'Добавить новый товар');
define('TEXT_INFO_PAYMENT_METHOD', 'Способ оплаты:');

define('TEXT_ALL_ORDERS', 'Всего заказы');
define('TEXT_NO_ORDER_HISTORY', 'Заказ не найден');

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Ваш заказ был обновлён');
define('EMAIL_TEXT_ORDER_NUMBER', 'Номер заказа:');
define('EMAIL_TEXT_INVOICE_URL', 'Подробная информация о заказе:');
define('EMAIL_TEXT_DATE_ORDERED', 'Дата заказа:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Спасибо за Ваш заказ!' . '<br><br>' . 'Статус Вашего заказа был изменён.' . '<br><br>' . 'Новый статус: %s' . '<br><br>');
define('EMAIL_TEXT_STATUS_UPDATE2', 'Если у Вас есть вопросы, задайте их нам в ответном письме.' . '<br><br>' . 'С уважением, ' . STORE_NAME . '<br>');
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Комментарии к Вашему заказу:' . '<br><br>%s<br><br>');

define('ERROR_ORDER_DOES_NOT_EXIST', 'Ошибка: Заказ не найден.');
define('SUCCESS_ORDER_UPDATED', 'Успешно: Заказ был успешно обновлён.');
define('WARNING_ORDER_NOT_UPDATED', 'Предупреждение: Никаких изменений сделано не было.');

define('ADDPRODUCT_TEXT_CATEGORY_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_PRODUCT', 'Выберите товар');
define('ADDPRODUCT_TEXT_PRODUCT_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_SELECT_OPTIONS', 'Выберите опцию');
define('ADDPRODUCT_TEXT_OPTIONS_CONFIRM', 'OK');
define('ADDPRODUCT_TEXT_OPTIONS_NOTEXIST', 'У товара нет опций, пропускаем...');
define('ADDPRODUCT_TEXT_CONFIRM_QUANTITY', 'количество данного товара');
define('ADDPRODUCT_TEXT_CONFIRM_ADDNOW', 'Добавить');
define('ADDPRODUCT_TEXT_STEP', 'Шаг');
define('ADDPRODUCT_TEXT_STEP1', ' &laquo; Выберите раздел. ');
define('ADDPRODUCT_TEXT_STEP2', ' &laquo; Выберите товар. ');
define('ADDPRODUCT_TEXT_STEP3', ' &laquo; Выберите опцию. ');

define('MENUE_TITLE_CUSTOMER', '1. Данные покупателя');
define('MENUE_TITLE_PAYMENT', '2. Способ оплаты');
define('MENUE_TITLE_ORDER', '3. Заказанные товары');
define('MENUE_TITLE_TOTAL', '4. Доставка и сумма');
define('MENUE_TITLE_STATUS', '5. Статус и уведомления');
define('MENUE_TITLE_UPDATE', '6. Обновить данные');

define('EMAIL_ACC_DISCOUNT_INTRO_OWNER', 'Один из ваших клиентов достиг предела накопительной скидки и был переведен в новую группу. ' . "\n\n" . 'Детали:');
define('EMAIL_TEXT_LIMIT', 'Достигнутый предел: ');
define('EMAIL_TEXT_CURRENT_GROUP', 'Новая группа: ');
define('EMAIL_TEXT_DISCOUNT', 'Скидка: ');
define('EMAIL_ACC_SUBJECT', 'Накопительная скидка');
define('EMAIL_ACC_INTRO_CUSTOMER', 'Поздравляем, Вы получили новую накопительную скидку. Все детали ниже:');
define('EMAIL_ACC_FOOTER', 'Теперь Вы можете сэкономить, делая покупки в нашем интернет-магазине.');

define('EMAIL_TEXT_CUSTOMER_NAME', 'Покупатель:');
define('EMAIL_TEXT_CUSTOMER_EMAIL_ADDRESS', 'Email:');
define('EMAIL_TEXT_CUSTOMER_TELEPHONE', 'Телефон:');

define('TEXT_ORDER_COMMENTS', 'Комментарий к заказу');

?>