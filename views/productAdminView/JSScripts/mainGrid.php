<?php
require_once "../../../helpers/Grid.php";
require_once "../../../helpers/Charts.php";
require_once "../../class.buildView.php";
require_once "../../class.buildChartView.php";

$chartParams = array("title"=>"totalcodesmonth","subtitle"=>"puntoacopio", "queryId" => "pieCodes","chartConfig" => array());
$viewChartC = new buildChartView("pie",$chartParams,"TotalCodesChart","codeproducttotal");

$chartParams = array("title"=>"totalvalidatecodesmonth","subtitle"=>"puntoacopio", "queryId" => "pieValidateCodes","chartConfig" => array());
$viewChartV = new buildChartView("pie",$chartParams,"TotalValidateCodesChart","codeproducttotal");

$chartParams = array("title"=>"totalvalidatecodescities","subtitle"=>"puntoacopio", "queryId" => "geoCodes","chartConfig" => array());
$viewChartGeo = new buildChartView("geo",$chartParams,"geoCodesChart","codeproducttotal");
?>
