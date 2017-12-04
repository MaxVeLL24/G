<?php

define('WATERMARK_IMAGE_FILE_PATH', __DIR__ . '/watermark.png');

/** @var string $source_image_requested_filename Путь к файлу в каталоге изображений */
$source_image_requested_filename = filter_input(INPUT_GET, 'thumb');

/** @var int $result_image_maximum_width Ширина результирующего изображения */
$result_image_maximum_width    = filter_input(INPUT_GET, 'w', FILTER_VALIDATE_INT, array('min_range' => 0));

/** @var int $result_image_maximum_height Высота результирующего изображения */
$result_image_maximum_height   = filter_input(INPUT_GET, 'h', FILTER_VALIDATE_INT, array('min_range' => 0));

/**
 * Метод ресайза изображения. Возможные значения:
 * <ul>
 *     <li><b>contain</b> - результирующее изображение будет вписано в область, ширина и высота которой заданы значениями параметров $result_image_maximum_width и $result_image_maximum_height.</li>
 *     <li><b>cover</b> - результирующее изображение будет перекрывать собой область, ширина и высота которой заданы значениями параметров $result_image_maximum_width и $result_image_maximum_height.</li>
 * </ul>
 * 
 * @var string
 */
$resize_method = filter_input(INPUT_GET, 'method');
if($resize_method !== 'contain' && $resize_method !== 'cover')
{
    $resize_method = 'contain';
}

/**
 * Отправляет клиенту сообщение о том, что запрашиваемая страница не найдена
 */
function notFound()
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    header('Content-Type: text/plain; charset=ISO-8859-1');
    exit('Not Found');
}

/**
 * Выполняет переадресацию клиента на указанный адрес
 * 
 * @param string $location URI, на оторый будет выполнен редирект
 */
function redirect($location)
{
    if($_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0')
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 302 Found');
    }
    else
    {
        header($_SERVER['SERVER_PROTOCOL'] . ' 303 See Other');
    }
    header('Location: ' . $location);
    exit();
}

/**
 * Кодирует все компоненты пути функцией rawurlencode
 * 
 * @param string $path Исходная строка пути
 * @return string
 */
function uriEncodePath($path)
{
    if(!$path)
    {
        return '';
    }
    $path = explode('/', $path);
    foreach($path as $i => $item)
    {
        $path[$i] = rawurlencode($item);
    }
    return implode('/', $path);
}

/**
 * Отправляет клиенту ответ со статусом 304 - ресурс не изменился
 */
function notModified()
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 304 Not Modified');
    exit();
}

function sendFile()
{
    global $source_image_type, $cache_file_last_modified_time, $source_image_requested_filename, $cache_file_name, $cache_file_filepath;
    switch($source_image_type)
    {
        case IMAGETYPE_JPEG :
            header('Content-Type: image/jpeg');
            break;
        case IMAGETYPE_PNG :
            header('Content-Type: image/png');
            break;
        case IMAGETYPE_GIF :
            header('Content-Type: image/gif');
            break;
    }
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s \G\M\T', $cache_file_last_modified_time));
    header('ETag: "' . $cache_file_name . '"');
    header('Cache-Control: max-age=2592000, public');
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
    header('Content-Disposition: inline; filename==?UTF-8?B?' . base64_encode($source_image_requested_filename) . '?=');
    $image = file_get_contents($cache_file_filepath);
    header(sprintf('Content-Length: %d', strlen($image)));
    exit($image);
}

// Поверяем, что имя файла не пустое
if(!$source_image_requested_filename)
{
    notFound();
}

// Проверяем, что файл действительно существует
$images_directory_filepath = realpath(__DIR__ . '/images/') . DIRECTORY_SEPARATOR;
$source_image_filepath = realpath($images_directory_filepath . $source_image_requested_filename);
if(!$source_image_filepath || !is_file($source_image_filepath))
{
    notFound();
}

// Проверяем, что запрашиваемый файл не находится за пределами каталога с изображениями
if(strcasecmp($source_image_filepath, $images_directory_filepath . $source_image_requested_filename) !== 0)
{
    notFound();
}

// Получаем информацию о изображении
$tmp = getimagesize($source_image_filepath);
if(!$tmp)
{
    notFound();
}
list($source_image_real_width, $source_image_real_height, $source_image_type) = $tmp;

// Строим имя кэша
$cache_file_name  = md5($source_image_requested_filename . filemtime($source_image_filepath) . (empty($result_image_maximum_width) ? $source_image_real_width : $result_image_maximum_width) . (empty($result_image_maximum_height) ? $source_image_real_height : $result_image_maximum_height) . $resize_method);
$cache_file_filepath = __DIR__ . '/cache/images/' . $cache_file_name;

// Если файл существует в кэше
if(is_file($cache_file_filepath))
{
    // Время последнего изменения файла в кэше
    $cache_file_last_modified_time = filemtime($cache_file_filepath);
    
    // Если клиент запросил валидацию кэша
    $if_modified   = empty($_ENV['HTTP_IF_MODIFIED_SINCE']) ? empty($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? null : $_SERVER['HTTP_IF_MODIFIED_SINCE'] : $_ENV['HTTP_IF_MODIFIED_SINCE'];
    $if_none_match = empty($_ENV['HTTP_IF_NONE_MATCH']) ? empty($_SERVER['HTTP_IF_NONE_MATCH']) ? null : $_SERVER['HTTP_IF_NONE_MATCH'] : $_ENV['HTTP_IF_NONE_MATCH'];
    
    // Убираем скобки и символ "мягкого" сравнения
    if($if_none_match)
    {
        $if_none_match = preg_replace('/^(?:W\/)"?([^"]+)"?$/', '$1', $if_none_match);
    }
    
    // Парсим дату последней модификации файла, присланную клиентом
    if($if_modified)
    {
        $if_modified = strtotime($if_modified);
    }
    
    // Если хэш одинаковый или время последней модификации не поменялось - делаем вывод, что файл не изменился
    if(($if_none_match && $if_none_match === $cache_file_name) || ($if_modified === $cache_file_last_modified_time))
    {
        notModified();
    }
    else
    {
        sendFile();
    }
}

// Если результирующие размеры равны или менее (так как скрипт работает только на
// уменьшение изображений) исходных, то не выполняем никаких преобразований, отправляем редирект
if(($result_image_maximum_width >= $source_image_real_width && $result_image_maximum_height >= $source_image_real_height) && ($source_image_real_width < 200 && $source_image_real_height < 200))
{
    $cache_file_filepath = $source_image_filepath;
    sendFile();
}

// Если результирующие размеры не указаны, то приравниваем их к размерам исходного изображения
if(empty($result_image_maximum_width))
{
    $result_image_maximum_width = $source_image_real_width;
}
if(empty($result_image_maximum_height))
{
    $result_image_maximum_height = $source_image_real_height;
}

// Уменьшить изображение
if($result_image_maximum_width < $source_image_real_width || $result_image_maximum_height < $source_image_real_height)
{
    switch($source_image_type)
    {
        case IMAGETYPE_JPEG :
            $source_image = imagecreatefromjpeg($source_image_filepath);
            break;
        case IMAGETYPE_PNG :
            $source_image = imagecreatefrompng($source_image_filepath);
            break;
        case IMAGETYPE_GIF :
            $source_image = imagecreatefromgif($source_image_filepath);
            break;
        default :
            notFound();
            break;
    }

    // Вычисляем размер результирующего изображения
    $width_ratio  = $result_image_maximum_width / $source_image_real_width;
    $height_ratio = $result_image_maximum_height / $source_image_real_height;

    if($resize_method === 'cover')
    {
        $scale_ratio = max($width_ratio, $height_ratio);
    }
    else
    {
        $scale_ratio = min($width_ratio, $height_ratio);
    }

    $result_image_real_width  = floor($source_image_real_width * $scale_ratio);
    $result_image_real_height = floor($source_image_real_height * $scale_ratio);
    $offset_x     = 0;
    $offset_y     = 0;

    if($result_image_real_width > $result_image_maximum_width)
    {
        $offset_x = round(abs(($result_image_real_width - $result_image_maximum_width) / 2) * $scale_ratio);
        $result_image_real_width = $result_image_maximum_width;
    }
    if($result_image_real_height > $result_image_maximum_height)
    {
        $offset_y = round(abs(($result_image_real_height - $result_image_maximum_height) / 2) * $scale_ratio);
        $result_image_real_height = $result_image_maximum_height;
    }

    $result_image = imagecreatetruecolor($result_image_real_width, $result_image_real_height);
    imagealphablending($result_image, false);
    imagesavealpha($result_image, true);
    imagecopyresampled($result_image, $source_image, 0, 0, $offset_x, $offset_y, $result_image_real_width, $result_image_real_height, round($result_image_real_width / $scale_ratio), round($result_image_real_height / $scale_ratio));
    imagedestroy($source_image);
}

// Добавить водяной знак
if(($result_image_maximum_width > 200 || $result_image_maximum_height > 200) && WATERMARK_IMAGE_FILE_PATH && ($tmp = getimagesize(WATERMARK_IMAGE_FILE_PATH)) !== false)
{
    if(empty($result_image))
    {
        $result_image_real_width = $source_image_real_width;
        $result_image_real_height = $source_image_real_height;
        $result_image_type = $source_image_type;
        switch($source_image_type)
        {
            case IMAGETYPE_JPEG :
                $result_image = imagecreatefromjpeg($source_image_filepath);
                break;
            case IMAGETYPE_PNG :
                $result_image = imagecreatefrompng($source_image_filepath);
                break;
            case IMAGETYPE_GIF :
                $result_image = imagecreatefromgif($source_image_filepath);
                break;
            default :
                notFound();
                break;
        }
    }
    list($watermark_image_real_width, $watermark_image_real_height) = $tmp;
    $watermark_image = imagecreatefrompng(WATERMARK_IMAGE_FILE_PATH);
    if($watermark_image)
    {
        // Подгоняем размеры изображения водяного знака, если оно оказывается больше результирующего изображения
        $scale_ratio = 1;
        if($watermark_image_real_width > $result_image_real_width * .5 || $watermark_image_real_height > $result_image_real_height * .5)
        {
            $scale_ratio = max(array(
                $watermark_image_real_width / $result_image_real_width / .5,
                $watermark_image_real_height / $result_image_real_height / .5
            ));
        }
        $watermark_image_final_width = $watermark_image_real_width / $scale_ratio;
        $watermark_image_final_height = $watermark_image_real_height / $scale_ratio;
        imagesavealpha($result_image, false);
        imagealphablending($result_image, true);
        imagecopyresampled(
                $result_image,
                $watermark_image,
                $result_image_real_width - $watermark_image_final_width,
                $result_image_real_height - $watermark_image_final_height,
                0,
                0,
                $watermark_image_final_width,
                $watermark_image_final_height,
                $watermark_image_real_width,
                $watermark_image_real_height
                );
        imagedestroy($watermark_image);
    }
}


ob_start();

// Сохраняем изображение на диск в кэш
if(!empty($result_image))
{
    switch($source_image_type)
    {
        case IMAGETYPE_JPEG :
            imagejpeg($result_image, null, 85);
            break;
        case IMAGETYPE_PNG :
            imagepng($result_image);
            break;
        case IMAGETYPE_GIF :
            imagegif($result_image);
            break;
    }
    imagedestroy($result_image);

    $image = ob_get_contents();
    ob_end_clean();

    // Отправляем клиенту
    file_put_contents($cache_file_filepath, $image);
    $cache_file_last_modified_time = filemtime($cache_file_filepath);
}
else
{
    $cache_file_filepath = $source_image_filepath;
    $cache_file_last_modified_time = filemtime($cache_file_filepath);
}

sendFile();