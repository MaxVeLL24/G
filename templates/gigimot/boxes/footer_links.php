<ul>
	<?php
		foreach ($info_pages['bottom-block1'] as $page) {
	    	echo '<li class="path1"><a class="path1" href="' . tep_href_link(FILENAME_INFORMATION, 'pages_id=' . $page['pages_id'])  . '">' . $page['pages_name'] . '</a></li>';
	    }
	?>
</ul>