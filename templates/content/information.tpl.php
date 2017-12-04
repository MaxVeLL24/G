<?php

/**
 * Шаблон инфостраниц
 */

?>
<article class="article" itemscope itemtype="http://schema.org/Article">
    <h1 itemprop="name headline"><?php echo tep_escape($page_info['pages_name']); ?></h1>
    <div class="tab-content common-styled-block" itemprop="articleBody"><?php echo $page_info['pages_description']; ?></div>
</article>