<?php if(PHOTOGALLERY_MODULE_ENABLED == 'true'): ?>



<div>
<?php
    $query = "select * from photoalbum ORDER BY date DESC";
    $query_1 = tep_db_query($query);
    while($row = mysql_fetch_assoc($query_1))
    {
        echo '<div class="photogallery_main clear"><a href="photogallery_full.php?PhotoAlbumID='.$row['PhotoAlbumID'].'">'.$row['PhotoAlbumName'].'</a></div>';
        echo '<div class="clear"></div>';

        $i = 0;

        $query_2 = "select * from photo where PhotoAlbumID=".$row['PhotoAlbumID']." ORDER BY date DESC LIMIT 0,6";
        $query_3 = tep_db_query($query_2);
        while($row_1 = mysql_fetch_array($query_3))
        {
                echo '<div class="img_f_gall"><a href="/admin/photoalbum/'.rawurlencode($row['alias']).'/full/'.rawurlencode($row_1['PhotoFullURL']).'"  id="ch_link" onclick="return hs.expand(this)"><img alt="" border="0" src="/admin/photoalbum/'.rawurlencode($row['alias']).'/small/'.rawurlencode($row_1['PhotoPreviewURL']).'"/></a></div>';
        }

    }


?>
</div>
<?php endif; ?>