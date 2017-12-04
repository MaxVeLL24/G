<?php

/*
 * Returns path to current theme
 */

function theme_path() {
    return DIR_WS_TEMPLATES . 'easy2/';
}

/**
 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
 *
 * @param string $text String to truncate.
 * @param integer $length Length of returned string, including ellipsis.
 * @param string $ending Ending to be appended to the trimmed string.
 * @param boolean $exact If false, $text will not be cut mid-word
 * @param boolean $considerHtml If true, HTML tags would be handled correctly
 *
 * @return string Trimmed string.
 */
function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
    if ($considerHtml) {
        // if the plain text is shorter than the maximum length, return the whole text
        if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
            return $text;
        }
        // splits all html-tags to scanable lines
        preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
        $total_length = strlen($ending);
        $open_tags = array();
        $truncate = '';
        foreach ($lines as $line_matchings) {
            // if there is any html-tag in this line, handle it and add it (uncounted) to the output
            if (!empty($line_matchings[1])) {
                // if it's an "empty element" with or without xhtml-conform closing slash
                if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                    // do nothing
                    // if tag is a closing tag
                } else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                    // delete tag from $open_tags list
                    $pos = array_search($tag_matchings[1], $open_tags);
                    if ($pos !== false) {
                        unset($open_tags[$pos]);
                    }
                    // if tag is an opening tag
                } else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                    // add tag to the beginning of $open_tags list
                    array_unshift($open_tags, strtolower($tag_matchings[1]));
                }
                // add html-tag to $truncate'd text
                $truncate .= $line_matchings[1];
            }
            // calculate the length of the plain text part of the line; handle entities as one character
            $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
            if ($total_length + $content_length > $length) {
                // the number of characters which are left
                $left = $length - $total_length;
                $entities_length = 0;
                // search for html entities
                if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                    // calculate the real length of all entities in the legal range
                    foreach ($entities[0] as $entity) {
                        if ($entity[1] + 1 - $entities_length <= $left) {
                            $left--;
                            $entities_length += strlen($entity[0]);
                        } else {
                            // no more characters left
                            break;
                        }
                    }
                }
                $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
                // maximum lenght is reached, so get off the loop
                break;
            } else {
                $truncate .= $line_matchings[2];
                $total_length += $content_length;
            }
            // if the maximum length is reached, get off the loop
            if ($total_length >= $length) {
                break;
            }
        }
    } else {
        if (strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = substr($text, 0, $length - strlen($ending));
        }
    }
    // if the words shouldn't be cut in the middle...
    if (!$exact) {
        // ...search the last occurance of a space...
        $spacepos = strrpos($truncate, ' ');
        if (isset($spacepos)) {
            // ...and cut the text in this position
            $truncate = substr($truncate, 0, $spacepos);
        }
    }
    // add the defined ending to the text
    $truncate .= $ending;
    if ($considerHtml) {
        // close all unclosed html-tags
        foreach ($open_tags as $tag) {
            $truncate .= '</' . $tag . '>';
        }
    }
    return $truncate;
}

function tep_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true)
{
    return tep_escape(\EShopmakers\Http\Rewrite::getInstance()->link($page, $parameters));
}

function clearData($data, $type = 's', $trim = 50) {
    if (!$type == 's')
        return $data;
    switch ($type) {
        case 's' :
            $output = clean_html_comments(substr($data, 0, $trim)) . ((strlen($data) >= $trim) ? '...' : '');
            return $output;
    }
}

////
// The HTML image wrapper function
// BOF Image Magic
function tep_image($src, $alt = '', $width = '', $height = '', $params = '') {
    global $product_info;

    //Allow for a new intermediate sized thumbnail size to be set
    //without any changes having to be made to the product_info page itself.
    //(see the lengths I go to to make your life easier :-)
    if (strstr($_SERVER['PHP_SELF'], "product_info.php")) {

        if (isset($product_info['products_image']) && $src == DIR_WS_IMAGES . $product_info['products_image'] && $product_info[products_id] == $_GET['products_id']) {   //final check just to make sure that we don't interfere with other contribs
            $width = PRODUCT_INFO_IMAGE_WIDTH == 0 ? '' : PRODUCT_INFO_IMAGE_WIDTH;
            $height = PRODUCT_INFO_IMAGE_HEIGHT == 0 ? '' : PRODUCT_INFO_IMAGE_HEIGHT;
            $product_info_image = true;
            $page = "prod_info";
        }
    }
    $image_size = @getimagesize($src);
    if ($image_size[0] != '') {
        //send the image for processing unless told otherwise
        $image = '<img src="' . $src . '"'; //set up the image tag just in case we don't want to process
        // поправляем размеры:

        $rwidth = $image_size[0];
        $rheight = $image_size[1];
        $new_height = '';
        $new_width = '';
        $margin_top = '';
        if ($rheight > $rwidth)
            $new_height = $height;
        else {
            $new_width = $width;
            $new_height = $width * $rheight / $rwidth;
            $margin_top = 'margin-top:' . (($height - $new_height) / 2) . 'px;';
        }
        //  $width = $new_width;
        //  $height = $new_height;
        // поправляем размеры //


        if ($new_width != '') {
            $image .= ' width="' . tep_output_string($new_width) . '"';
        }

        if ($new_height != '') {
            $image .= ' height="' . tep_output_string($new_height) . '"';
        }

        // добавляем маржин в стайл:-----------------------------------
        $style_vars = array('style="', 'style= "', 'style ="', 'style = "', 'style=', 'style =', 'style= ', 'style = ', "style='", "style= '", "style ='", "style = '");

        foreach ($style_vars as $vars) {
            if (strstr($params, $vars) != '') {
                $is_style = explode($vars, $params);
                $params = $is_style[0] . 'style="' . $margin_top . $is_style[1] . '"';
                $gi = 1;
                break;
            }
        }
        if ($gi != 1 && $margin_top != '')
            $params .= ' style="' . $margin_top . ';"';
        // добавляем маржин в стайл-----------------------------------//

        if (tep_not_null($params))
            $image .= ' ' . $params;

        $image .= ' alt="' . tep_output_string($alt) . '"';

        if (tep_not_null($alt)) {
            $image .= ' title="' . tep_output_string($alt) . '"';
        }


        $image .= '>';
    }
    return $image;
}

//EOF Image Magic
// The FLASH image wrapper function
function tep_flash_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
    if ((empty($src) || ($src == DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false')) {
        return false;
    }


// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
    $image = '<embed src="' . tep_output_string($src) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt)) {
        $image .= ' title=" ' . tep_output_string($alt) . ' "';
    }

    if ((CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height))) {
        if ($image_size = @getimagesize($src)) {
            if (empty($width) && tep_not_null($height)) {
                $ratio = $height / $image_size[1];
                $width = $image_size[0] * $ratio;
            } elseif (tep_not_null($width) && empty($height)) {
                $ratio = $width / $image_size[0];
                $height = $image_size[1] * $ratio;
            } elseif (empty($width) && empty($height)) {
                $width = $image_size[0];
                $height = $image_size[1];
            }
        } elseif (IMAGE_REQUIRED == 'false') {
            return false;
        }
    }

    if (tep_not_null($width) && tep_not_null($height)) {
        $image .= ' width="' . tep_output_string($width) . '" height="' . tep_output_string($height) . '"';
    }

    if (tep_not_null($parameters))
        $image .= ' ' . $parameters;

    $image .= '>';

    return $image;
}

////
// The HTML form submit button wrapper function
// Outputs a button in the selected language
function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image) . '" border="0" alt="' . tep_output_string($alt) . '"';

    if (tep_not_null($alt))
        $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';

    if (tep_not_null($parameters))
        $image_submit .= ' ' . $parameters;

    $image_submit .= '>';

    return $image_submit;
}

////
// Output a function button in the selected language
function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;

    return tep_image(DIR_WS_LANGUAGES . $language . '/images/buttons/' . $image, $alt, '', '', $parameters);
}

////
// Output a separator either through whitespace, or with an image
function tep_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '0') {

    $image = '<img src="/images/pixel_black.gif" />';

    return $image;
}

////
// Output a form
function tep_draw_form($name, $action, $method = 'post', $parameters = '') {
    $form = '<form name="' . tep_output_string($name) . '" action="' . tep_output_string($action) . '" method="' . tep_output_string($method) . '"';

    if (tep_not_null($parameters))
        $form .= ' ' . $parameters;

    // AJAX Addto shopping_cart - Begin
    if (preg_match("/add_product/i", $action)) {
        $form .= ' onSubmit="doAddProduct(this); return false;"';
    }
    // AJAX Addto shopping_cart - End
    $form .= '>';

    return $form;
}

////
// Output a form input field
function tep_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true, $size = '') {
    $field = '<input size="' . $size . '"  type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if ((isset($GLOBALS[$name])) && ($reinsert_value == true)) {
        $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (tep_not_null($value)) {
        $field .= ' value="' . tep_output_string($value) . '"';
    }

    if (tep_not_null($parameters))
        $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
}

////
// Output a form password field
function tep_draw_password_field($name, $value = '', $parameters = 'maxlength="40"') {
    return tep_draw_input_field($name, $value, $parameters, 'password', false);
}

////
// Output a selection field - alias function for tep_draw_checkbox_field() and tep_draw_radio_field()
function tep_draw_selection_field($name, $type, $value = '', $checked = false, $parameters = '') {
    $selection = '<input type="' . tep_output_string($type) . '" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value))
        $selection .= ' value="' . tep_output_string($value) . '"';

    if (($checked == true) || ( isset($GLOBALS[$name]) && is_string($GLOBALS[$name]) && ( ($GLOBALS[$name] == 'on') || (isset($value) && (stripslashes($GLOBALS[$name]) == $value)) ) )) {
        $selection .= ' CHECKED';
    }

    if (tep_not_null($parameters))
        $selection .= ' ' . $parameters;

    $selection .= '>';

    return $selection;
}

////
// Output a form checkbox field
function tep_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'checkbox', $value, $checked, $parameters);
}

////
// Output a form radio field
function tep_draw_radio_field($name, $value = '', $checked = false, $parameters = '') {
    return tep_draw_selection_field($name, 'radio', $value, $checked, $parameters);
}

////
// Output a form textarea field
function tep_draw_textarea_field($name, $wrap, $width, $height, $text = '', $parameters = '', $reinsert_value = true) {
    $field = '<textarea name="' . tep_output_string($name) . '" wrap="' . tep_output_string($wrap) . '" cols="' . tep_output_string($width) . '" rows="' . tep_output_string($height) . '"';

    if (tep_not_null($parameters))
        $field .= ' ' . $parameters;

    $field .= '>';

    if ((isset($GLOBALS[$name])) && ($reinsert_value == true)) {
        $field .= tep_output_string_protected(stripslashes($GLOBALS[$name]));
    } elseif (tep_not_null($text)) {
        $field .= tep_output_string_protected($text);
    }

    $field .= '</textarea>';

    return $field;
}

////
// Output a form hidden field
function tep_draw_hidden_field($name, $value = '', $parameters = '') {
    $field = '<input type="hidden" name="' . tep_output_string($name) . '"';

    if (tep_not_null($value)) {
        $field .= ' value="' . tep_output_string($value) . '"';
    } elseif (isset($GLOBALS[$name])) {
        $field .= ' value="' . tep_output_string(stripslashes($GLOBALS[$name])) . '"';
    }

    if (tep_not_null($parameters))
        $field .= ' ' . $parameters;

    $field .= '>';

    return $field;
}

////
// Hide form elements
function tep_hide_session_id() {
    global $session_started, $SID;

    if (($session_started == true) && tep_not_null($SID)) {
        return tep_draw_hidden_field(tep_session_name(), tep_session_id());
    }
}

////
// Output a form pull down menu
function tep_draw_pull_down_menu($name, $values, $default = '', $parameters = '', $required = false, $mode = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters))
        $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name]))
        $default = stripslashes($GLOBALS[$name]);

    for ($i = 0, $n = sizeof($values); $i < $n; $i++) {
        if ($mode) {
            $prefix = 'data-prefix="' . tep_output_string($values[$i]['prefix']) . '"';
        } else {
            $prefix = '';
        }
        $field .= '<option ' . $prefix . ' value="' . tep_output_string($values[$i]['id']) . '"';
        if ($default == $values[$i]['id']) {
            $field .= ' SELECTED';
        }

        $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true)
        $field .= TEXT_FIELD_REQUIRED;

    return $field;
}

function tep_draw_pull_down_menu_for_row_by_page($name, $values, $default = '', $parameters = '', $required = false) {
    $field = '<select name="' . tep_output_string($name) . '"';

    if (tep_not_null($parameters))
        $field .= ' ' . $parameters;

    $field .= '>';

    if (empty($default) && isset($GLOBALS[$name]))
        $default = stripslashes($GLOBALS[$name]);

    for ($i = 1, $n = sizeof($values); $i < $n; $i++) {
        $field .= '<option value="' . tep_output_string($values[$i]['id']) . '"';
        if ($default == $values[$i]['id']) {
            $field .= ' SELECTED';
        }
        $field .= '>' . tep_output_string($values[$i]['text'], array('"' => '&quot;', '\'' => '&#039;', '<' => '&lt;', '>' => '&gt;')) . '</option>';
    }
    $field .= '</select>';

    if ($required == true)
        $field .= TEXT_FIELD_REQUIRED;

    return $field;
}

////
// Creates a pull-down list of countries
function tep_get_country_list($name, $selected = '', $parameters = '') {
    $countries_array = array(array('id' => '', 'text' => PULL_DOWN_DEFAULT));
    $countries = tep_get_countries();

    for ($i = 0, $n = sizeof($countries); $i < $n; $i++) {
        $countries_array[] = array('id' => $countries[$i]['countries_id'], 'text' => $countries[$i]['countries_name']);
    }

    return tep_draw_pull_down_menu($name, $countries_array, $selected, $parameters);
}