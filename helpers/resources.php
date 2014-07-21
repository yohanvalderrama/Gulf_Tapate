<?php 
class resources{
	private $locale;
	
	function __construct($l = "es"){
		$locale = array();
		require_once 'resources/'. $l .'.php';
		$this->locale = $locale;
	}
	
	public function getWord($key){
		if(is_array ( $this->locale ))
			$word = (array_key_exists($key, $this->locale ))? $this->locale[$key] : $key;
		else 
			$word = $key;
		return	$word;
	}
}
?>