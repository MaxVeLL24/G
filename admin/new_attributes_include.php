<TR>
<TD class="pageHeading" colspan="9"><?=$pageTitle?></TD>
</TR>
<FORM ACTION="<?=$PHP_SELF?>" METHOD="POST" NAME="SUBMIT_ATTRIBUTES">
<INPUT TYPE="HIDDEN" NAME="current_product_id" VALUE="<?=$current_product_id?>">
<INPUT TYPE="HIDDEN" NAME="action" VALUE="change">
<?

if ( $cPath ) echo "<INPUT TYPE=\"HIDDEN\" NAME=\"cPathID\" VALUE=\"" . $cPath . "\">";

require( 'new_attributes_functions.php');

// Temp id for text input contribution.. I'll put them in a seperate array.
$tempTextID= "1999043";

// Lets get all of the possible options

$query = "SELECT * FROM products_options where products_options_id LIKE '%' AND language_id = '$languageFilter'";

$result = mysql_query($query) or die(mysql_error());

$matches = mysql_num_rows($result);
// ########################## added for download
//        echo "<p class=\"smallText\">(Download-Dateien nur in Optionen, die im Namen 'download' enthalten!)</p>";
// ########################## added for download
if ($matches) {

   while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
                                                           	
        $current_product_option_name = $line['products_options_name'];
        $current_product_option_id = $line['products_options_id'];

// Print the Option Name
        echo "<TR class=\"dataTableHeadingRow\">";
        echo "<TD class=\"dataTableHeadingContent\" width=150><B>" . $current_product_option_name . "</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_PREFIX . "</B></TD>";
        echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_PRICE . "</B></TD>";
        
        if ( $optionTypeInstalled == "1" ) {
                
                echo "<TD class=\"dataTableHeadingContent\"><B>Option Type</B></TD>";
                echo "<TD class=\"dataTableHeadingContent\"><B>Quantity</B></TD>";
                echo "<TD class=\"dataTableHeadingContent\"><B>Order</B></TD>";
                echo "<TD class=\"dataTableHeadingContent\"><B>Linked Attr.</B></TD>";
                echo "<TD class=\"dataTableHeadingContent\"><B>ID</B></TD>";
                
        }
        
                echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_PREFIX . "</B></TD>";
                echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_WEIGHT . "</B></TD>";
                echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_SORT_ORDER . "</B></TD>";

// ############################# added for download
        if (DOWNLOAD_ENABLED == 'true') 
        {
//                if(preg_match('/download/',strtolower($current_product_option_name)))
//                {
						        echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_FILE . "</B></TD>";
						        echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_MAX_COUNT . "</B></TD>";
						        echo "<TD class=\"dataTableHeadingContent\"><B>". TEXT_NEW_ATTRIBUTE_MAX_DOWNLOAD . "</B></TD>";
//                }
        }
// ############################# end added for download

        echo "</TR>";
        
// Find all of the Current Option's Available Values
        $query2 = "SELECT * FROM products_options_values_to_products_options WHERE products_options_id = '$current_product_option_id' ORDER BY products_options_values_id DESC";

        $result2 = mysql_query($query2) or die(mysql_error());
        
        $matches2 = mysql_num_rows($result2);

        if ($matches2) {

           $i = "0";

           while ($line = mysql_fetch_array($result2, MYSQL_ASSOC)) {

                $i++;
                
                $rowClass = rowClass( $i );

                $current_value_id = $line['products_options_values_id'];

                $isSelected = checkAttribute( $current_value_id, $current_product_id, $current_product_option_id );

                if ($isSelected) { $CHECKED = " CHECKED"; } else { $CHECKED = ""; }
                
                $query3 = "SELECT * FROM products_options_values WHERE products_options_values_id = '$current_value_id' AND language_id = '$languageFilter'";
                
                $result3 = mysql_query($query3) or die(mysql_error());

                while($line = mysql_fetch_array($result3, MYSQL_ASSOC)) {

                        $current_value_name = $line['products_options_values_name'];

// Print the Current Value Name
                        echo "<TR class=\"" . $rowClass . "\">";
                        echo "<TD class=\"main\" width=150>";

// Add Support for multiple text input option types (for Chandra's contribution).. and using ' to begin/end strings.. less of a mess.
                        if ( $optionTypeTextInstalled == "1" && $current_value_id == $optionTypeTextInstalledID ) {

                                $current_value_id_old = $current_value_id;
                                $current_value_id = $tempTextID;

                                echo '<input type="checkbox" name="optionValuesText[]" value="' . $current_value_id . '"' . $CHECKED . '>&nbsp;&nbsp;' . $current_value_name . '&nbsp;&nbsp;';
                                echo '<input type="hidden" name="' . $current_value_id . '_options_id" value="' . $current_product_option_id . '">';
                                
                        } else {

                        echo "<input type=\"checkbox\" name=\"optionValues[]\" value=\"" . $current_value_id . "\"" . $CHECKED . ">&nbsp;&nbsp;" . $current_value_name . "&nbsp;&nbsp;";
                        
                        }

                        echo "</TD>";
                                echo "<TD class=\"main\" align=\"right\"><SELECT name=\"" . $current_value_id . "_prefix\"> <OPTION value=\"+\"" . $posCheck . ">+<OPTION value=\"-\"" . $negCheck . ">-</SELECT></TD>";
                                getSortCopyValues( $current_value_id, $current_product_id );

                        echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_price\" value=\"" . $attribute_value_price . "\" size=\"10\"></TD>";
                               
                                echo "<TD class=\"main\" align=\"right\"><SELECT name=\"" . $current_value_id . "_weight_prefix\">";
                                sortCopyWeightPrefix( $attribute_weight_prefix );
                                echo "</SELECT></TD>";
                                                            	
                               
                                echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_weight\" value=\"" . $attribute_weight . "\" size=\"10\"></TD>";

                        echo "<TD class=\"smallText\" align=\"left\" NOWRAP><input type=\"text\" name=\"" . $current_value_id . "_sort\" value=\"" . $attribute_value_sort_order . "\" size=\"3\"></TD>";


// ############################# added for download
        if (DOWNLOAD_ENABLED == 'true') 
        {
//                if(preg_match('/download/',strtolower($current_product_option_name)))
//                {
	                $query4 = "SELECT pa.products_attributes_id, 
	                pad.products_attributes_filename, 
	                pad.products_attributes_maxdays,
	                pad.products_attributes_maxcount 
	                FROM products_attributes pa,products_attributes_download pad 
	                WHERE pa.products_id='$current_product_id' AND pa.options_id='$current_product_option_id' AND pa.options_values_id = '$current_value_id' AND pad.products_attributes_id=pa.products_attributes_id";
	                $result4 = mysql_query($query4) or die(mysql_error());                   
	    
	                $current_products_attributes_id=$dl_line['products_attributes_id'];

	                $dl_line = mysql_fetch_array($result4, MYSQL_ASSOC);
                  
	                if($dl_line['products_attributes_filename']=='')
	                {
	                  $dl_line['products_attributes_maxdays']=DOWNLOAD_MAX_DAYS;
	                  $dl_line['products_attributes_maxcount']=DOWNLOAD_MAX_COUNT;
	                }

	                echo "<TD class=\"main\" align=\"left\"><input type=\"text\" id=\"" . $current_value_id . "_dlfile\"  name=\"" . $current_value_id . "_dlfile\" value=\"" . $dl_line['products_attributes_filename'] . "\" size=\"10\">&nbsp;<a href=\"javascript:number=" . $current_value_id . ";popupWindow('phpfm.php');\">" . TEXT_NEW_ATTRIBUTE_UPLOAD . "</a>&nbsp;</TD>";
	                echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_dldays\" value=\"" . $dl_line['products_attributes_maxdays'] . "\" size=\"10\"></TD>";
	                echo "<TD class=\"main\" align=\"left\"><input type=\"text\" name=\"" . $current_value_id . "_dlcount\" value=\"" . $dl_line['products_attributes_maxcount'] . "\" size=\"10\"></TD>";
//                }
/*
                else
                {
	                echo "<TD class=\"main\" align=\"left\">&nbsp;</TD>";
	                echo "<TD class=\"main\" align=\"left\">&nbsp;</TD>";
	                echo "<TD class=\"main\" align=\"left\">&nbsp;</TD>";
                }
*/
// ############################# end added for download
        }
                        echo "</TR>";

                        if ( $optionTypeTextInstalled == "1" && $current_value_id_old == $optionTypeTextInstalledID ) {

                           $tempTextID++;

                        }

                }
                
                if( $i == $matches2 ) { $i = "0"; }

           }

        } else {
          echo "<TR>";
          echo "<TD class=\"main\"><SMALL>" . TEXT_NEW_ATTRIBUTE_NO_VALUES . "</SMALL></TD>";
          echo "</TR>";


   }

}
}

?>
<TR>
<TD colspan="8" class="main"><BR><INPUT TYPE="image" src="<?=$adminImages?>button_save.gif">&nbsp;&nbsp;&nbsp;<?=$backLink?><img src="<?=$adminImages?>button_cancel.gif" border="0"></A></TD>
</TR>
</FORM>

