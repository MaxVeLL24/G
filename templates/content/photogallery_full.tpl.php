<div class="clear"></div>
<div>
<?php
        $query_7 = "select PhotoAlbumName, alias from photoalbum where PhotoAlbumID=".$_GET['PhotoAlbumID'];
        $query_8 = tep_db_query($query_7);
        $row_9 = mysql_fetch_assoc($query_8);


		$items_per_page = 9;

		$result_count = tep_db_query('SELECT COUNT(*) AS `count` FROM photo where PhotoAlbumID='.$_GET['PhotoAlbumID']);
		$aCount	 = mysql_fetch_assoc($result_count);

		$iPages = ceil($aCount['count'] / $items_per_page);
		$page = (isset($_GET['page']) && $_GET['page'] <= $aCount['count']) ? $_GET['page'] : 1;
		$iOffsetLimit = ($page - 1) * $items_per_page;
		$iStart = ($page > 5) ? $page - 4 : 1;



        echo '<div class="photogallery_main2">'.$row_9['PhotoAlbumName'].'</div><div class="clear"></div>';

        $i = 0;


		$result1 = 'SELECT * FROM photo where PhotoAlbumID='.$_GET['PhotoAlbumID'].'
                    LIMIT ' . $iOffsetLimit . ',' . $items_per_page;
		$query3 = tep_db_query($result1);
        while($row_1 = mysql_fetch_assoc($query3))
        {

 				echo '<div class="img_f_gall"><a href="/admin/photoalbum/'.rawurlencode($row_9['alias']).'/full/'.rawurlencode($row_1['PhotoFullURL']).'"  id="ch_link" onclick="return hs.expand(this)"><img alt="" border="0" src="/admin/photoalbum/'.rawurlencode($row_9['alias']).'/small/'.rawurlencode($row_1['PhotoPreviewURL']).'"/></a></div>';

        }
        echo '<div class="clear"></div>';
        echo '<div style="height: 20px;"></div>';

		if ($iPages > 1)
		{
			echo '<div align="center">Страницы: ';
			for ($i = $iStart; $i <= $iPages; $i++) {
				if ($page > 4 && $i == $page - 4) {
					echo '<a href="/photogallery_full.php?PhotoAlbumID='.$_GET['PhotoAlbumID'].'&page=' . $i . '">...</a>' . "\n";
					continue;
				}
				if ($i > $page + 3) {
					echo '<a href="/photogallery_full.php?PhotoAlbumID='.$_GET['PhotoAlbumID'].'&page=' . $i . '">...</a>' . "\n";
					break;
				}
				if ($i == $page) {
					echo ' <span style="font-weight: bold;"> ' . $i . ' </span> ' . "\n";
				} else {
					echo '<a href="/photogallery_full.php?PhotoAlbumID='.$_GET['PhotoAlbumID'].'&page=' . $i . '">' . $i . '</a>' . "\n";
				}
			   }
			echo '</div>' . "\n";
		}
?>
</div>