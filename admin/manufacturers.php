<?php

include_once __DIR__ . '/includes/application_top.php';

/* @var $messageStack \messageStack */

$action = !empty($_GET['action']) ? $_GET['action'] : null;

// Редактировать производителя
if($action === 'edit')
{
    $mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT, array('min_range' => 1));
    if($mID === false)
    {
        $messageStack->add_session(MESSAGE_MANUFACTURER_DOES_NOT_EXISTS);
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
    }
    
    // Языки
    $query = tep_db_query("SELECT * FROM languages");
    $_languages = array();
    while(($row = tep_db_fetch_array($query)) !== false)
    {
        $_languages[] = $row;
    }
    
    // Выгрузить существующую запись
    if($mID)
    {
        // Производитель
        $query = tep_db_query("SELECT * FROM manufacturers WHERE manufacturers_id = " . $mID);
        if(!tep_db_num_rows($query))
        {
            $messageStack->add_session(MESSAGE_MANUFACTURER_DOES_NOT_EXISTS);
            tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
        }
        $manufacturer = tep_db_fetch_array($query);
        if($manufacturer['manufacturers_image'] && is_file(DIR_FS_CATALOG_IMAGES . $manufacturer['manufacturers_image']))
        {
            // Вес изображения
            $manufacturer['image_size'] = filesize(DIR_FS_CATALOG_IMAGES . $manufacturer['manufacturers_image']);
            $exponent = floor(log($manufacturer['image_size'], 1024));
            if($exponent >= 3)
            {
                $manufacturer['image_size_units'] = GIGABYTES;
            }
            elseif($exponent >= 2)
            {
                $manufacturer['image_size_units'] = MEGABYTES;
            }
            elseif($exponent >= 1)
            {
                $manufacturer['image_size_units'] = KILOBYTES;
            }
            else
            {
                $manufacturer['image_size_units'] = BYTES;
            }
            $manufacturer['image_size'] = round($manufacturer['image_size'] / pow(1024, $exponent), 2);
            
            // Размер изображения
            $image_size = getimagesize(DIR_FS_CATALOG_IMAGES . $manufacturer['manufacturers_image']);
            if($image_size)
            {
                list($manufacturer['image_width'], $manufacturer['image_height']) = $image_size;
            }
        }
        
        // Описание производителя
        $query = tep_db_query("SELECT * FROM manufacturers_info WHERE manufacturers_id = " . $mID);
        $manufacturers_info = array();
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $manufacturers_info[$row['languages_id']] = $row;
        }
    }
    
    // Сохранить данные
    if(!empty($_POST))
    {
        if(empty($mID))
        {
            tep_db_query("INSERT INTO manufacturers SET manufacturers_name = '" . tep_db_input(empty($_POST['manufacturers_name']) ? '' : trim($_POST['manufacturers_name'])) . "', status = " . (filter_input(INPUT_POST, 'status', FILTER_VALIDATE_BOOLEAN) ? 1 : 0) . ", date_added = NOW()");
            $mID = tep_db_insert_id();
        }
        else
        {
            tep_db_query("UPDATE manufacturers SET manufacturers_name = '" . tep_db_input(empty($_POST['manufacturers_name']) ? '' : trim($_POST['manufacturers_name'])) . "', status = " . (filter_input(INPUT_POST, 'status', FILTER_VALIDATE_BOOLEAN) ? 1 : 0) . ", last_modified = NOW() WHERE manufacturers_id = " . $mID);
        }
        foreach($_languages as $_language)
        {
            tep_db_query("REPLACE INTO manufacturers_info SET manufacturers_id = {$mID}, languages_id = {$_language['languages_id']}, manufacturers_description = '" . tep_db_input(empty($_POST['manufacturers_description'][$_language['languages_id']]) ? '' : trim($_POST['manufacturers_description'][$_language['languages_id']])) . "', manufacturers_title = '" . tep_db_input(empty($_POST['manufacturers_title'][$_language['languages_id']]) ? '' : trim($_POST['manufacturers_title'][$_language['languages_id']])) . "', manufacturers_meta_keywords = '" . tep_db_input(empty($_POST['manufacturers_meta_keywords'][$_language['languages_id']]) ? '' : trim($_POST['manufacturers_meta_keywords'][$_language['languages_id']])) . "', manufacturers_meta_description = '" . tep_db_input(empty($_POST['manufacturers_meta_description'][$_language['languages_id']]) ? '' : trim($_POST['manufacturers_meta_description'][$_language['languages_id']])) . "', name_header = '" . tep_db_input(empty($_POST['name_header'][$_language['languages_id']]) ? '' : trim($_POST['name_header'][$_language['languages_id']])) . "'");
        }
        $messageStack->add_session(MESSAGE_SAVED, 'success');
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $mID . '&action=edit'));
    }
}
// Включить/выключить производителя
elseif($action === 'switch_status')
{
    $mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT, array('min_range' => 1));
    if($mID)
    {
        $query = tep_db_query("UPDATE manufacturers SET status = !status, last_modified = NOW() WHERE manufacturers_id = " . $mID);
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'action=go_find&mID=' . $mID));
    }
    tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
}
// Найти конкретного производителя в списке
elseif($action === 'go_find')
{
    $mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT, array('min_range' => 1));
    if($mID)
    {
        $query = tep_db_query("SELECT COUNT(*) AS `count` FROM manufacturers WHERE manufacturers_id < " . $mID);
        $result = tep_db_fetch_array($query);
        $result = floor($result['count'] / MAX_DISPLAY_SEARCH_RESULTS);
        if($result > 1)
        {
            tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'page=' . $result) . '#manufacturer_' . $mID);
        }
        else
        {
            tep_redirect(tep_href_link(FILENAME_MANUFACTURERS) . '#manufacturer_' . $mID);
        }
    }
    tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
}
// Удалить производителя
elseif($action === 'delete')
{
    $mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT, array('min_range' => 1));
    if($mID && filter_input(INPUT_GET, 'confirm', FILTER_VALIDATE_BOOLEAN))
    {
        tep_db_query("DELETE FROM manufacturers WHERE manufacturers_id = " . $mID);
        tep_db_query("DELETE FROM manufacturers_info WHERE manufacturers_id = " . $mID);
        $messageStack->add_session(MESSAGE_MANUFACTURER_DELETED, 'success');
    }
    tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
}
// Удалить изображение
elseif($action === 'remove_image')
{
    $mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT, array('min_range' => 1));
    if($mID)
    {
        if(filter_input(INPUT_GET, 'confirm', FILTER_VALIDATE_BOOLEAN))
        {
            $query = tep_db_query("SELECT manufacturers_image FROM manufacturers WHERE manufacturers_id = " . $mID);
            if(tep_db_num_rows($query))
            {
                $image_filename = tep_db_fetch_array($query);
                $image_filename = $image_filename['manufacturers_image'];
                if($image_filename && is_file(DIR_FS_CATALOG_IMAGES . $image_filename))
                {
                    @unlink(DIR_FS_CATALOG_IMAGES . $image_filename);
                }
                tep_db_query("UPDATE manufacturers SET manufacturers_image = NULL, last_modified = NOW() WHERE manufacturers_id = " . $mID);
            }
            $messageStack->add_session(MESSAGE_IMAGE_DELETED, 'success');
        }
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $mID . '&action=edit'));
    }
    tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
}
// Загрузить изображение
elseif($action === 'upload_image')
{
    $mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT, array('min_range' => 1));
    // Проверям, что такой производитель действительно существует
    if($mID)
    {
        $query = tep_db_query("SELECT COUNT(*) AS `count` FROM manufacturers WHERE manufacturers_id = " . $mID . " LIMIT 1");
        $result = tep_db_fetch_array($query);
        if(!$result['count'])
        {
            unset($mID);
        }
    }
    if(!empty($mID))
    {
        if(isset($_FILES['manufacturers_image']))
        {
            if($_FILES['manufacturers_image']['error'] === UPLOAD_ERR_OK)
            {
                $image_size = getimagesize($_FILES['manufacturers_image']['tmp_name']);
                if($image_size && ($image_size[2] === IMAGETYPE_JPEG || $image_size[2] === IMAGETYPE_PNG || $image_size[2] === IMAGETYPE_GIF))
                {
                    $filename = makeUniqueFileName($_FILES['manufacturers_image']['name'], DIR_FS_CATALOG_IMAGES);
                    if(move_uploaded_file($_FILES['manufacturers_image']['tmp_name'], DIR_FS_CATALOG_IMAGES . $filename))
                    {
                        // Удалить с диска файл со старым изображением, если есть
                        $query = tep_db_query("SELECT manufacturers_image FROM manufacturers WHERE manufacturers_id = " . $mID);
                        $result = tep_db_fetch_array($query);
                        if($result['manufacturers_image'] && is_file(DIR_FS_CATALOG_IMAGES . $result['manufacturers_image']))
                        {
                            @unlink(DIR_FS_CATALOG_IMAGES . $result['manufacturers_image']);
                        }
                        
                        // Записать в базу имя файла нового изображения
                        tep_db_query("UPDATE manufacturers SET manufacturers_image = '" . tep_db_input($filename) . "', last_modified = NOW() WHERE manufacturers_id = " . $mID);
                        $messageStack->add_session(MESSAGE_WAS_UPLOADED_SUCCESSFULLY, 'success');
                    }
                    else
                    {
                        $messageStack->add_session(MESSAGE_IMAGE_UPLOADING_ERROR);
                    }
                }
                else
                {
                    $messageStack->add_session(MESSAGE_BAD_IMAGE_ERROR);
                }
            }
            else
            {
                $messageStack->add_session(MESSAGE_IMAGE_UPLOADING_ERROR);
            }
        }
        else
        {
            $messageStack->add_session(MESSAGE_IMAGE_NOT_UPLOADED, 'info');
        }
        tep_redirect(tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $mID . '&action=edit'));
    }
    tep_redirect(tep_href_link(FILENAME_MANUFACTURERS));
}
// Список производителей
else
{
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array('min_range' => 1));
    if(!$page)
    {
        $page = 1;
    }
    $query = tep_db_query("SELECT COUNT(*) AS `count` FROM manufacturers");
    $pages_count = tep_db_fetch_array($query);
    $pages_count = ceil($pages_count['count'] / MAX_DISPLAY_SEARCH_RESULTS);
    if(!$pages_count)
    {
        $pages_count = 1;
    }
    if($page > $pages_count)
    {
        $page = 1;
    }
    $offset = MAX_DISPLAY_SEARCH_RESULTS * ($page - 1);
    $query = tep_db_query("SELECT * FROM manufacturers ORDER BY manufacturers_id LIMIT {$offset}, " . MAX_DISPLAY_SEARCH_RESULTS);
    if(tep_db_num_rows($query))
    {
        $manufacturers = array();
        while(($row = tep_db_fetch_array($query)) !== false)
        {
            $manufacturers[] = $row;
        }
    }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
        <title><?php echo TITLE; ?></title>
        <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
        <script language="javascript" src="includes/menu.js"></script>
        <script>
            function confirmDelete(event)
            {
                if(event.stopPropagation)
                {
                    event.stopPropagation();
                }
                else
                {
                    event.cancelBubble = true;
                }
                if(confirm('<?php echo tep_escape(addslashes(DELETE_CONFIRMATION_REQUEST)); ?>'))
                {
                    location = this.href + '&confirm=yes';
                }
                return false;
            }
        </script>
        <?php if($action === 'edit') : ?>
        <script type="text/javascript" src="../includes/ckeditor/ckeditor.js"></script>
        <script type="text/javascript" src="../includes/ckfinder/ckfinder.js"></script>
        <script type="text/javascript" src="../includes/javascript/lib/jquery-1.7.1.min.js"></script>
        <link type="text/css" href="../includes/javascript/ui/css/smoothness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
        <script type="text/javascript" src="includes/javascript/jquery-ui-1.9.2.custom.min.js"></script>
        <script type="text/javascript">
            $(function(){
                $('.tabs').tabs({ fx: { opacity: 'toggle', duration: 'fast' } });
                $('.CKEDITORField').each(function(){
                    CKFinder.setupCKEditor( CKEDITOR.replace( this, {height: '400px'}), '../includes/ckfinder/' ) ;
                });
            });
        </script>
        <style>
            .ui-tabs-anchor {
                text-align: center;
            }
        </style>
        <?php endif; ?>
    </head>
    <body>
        <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
        <table border="0" width="100%" cellspacing="2" cellpadding="2">
            <tbody>
                <tr>
                    <td valign="top">
                        <?php if($action === 'edit') : ?>
                        <?php /* Редактирование производителя */ ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="pageHeading"><?php echo tep_escape(empty($mID) ? CREATE_PAGE_HEADER : sprintf(EDIT_PAGE_HEADER, $manufacturer['manufacturers_id'], $manufacturer['manufacturers_name'])); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td>
                                        <form method="POST" action="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'action=edit&' . (empty($mID) ? '' : 'mID=' . $mID)) ?>">
                                            <table border="0" width="100%" cellspacing="0" cellpadding="5">
                                                <tbody>
                                                    <tr>
                                                        <td class="main">
                                                            <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS); ?>" class="button">
                                                                <img src="images/icon_status_red.gif" width="16" height="16" alt="<?php echo LINK_CANCEL; ?>">
                                                                <?php echo LINK_CANCEL; ?>
                                                            </a>
                                                        </td>
                                                        <td class="main" align="right">
                                                            <button type="submit" class="button">
                                                                <img src="images/icon_save.gif" width="16" height="16" alt="<?php echo SAVE_BUTTON; ?>">
                                                                <?php echo SAVE_BUTTON; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr bgcolor="#cef0ff">
                                                        <td class="main" width="150px"><?php echo NAME_LABEL; ?></td>
                                                        <td class="main">
                                                            <input
                                                                class="fullWidthInput"
                                                                type="text"
                                                                name="manufacturers_name"
                                                                maxlength="32"
                                                                <?php if(!empty($manufacturer['manufacturers_name'])) : ?>value="<?php echo tep_escape($manufacturer['manufacturers_name']); ?>"<?php endif; ?>
                                                                >
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main" width="150px"><?php echo STATUS_LABEL; ?></td>
                                                        <td class="main">
                                                            <input
                                                                id="status_yes"
                                                                type="radio"
                                                                name="status"
                                                                value="yes"
                                                                <?php if(empty($manufacturer) || $manufacturer['status']) : ?>checked<?php endif; ?>
                                                                >
                                                            <label for="status_yes"><?php echo STATUS_YES; ?></label>
                                                            <input
                                                                id="status_no"
                                                                type="radio"
                                                                name="status"
                                                                value="no"
                                                                <?php if(!empty($manufacturer) && !$manufacturer['status']) : ?>checked<?php endif; ?>
                                                                >
                                                            <label for="status_no"><?php echo STATUS_NO; ?></label>
                                                        </td>
                                                    </tr>
                                                    <tr bgcolor="#cef0ff">
                                                        <td class="main" width="150px"><?php echo TITLE_LABEL; ?></td>
                                                        <td class="main">
                                                            <div class="tabs">
                                                                <ul>
                                                                    <?php foreach($_languages as $_language) : ?>
                                                                    <li><a href="#title_<?php echo tep_escape($_language['languages_id']); ?>"><img src="/includes/languages/<?php echo tep_escape($_language['directory']); ?>/images/<?php echo tep_escape($_language['image']); ?>" alt="<?php echo tep_escape($_language['name']); ?>" title="<?php echo tep_escape($_language['name']); ?>" width="16" height="16"><br><small><?php echo tep_escape($_language['name']); ?></small></a></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                                <?php foreach($_languages as $_language) : ?>
                                                                <div id="title_<?php echo tep_escape($_language['languages_id']); ?>">
                                                                    <input
                                                                        class="fullWidthInput"
                                                                        type="text"
                                                                        maxlength="1024"
                                                                        name="manufacturers_title[<?php echo tep_escape($_language['languages_id']); ?>]"
                                                                        <?php if(!empty($manufacturers_info[$_language['languages_id']]['manufacturers_title'])) : ?>value="<?php echo tep_escape($manufacturers_info[$_language['languages_id']]['manufacturers_title']); ?>"<?php endif; ?>
                                                                        >
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main" width="150px"><?php echo KEYWORDS_LABEL; ?></td>
                                                        <td class="main">
                                                            <div class="tabs">
                                                                <ul>
                                                                    <?php foreach($_languages as $_language) : ?>
                                                                    <li><a href="#keywords_<?php echo tep_escape($_language['languages_id']); ?>"><img src="/includes/languages/<?php echo tep_escape($_language['directory']); ?>/images/<?php echo tep_escape($_language['image']); ?>" alt="<?php echo tep_escape($_language['name']); ?>" title="<?php echo tep_escape($_language['name']); ?>" width="16" height="16"><br><small><?php echo tep_escape($_language['name']); ?></small></a></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                                <?php foreach($_languages as $_language) : ?>
                                                                <div id="keywords_<?php echo tep_escape($_language['languages_id']); ?>">
                                                                    <textarea
                                                                        maxlength="1024"
                                                                        class="fullWidthInput smallArea"
                                                                        name="manufacturers_meta_keywords[<?php echo tep_escape($_language['languages_id']); ?>]"
                                                                        ><?php echo tep_escape($manufacturers_info[$_language['languages_id']]['manufacturers_meta_keywords']); ?></textarea>
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr bgcolor="#cef0ff">
                                                        <td class="main" width="150px"><?php echo META_DESCRIPTION_LABEL; ?></td>
                                                        <td class="main">
                                                            <div class="tabs">
                                                                <ul>
                                                                    <?php foreach($_languages as $_language) : ?>
                                                                    <li><a href="#meta_description_<?php echo tep_escape($_language['languages_id']); ?>"><img src="/includes/languages/<?php echo tep_escape($_language['directory']); ?>/images/<?php echo tep_escape($_language['image']); ?>" alt="<?php echo tep_escape($_language['name']); ?>" title="<?php echo tep_escape($_language['name']); ?>" width="16" height="16"><br><small><?php echo tep_escape($_language['name']); ?></small></a></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                                <?php foreach($_languages as $_language) : ?>
                                                                <div id="meta_description_<?php echo tep_escape($_language['languages_id']); ?>">
                                                                    <textarea
                                                                        maxlength="1024"
                                                                        class="fullWidthInput smallArea"
                                                                        name="manufacturers_meta_description[<?php echo tep_escape($_language['languages_id']); ?>]"
                                                                        ><?php echo tep_escape($manufacturers_info[$_language['languages_id']]['manufacturers_meta_description']); ?></textarea>
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr bgcolor="#cef0ff">
                                                        <td class="main" width="150px"><?php echo NAME_HEADER; ?></td>
                                                        <td class="main">
                                                            <div class="tabs">
                                                                <ul>
                                                                    <?php foreach($_languages as $_language) : ?>
                                                                    <li><a href="#name_header_<?php echo tep_escape($_language['languages_id']); ?>"><img src="/includes/languages/<?php echo tep_escape($_language['directory']); ?>/images/<?php echo tep_escape($_language['image']); ?>" alt="<?php echo tep_escape($_language['name']); ?>" title="<?php echo tep_escape($_language['name']); ?>" width="16" height="16"><br><small><?php echo tep_escape($_language['name']); ?></small></a></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                                <?php foreach($_languages as $_language) : ?>
                                                                <div id="name_header_<?php echo tep_escape($_language['languages_id']); ?>">
                                                                    <input
                                                                        class="fullWidthInput"
                                                                        type="text"
                                                                        maxlength="1024"
                                                                        name="name_header[<?php echo tep_escape($_language['languages_id']); ?>]"
                                                                        <?php if(!empty($manufacturers_info[$_language['languages_id']]['name_header'])) : ?>value="<?php echo tep_escape($manufacturers_info[$_language['languages_id']]['name_header']); ?>"<?php endif; ?>
                                                                        >
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main" width="150px"><?php echo DESCRIPTION_LABEL; ?></td>
                                                        <td class="main">
                                                            <div class="tabs">
                                                                <ul>
                                                                    <?php foreach($_languages as $_language) : ?>
                                                                    <li><a href="#description_<?php echo tep_escape($_language['languages_id']); ?>"><img src="/includes/languages/<?php echo tep_escape($_language['directory']); ?>/images/<?php echo tep_escape($_language['image']); ?>" alt="<?php echo tep_escape($_language['name']); ?>" title="<?php echo tep_escape($_language['name']); ?>" width="16" height="16"><br><small><?php echo tep_escape($_language['name']); ?></small></a></li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                                <?php foreach($_languages as $_language) : ?>
                                                                <div id="description_<?php echo tep_escape($_language['languages_id']); ?>">
                                                                    <textarea
                                                                        maxlength="1024"
                                                                        class="fullWidthInput bigArea CKEDITORField"
                                                                        name="manufacturers_description[<?php echo tep_escape($_language['languages_id']); ?>]"
                                                                        ><?php echo tep_escape($manufacturers_info[$_language['languages_id']]['manufacturers_description']); ?></textarea>
                                                                </div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="main">
                                                            <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS); ?>" class="button">
                                                                <img src="images/icon_status_red.gif" width="16" height="16" alt="<?php echo LINK_CANCEL; ?>">
                                                                <?php echo LINK_CANCEL; ?>
                                                            </a>
                                                        </td>
                                                        <td class="main" align="right">
                                                            <button type="submit" class="button">
                                                                <img src="images/icon_save.gif" width="16" height="16" alt="<?php echo SAVE_BUTTON; ?>">
                                                                <?php echo SAVE_BUTTON; ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </td>
                                    <td width="10px">&nbsp;</td>
                                    <?php /* Картинка */ ?>
                                    <td width="400px" valign="top">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="infoBoxHeading">
                                            <tbody>
                                                <tr>
                                                    <td><?php echo UPLOADED_IMAGE_HEADER; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php if(empty($mID)) : ?>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="messageBox messageStackWarning">
                                            <tbody>
                                                <tr>
                                                    <td><img src="images/icon_info.gif" width="16" height="16" alt=""></td>
                                                    <td width="100%"><?php echo MESSAGE_SAVE_TO_UPLOAD_IMAGE; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php else : ?>
                                        <?php if(empty($manufacturer['manufacturers_image'])) : ?>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="5" class="messageBox messageStackWarning">
                                            <tbody>
                                                <tr>
                                                    <td><img src="images/icon_info.gif" width="16" height="16" alt=""></td>
                                                    <td width="100%"><?php echo MESSAGE_IMAGE_NOT_UPLOADED; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php else : ?>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="10" class="infoBoxContent">
                                            <tbody>
                                                <tr>
                                                    <td align="center"><img src="/r_imgs.php?thumb=<?php echo tep_escape(rawurlencode($manufacturer['manufacturers_image'])); ?>&amp;w=390&amp;height=390" alt=""></td>
                                                </tr>
                                                <tr>
                                                    <td align="center">
                                                        <?php echo $manufacturer['image_width']; ?>
                                                        &times;
                                                        <?php echo $manufacturer['image_height']; ?>
                                                        <?php echo PIXELS; ?>
                                                        (<?php echo $manufacturer['image_size'], $manufacturer['image_size_units']; ?>)
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center">
                                                        <a
                                                            href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $mID . '&action=remove_image'); ?>"
                                                            class="button"
                                                            onclick="return confirmDelete.call(this, event || window.event);"
                                                            >
                                                            <img src="images/trash.gif" width="16" height="16" alt="<?php echo DELETE_IMAGE; ?>">
                                                            <?php echo DELETE_IMAGE; ?>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php endif; ?>
                                        <br>
                                        <form method="POST" action="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $mID . '&action=upload_image'); ?>" enctype="multipart/form-data">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tbody>
                                                    <tr>
                                                        <td class="infoBoxHeading"><?php echo IMAGE_UPLOAD_HEADER; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="infoBoxContent">
                                                            <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo getUploadMaxFileSize(); ?>">
                                                            <input type="file" name="manufacturers_image">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="infoBoxContent" align="center">
                                                            <button type="submit" class="button"><?php echo SEND; ?></button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php else : ?>
                        <?php /* Список производителей */ ?>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="pageHeading"><?php echo LISTING_PAGE_HEADER; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="main">
                                        <?php echo sprintf(PAGINATION, $page, $pages_count); ?>
                                        <?php if($page > 1) : ?>
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'page=' . ($page - 1)); ?>" rel="prev"><?php echo PREV; ?></a>
                                        <?php endif; ?>
                                        <?php if($page < $pages_count) : ?>
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'page=' . ($page + 1)); ?>" rel="next"><?php echo NEXT; ?></a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="main" align="right">
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'action=edit'); ?>" class="button"><img src="images/icon_add.gif" width="16" height="16" alt="<?php echo CREATE; ?>"> <?php echo CREATE; ?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <table border="0" width="100%" cellspacing="0" cellpadding="2">
                            <thead>
                                <tr class="dataTableHeadingRow">
                                    <td class="dataTableHeadingContent"><?php echo LISTING_TABLE_ID_COLUMN_HEADING; ?></td>
                                    <td class="dataTableHeadingContent"><?php echo LISTING_TABLE_NAME_COLUMN_HEADING; ?></td>
                                    <td class="dataTableHeadingContent"><?php echo LISTING_TABLE_DATE_ADDED_COLUMN_HEADING; ?></td>
                                    <td class="dataTableHeadingContent"><?php echo LISTING_TABLE_DATE_MODIFIED_COLUMN_HEADING; ?></td>
                                    <td class="dataTableHeadingContent" align="center"><?php echo LISTING_TABLE_STATUS_COLUMN_HEADING; ?></td>
                                    <td class="dataTableHeadingContent" align="right"><?php echo LISTING_TABLE_ACTION_COLUMN_HEADING; ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($manufacturers)) : ?>
                                <?php foreach($manufacturers as $manufacturer) : ?>
                                <tr class="dataTableRow hasHoverEffect" id="manufacturer_<?php echo $manufacturer['manufacturers_id']; ?>" onclick="location = document.getElementById('edit_<?php echo $manufacturer['manufacturers_id']; ?>').href;">
                                    <td class="dataTableContent"><?php echo $manufacturer['manufacturers_id']; ?></td>
                                    <td class="dataTableContent"><?php echo tep_escape($manufacturer['manufacturers_name']); ?></td>
                                    <td class="dataTableContent"><?php echo tep_date_long($manufacturer['date_added']); ?></td>
                                    <td class="dataTableContent"><?php echo tep_date_long($manufacturer['last_modified']); ?></td>
                                    <td class="dataTableContent" align="center">
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $manufacturer['manufacturers_id'] . '&action=switch_status') ?>" title="<?php echo $manufacturer['status'] ? DISABLE : ENABLE ; ?>"><img src="images/icon_status_green<?php if(!$manufacturer['status']) : ?>_light<?php endif; ?>.gif" width="16" height="16" alt="<?php echo $manufacturer['status'] ? STATUS_ENABLED : STATUS_DISABLED ; ?>"></a>
                                    </td>
                                    <td class="dataTableContent" align="right">
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $manufacturer['manufacturers_id'] . '&action=edit') ?>" title="<?php echo EDIT; ?>" id="edit_<?php echo $manufacturer['manufacturers_id']; ?>"><img src="images/icon_properties_add.gif" width="16" height="16" alt="<?php echo EDIT ; ?>"></a>
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'mID=' . $manufacturer['manufacturers_id'] . '&action=delete') ?>" title="<?php echo DELETE; ?>" onclick="return confirmDelete.call(this, event || window.event);"><img src="images/icons/del.gif" width="16" height="16" alt="<?php echo DELETE ; ?>"></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                <tr class="dataTableRow">
                                    <td class="dataTableContent" colspan="6"><?php echo MESSAGE_NO_MANUFACTURERS; ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                        <br>
                        <table border="0" width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                                <tr>
                                    <td class="main">
                                        <?php echo sprintf(PAGINATION, $page, $pages_count); ?>
                                        <?php if($page > 1) : ?>
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'page=' . ($page - 1)); ?>" rel="prev"><?php echo PREV; ?></a>
                                        <?php endif; ?>
                                        <?php if($page < $pages_count) : ?>
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'page=' . ($page + 1)); ?>" rel="next"><?php echo NEXT; ?></a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="main" align="right">
                                        <a href="<?php echo tep_href_link(FILENAME_MANUFACTURERS, 'action=edit'); ?>" class="button"><img src="images/icon_add.gif" width="16" height="16" alt="<?php echo CREATE; ?>"> <?php echo CREATE; ?></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
        <br>
    </body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
