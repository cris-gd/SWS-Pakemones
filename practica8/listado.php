<?php
session_start();
include('logger.php');

save_logging();

if(isset($_SESSION['username']) && isset($_SESSION['type'])){
	if($_SESSION['type']!=='admin'){
		header('Location: index.php');
		exit;
	}
}else{
	header('Location: index.php');
	exit;
}

//verificar que usuario no se ha eliminado
$filename="../private/json/users.json";
$data=file_get_contents($filename);
$userdata=json_decode($data,true);

$valido = false;
foreach ($userdata["users"] as $usuario) {
	if ($usuario['id'] == $_SESSION['id']) {
		$valido = true;
		break;
	}
}

if($valido === false){
	session_destroy();
	header('Location: index.php');
	exit;
}

//mostrar contenido solo si usuario existe

$web_plantilla=file_get_contents('../private/templates/listado.html');

$partes=explode('##INIT_LIST##', $web_plantilla);
$cabecera=$partes[0];

$resto=$partes[1];
$partes2=explode('##END_LIST##', $resto);
$parte_usuario=$partes2[0];
$pie=$partes2[1];


$partes3 = explode('##INIT_ADMIN##', $pie);
$resto2=$partes3[1];
$partes4=explode('##END_ADMIN##', $resto2);
$parte_admin=$partes4[0];
$pie=$partes4[1];

$contenido_usuarios='';

foreach ($userdata["users"] as $usuario) {
	if ($usuario['type']=="user"){
		$reemplazos = [
			'##ID##' => $usuario['id'],
			'##USERNAME##' => $usuario['username'],
			'##FIRST_NAME##' => $usuario['first_name'],
			'##LAST_NAME##' => $usuario['last_name'],
			'##EMAIL##' => $usuario['email']
		];
		
		$item=$parte_usuario;
		$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
		
		$contenido_usuarios.=$item;
	}
	if ($usuario['type']=="admin"&&$usuario['username']!=$_SESSION['username']){
		$reemplazos = [
			'##ID##' => $usuario['id'],
			'##USERNAME##' => $usuario['username'],
			'##FIRST_NAME##' => $usuario['first_name'],
			'##LAST_NAME##' => $usuario['last_name'],
			'##EMAIL##' => $usuario['email']
		];
		
		$item=$parte_admin;
		$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
		
		$contenido_usuarios.=$item;
	}
    
}

$web_final=$cabecera.$contenido_usuarios.$pie;
echo $web_final;
?>
