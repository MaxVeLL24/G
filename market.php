<?php
/*
1. Язык, в котором отдаётся xml, определяется по умолчанию или задаётся в адресной строке.
2. Валюта, в которой отдаются цены, определяется по умолчанию или задаётся в адресной строке.
	 Т.е. можно определять для сторонних сайтов ссылки вида:
	 http://<domain>/xml_yml_catalog.php?language=ru&currency=RUR
3. Изготовителя может быть задан, а может и не быть задан;
4. Короткие описания могут быть установлены, а могут и не быть установлены;
5. Поле xml-флага в таблице товаров может существовать, а может и не существовать.
6. Все валюты и их курсы формируются автоматически;
7. Все ссылки на товар и картинки преобразуются в соответсвии с правилами (urlencode()),
	 что решает проблему с использованием нестандарных символов в ссылках.
8. Поддержка xml-флага для категорий, содержащих только товары;
9. Поддержка нескольких категорий для товара;
10. Поддержка доступа по паролю (логин/пароль можно задать в админе или определить здесь,
		в константах). Константы YML_AUTH_USER, YML_AUTH_PW;
11. Доставка включена или нет определяется константой доступа по паролю (логин/пароль можно
		задать в админе или определить здесь, в константах). Константа YML_DELIVERYINCLUDED;
12. Поддерживает типы продуктов (страницы отображения информации о товаре для разных типов
		товара);
13. Поддержка <offer available; Константа YML_AVAILABLE может принимать одно из трёх 
		значений: "true", "false" и "stock". В последнем случае доступность товара определяется
		по наличию его на складе (поле products_quantity);
14. Добавлены константы YML_NAME & YML_COMPANY;
15. Добавлены константы YML_REFERER (для тех, кто не умеет отслеживать заходы через 
		партнёрку);
16. Добавлена опция "убирания" тегов (константа YML_STRIP_TAGS);
17. Добавлена опция перекодирования в utf-8 (константа YML_UTF8);
18. Поддержка специальных цен;
19. Кеширование производителей;


-- TODO:
1. Поддержка xml-флага для категорий, включая вложенные категории;

-- Константы в админе:
insert into configuration values ('', 'Название магазина', 'YML_NAME', '', 'Название магазина для Яндекс-Маркет. Если поле пустое, то используется STORE_NAME.', 999, 2, NULL, NOW(), NULL,  NULL);
insert into configuration values ('', 'Название компании', 'YML_COMPANY', '', 'Название компании для Яндекс-Маркет. Если поле пустое, то используется STORE_OWNER.', 999, 2, NULL, NOW(), NULL,  NULL);
insert into configuration values ('', 'Доставка включена', 'YML_DELIVERYINCLUDED', 'true', 'Доставка включена в стоимость товара?', 999, 2, NULL, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', '\false\'),');
insert into configuration values ('', 'Товар в наличии', 'YML_AVAILABLE', 'stock', 'Товар в наличии или под заказ?', 999, 2, NULL, NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\', \'stock\'),');
insert into configuration values ('', 'Логин', 'YML_AUTH_USER', '', 'Логин для доступа к YML', 999, 2, NULL, NOW(), NULL, NULL);
insert into configuration values ('', 'Пароль', 'YML_AUTH_PW', '', 'Пароль для доступа к YML', 999, 2, NULL, NOW(), NULL, NULL);
insert into configuration values ('', 'Ссылка', 'YML_REFERER', 'stock', 'Добавить в адрес товара параметр с ссылкой на User agent или ip?', 999, 2, NULL, NOW(), NULL, 'zen_cfg_select_option(array(\'false\', \'ip\', \'agent\'),');
insert into configuration values ('', 'Теги', 'YML_STRIP_TAGS', 'true', 'Убирать html-теги в строках?', 999, 2, NULL, NOW(), NULL, 'zen_cfg_select_option(array(\'false\', \'true\'),');
insert into configuration values ('', 'Перекодировка в UTF-8', 'YML_UTF8', 'true', 'Перекодировать в UTF-8?', 999, 2, NULL, NOW(), NULL, 'zen_cfg_select_option(array(\'false\', \'true\'),');

insert into configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) values ('', 'Яндекс-Маркет', 'Конфигурирование Яндекс-Маркет', '99', '1');
update configuration set configuration_group_id=last_insert_id() where configuration_group_id='999';
*/

 include_once __DIR__ . '/includes/application_top.php';
// $currency = 'USD';
 
if (!defined('YML_NAME')) define('YML_NAME','');
if (!defined('YML_COMPANY')) define('YML_COMPANY','');
if (!defined('YML_AVAILABLE')) define('YML_AVAILABLE','stock');
if (!defined('YML_DELIVERYINCLUDED')) define('YML_DELIVERYINCLUDED','false');
if (!defined('YML_AUTH_USER')) define('YML_AUTH_USER','');
if (!defined('YML_AUTH_PW')) define('YML_AUTH_PW','');
if (!defined('YML_REFERER')) define('YML_REFERER','false');
if (!defined('YML_STRIP_TAGS')) define('YML_STRIP_TAGS','true');
if (!defined('YML_UTF8')) define('YML_UTF8','false');

if(XML_MODULE_ENABLED == 'true') {

$yml_referer = (YML_REFERER == 'false' ? "" : (YML_REFERER == 'ip' ? '&amp;ref_ip=' . $_SERVER["REMOTE_ADDR"] : '&amp;ref_ua=' . $_SERVER["HTTP_USER_AGENT"]));

if (YML_AUTH_USER != "" && YML_AUTH_PW != "") {
	if (!isset($PHP_AUTH_USER) || $PHP_AUTH_USER != YML_AUTH_USER || $PHP_AUTH_PW != YML_AUTH_PW) {
		header('WWW-Authenticate: Basic realm="Realm-Name"');
		header("HTTP/1.0 401 Unauthorized");
		die;
	} 
}

$charset = (YML_UTF8 == 'true') ? 'utf-8' : CHARSET;

$manufacturers_array = array();

header('Content-Type: text/xml');

echo "<?xml version=\"1.0\" encoding=\"" . $charset ."\"?><!DOCTYPE yml_catalog SYSTEM \"shops.dtd\">\n" .
		 "<yml_catalog date=\"" . date('Y-m-d H:i') . "\">\n\n" .
		 "<shop>\n" .
		 "<name>" . _clear_string((YML_NAME == "" ? STORE_NAME : YML_NAME)) ."</name>\n" .
		 "<company>" . _clear_string((YML_COMPANY == "" ? STORE_OWNER : YML_COMPANY)) . "</company>\n" .
		 "<url>" . HTTP_SERVER . "/</url>\n\n";



echo "  <currencies>\n";
foreach($currencies->currencies as $code => $v){
//	echo "    <currency id=\"" . $code . "\" rate=\"" . number_format(1/$v["value"],4) . "\"/>\n";
	echo "    <currency id=\"" . $code . "\" rate=\"" . number_format($v["value"],4) . "\"/>\n";
}
echo "  </currencies>\n\n";

echo "  <categories>\n";
$categories_to_xml_query = tep_db_query('describe ' . TABLE_CATEGORIES . ' categories_to_xml');

// raid подкатегории!!!   
$r_subcats = "c.parent_id !=0 ";  
$categories_disable = array(); 
$categories_disabled_query = tep_db_query("select c.categories_id from " . TABLE_CATEGORIES . " c where c.categories_to_xml = 0");
while ($categories_disabled = tep_db_fetch_array($categories_disabled_query)) {
 //   $r_current_subcats = tep_get_categories('',$categories_disabled['categories_id']); 
    $r_current_subcats = tep_make_cat_list($categories_disabled['categories_id']);
    $categories_disable[] = $categories_disabled['categories_id'];
    for($i=0;$i<count($r_current_subcats);$i++) {
      $categories_disable[] = $r_current_subcats[$i][id];
    } 
}
// raid подкатегории!!!  

$categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id " .((tep_db_num_rows($categories_to_xml_query) > 0) ? ", c.categories_to_xml " : "") . "
														from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
														where c.categories_status = '1'
															and c.categories_id = cd.categories_id
															and cd.language_id='" . (int)$languages_id ."'
														order by c.parent_id, c.sort_order, cd.categories_name"
													);

while ($categories = tep_db_fetch_array($categories_query)) {
	if (!isset($categories["categories_to_xml"]) || $categories["categories_to_xml"] == 1) {
		echo "<category id=\"" . $categories["categories_id"] . "\"" .
				 (($categories["parent_id"] == "0") ? ">" : " parentId=\"" . $categories["parent_id"] . "\">" ) .
				 _clear_string($categories["categories_name"]) .
				 "</category>\n";
	} else {
		$categories_disable[] = $categories["categories_id"];
	}
}                    
echo "  </categories>\n";

echo"<offers>\n";
$products_short_desc_query = tep_db_query('describe ' . TABLE_PRODUCTS_DESCRIPTION . ' products_info');
$products_to_xml_query = tep_db_query('describe ' . TABLE_PRODUCTS . ' products_to_xml');
$products_sql = "select p.products_id, p.products_model, p.products_quantity, p.products_images, p.products_price, products_tax_class_id, p.manufacturers_id, pd.products_name, p2c.categories_id, pd.products_description" .
								((tep_db_num_rows($products_short_desc_query) > 0) ? ", pd.products_info " : " ") . ", l.code as language " .
								"from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_LANGUAGES . " l
								 where p.products_id = pd.products_id
									 and p.products_status = 1" .
									 ((tep_db_num_rows($products_to_xml_query) > 0) ? " and p.products_to_xml = 1" : "") .
								 " and p.products_id = p2c.products_id
									 and pd.language_id = " . (int)$languages_id . "
									 and p.products_price > 0
									 and p.products_quantity > 0
									 and l.languages_id=pd.language_id
								 order by pd.products_name";
$products_query = tep_db_query($products_sql);
$prev_prod['products_id'] = 0;
$cats_id = array();

for ($iproducts = 0, $nproducts = tep_db_num_rows($products_query); $iproducts <= $nproducts; $iproducts++) {
	$products = tep_db_fetch_array($products_query);
	if ($prev_prod['products_id'] == $products['products_id']) {
		if (!in_array($products['categories_id'], $categories_disable)) {
			$cats_id[] = $products['categories_id'];
		}
	} else {
		if (sizeof($cats_id) > 0) {
		$available = "false";
			switch(YML_AVAILABLE) {
				case "stock":
					if($prev_prod['products_quantity'] > 0)
						$available = "true";
					else
						$available = "false";
					break;
				case "false":
				case "true":
					$available = YML_AVAILABLE;
					break;
			}

			if ($products_price = tep_get_products_special_price($prev_prod['products_id'])) {
			} else {
				$products_price = $prev_prod['products_price'];
			}
			

			echo "<offer id=\"" . $prev_prod['products_id'] . "\" available=\"" . $available . "\">\n" .
					 "  <url>" . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $prev_prod['products_id'] . '&amp;language=' . $prev_prod['language'] . $yml_referer, 'NONSSL', false) . "</url>\n" .
					 "  <price>" . number_format(tep_round(tep_add_tax($products_price, tep_get_tax_rate($prev_prod['products_tax_class_id']))*$currencies->currencies['UAH']['value'],$currencies->currencies['UAH']['decimal_places']),$currencies->currencies['UAH']['decimal_places'],'.','') . "</price>\n" .
					 "  <currencyId>UAH</currencyId>\n";
			for ($ic=0,$nc=sizeof($cats_id); $ic < $nc; $ic++) {
				// для prom.ua: 
				if($ic==0) echo "  <categoryId>" . $cats_id[$ic] . "</categoryId>\n";
				
				// для маркета: echo "  <categoryId>" . $cats_id[$ic] . "</categoryId>\n";
			}

			if(tep_not_null($prev_prod['products_images'])) {
			  $images_array = explode(';',$prev_prod['products_images']);
			  foreach($images_array as $curr_img) {
				  echo "<picture>" . dirname(HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_IMAGES . $curr_img) . "/" . urlencode(basename($curr_img)) . "</picture>\n";
				} 
			}
				echo	 (YML_DELIVERYINCLUDED == "true" ? "  <deliveryIncluded/>\n" : "") .
					 "  <name>" . _clear_string($prev_prod['products_name']) . "</name>\n";
			if ($prev_prod['manufacturers_id'] != 0) {
				if(!isset($manufacturers_array[$prev_prod['manufacturers_id']])) {
					$manufacturer_query = tep_db_query("select manufacturers_name
																							from " . TABLE_MANUFACTURERS . "
																							where manufacturers_id ='" . $prev_prod['manufacturers_id'] . "'");
					$manufacturer = tep_db_fetch_array($manufacturer_query);
					$manufacturers_array[$prev_prod['manufacturers_id']] = $manufacturer['manufacturers_name'];
				}
//				echo "  <vendor>" . _clear_string($manufacturers_array[$prev_prod['manufacturers_id']]) . "</vendor>\n";
			} 
			if (isset($prev_prod['products_description']) && tep_not_null($prev_prod['products_description'])) {
				echo "  <description>" . _clear_string($prev_prod['products_description']) . "</description>\n";
			} elseif (tep_not_null($prev_prod['products_info'])) {
				echo "  <description>" . _clear_string($prev_prod['products_info']) . "</description>\n";
			}
      
// -------------------------- АТРИБУТЫ!!!-------------------------------------

      $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name, popt.products_options_type, popt.products_options_length, popt.products_options_comment from " . TABLE_PRODUCTS_OPTIONS . " popt, " . TABLE_PRODUCTS_ATTRIBUTES . " pa where pa.products_id='" . (int)$prev_prod['products_id'] . "' and pa.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_sort_order, popt.products_options_name");
      while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
        $many_vals = '';
            $selected_attribute = false;
        		$products_options_array = array();
        		$products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " pa, " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov where pa.products_id = '" . (int)$prev_prod['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "' order by pa.products_options_sort_order");
        		while ($products_options = tep_db_fetch_array($products_options_query)) {
          		$products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
        		}
      	echo "<param name=\"".$products_options_name['products_options_name']."\">";
        
                  if($products_options_array[1]['text']!='') {
                       foreach($products_options_array as $poa_val) {
                         $many_vals .= $poa_val['text'].', ';
                       }
                       echo substr($many_vals,0,-2);
                  } else echo $products_options_array[0]['text'];
        echo "</param>";      
      } 
      
      
      
			echo "</offer>\n\n";
		}
		$prev_prod = $products;
		$cats_id = array();
		if (!in_array($products['categories_id'], $categories_disable)) {
			$cats_id[] = $products['categories_id'];
		}
	}
}
echo "</offers>\n" .
		 "</shop>\n" .
		 "</yml_catalog>\n";
} 

	function _clear_string($str) {
		if (YML_STRIP_TAGS == 'true') {
			$str = strip_tags($str);
		}
		if (YML_UTF8 == 'true')
			$str = iconv(CHARSET, "UTF-8", $str);
		return htmlspecialchars($str, ENT_QUOTES);
	}
  
 
?>