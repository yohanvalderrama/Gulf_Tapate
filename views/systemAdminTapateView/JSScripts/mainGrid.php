<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
require_once "../../../helpers/Grid.php";
require_once "../../../helpers/Charts.php";
require_once "../../class.buildView.php";
require_once "../../class.buildChartView.php";
$chartParams = array("title"=>"distributorxdistributionpoint");

$chartParams = array("title"=>"distributorxdistributionpoint","subtitle"=>"puntoacopio", "queryId" => "pieDistributor","chartConfig" => array());


$viewChart = new buildChartView("pie",$chartParams,"distributorChart","distributor");

$chartParams = array("title"=>"distributorxdistributionpoint","subtitle"=>"puntoacopio", "queryId" => "pieDistributor","chartConfig" => array());
$viewChartGeo = new buildChartView("geo",$chartParams,"distributionPointChart","cities");
/*header('Content-type: text/javascript');
$params = array("numRows" => 10
                , "sortname" => "nonConformityId"
                , "actions" => array(
                                        array("type" => "onSelectRow"
                                                  ,"function" => 'function(id) {
                                                                    if(id != null) {
                                                                            var postDataObj = jQuery("#notes").jqGrid("getGridParam","postData");
                                                                            postDataObj["filter"] = id;
                                                                            postDataObj["parent"] = "'.$_GET["view"].'";
                                                                            jQuery("#notes").jqGrid("setGridParam",{postData: postDataObj})
                                                                                            .trigger("reloadGrid");

                                                                            postDataObj = jQuery("#tasks").jqGrid("getGridParam","postData");
                                                                            postDataObj["filter"] = id;
                                                                            postDataObj["parent"] = "'.$_GET["view"].'";
                                                                            jQuery("#tasks").jqGrid("setGridParam",{postData: postDataObj})
                                                                                            .trigger("reloadGrid");
                                                                    }
                                                                }'
                                                )
                                    )
            );
$view = new buildView($_GET["view"], $params, "nonConformities");*/
?>
