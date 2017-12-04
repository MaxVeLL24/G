<?php

/*
  $Id: breadcrumb.php,v 1.1.1.1 2003/09/18 19:05:14 wilt Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

class breadcrumb {

    var $_trail;

    function breadcrumb() {
        $this->reset();
    }

    function reset() {
        $this->_trail = array();
    }

    function add($title, $link = '') {
        $this->_trail[] = array('title' => $title, 'link' => $link);
    }

    function trail($separator = ' - ') {
        $content = '';
        if(is_file(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/breadcrumb.php')) {
            ob_start();
            require DIR_WS_TEMPLATES . TEMPLATE_NAME . '/breadcrumb.php';
            $content = ob_get_contents();
            ob_end_clean();
        }
        return $content;
    }

    function size() {
        return sizeof($this->_trail);
    }

}
