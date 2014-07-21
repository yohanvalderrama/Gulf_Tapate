<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
class mainController
{
	private $pluginPath = "";
	private $pluginURL = "";
	private $model;
	private $viewObject;
	private $prefix;
	private $PrefixPlugin;
	private $controllerName;
	private $headScripts = array();
	private $resource;
	private $userPages;
	function __construct($controller = "basic", $showView = true) {

		global $prefixPlugin;
		global $pluginURL;
		global $pluginPath;
                global $resource;
		global $user_page;
                if(isset($_POST['subview'])){
                    $controller = $_POST['subview'];                   
                }
		
		
		$this->userPages = $user_page;
		$this->prefix = $prefixPlugin;
		$this->pluginURL = $pluginURL;
		$this->pluginPath = $pluginPath;
		$this->resource = $resource;
		
		$controllerFile = $this->pluginPath."/models/".$controller."Model.php";
                
		if(file_exists($controllerFile)){ 
			require_once($controllerFile);
		}                
		else
		{                    
                    $controller = "basic"; //echo "ok2";
                    require_once($this->pluginPath. "/models/basicModel.php");
		}
		 
		$this->model = new $controller();
		$this->controllerName = $controller;		
                
		$this->PrefixPlugin = $this->model->pluginPrefix;
				
		if(substr_count($_SERVER["SCRIPT_NAME"], "admin-ajax") == 0)
		{
                    if($showView){
                        add_action('admin_head', array($this, 'setHeadScripts'));
                        add_action('admin_head', array($this, 'viewJSScripts'));
                    }
		}
                
                if($showView){
                    
                    $this->view = $controller."View";
                   
                    add_action( 'admin_menu', array($this, 'Plugin_menu') );
                    add_action( 'wp_ajax_action', array($this, 'action_callback'));
                }
	}
	
	function __destruct() {
	}
	
	function Plugin_menu() {
		
		//print_r($this->userPages->roles[0]);
		
		$object = $this->model->getDataGrid("SELECT * FROM ".$this->PrefixPlugin."menus WHERE MenuStatus = 0 ORDER BY PositionMenu ASC ",0, 200); 
		$menus = $object["data"];
		$countMenus = count($menus);
		
		for ( $i = 0; $i <  $countMenus; $i++)
		{
			if( $menus[$i]->FunctionMenu == 'distributorAdmin' && $this->userPages->roles[0] == 'subscriber'  )
			{
				"";//print_r(this->userPages->roles);
				
			}else{
				if($menus[$i]->MenuType == 1)
					add_menu_page($menus[$i]->PageTitle,$menus[$i]->MenuTitle, $menus[$i]->Capability, $menus[$i]->MenuSlug, $menus[$i]->FunctionMenu);
				else
					add_submenu_page( $menus[$i]->PageTitle, $menus[$i]->MenuTitle,$menus[$i]->MenuTitle, $menus[$i]->Capability, $menus[$i]->MenuSlug, $menus[$i]->FunctionMenu );
			}
		}
	}
	
	function setHeadScripts()
	{
		wp_register_style( 'bootstrapCss', $this->pluginURL . 'css/bootstrap.css');
		wp_enqueue_style( 'bootstrapCss' );
		wp_register_style( 'uiCss', $this->pluginURL . 'css/jqGrid/themes/ui-lightness/jquery-ui.min.css');
		wp_enqueue_style( 'uiCss' );
		wp_register_style( 'gridCss', $this->pluginURL . 'css/jqGrid/ui.jqgrid.css');
		wp_enqueue_style( 'gridCss' );
		wp_register_style( 'pluginCss', $this->pluginURL . 'css/plugincss.css');
		wp_enqueue_style( 'pluginCss' );
		
		
		$this->headScripts[] = 'jquery';
		wp_register_script('locale_es', $this->pluginURL . 'js/jqGrid/grid.locale-es.js', $this->headScripts);
		wp_enqueue_script('locale_es');
		
		$this->headScripts[] = 'locale_es';
		wp_register_script('jqGrid', $this->pluginURL . 'js/jqGrid/jquery.jqGrid.src.js', $this->headScripts);
		wp_enqueue_script('jqGrid');
		
		$this->headScripts[] = 'jqGrid';
		wp_register_script('pluginjs',  $this->pluginURL . 'js/pluginjs.js',$this->headScripts);
		wp_enqueue_script( 'pluginjs' );
		
		$this->headScripts[] = 'pluginjs';
		wp_register_script('jquery-u', $this->pluginURL . 'js/jquery-ui-1.10.4.custom.min.js' ,$this->headScripts);
		wp_enqueue_script('jquery-u');
		
		$this->headScripts[] = 'jquery-u';
		wp_register_script('jCombo', $this->pluginURL . 'js/jquery.jCombo.js' ,$this->headScripts);
		wp_enqueue_script('jCombo');
		
		$this->headScripts[] = 'jCombo';
		wp_register_script('bootstrap',  $this->pluginURL . 'js/bootstrap.js',$this->headScripts);
		wp_enqueue_script( 'bootstrap' );
		
		$this->headScripts[] = 'bootstrap';
                
                wp_register_script('googlechart', 'https://www.google.com/jsapi',$this->headScripts);
		wp_enqueue_script( 'googlechart' );
		
		$this->headScripts[] = 'googlechart';
		
	}
	
	function viewJSScripts() {
		$viewJSScripts = "/views/" . $this->view . "/JSScripts/";
		$JSPath = $this->pluginPath . $viewJSScripts;
		
		if(is_dir($JSPath))
		{
			$dir = opendir($JSPath);
			while ($file = readdir($dir)){
				if( $file != "." && $file != ".."){
					if(is_file($JSPath.$file)){
						$js =  $this->pluginURL . $viewJSScripts . $file ."?view=" . $this->controllerName;
						$registerName = str_replace(".","",$file)."_" . $this->controllerName;
						wp_register_script($registerName, $js, $this->headScripts);
						wp_enqueue_script($registerName);
                                               	$this->headScripts[] = $registerName;
					}
				}
			}
		}
	}
	
	function action_callback() {
		$responce = new StdClass;
		$page = $_POST['page']; // get the requested page
		$limit = $_POST['rows']; // get how many rows we want to have into the grid
		$sidx = $_POST['sidx']; // get index row - i.e. user click to sort
		$sord = $_POST['sord']; // get the direction
		if ($limit < 0) $limit = 0;
		
		if(!$sidx) $sidx =1;
		
		$params = array(
                                "page" => $page
                                ,"sidx" => $sidx
                                ,"sord" => $sord
                                ,"limit" => $limit
                            );
		
		if(array_key_exists('filter', $_POST))
			$params["filter"] = $_POST["filter"];

                if(array_key_exists('filters', $_POST) && !empty($_POST["filters"]))
                    $params["where"] = json_decode (stripslashes($_POST["filters"]));
                
		if(array_key_exists('method', $_POST))
			$grid = $this->model->$_POST["method"]($params);
		else
			$grid = $this->model->getList($params);

		if( $grid["totalRows"] > 0 && $limit > 0)
			$total_pages = ceil($grid["totalRows"]/$limit);
		else 
			$total_pages = 0;
		
		if ($page > $total_pages) $page = $total_pages;
		
		$responce->page = $page;
		$responce->total = $total_pages;
		$responce->records = $grid["totalRows"];
		
		$countRows = count($grid["data"]);
		$j = 0;
		for ( $i = 0; $i < $countRows; $i++ )
		{
			foreach ( $grid["data"][$i] as $key => $value ){
				if($j == 0){
					$responce->rows[$i]['id']=$value;
					$j = 1;
				}
				$responce->rows[$i]['cell'][]=$value;
			}
			$j = 0;
		}
		
		//echo JSONUNESCAPEDUNICODE(json_encode($responce));
		echo json_encode($responce);
		die();
	}
        
        function editOper($oper){
            $this->model->$oper();
        }
        
        
}
?>