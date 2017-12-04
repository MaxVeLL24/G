<?php
   if(isset($_GET['score'])){ 
      //function connection Db 
      chdir('../../../');
      require('includes/configure.php');
      require('includes/functions/database.php');
   }

	class rating{

	public $average = 0;
	public $averageP = 0;
	public $votes;
	public $status;
	public $table;
	private $path;
	public $ip;
	public $personalVotes;
	
	
	function __construct($table,$ip){
      	try{
			//connection DB  Mysql
			tep_db_connect();
			$statement = tep_db_query("SELECT rating FROM ratings where product_id='$table'");
			$total = $quantity = 0;
			while($row = tep_db_fetch_array($statement)){
				$total = $total + $row['rating'];
				$quantity++;
			}
			if($quantity==0){
			   $this->average = 0;
			}
			else{
				$this->average = round((($total*20)/$quantity),0);
			}		 
			
			//Para rating actual
			$personalVotes=0;
			$statement = tep_db_query("SELECT rating FROM ratings where product_id='$table' and ip_address='$ip'");
			if($row = tep_db_fetch_array($statement)){
				$personalVotes = $row['rating'];
			}
			$this->averageP = ($personalVotes*20);
	
	    }catch( Exception $exception ){
			die($exception->getMessage());
	    }
		$dbh = NULL;		
	}

	function set_score($score, $ip, $product){
		try{
		     $type=0;
			 if(strpos($ip,'.')==0){
			   $type=1;
			 }
		    $voted = tep_db_query("SELECT ratings_id FROM ratings WHERE product_id='$product' and ip_address='$ip'");
			
			if(tep_db_num_rows($voted )==0){
				tep_db_query("INSERT INTO ratings (ip_address,product_id,rating,type) VALUES ('$ip',$product,$score,$type)");
				$this->votes++;
				
				$statement = tep_db_query("SELECT rating FROM ratings where product_id='$product'");
				$total = $quantity = 0;
				while($row = tep_db_fetch_array($statement)){
					$total = $total + $row['rating'];
					$quantity++;
				}
				 if($quantity==0){
					$this->average = 0;
				 } 
				 else{
					$this->average = round((($total*20)/$quantity),0);
				}	
				//Para rating actual
				$personalVotes=0;
				$statement = tep_db_query("SELECT rating FROM ratings where product_id='$product' and ip_address='$ip'");
				if($row = tep_db_fetch_array($statement)){
					$personalVotes = $row['rating'];
				}
				$this->averageP = ($personalVotes*20);
				
			
				
			} else {
				
				tep_db_query("update ratings set rating=$score where product_id='$product' and ip_address='$ip'");
				
				$statement = tep_db_query("SELECT rating FROM ratings where product_id='$product'");
				$total = $quantity = 0;
				while($row = tep_db_fetch_array($statement)){
					$total = $total + $row['rating'];
					$quantity++;
				}
				if($quantity==0){
				   $this->average = 0;
				}
				else{
					$this->average = round((($total*20)/$quantity),0);
				}
				//Para rating actual
				$personalVotes=0;
				$statement = tep_db_query("SELECT rating FROM ratings where product_id='$product' and ip_address='$ip'");
				if($row = tep_db_fetch_array($statement)){
					$personalVotes = $row['rating'];
				}
				$this->averageP = ($personalVotes*20);
				
				
				
				
				
			}
			
		}catch( Exception $exception ){
				die($exception->getMessage());
		}
		$dbh = NULL;
	}
}

function rating_form($table,$mode=''){
    if ($_SESSION['customer_id']!=""){
	      $ip = $_SESSION['customer_id'];
    }
    else{
      $ip = $_SERVER["REMOTE_ADDR"];
    }
	//echo "table: $table";
	if(!isset($table) && isset($_GET['table'])){
		$table = $_GET['table'];
	}
	$rating = new rating($table,$ip);
	$status = '<div class="score score_pr'.$table.'">
				<a class="score1" id="?score=1&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(1,'.$table.',\'' . $ip . '\');">1</a>
				<a class="score2" id="?score=2&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(2,'.$table.',\'' . $ip . '\');">2</a>
				<a class="score3" id="?score=3&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(3,'.$table.',\'' . $ip . '\');">3</a>
				<a class="score4" id="?score=4&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(4,'.$table.',\'' . $ip . '\');">4</a>
				<a class="score5" id="?score=5&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(5,'.$table.',\'' . $ip . '\');">5</a>
			</div>';
			
	if(isset($_GET['score'])){
		$score = $_GET['score'];
		$product = $_GET['table'];
		$ip= $_GET['user'];
		
		if(is_numeric($score) && $score <=5 && $score >=1 && ($table==$_GET['table']) && isset($_GET["user"])){
			$rating->set_score($score, $ip,$product);
			$status = $rating->status;
		}
	}
	
	if($mode == 'listing'){
		if(!isset($_GET['update'])){ $z_rate = "<div class='rating_wrapper'>"; }
		
		$z_rate .= '<div class="sp_rating">
			<div class="base">
				<div class="average" style="width:'.$rating->average.'%">'.$rating->average.'</div></div>
				<div class="status">'.$status.'</div>
			</div>';
		if(!isset($_GET['update'])){ $z_rate .= "</div>"; }
		return $z_rate;
	}else{
		if(!isset($_GET['update'])){ echo "<div class='rating_wrapper'>"; }
		?>
		<div class="sp_rating">

			<div class="base"><div class="average" style="width:<?php echo $rating->average; ?>%"><?php echo $rating->average; ?></div></div>
			
			<div class="status">
				<?php echo $status; ?>
			</div>	
		</div>
		<?php
		if(!isset($_GET['update'])){ echo "</div>"; }
	}
	
	
}

function rating_form2($table){
    $r_text ='';
    if ($_SESSION['customer_id']!=""){
	      $ip = $_SESSION['customer_id'];
    }
    else{
      $ip = $_SERVER["REMOTE_ADDR"];
    }
	//echo "table: $table";
	if(!isset($table) && isset($_GET['table'])){
		$table = $_GET['table'];
	}
	$rating = new rating($table,$ip);
	$status = '<div class="score score_pr'.$table.'">
				<a class="score1" id="?score=1&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(1,'.$table.',\'' . $ip . '\');">1</a>
				<a class="score2" id="?score=2&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(2,'.$table.',\'' . $ip . '\');">2</a>
				<a class="score3" id="?score=3&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(3,'.$table.',\'' . $ip . '\');">3</a>
				<a class="score4" id="?score=4&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(4,'.$table.',\'' . $ip . '\');">4</a>
				<a class="score5" id="?score=5&amp;table='.$table.'&amp;user='.$ip.'" href="javascript:rrating(5,'.$table.',\'' . $ip . '\');">5</a>
			</div>';
	if(isset($_GET['score'])){
		$score = $_GET['score'];
		$product = $_GET['table'];
		$ip= $_GET['user'];
		
		if(is_numeric($score) && $score <=5 && $score >=1 && ($table==$_GET['table']) && isset($_GET["user"])){
			$rating->set_score($score, $ip,$product);
			$status = $rating->status;
		}
	}
	
	if(!isset($_GET['update'])){ $r_text .= "<div class='rating_wrapper'>"; }
	$r_text .='
	<div class="sp_rating">

		<div class="base"><div class="average" style="width:'.$rating->average.'%">'.$rating->average.'</div></div>
		
		<div class="status">
			'.$status.'
		</div>	
	</div>';
	
	if(!isset($_GET['update'])){ $r_text .= "</div>"; }
	return $r_text;
}

if(isset($_GET['update'])&&isset($_GET['table'])){
	rating_form($_GET['table']);
}
