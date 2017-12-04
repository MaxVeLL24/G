<?php

/* @var $currencies \currencies */

?>
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex, nofollow" />
        <title><?php echo sprintf(INVOICE_HTML_TITLE, $orders_id); ?></title>
        <style type="text/css">
            html {
                background: #fff;
                color: #000;
                font: normal normal 11pt/1.4 'Tahoma', 'Arial', 'Verdana', sans-serif;
            }
            
            /* Структурная таблица */
            .structure-table {
                border: none;
                border-collapse: collapse;
                width: 100%;
            }
            .title-cell,
            .content-cell {
                padding: 20pt;
                vertical-align: top;
            }
            .title-cell {
                border-right: 2pt solid #000;
                font-weight: bold;
                text-align: center;
                width: 25%;
            }
            .row-notice .title-cell,
            .row-notice .content-cell {
                border-bottom: 2pt solid #000;
            }
            
            /* Подсказка как напечатать */
            #how-to-print-alert {
                border: 1pt dashed #ff6666;
                color: #e82a2a;
                margin-bottom: 15pt;
                padding: 8pt;
            }
        </style>
        <style type="text/css" media="screen">
            body {
                margin: auto;
                min-height: 297mm;
                padding: 10mm;
                width: 210mm;
            }
        </style>
        <style type="text/css" media="print">
            @page {
                margin: 10mm;
                size: 210mm 297mm;
            }
            body {
                margin: 0;
            }
            #how-to-print-alert {
                display: none;
            }
        </style>
        <script type="application/javascript">
            window.onload = function(){
                document.getElementById('how-to-print-alert').style.display = null;
            };
        </script>
    </head>
    <body>
        <div id="how-to-print-alert" role="alert" style="display: none;">
            <?php echo INVOICE_HTML_TEXT_PRINT_EXPLANATION; ?>
            <button type="button" onclick="window.print();"><?php echo INVOICE_HTML_TEXT_BUTTON_PRINT; ?></button>
        </div>
        <table class="structure-table">
            <tbody>
                <tr class="row-notice">
                    <td class="title-cell"><?php echo INVOICE_HTML_TEXT_NOTICE; ?></td>
                    <td class="content-cell">
                        <?php /* Получатель платежа */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_PAYMENT_RECEIVER; ?></b>
                        <?php echo defined('MODULE_PAYMENT_BANK_PAYMENT_RECEIVER') &&  MODULE_PAYMENT_BANK_PAYMENT_RECEIVER ? MODULE_PAYMENT_BANK_PAYMENT_RECEIVER : STORE_NAME; ?>
                        <br />
                        
                        <?php /* Номер счёта */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_BANK_ACCOUNT; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_ACCOUNT; ?>
                        <br />
                        
                        <?php /* ЕДРПОУ */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_VATIN; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_VATIN; ?>
                        <br />
                        
                        <?php /* Название банка */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_BANK_NAME; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_BANK_NAME; ?>
                        <br />
                        
                        <?php /* Код банка */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_BANK_CODE; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_BANK_CODE; ?>
                        <br />
                        <br />
                        
                        <?php /* Имя плательщика */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_PAYER; ?></b>
                        <?php echo tep_escape($order['customers_name']); ?>
                        <br />
                        
                        <?php /* Назначение платежа */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_PURPOSE_OF_PAYMENT; ?></b>
                        <?php echo sprintf(INVOICE_HTML_PURPOSE_OF_PAYMENT, $orders_id); ?>
                        <br />
                        
                        <?php /* Итого к оплате */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_TOTAL; ?></b>
                        <?php echo $currencies->format($order['value'], true, 'UAH'); ?>
                        <br />
                        <br />
                        
                        <?php /* Дата */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_DATE; ?></b>
                        <?php echo date('d.m.Y'); ?>
                    </td>
                </tr>
                <tr class="row-ticket">
                    <td class="title-cell"><?php echo INVOICE_HTML_TEXT_TICKET; ?></td>
                    <td class="content-cell">
                        <?php /* Получатель платежа */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_PAYMENT_RECEIVER; ?></b>
                        <?php echo defined('MODULE_PAYMENT_BANK_PAYMENT_RECEIVER') &&  MODULE_PAYMENT_BANK_PAYMENT_RECEIVER ? MODULE_PAYMENT_BANK_PAYMENT_RECEIVER : STORE_NAME; ?>
                        <br />
                        
                        <?php /* Номер счёта */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_BANK_ACCOUNT; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_ACCOUNT; ?>
                        <br />
                        
                        <?php /* ЕДРПОУ */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_VATIN; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_VATIN; ?>
                        <br />
                        
                        <?php /* Название банка */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_BANK_NAME; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_BANK_NAME; ?>
                        <br />
                        
                        <?php /* Код банка */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_BANK_CODE; ?></b>
                        <?php echo MODULE_PAYMENT_BANK_BANK_CODE; ?>
                        <br />
                        <br />
                        
                        <?php /* Имя плательщика */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_PAYER; ?></b>
                        <?php echo tep_escape($order['customers_name']); ?>
                        <br />
                        
                        <?php /* Назначение платежа */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_PURPOSE_OF_PAYMENT; ?></b>
                        <?php echo sprintf(INVOICE_HTML_PURPOSE_OF_PAYMENT, $orders_id); ?>
                        <br />
                        
                        <?php /* Итого к оплате */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_TOTAL; ?></b>
                        <?php echo $currencies->format($order['value'], true, 'UAH'); ?>
                        <br />
                        <br />
                        
                        <?php /* Дата */ ?>
                        <b><?php echo INVOICE_HTML_TEXT_DATE; ?></b>
                        <?php echo date('d.m.Y'); ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </body>
</html>