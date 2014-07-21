<?php
/*require_once dirname(__FILE__)."/../pluginConfig.php";
require_once(getPahtFile('wp-load.php') );
global $wpdb;
*/

        function caracterespReve($result)
	{
		$result = str_replace("&aacute;", "á", $result);
		$result = str_replace("&eacute;", "é", $result);
		$result = str_replace("&iacute;", "í", $result);
		$result = str_replace("&oacute;", "ó", $result);
		$result = str_replace("&uacute;", "ú", $result);
		$result = str_replace("&eacute;", "Ú", $result);
		$result = str_replace("&Aacute;", "Á", $result);
		$result = str_replace("&Eacute;", "É", $result);
		$result = str_replace("&Iacute;", "Í", $result);
		$result = str_replace("&Oacute;", "Ó", $result);
		$result = str_replace("&Uacute;", "Ú", $result);
		$result = str_replace("&ntilde;", "ñ", $result);
		$result = str_replace("&Ntilde;", "Ñ", $result);
		return $result;
	}
        function acentos($cadena) 
	{
	   $search = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ã¡,Ã©,Ã­,Ã³,Ãº,Ã±,ÃÃ¡,ÃÃ©,ÃÃ­,ÃÃ³,ÃÃº,ÃÃ±,Ã“,Ã ,Ã‰,Ã ,Ãš,â€œ,â€ ,Â¿,ü");
	   $replace = explode(",","á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,á,é,í,ó,ú,ñ,Á,É,Í,Ó,Ú,Ñ,Ó,Á,É,Í,Ú,\",\",¿,&uuml;");
	   $cadena= str_replace($search, $replace, $cadena);
	 
	   return $cadena;
	}
        if (!defined('JSON_UNESCAPED_UNICODE')) {

			define('JSON_UNESCAPED_SLASHES', 64);
		   
			define('JSON_UNESCAPED_UNICODE', 256);
		   
		    }
        $data = caracterespReve('POPAY\u00c1N');
$string = iconv(
mb_detect_encoding('POPAY\u00c1N'),
'UTF-8',
$string
);
echo $string . "\r\n";

echo mb_strlen('POPAYÀN', 'latin1');

/*echo convertToJson('POPAYÀN').'<br>';
echo raw_json_encode('POPAYÀN').'<br>';*/
//echo json_encode(htmlspecialchars('POPAYÀN', ENT_COMPAT));
echo json_encode($data);
 
/*
function rand_uniqid($in, $to_num = false, $pad_up = false, $passKey = null)
{
    $index = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($passKey !== null) {
        // Although this function's purpose is to just make the
        // ID short - and not so much secure,
        // you can optionally supply a password to make it harder
        // to calculate the corresponding numeric ID
        for ($n = 0; $n<strlen($index); $n++) {
            $i[] = substr( $index,$n ,1);
        }

        $passhash = hash('sha256',$passKey);
        $passhash = (strlen($passhash) < strlen($index))
            ? hash('sha512',$passKey)
            : $passhash;

        for ($n=0; $n < strlen($index); $n++) {
            $p[] =  substr($passhash, $n ,1);
        }

        array_multisort($p,  SORT_DESC, $i);
        $index = implode($i);
    }

    $base  = strlen($index);

    if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $in  = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out   = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a   = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in  = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
    }

    return $out;
}
date_default_timezone_set("America/Bogota");
//echo rand_uniqid(1);
for($i=0;$i<=60;$i++){
    $objDateTime = date('YmdHis'.$i);
   
    $codevalue = rand_uniqid($objDateTime.'5');

    $decodevalue = rand_uniqid($codevalue, true);

    echo $codevalue."-----".$decodevalue.'<br>';
}







$codevalue = rand_uniqid($objDateTime);

$decodevalue = rand_uniqid($codevalue, true);

echo $codevalue."-----".$decodevalue;
*/
/*
$result = add_role(
    'distributor',
    __( 'Distribuidor' ),
    array(
        'read'         => true,  // true allows this capability
        'edit_posts'   => false,
        'delete_posts' => false, // Use false to explicitly deny
    )
);
if ( null !== $result ) {
    echo 'Yay! New role created!';
}
else {
    echo 'Oh... the basic_contributor role already exists.';
}*/

        //674 usuarios
      /*      $query = "SELECT DISTINCT *
                    FROM `wp_tpt_formregisterusers` 
                    
                                        
            ";
            $data = $wpdb->get_results($query);
        
       
        
        foreach ($data as $key => $value){
            
            echo $key.' | '.$value->id.' | '.$value->user.' | '.$userMail.'</br>';
            
           
            $wpdb->insert(
                'wp_users',
                array(
                    'user_login' => $value->user,
                    'user_pass' => MD5($value->user),
                    'user_nicename' => $value->user,
                    'user_email' => $value->email,
                    'user_url' => '',
                    'user_registered' => '2014-06-05 13:20:15',
                    'user_activation_key' => '',
                    'user_status' => 0,
                    'display_name' => $value->Name,
                )
            );
            
            $get_userdata = get_user_by('email', $value->email);
            $userwordpress=$get_userdata->id;
                    
            add_user_meta( $userwordpress, 'first_name', $value->Name);
	    add_user_meta( $userwordpress, 'last_name', '');
	    add_user_meta( $userwordpress, 'nickname', $value->user);        
            add_user_meta( $userwordpress, 'description', '');  
            add_user_meta( $userwordpress, 'rich_editing', true);  
            add_user_meta( $userwordpress, 'comment_shortcuts', false);
            add_user_meta( $userwordpress, 'admin_color', 'fresh');  
            add_user_meta( $userwordpress, 'use_ssl', 0);  
            add_user_meta( $userwordpress, 'show_admin_bar_front', false);  
            add_user_meta( $userwordpress, 'wp_capabilities', 'a:1:{s:10:"subscriber";b:1;}');          
            add_user_meta( $userwordpress, 'wp_user_level', 0);  
            add_user_meta( $userwordpress, 'dismissed_wp_pointers', 'wp330_toolbar,wp330_saving_widgets,wp340_choose_image_from_library,wp340_customize_current_theme_link,wp350_media,wp360_revisions,wp360_locks');  
        
                $wpdb->update( 
                    'wp_tpt_formregisterusers', 
                    array(
                        'User_Id' => $userwordpress
                    ), 
                    array( 'formRegisterUserId' => $value->formRegisterUserId )
                );
            
        }   */   




/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

