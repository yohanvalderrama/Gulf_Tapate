<?php
class buildView extends Grid
{
	private $gridType;
	function __construct($v = "basic", $params = array(), $t = "", $s = "", $subparams = array()) {		
                    $this->gridType = (empty($t))? "query" : "table";
		parent::__construct($this->gridType, $params, $v, $t, $s, $subparams);
	}
}
?>