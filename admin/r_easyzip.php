<?php 
  include_once __DIR__ . '/includes/application_top.php';
  
  chdir('../'); // возвращаемся  корень!! 
	$zipfolder = 'admin/backups/';
	$zipname = 'easy2.zip';
	
if($_GET['actos']=='zipp') {
//эта функция рекурсивно обходит все папки и составляет список файлов
//результат её работы можете посмотреть, вывев var_dump($allfiles) после её вызова
function recoursiveDir($dir){
    global $allfiles;
    if ($files = glob($dir.'/*') or $files = glob('.htaccess', GLOB_BRACE)){
        foreach($files as $file){
            if (is_dir($file)){
                recoursiveDir($file);
            } else {
                $allfiles[]    =  str_replace('./', '', $file);
                
            }
        }
    }
} 

  if(file_exists($zipfolder.$zipname)) unlink($zipfolder.$zipname);
	
	$zip = new ZipArchive;
  
	if ($zip->open($zipfolder.$zipname, ZipArchive::CREATE) === true){
		$allfiles   =   array();
    
		recoursiveDir('.');
  	foreach ($allfiles as $key=>$val){
			$zip->addFile($val);
		}
		$zip->close();
                //указываем в заголовках тип передаваемых данных: архив zip
                header('Content-type: application/zip; name='.$zipname);
                header('Content-Disposition: attachment; filename=' .urlencode($zipname));
                header('Content-Transfer-Encoding: binary');
                //отдаём файл архива
                echo file_get_contents($zipfolder.$zipname);
                
                unlink($zipfolder.$zipname); // удаляем чтоб потом никто не скачал..
	
	}else{
		echo 'Ошибка! Невозможно создать архив!';
	}

} elseif($_GET['actos']=='downloaddb') {
    if ($files = glob('admin/backups/./*')){
        $allfiles   =   array();
				foreach($files as $file){
            if (!is_dir($file)){
                $allfiles[str_replace('./', '', $file)]   =   filemtime($file);
           //     echo str_replace('./', '', $file)." was last modified: " . filemtime($file).'<br />';
            }
        }
        asort($allfiles);
        $newest_key = (end($allfiles));
        $newest = array_search($newest_key, $allfiles);
  //      echo '<a href="http://'.$_SERVER['HTTP_HOST'].'/'.$newest.'">'.$newest.'</a>';
        
          //      header('Content-type: application/zip; name='.$zipname);
                header('Content-Disposition: attachment; filename=' .urlencode('easy.sql'));
                header('Content-Transfer-Encoding: binary');
                echo file_get_contents($newest);
    }
}
?>