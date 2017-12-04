<?php
	/**
	 * Google Sitemap Generator
	 * 
	 * Script to generate a Google sitemap for osCommerce based stores
	 *
	 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
	 * @version 1.2
	 * @link http://www.oscommerce-freelancers.com/ osCommerce-Freelancers
	 * @copyright Copyright 2006, Bobby Easland 
	 * @author Bobby Easland 
	 * @filesource
	 */
  
	/*
	 * Include the application_top.php script
	 */
	 //Modified by misstop.co.uk 01/12/2008
	 define('SEARCH_ENGINE_FRIENDLY_URLS', 'false');
	 require('includes/configure.php');
	 require(DIR_WS_INCLUDES . 'filenames.php');
	 require(DIR_WS_INCLUDES . 'database_tables.php');
	 require(DIR_WS_FUNCTIONS . 'database.php');
	 tep_db_connect() or die('Unable to connect to database server!');
	 require(DIR_WS_FUNCTIONS . 'general.php');
	 require(DIR_WS_FUNCTIONS . 'html_output.php');
	 //include_once('includes/application_top.php');
	 //Modification end 
	
	/*
	 * Send the XML content header
	 */
	header('Content-Type: text/xml');
	
	/*
	 * Echo the XML out tag
	 */
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
 <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php

	/*
	 * Define the uniform node function 
	 */
	function GenerateNode($data){
		$content = '';
		$content .= "\t" . '<url>' . "\n";
		$content .= "\t\t" . '<loc>'.trim($data['loc']).'</loc>' . "\n";
		$content .= "\t\t" . '<lastmod>'.trim($data['lastmod']).'</lastmod>' . "\n";
		$content .= "\t\t" . '<changefreq>'.trim($data['changefreq']).'</changefreq>' . "\n";
		$content .= "\t\t" . '<priority>'.trim($data['priority']).'</priority>' . "\n";
		$content .= "\t" . '</url>' . "\n";
		return $content;
	} # end function

	/*
	 * Define the SQL for the products query 
	 */
	$sql = "SELECT manufacturers_id as mID,
								 manufacturers_name as name, 
								 date_added, 
								 last_modified as last_mod 
					FROM " . TABLE_MANUFACTURERS . " 
					ORDER BY last_modified DESC, 
					         date_added DESC";
	
	/*
	 * Execute the query
	 */
	$query = tep_db_query($sql);

	/*
	 * If there are returned rows...
	 * Basic sanity check 
	 */
	if ( tep_db_num_rows($query) > 0 ){

		/*
		 * Initialize the variable containers
		 */
		$container = array();
		$number = 0;
		$top = 0;

		/*
		 * Loop the query result set
		 */
		while( $result = tep_db_fetch_array($query) ){
			$top = max($top, $result['date_added']);
			$location = tep_href_link(FILENAME_DEFAULT, 'manufacturers_id=' . $result['mID'], 'NONSSL', false);
			if ( tep_not_null($result['last_mod']) ){
				$lastmod = $result['last_mod'];
			} else {
				$lastmod = $result['date_added'];
			}
			$changefreq = 'weekly';
			$ratio = ($top > 0) ? ($result['date_added']/$top) : 0;
			$priority = $ratio < .1 ? .1 : number_format($ratio, 1, '.', ''); 
			
			/*
			 * Initialize the content container array
			 */
			$container = array('loc' => htmlspecialchars(utf8_encode($location)),
								 				 'lastmod' => date ("Y-m-d", strtotime($lastmod)),
								 				 'changefreq' => $changefreq,
								 				 'priority' => $priority
												);

			/*
			 * Echo the generated node
			 */
			echo generateNode($container);
		} # end while
	} # end if
	
	/*
	 * Close the urlset
	 */
	echo '</urlset>';

	/*
	 * Include the application_bottom.php script 
	 */
	 //Modified by misstop.co.uk 01/12/2008
	 //include_once('includes/application_bottom.php');
	 //Modification end 
?>