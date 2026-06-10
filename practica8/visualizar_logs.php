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

$filename="../private/json/logs.json";
$data=file_get_contents($filename);
$logdata=json_decode($data,true);

$web_plantilla=file_get_contents('../private/templates/visualizar_logs.html');

$partes=explode('##INIT_LIST##', $web_plantilla);
$cabecera=$partes[0];

$resto=$partes[1];
$partes2=explode('##END_LIST##', $resto);
$parte_usuario=$partes2[0];
$pie=$partes2[1];

$contenido_usuarios='';

$start = count($logdata)-1;
if(isset($_GET['offset'])){
	$offset=intval(htmlspecialchars($_GET['offset'], ENT_QUOTES));
} else{
	$offset=0;
}

if($offset!=0){
	$offset_prev= $offset-10;
}else{
	$offset_prev=0;
}

if($offset + 10 < count($logdata)){
	$offset_next= $offset+10;
}else{
	$offset_next=$offset;
}

$reemplazos2 = [
	'##OFFSET_PREV##' => $offset_prev,
	'##OFFSET_NEXT##' => $offset_next
		
];

$pie=str_replace(array_keys($reemplazos2), array_values($reemplazos2), $pie);

$start_idx=$start-$offset;
$end_idx=max(0,$start_idx-9);

for ($i=$start_idx; $i>=$end_idx; $i--) {
    if(isset($logdata[$i])) {
		$usuario = $logdata[$i];

	 	$session_json = json_encode($usuario['session'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
       	$cookie_json = json_encode($usuario['cookie'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
       
		$reemplazos = [
			'##TIME##' => $usuario['fecha'],
			'##IP##' => $usuario['ip'],
			'##URI##' => $usuario['url'],
			'##METHOD##' => $usuario['metodo'],
			'##GET_DATA##' => $usuario['get_raw'],
			'##POST_DATA##' => $usuario['post_raw'],
			'##SESSION_DATA##' => $session_json,
			'##COOKIE_DATA##' => $cookie_json
		
		];

		$item=$parte_usuario;
		$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
		
		$contenido_usuarios.=$item;
	}
}


$web_final=$cabecera.$contenido_usuarios.$pie;
echo $web_final;
?>
