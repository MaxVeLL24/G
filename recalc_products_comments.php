<?php

include_once __DIR__ . '/includes/application_top.php';

if(PHP_SAPI !== 'cli')
{
    require FILENAME_FORBIDDEN;
}

$offset = 0;

while(true)
{
    $query = tep_db_query("select num, url from road where url regexp '\\/p-' limit {$offset}, 100");
    $num_rows = tep_db_num_rows($query);
    if(!$num_rows)
    {
        break;
    }
    
    $insert_data = array();
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $m = array();
        if(preg_match('/\/p-(\d+)\.html/', $row['url'], $m))
        {
            $insert_data[] = "({$row['num']}, {$m[1]})";
        }
    }
    
    if($insert_data)
    {
        tep_db_query("insert into road (num, products_id) values " . implode(', ', $insert_data) . " on duplicate key update products_id = values(products_id)");
    }
    
    $offset += $num_rows;
    if($num_rows < 100)
    {
        break;
    }
}

tep_db_query("create temporary table tmp (products_id int unsigned, products_comments_count int unsigned, primary key (products_id))");
tep_db_query("insert into tmp (products_id, products_comments_count) select r.products_id, count(r.products_id) from road as r inner join products as p on p.products_id = r.products_id where r.products_id is not null");
tep_db_query("insert into products (products_id, products_comments_count) select products_id, products_comments_count from tmp on duplicate key update products_comments_count = values(products_comments_count)");