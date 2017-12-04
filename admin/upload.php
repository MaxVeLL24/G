<?php      
if($_GET['method']=='colors') $myfile = 'myfile2';
elseif($_GET['method']=='attribs') $myfile = 'myfile3';
elseif($_GET['method']=='opt_values') $myfile = 'myfile_'.$_GET['thisval'];
else $myfile = 'myfile';   

function check_name($uploaddir, $uploadfile,$tmpfile) {

if (file_exists($uploaddir.$uploadfile)) {
      $rexplode = explode('.', $uploaddir.$uploadfile); // разрезаем имя по точкам
      $ri = count($rexplode) - 1; // извращаемся на случай, если в названии файла были еще точки
      $rextension = $rexplode[$ri]; // расширение файла нашли
      $rlen = strlen($rextension)+1;
      $new_name = substr($uploadfile, 0, -$rlen);        
      $picture = $new_name.'_.'.$rextension;
       
      return check_name($uploaddir, $picture,$tmpfile);
    } else {
      move_uploaded_file($tmpfile, $uploaddir.$uploadfile);
      return $uploadfile;
    }
}

// ---------------перевод с кирилицы и всякие проверки-------------------------
function sanit_fname($string) {
			$cyrillic = array("Q","W","E","R","T","Y","U","I","O","P","A","S","D","F","G","H","J","K","L","Z","X","C","V","B","N","M","ж", "ё", "й","ю", "ь","ч", "щ", "ц","у","к","е","н","г","ш", "з","х","ъ","ф","ы","в","а","п","р","о","л","д","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б");
			$translit = array("q","w","e","r","t","y","u","i","o","p","a","s","d","f","g","h","j","k","l","z","x","c","v","b","n","m","zh","yo","i","yu","'","ch","sh","c","u","k","e","n","g","sh","z","h","'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "yе", "jа", "s",  "m",  "i",  "t",  "b",  "yo", "i",  "yu", "ch", "'",  "sh", "c",  "u",  "k",  "e",  "n",  "g",  "sh", "z",  "h",  "'",  "f",  "y",  "v",  "a",  "p",  "r",  "o",  "l",  "d",  "zh", "ye", "ja", "s",  "m",  "i",  "t",  "b");
			$string = str_replace($cyrillic, $translit, $string);
			$string = preg_replace(array('@\s@','@[^a-z0-9\-_\.]+@',"@_+\-+@","@\-+_+@","@\-\-+@","@__+@"), array('_', '', "-","-","-","_"), $string);
			$string = mb_strtolower($string);
			$string = preg_replace('/ /','_',$string); // пробел
			$string = preg_replace('#\(?(\w)\)?#s','$1',$string); // замена скобок
			return($string);
} 

$uploaddir = '../images/';

$uploadfile = sanit_fname($_FILES[$myfile]['name']); // проверка на кривые символы
$tmpfile = $_FILES[$myfile]['tmp_name'];

$r_name = check_name($uploaddir,$uploadfile,$tmpfile);
$file = $r_name;

$thumb_directory =  "../images/thumb";    	//Папка для миниатюр 
$orig_directory = "../images/";    	//Папка для полноразмерных изображений 
 
				//Проверяем, что папка открыта и в ней есть файлы
 
$allowed_types=array('jpg','jpeg','gif','png'); // Список обрабатываемых расширений
$file_parts=array();
$ext='';
$title='';
$i=0;

$file = $r_name;
//$file = $_FILES['myfile']['name'];
//print_r($file);
    /* Пропускаем системные файлы: */
    if($file=='.' || $file == '..') continue;
 
    $file_parts = explode('.',$file);    	//Разделяем имя файла на части 
    $ext = strtolower(array_pop($file_parts));
 
    /* Используем имя файла (без расширения) как заголовок изображения: */
    $title = implode('.',$file_parts);
    $title = htmlspecialchars($title);
 
    /* Если расширение входит в список обрабатываемых: */
    if(in_array($ext,$allowed_types))
    {
 
        /* Если вы планируете хранить изображения в базе данных, вставьте код для запроса здесь */
 
        /* Далее следует код, который разбирался в уроке */
        /* Выводим каждое изображение: */
 
        $nw = 100;
        $nh = 100;
        $source = $orig_directory . $file;
        $stype = explode(".", $source);
        $stype = $stype[count($stype)-1]; 
        $dest = $thumb_directory . $file;
 
        $size = getimagesize($source);
        $w = $size[0];
        $h = $size[1];
 
        switch($stype) {
            case 'gif':
                $simg = imagecreatefromgif($source);
                break;
            case 'jpg':
                $simg = imagecreatefromjpeg($source);
                break;
            case 'jpeg':
                $simg = imagecreatefromjpeg($source);
                break;
            case 'png':
                $simg = imagecreatefrompng($source);
                break;
        }

        $wm = $w/$nw;
        $hm = $h/$nh;
        $h_height = $nh/2;
        $w_height = $nw/2;
 
        if($w > $h) {
            $r_height = $nh*$h/$w;
            $r_width = $nw;
            $dimg = imagecreatetruecolor($r_width, $r_height);
            imagecopyresampled($dimg,$simg,0,0,0,0,$r_width,$r_height,$w,$h);
        } elseif(($w <$h) || ($w == $h)) {
            $r_height = $nw;
            $r_width = $nw*$w/$h;
            $dimg = imagecreatetruecolor($r_width, $r_height);
            imagecopyresampled($dimg,$simg,0,0,0,0,$r_width,$r_height,$w,$h);
        } else {
            imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
        }
            imagejpeg($dimg,$dest,80);
        }


echo $file; // название файла для джаваскрипта в script.js


?>
