<?php 
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
function khash($data) {
    static $map="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $hash=crc32($data)+0x100000000;
    $str = "";
    do {
        $str = $map[31+ ($hash % 31)] . $str;
        $hash /= 31;
    } while($hash >= 1);    
    return strtoupper($str);
}

function getPahtFile($fileName){
    $CurrentDir = dirname(__FILE__);
    for($i=0;$i<10;$i++){
        if($i==0){
            $dir = $CurrentDir;
        }else{
            $dir = dirname($dir);
        }
        $file = $dir.DIRECTORY_SEPARATOR.$fileName;
        if(file_exists($file)){
            break;
        }      
    }
    return $file;
}

function updateUserWordpress($paramsuser,$perfil = 'subscriber'){
        $usuario = $paramsuser['user'];
        $email = $paramsuser['email'];
        $password = $paramsuser['password']; 
        $first_name = $paramsuser['description']; 
        $user_error = "";
        if(isset($paramsuser['User_Id']))
            $userId = $paramsuser['User_Id'];
        else    
            $userId = $paramsuser['userwordpress'];
        
        $getUser = get_userdata( $userId );

        if( $getUser->id == 0 ){
            $user_error = addUserWordpress($paramsuser,$perfil);
            $data = $user_error;            
        }else{                    
            if(strlen( $usuario ) < 4) {
                 // Comprobamos que el nombre de usuario más de 4 caracteres
                 $error = true;
                 $user_error = 'Hey! No ingresaste ningún nombre de usuario';
             }
            if(!is_email( $email ))
             {
                 // is_email() es una función de WP que chequea si el string tiene el formato de un email
                 $error = true;
                 $user_error = 'Ups! el correo que ingresaste no es válido';
             }
             if($getUser->user_email != $email){
                if(email_exists( $email ))
                {
                    // email_exists() verifica en la BD si el email ingresado se encuentra registrado
                    $error = true;
                    $user_error = 'Ups! el correo que ingresaste ya se '.$getUser->user_email.' - '.$email.' encuentra registrado';
                } 
             }
             if($getUser->user_login  != $usuario){
                 if(username_exists( $usuario ))
                 {
                     // username_exists() verifica en la BD si el nombre de usuario ingresado se encuentra ocupado
                     $error = true;
                     $user_error = 'Ups! al parecer el nombre de usuario '.$user_info->user_login.' '.$usuario.' que elegiste ya esta ocupado';
                 }
             }
            if(!validate_username( $usuario ))
             {
                 // validate_username() verifica que el nombre de usuario no tenga ningún caracter extraño
                 $error = true;
                 $user_error = 'Ups! al parecer ingresaste un nombre de usuario inválido';
             }
            if(strlen( $password ) < 8 && strlen( $password ) > 16)
             {
                 // Con strlen() verificamos que la cantidad de caracteres de la contraseña debe ser entre 8 y 16 caracteres
                 $error = true;
                 $user_error = 'Hey! La contraseña debe tener entre 8 y 16 caracteres';
             }
             
                /*$error = true;
                 $user_error = 'Error'.$userId.'-'.$usuario;*/
             
            // Si la variable (string) $error se encuentra vacía quiere decir que no hubo ningún error, entonces ejecuta el código para registrar al usuario.
             if(empty( $error ))
             {
                 // Con sanitize_email() nos encargamos de limpiar el correo solamente por las dudas
                 $email = sanitize_email($email);
                 // Lo mismo hacemos con el nombre de usuario usando la función sanitize_user() de WP
                 $usuario = sanitize_user($usuario);
                 // Creamos un array pasando los datos que necesitaremos para crear el nuevo usuario
                 $userdata = array(
                     'ID' => $userId,
                     'user_pass' => $password,
                     'user_email' => $email,
                     'user_login' => $usuario,
                     'first_name' => $first_name,            
                     'role' => $perfil
                 );
                 // wp_insert_user() agrega el nuevo usuario a WP
                 wp_update_user($userdata);                 
                 // Con wp_new_user_notification() enviamos un correo al usuario que recién se registro, pasandole su nombre de usuario y contraseña. Además nos avisará cada vez que un usuario se registre
                 wp_new_user_notification($getUser->id, $password);

                 $to = $email;
                 $subject = "Tapaté con Gulf Señor Distribuidor ".$first_name;
                 $message = 'Su usuario ' .$usuario. ',' . "\r\n" .
                 'Usted puede iniciar sesión con su nombre de usuario ('.$usuario.') and password (' .$password. ')';        
                 $headers[] = 'From: Tapaté con Gulf ';
                 wp_mail( $to, $subject, $message, $headers );

                 $data = $getUser->id;

             }else{
                 $data = $user_error;
             }
    }
    return $data;
}

function addUserWordpress($paramsuser,$perfil){
        $usuario = $paramsuser['user'];
        $email = $paramsuser['email'];
        $password = $paramsuser['password']; 
        $first_name = $paramsuser['description']; 
        $user_error = "";
   if(strlen( $usuario ) < 4) {
        // Comprobamos que el nombre de usuario más de 4 caracteres
        $error = true;
        $user_error = 'Hey! No ingresaste ningún nombre de usuario';
    }
   if(!is_email( $email ))
    {
        // is_email() es una función de WP que chequea si el string tiene el formato de un email
        $error = true;
        $user_error = 'Ups! el correo que ingresaste no es válido';
    }
   if(email_exists( $email ))
    {
        // email_exists() verifica en la BD si el email ingresado se encuentra registrado
        $error = true;
        $user_error = 'Ups! el correo que ingresaste ya se encuentra registrado';
    }
   if(username_exists( $usuario ))
    {
        // username_exists() verifica en la BD si el nombre de usuario ingresado se encuentra ocupado
        $error = true;
        $user_error = 'Ups! al parecer el nombre de usuario que elegiste ya esta ocupado';
    }
   if(!validate_username( $usuario ))
    {
        // validate_username() verifica que el nombre de usuario no tenga ningún caracter extraño
        $error = true;
        $user_error = 'Ups! al parecer ingresaste un nombre de usuario inválido';
    }
   if(strlen( $password ) < 8 && strlen( $password ) > 16)
    {
        // Con strlen() verificamos que la cantidad de caracteres de la contraseña debe ser entre 8 y 16 caracteres
        $error = true;
        $user_error = 'Hey! La contraseña debe tener entre 8 y 16 caracteres';
    }
   // Si la variable (string) $error se encuentra vacía quiere decir que no hubo ningún error, entonces ejecuta el código para registrar al usuario.
    if(empty( $error ))
    {
        // Con sanitize_email() nos encargamos de limpiar el correo solamente por las dudas
        $email = sanitize_email($email);
        // Lo mismo hacemos con el nombre de usuario usando la función sanitize_user() de WP
        $usuario = sanitize_user($usuario);
        // Creamos un array pasando los datos que necesitaremos para crear el nuevo usuario
        $userdata = array(
            'user_pass' => $password,
            'user_email' => $email,
            'user_login' => $usuario,
            'first_name' => $first_name,            
            'role' => $perfil
        );
        // wp_insert_user() agrega el nuevo usuario a WP
        wp_insert_user($userdata);
        // get_user_by() lo utilizamos para obtener el ID del usuario recién creado que lo necesitaremos para wp_new_user_notification()
        $get_userdata = get_user_by('email', $email);
        // Con wp_new_user_notification() enviamos un correo al usuario que recién se registro, pasandole su nombre de usuario y contraseña. Además nos avisará cada vez que un usuario se registre
        // wp_new_user_notification($get_userdata->id, $password);        
        wp_password_change_notification( $get_userdata->id );
        
        $to = $email;
        $subject = "Tapaté con Gulf Señor Distribuidor ".$first_name;
        $message = 'Su usuario ' .$usuario. ',' . "\r\n" .
        'Usted puede iniciar sesión con su nombre de usuario ('.$usuario.') and password (' .$password. ')';        
        $headers[] = 'From: Tapaté con Gulf ';
        wp_mail( $to, $subject, $message, $headers );
        
        $data = $get_userdata->id;
        
    }else{
        $data = $user_error;
    }
   
    return $data;
    
}

$pluginPath = dirname(__FILE__);
$rp = explode("wp-content", $pluginPath);
$rootPath = $rp[0];
global $current_user;
$user_page = $current_user;
$prot = explode("/",$_SERVER['SERVER_PROTOCOL']);
$protocol = strtolower($prot[0]);
$preFX = explode("/",$_SERVER['REQUEST_URI']);
$URLPrefix = ($preFX[1] != $_SERVER['HTTP_HOST'])? $preFX[1] : '' ;
$pluginName = "Gulf_Tapate";//explode(DIRECTORY_SEPARATOR,__DIR__);
$pluginURL = $protocol."://".$_SERVER['HTTP_HOST']."/Tapate_Gulf/wp-content/plugins/".$pluginName."/";
$prefixPlugin = "tpt_";
$GeographicHierarchy = array("country" => array("table" => "countries"
						,"child" => array(
								"table" => "regions"
								,"child" => array(
										"table" => "cities"
										)
								)
						)
			);
?>