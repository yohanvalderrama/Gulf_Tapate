<?php
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
/*
Plugin Name: Gulf_Tapate
Plugin URI: http://localhost
Description: Plugin registro de usuarios Tapate con Gulf
Version: 1.0
Author: Yohan Valderrama - Byron Otalvaro
Author URI: http://localhost
License: GPL2
*/
if(!function_exists('wp_get_current_user'))
    require_once(ABSPATH . "wp-includes/pluggable.php"); 
wp_cookie_constants();
global $current_user;
$current_user = wp_get_current_user();


/*ocultar opciones de edicion perfil wordpress*/
add_action( 'personal_options', 'ozh_personal_options');
function ozh_personal_options() {
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
    jQuery("#createuser .form-table:first, #createuser h3:first").remove();
    
    jQuery("#submit").remove();    
  });
</script>
<?php
}


/*Redireccionar deacuerdo al perfil*/
function soi_login_redirect($redirect_to, $request, $user)
{       
    if((is_array($user->roles) && in_array('administrator', $user->roles)))
            $return = admin_url('admin.php?page=systemAdminTapate');
    elseif((is_array($user->roles) && in_array('distributor', $user->roles))){           
            $return = admin_url('admin.php?page=distributorAdmin');
    }else{
         //$return = site_url();
         $return = admin_url('admin.php?page=loadCodes');
    }
    return $return;
} 
add_filter('login_redirect', 'soi_login_redirect', 10, 3);



require_once "pluginConfig.php";
require_once "views/mainView.php";
require_once 'controllers/mainController.php';
$controlerId = 0;
if(!empty($_POST["id"])){
    $controlerId = $_POST["id"];
}elseif(!empty($_GET["controller"])){
    $controlerId = $_GET["controller"];
}elseif(!empty($_REQUEST["page"])){
    $controlerId = $_REQUEST["page"];
}

if(!isset($controller))
    $controller = new mainController($controlerId);

function js_includer_opciones() {
       include("config.php");
}

?>