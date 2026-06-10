<?php
session_start();
include('logger.php');

save_logging();

function muestra_registro(){

	$web_plantilla =file_get_contents('../private/templates/registro.html');
	$web_final=$web_plantilla;
	$reemplazos = [
    	'##USERNAME##' => '',
    	'##FIRST_NAME##' => '',
    	'##LAST_NAME##' => '',
    	'##EMAIL##' => ''
	];
	$web_final=str_replace(array_keys($reemplazos), array_values($reemplazos), $web_final);
	print($web_final);

}
function muestra_registro2($username, $first_name, $last_name, $email){
	$web_plantilla=file_get_contents('../private/templates/registro.html');
	$web_final=$web_plantilla;

	$reemplazos = [
    	'##USERNAME##' => $username,
    	'##FIRST_NAME##' => $first_name,
    	'##LAST_NAME##' => $last_name,
    	'##EMAIL##' => $email
	];
	$web_final=str_replace(array_keys($reemplazos), array_values($reemplazos), $web_final);
	print($web_final);

}
function ve_login(){

    header('Location: index.php');

}
 


$filename="../private/json/users.json";
$data=file_get_contents($filename);
$userdata=json_decode($data,true);


$campos_obligatorios=['username','email','password','password2','first_name','last_name'];
$formulario_valido=true;
$usuario_valido=true;

foreach($campos_obligatorios as $campo){
	if(!isset($_POST[$campo]) || htmlspecialchars($_POST[$campo], ENT_QUOTES)===''){
		$formulario_valido=false;
		break;
	}
}
if ($formulario_valido!==false){
	$username=htmlspecialchars($_POST['username'], ENT_QUOTES);
	$email=htmlspecialchars($_POST['email'], ENT_QUOTES);
	$first_name=htmlspecialchars($_POST['first_name'], ENT_QUOTES);
	$last_name=htmlspecialchars($_POST['last_name'], ENT_QUOTES);
	$password=sha1(htmlspecialchars($_POST['password'], ENT_QUOTES));
	
	foreach ($userdata["users"] as $usuario) {
        if ($usuario['username'] === $username || $usuario['email'] === $email) {
            $usuario_valido=false;
            break;
        }
    }
    
    if ($usuario_valido===true){
		$idmax=0;
		foreach ($userdata["users"] as $usuario) {
			if(intval($usuario["id"])>$idmax){
				$idmax=$usuario["id"];
			}
		}
		$nuevo_id = $idmax+1;
		
		$nuevos_campos = [
			'id' => $nuevo_id,
			'email' => $email,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'password' => $password,
			'username' => $username,
			'type' => 'user'
			
		];
		
		$userdata["users"][]=$nuevos_campos;
		
		$meter_datos = json_encode($userdata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		file_put_contents($filename,$meter_datos);
		
		ve_login();
	}
}


if($formulario_valido===false){
	muestra_registro();
}else{
	muestra_registro2($username, $first_name, $last_name, $email);
}

?>
