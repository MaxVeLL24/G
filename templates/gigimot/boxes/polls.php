<div id="polls_box" class="box clearfix">
    <div class="box__title"><?php echo _POLLS; ?></div>
    <div class="box__content">
        <?php
        $hide = tep_hide_session_id();

        function pollnewest()
        {
            global $customer_id, $_GET;
            if(DISPLAY_POLL_HOW == 3)
            {
                $extra_query = " and pollID='" . DISPLAY_POLL_ID . "'";
            }
            if(!tep_session_is_registered('customer_id'))
            {
                $extra_query.=" and poll_type='0' ";
            }
            if(DISPLAY_POLL_HOW == 2)
            {
                $order = 'voters DESC';
            }
            else
            {
                $order = 'timestamp DESC';
            }
            if(DISPLAY_POLL_HOW == 0)
            {
                $order = 'RAND()';
            }

            if(DISPLAY_POLL_HOW == 0)
            {
                $query = tep_db_query("select pollid, catID FROM phesis_poll_desc where poll_open='0'" . $extra_query . "and catID != 0 order by " . $order);
            }
            else
            {
                $query = tep_db_query("select pollid, catID FROM phesis_poll_desc where poll_open='0'" . $extra_query . "and catID != 0 order by " . $order);
            }

            $count = tep_db_num_rows($query);
            $result = tep_db_fetch_array($query);
            $pollid = false;
            if($count > 0)
            {
                if($_GET['cPath'])
                    $mypath = $_GET['cPath'];
                if($_GET['products_id'])
                    $mypath = tep_get_product_path($_GET['products_id']);
                if($mypath)
                {
                    $sub_cat_ids = split("[_]", $mypath);
                    for($i = 0; $i < count($sub_cat_ids); $i++)
                    {
                        if($sub_cat_ids[$i] == $result['catID'])
                            $pollid = $result['pollid'];
                    }
                }
            }
            $query = tep_db_query("select pollid, catID FROM phesis_poll_desc where poll_open='0'" . $extra_query . " and catID = 0 order by " . $order);
            $count = tep_db_num_rows($query);
            if((!DISPLAY_POLL_HOW == 0 || $count == 1) && !$pollid)
            {
                if($result = tep_db_fetch_array($query))
                {
                    $pollid = $result['pollid'];
                }
            }
            elseif(!$pollid)
            {
                mt_srand((double) microtime() * 1000000);
                $rand = mt_rand(1, $count);
                for($i = 0; $i < $rand; $i++)
                {
                    $result = tep_db_fetch_array($query);
                    $pollid = $result['pollid'];
                }
            }
            return $pollid;
        }

        if(basename($PHP_SELF) != 'pollbooth.php')
        {
            $pollid = pollnewest();

            if($pollid)
            {
                ?>

                <?php
                $poll_query = tep_db_query("select voters from phesis_poll_desc where pollid=$pollid and poll_open='0'");
                $poll_details = tep_db_fetch_array($poll_query);
                $title_query = tep_db_query("select optionText from phesis_poll_data where pollid=$pollid and voteid='0' and language_id = '" . $languages_id . "'");
                $title = tep_db_fetch_array($title_query);


                $url = tep_href_link('pollbooth.php', 'op=results&pollid=' . $pollid);
                $cont = "<div class=\"polls_1\">" . $title['optionText'] . "";
                $cont .= "<input type=\"hidden\" name=\"pollid\" value=\"" . $pollid . "\">\n";
                $cont .= "<input type=\"hidden\" name=\"forwarder\" value=\"" . $url . "\"></div>\n";
                for($i = 1; $i <= 15; $i++)
                {
                    $query = tep_db_query("select pollid, optiontext, optioncount, voteid from phesis_poll_data where (pollid=$pollid) and (voteid=$i) and (language_id=$languages_id)");
                    // debug(tep_db_fetch_array($query));
                    if($result = tep_db_fetch_array($query))
                    {
                        if($result['optiontext'])
                        {
                            $cont .= '<div class="radiobox-group">'
                                  .  '<span class="custom-radiobox">'
                                  .  '<input type="radio" name="voteid" id="voteid-' . $i . '" value="' . $i . '" />'
                                  .  '<label for="voteid-' . $i . '"></label>'
                                  .  '</span>'
                                  .  '<label for="voteid-' . $i . '">' . $result['optiontext'] .  '</label>'
                                  .  '</div>';
                        }
                    }
                }
                $cont .= '<div class="buttons-block"><button class="button" type="submit">' . _VOTE . '</button></div><div>';
                $query = tep_db_query("select sum(optioncount) as sum from phesis_poll_data where pollid=$pollid");
                $query1 = tep_db_query("select count(pollid) as comments from phesis_comments where pollid=$pollid and language_id=$languages_id");
                $result1 = tep_db_fetch_array($query1);
                $comments1 = $result1['comments'];
                if($result = tep_db_fetch_array($query))
                {
                    $sum = $result['sum'];
                }
                $cont .= _VOTES . "<b>" . $sum . "</b><br /><a href=\"" . tep_href_link('pollbooth.php', 'op=results&pollid=' . $pollid, 'NONSSL') . "\">" . _RESULTS . "</a> / <a href=\"" . tep_href_link('pollbooth.php', 'op=list') . "\">" . _POLLS . "</a></div><br />";

                $info_box_contents = array();
                $info_box_contents[] = array('form' => '<form name="poll" method="post" action="' . tep_href_link('pollcollect.php') . '">',
                    'params' => '',
                    'text' => '<div width="100%">' . $cont . '</div>'
                );
                new contentBox($info_box_contents);
            }
            elseif(SHOW_NOPOLL == 1)
            {
                ?>
                <div>
                    <?php
                    $info_box_contents = array();
                    $info_box_contents[] = array('text' => '<font color="' . $font_color . '">' . _NOPOLLS . '</font>');
                    new infoBoxHeading($info_box_contents, false, false);

                    $info_box_contents = array();
                    $info_box_contents[] = array('align' => 'center',
                        'text' => _NOPOLLSCONTENT
                    );
                    new infoBox($info_box_contents);
                    ?>
                </div>

                <?php
            }
        }
        ?>
    </div>
</div>