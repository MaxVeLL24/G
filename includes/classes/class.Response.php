<?php
class Response{
	public static function json(array $data=array()){
		$output = array();
		foreach ($data as $key => $value) {
				$output[$key] = $value;
		}
		echo json_encode($output);
		die();
	}
}
?>