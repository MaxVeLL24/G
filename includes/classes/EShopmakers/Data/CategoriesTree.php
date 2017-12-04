<?php

namespace EShopmakers\Data;

abstract class CategoriesTree
{
    public static $categories_children = array(), $categories_parents = array(), $categories_names = array(), $categories_status = array();
    protected static $loaded = false;
    public static function loadCategories()
    {
        if(self::$loaded)
        {
            return;
        }
        $query_string = <<<SQL
SELECT
    c.categories_id,
    c.parent_id,
    c.categories_status,
    cd.categories_name
FROM categories AS c
INNER JOIN categories_description AS cd
ON c.categories_id = cd.categories_id AND cd.language_id = {$_SESSION['languages_id']}
ORDER BY c.sort_order ASC, cd.categories_name ASC
SQL;
        $query = tep_db_query($query_string);
        if(!tep_db_num_rows($query))
        {
            return;
        }
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            // Родитель - потомок
            if(empty(self::$categories_children[$row['parent_id']]))
            {
                self::$categories_children[$row['parent_id']] = array();
            }
            self::$categories_children[$row['parent_id']][] = $row['categories_id'];
            // Потомок - родитель
            self::$categories_parents[$row['categories_id']] = $row['parent_id'];
            // Имя
            self::$categories_names[$row['categories_id']] = $row['categories_name'];
            // Статус
            self::$categories_status[$row['categories_id']] = $row['categories_status'];
        }
        self::$loaded = true;
    }
    public static function getAllChildren($category_id)
    {
        self::loadCategories();
        if(!empty(self::$categories_children[$category_id]))
        {
            return self::$categories_children[$category_id];
        }
        return array();
    }
    public static function getParentsChain($category_id)
    {
        self::loadCategories();
        $parent_chain = array($category_id);
        while(!empty(self::$categories_parents[$category_id]))
        {
            $category_id = self::$categories_parents[$category_id];
            $parent_chain[] = $category_id;
        }
        return array_reverse($parent_chain);
    }
    public static function filterByStatus($categories_ids, $search_status)
    {
        self::loadCategories();
        if(!is_array($categories_ids))
        {
            return array();
        }
        foreach($categories_ids as $key => $value)
        {
            if(self::$categories_status[$value] != $search_status)
            {
                unset($categories_ids[$key]);
            }
        }
        return $categories_ids;
    }
}