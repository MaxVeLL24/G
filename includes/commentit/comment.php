<?php
#This work CommentIt 5 Ajax
error_reporting(0);
if(!isset($nocommentit))
{
    $error = '';
    include_once (__DIR__ . '/config.php');
    if($_SERVER["REQUEST_URI"] == "/" . $wwp . "/comment.php")
    {
        exit('This work CommentIt 5 Ajax');
    }
    include_once (__DIR__ . '/lang/' . $mylang . '.php');
    if(!$error)
    {
        if(!function_exists('json_encode'))
        {
            include_once (__DIR__ . '/json.php');
        }
        include_once (__DIR__ . '/func.php');
        if($massparam['sort'] == 1)
            echo '<a name="commentitstart"></a> ';
        if($massparam['loginzaglob'] == 1)
        {
            if(!$_SESSION['djos']['identity'])
            {
                $_SESSION['djos'] = json_decode(file_get_contents('http://loginza.ru/api/authinfo?token=' . $_POST['token'] . ''), true);
            }
        }
        echo "\r\n<script src='/" . $wwp . "/ajax.js.php' type='text/javascript'></script>\r\n";
#Comments
        echo "<div id='ok'>";
        view2();
        echo "</div>";
        echo '<div id="tableDiv" style="display:none;"><img alt="" title="" src="/' . $wwp . '/im/loader.gif" border="0" align="absmiddle" />  ' . $langcommentit['core_loading'] . '</div>';
#
#Add Forms

        if(!isset($staticcommentit))
        {
            echo '<h3>' . ADD_COMMENT_HEAD_TITLE . tep_escape($product_info['products_name'])."</h3>";
            echo "<div id='addfomz'>";
            if($massparam['loginzaglob'] == 1)
            {
                if(empty($_SESSION['djos']['error_message']))
                {
                    viewform();
                }
                else
                {
                    loginza();
                    if($massparam['blockeasy'])
                    {
                        viewform();
                    }
                }
            }
            else
            {
                viewform();
            }echo "</div>";
        }
#
        ?>
        <input type="hidden" name="url" id="urls" value="<?php echo $specurl; ?>" />
        <input type="hidden" name="for" id="forms" value="123" />
        <input type="hidden" name="idcom" id="idcomnow" value="0" />
        <input type="hidden" name="oldid" id="oldid" value="addfomz" />
        <input type="hidden" name="token" id="token" value="<?php echo @$_POST['token']; ?>" />
        <?php
    }
    else
    {
        echo $error;
    }
}