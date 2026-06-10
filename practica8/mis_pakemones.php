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

$filename1="../private/json/pakemones.json";
$data=file_get_contents($filename1);
$pokedata=json_decode($data,true);

$filename3="../private/json/mypakemons.json";
$data3=file_get_contents($filename3);
$entrenadordata=json_decode($data3,true);


$web_plantilla=file_get_contents('../private/templates/mis_pakemones.html');

$partes=explode('##INIT_LIST##', $web_plantilla);
$cabecera=$partes[0];

$resto=$partes[1];
$partes2=explode('##END_LIST##', $resto);
$parte_pokemon=$partes2[0];
$pie=$partes2[1];

$contenido_usuarios='';

foreach ($pokedata["pakemon"] as $pokemon) {
    $capturado = false;

    foreach ($entrenadordata["mypakemons"] as $entrenador) {
        if(($entrenador['id_user'] == $_SESSION['id']) && ($entrenador['id_pakemon'] == $pokemon['id'])){
            $capturado = true;
        }

    }

    if($capturado === true){
        
        $reemplazos = [
            '##ID##' => $pokemon['id'],
            '##PHOTO##' => "images/pakemon/".$pokemon['photo'],
            '##NAME##' => $pokemon['name']

        ];
        
        $item=$parte_pokemon;
        $item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
        
        $contenido_usuarios.=$item;
    }
}

$web_final=$cabecera.$contenido_usuarios.$pie;
echo $web_final;

?>
