<?php

/*
  $Id: categories.php,v 1.7 21.05.2013 by Shopmakers
 */

if(PHP_SAPI == 'cli')
{
    die('This example should only be run from a Web Browser');
}

include_once __DIR__ . '/includes/application_top.php';
include_once __DIR__ . '/../includes/classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_EXACT);
$headStyle = array(
    'font' => array(
        'bold' => true,
    )
);
$headStyle2 = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'cccccc')
    )
);
// Set document properties
$objPHPExcel->getProperties()
        ->setCreator(STORE_NAME)
        ->setTitle("Покупатели");

$customers_groups_id_xls = isset($_GET['customers_groups_id_xls']) ? filter_var($_GET['customers_groups_id_xls'], FILTER_VALIDATE_INT, array('min_range' => 0)) : null;
if($customers_groups_id_xls)
{
    $customers_groups_sql = 'WHERE c.customers_groups_id = ' . $customers_groups_id_xls;
}

$history_query_raw = tep_db_query("SELECT 
	c.customers_id, 
	c.customers_firstname,  
	c.customers_lastname, 
	ab.entry_city, 
	c.customers_telephone, 
	ci.customers_info_date_account_created,
	c.customers_email_address,
	COUNT(o.customers_id) as number_orders
	FROM 
	customers c
	INNER JOIN address_book ab ON ab.customers_id = c.customers_id
	INNER JOIN customers_info ci ON ci.customers_info_id = c.customers_id
	AND ab.address_book_id = c.customers_default_address_id LEFT OUTER JOIN orders o ON o.customers_id = c.customers_id 
	" . $customers_groups_sql . "
	GROUP BY c.customers_email_address, c.customers_telephone order by c.customers_email_address, c.customers_telephone");

$listing = array();
while(($row = tep_db_fetch_array($history_query_raw)) !== false)
{
    $listing[] = $row;
}

$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

foreach(range('A','H') as $column_id)
{
    $sheet->getColumnDimension($column_id)->setAutoSize(true);
}

// Rename worksheet
$sheet->setTitle('Пользователи');
$sheet->setCellValue('A1', 'Код')
        ->setCellValue('B1', 'Имя')
        ->setCellValue('C1', 'Фамилия')
        ->setCellValue('D1', 'Город')
        ->setCellValue('E1', 'Телефон')
        ->setCellValue('F1', 'Email')
        ->setCellValue('G1', 'Количество заказов')
        ->setCellValue('H1', 'Дата регистрации');

$row = 2;

foreach($listing as $k => $v)
{
    $sheet->setCellValue('A' . $row, $v['customers_id'])
            ->setCellValue('B' . $row, $v['customers_firstname'])
            ->setCellValue('C' . $row, $v['customers_lastname'])
            ->setCellValue('D' . $row, $v['entry_city'])
            ->setCellValue('E' . $row, $v['customers_telephone'])
            ->setCellValue('F' . $row, $v['customers_email_address'])
            ->setCellValue('G' . $row, $v['number_orders'])
            ->setCellValue('H' . $row, $v['customers_info_date_account_created']);
    $row++;
}

// Redirect output to a client’s web browser (Excel2007)
if(isset($_GET['save']) && $_GET['save'] == true)
{
    $saveDestination = __DIR__ . '/temp';
    // $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save($saveDestination . '/customers.xls');
}
else
{
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="customers.xlsx"');
    header('Cache-Control: max-age=0');
    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');

    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0
    //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}

exit();