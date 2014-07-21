<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once "../../../helpers/Grid.php";
require_once "../../class.buildView.php";
header('Content-type: text/javascript');
$params = array("numRows" => 10, "sortname" => "Name");
$view = new buildView("familyDistributor", $params, "familyinformation");
?>
