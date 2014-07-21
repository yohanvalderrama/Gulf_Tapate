<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once "../../../helpers/Grid.php";
require_once "../../class.buildView.php";
header('Content-type: text/javascript');
$params = array("numRows" => 10
                , "sortname" => "date_entered"
                , "actions" => array(
                    array("type" => "onSelectRow"
                        ,"function" => 'function(id) {
                            if(id != null) {
                                $.post( "'.$pluginURL.'edit.php?controller=codeproducttotal", {"oper":"count", "filter":id} )
                                    .done(function( datavalue ) {
                                        
                                        if(parseInt(datavalue)==0){
                                            jQuery("#csv_codeproducttotal").hide();
                                        }
                                        else{
                                            jQuery("#csv_codeproducttotal").show();
                                        };
                                    });
                            }
                        }'
                    )
                 )
            );
$view = new buildView("codeproducttotal", $params, "codeproducttotal");
?>
