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
	if(htmlspecialchars($_POST['password'], ENT_QUOTES) !== htmlspecialchars($_POST['password2'], ENT_QUOTES)){
		$usuario_valido=false;
	}
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
			'type' => 'admin'
			
		];
		
		$userdata["users"][]=$nuevos_campos;
		
		$meter_datos = json_encode($userdata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		file_put_contents($filename,$meter_datos);
		header('Location: listado.php');
		exit;
	}
}

$web_plantilla=file_get_contents('../private/templates/nuevo_admin.html');
$web_final=$web_plantilla;


echo $web_final;
?>
