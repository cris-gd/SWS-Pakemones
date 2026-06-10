<?php
session_start();
include('logger.php');

save_logging();

if(isset($_SESSION['username']) && isset($_SESSION['type']) && isset($_SESSION['id'])){
	if($_SESSION['type']!=='user'){
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

$filename="../private/json/mypakemons.json";
$data=file_get_contents($filename);
$entrenadordata=json_decode($data,true);

$filename2="../private/json/pakemones.json";
$data2=file_get_contents($filename2);
$pokedata=json_decode($data2,true);

foreach ($pokedata["pakemon"] as $pokemon) {
    if(isset($_GET['id'])){
        if(htmlspecialchars($_GET['id'], ENT_QUOTES) == $pokemon['id']){
            $nuevos_campos = [
			    'id_user' => $_SESSION['id'],
			    'id_pakemon' => $pokemon['id']
			
		    ];
            $entrenadordata["mypakemons"][]=$nuevos_campos;
            $meter_datos = json_encode($entrenadordata, JSON_PRETTY_PRINT);
		    file_put_contents($filename,$meter_datos);
            break;
        }
    }
}

header('Location: pakedex.php');
exit;
?>
