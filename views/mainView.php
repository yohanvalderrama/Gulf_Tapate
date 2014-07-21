<?php 
require_once $pluginPath . "/helpers/resources.php";
$viewName = (empty($_GET["page"]))? "basic": $_GET["page"]."View";
$viewFile = $pluginPath . "/views/" . $viewName . "/" . $viewName . ".php";
$resource = new resources();

if(!file_exists($viewFile)){
	$viewFile = $pluginPath. "/views/basicView/basicView.php";
}

function systemAdminTapate() {
	global $viewFile;
	global $resource;
	require_once($viewFile);
}

function distributorAdmin() {
	global $viewFile;
	global $resource;
	require_once($viewFile);
}

function productAdmin() {
	global $viewFile;
	global $resource;
	require_once($viewFile);
}

function loadCodes() {
    global $pluginPath;
    global $pluginURL;
    global $viewFile;
    global $resource;
    require_once($viewFile);
}


?>