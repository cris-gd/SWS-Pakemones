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

function ve_listado(){

    header('Location: listado_pakemones.php');
    exit;

}
 
$filename="../private/json/pakemones.json";
$data=file_get_contents($filename);
$pokedata=json_decode($data,true);

if (isset($_GET['id'])) {
    
    $id=htmlspecialchars($_GET['id'], ENT_QUOTES);
    
	foreach ($pokedata["pakemon"] as $usuario) {
        if ($usuario['id'] != $id) {
            $pokedata_new["pakemon"][]=$usuario;
        }
    }
    
	$meter_datos = json_encode($pokedata_new);
	file_put_contents($filename,$meter_datos);
		
   
}
ve_listado();   

?>
