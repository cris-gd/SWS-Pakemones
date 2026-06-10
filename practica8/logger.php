<?php

function save_logging(){
	
	$fecha = $_SERVER['REQUEST_TIME'];
	$ip = $_SERVER['REMOTE_ADDR'];
	

	$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$metodo = $_SERVER['REQUEST_METHOD'];
		
	$get_raw = http_build_query($_GET);
	$post_raw = file_get_contents("php://input");

	$session_json = json_encode($_SESSION,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	$cookie_json = json_encode($_COOKIE,JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

	$entrada = [
		'fecha' => $fecha,
		'ip' => $ip,
		'url' => $url,
		'metodo' => $metodo,
		'get_raw' => $get_raw,
		'post_raw' => $post_raw,
		'session' => json_decode($session_json, true),
		'cookie' => json_decode($cookie_json, true)
	];

	$filename="../private/json/logs.json";

	if (file_exists($filename) && filesize($filename) > 0) {
        $contenido_actual = file_get_contents($filename);
        $logs = json_decode($contenido_actual, true);
        
    } else {
        $logs = [];
    }

	$logs[] = $entrada;
    file_put_contents($filename, json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

}

?>
