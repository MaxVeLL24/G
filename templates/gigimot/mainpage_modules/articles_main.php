<?php if(ARTICLES_MODULE_ENABLED == 'true'): ?>
<div id="articles">
<div class="section_template_title"><?php echo BOX_NEW_ARTICLES; ?></div>
<?php
        $query_art = "select a.articles_date_added, ad.articles_image, ad.articles_id, ad.articles_name, ad.articles_description, ad.articles_head_desc_tag
                      from " . TABLE_ARTICLES_DESCRIPTION . " ad, " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_TO_TOPICS . " a2t, " . TABLE_TOPICS . " t
                      where a.articles_id=ad.articles_id
                      and ad.articles_id=a2t.articles_id
                      and a2t.topics_id=t.topics_id
                      and ad.articles_name != ''
                      and a.articles_status=1
                      and ad.language_id = " . $languages_id . "
                      order by ad.articles_id DESC LIMIT 3";
        $query_art_info = tep_db_query($query_art);
        while ($row1 = tep_db_fetch_array($query_art_info)){
          $link = tep_href_link(FILENAME_ARTICLE_INFO, 'articles_id=' . $row1['articles_id']);
          $image = 'http://'.$_SERVER['HTTP_HOST'].'/r_imgs.php?thumb='.$row1['articles_image'].'&amp;w=116&amp;h=116';
          $art_shorttext = '<a href="' . $link . '">'.truncateHtml($row1['articles_description'],500).'</a>';
          $output = '
            <div class="subnew clearfix">
              <div class="title"><a href="'.$link.'">'.$row1['articles_name'].'</a></div>
              <div class="date">'.date('d.m.Y',strtotime($row1['articles_date_added'])).'</div>
              <div class="image left"><a href="'.$link.'"><img src="'.$image.'" alt="'.$row1["articles_name"].'"></a></div>
              <div class="text">'.$art_shorttext.'</div>
            </div>';
          echo $output;
        }
        echo "<br><p><a class='btn' href='/allarticles.php' title='".BOX_ALL_ARTICLES."'>".BOX_ALL_ARTICLES."</a></p>";

?>
</div><div class="clear"></div>
<?php endif; ?>
