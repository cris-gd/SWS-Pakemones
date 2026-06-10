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

$id_buscado = htmlspecialchars($_REQUEST['id'], ENT_QUOTES);

$usuario_encontrado = null;
foreach ($userdata["users"] as $usuario) {
    if ($usuario['id'] == $id_buscado && $id_buscado != "") {
        $usuario_encontrado = $usuario;
        break;
    }
}

$web_plantilla=file_get_contents('../private/templates/modificar_usuario.html');
$web_final=$web_plantilla;

$reemplazos = [
    '##ID##' => $usuario_encontrado['id'],
    '##USERNAME##' => $usuario_encontrado['username'],
    '##FIRST_NAME##' => $usuario_encontrado['first_name'],
    '##LAST_NAME##' => $usuario_encontrado['last_name'],
    '##EMAIL##' => $usuario_encontrado['email']
];
$web_final=str_replace(array_keys($reemplazos), array_values($reemplazos), $web_final);


$campos_obligatorios=['username','email','first_name','last_name'];
$formulario_valido=true;
$usuario_valido=true;

foreach($campos_obligatorios as $campo){
	if(!isset($_POST[$campo]) || htmlspecialchars($_POST[$campo], ENT_QUOTES)===''){
		$formulario_valido=false;
		break;
	}
}
if ($formulario_valido!==false){
	
	if(isset($_POST['password']) && htmlspecialchars($_POST['password'], ENT_QUOTES)!=""){
		if(htmlspecialchars($_POST['password'], ENT_QUOTES) !== htmlspecialchars($_POST['password2'], ENT_QUOTES)){
			$usuario_valido=false;
		}
		$password=sha1(htmlspecialchars($_POST['password'], ENT_QUOTES));
	}else{
	
		$password=$usuario_encontrado['password'];
	}
	
	$username=htmlspecialchars($_POST['username'], ENT_QUOTES);
	$email=htmlspecialchars($_POST['email'], ENT_QUOTES);
	$first_name=htmlspecialchars($_POST['first_name'], ENT_QUOTES);
	$last_name=htmlspecialchars($_POST['last_name'], ENT_QUOTES);
	
	
	foreach ($userdata["users"] as $usuario) {
		if($usuario['id']!=$usuario_encontrado['id']){
			if ($usuario['username'] === $username || $usuario['email'] === $email) {
				$usuario_valido=false;
				break;
			}

		}
    }
    
    if ($usuario_valido===true){
		
		$nuevos_campos = [
			'id' => $usuario_encontrado['id'],
			'email' => $email,
			'first_name' => $first_name,
			'last_name' => $last_name,
			'password' => $password,
			'username' => $username,
			'type' => $usuario_encontrado['type']
			
		];
		
		foreach ($userdata["users"] as $key => $usuario) {
			if($usuario['id'] == $usuario_encontrado['id']){
				$userdata["users"][$key]=$nuevos_campos;
			
			}
		}
		
		$meter_datos = json_encode($userdata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		file_put_contents($filename,$meter_datos);
		header('Location: listado.php');
		exit;
	}
}


echo $web_final;
?>
