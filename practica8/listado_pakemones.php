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

$filename0="../private/json/pakemones.json";
$data=file_get_contents($filename0);
$pokedata=json_decode($data,true);

$filename1="../private/json/regiones.json";
$data1=file_get_contents($filename1);
$regiondata=json_decode($data1,true);

$filename2="../private/json/tipos.json";
$data2=file_get_contents($filename2);
$tipodata=json_decode($data2,true);


$web_plantilla=file_get_contents('../private/templates/listado_pakemones.html');

$partes=explode('##INIT_LIST##', $web_plantilla);
$cabecera=$partes[0];

$resto=$partes[1];
$partes2=explode('##END_LIST##', $resto);
$parte_pokemon=$partes2[0];
$pie=$partes2[1];

$contenido_poke='';

foreach ($pokedata["pakemon"] as $pokemon) {

	$reemplazos = [
        '##PHOTO##' => "images/pakemon/".$pokemon['photo'],
        '##NAME##' => $pokemon['name'],
        '##PAKEDEX_NUMBER##' => $pokemon['pakedex_number'],
        '##GENERATION##' => $pokemon['generation'],
        '##FORM##' => $pokemon['form'],
        '##PRIMARY_TYPE##' => $tipodata["types"][$pokemon['primary_type_id']],
        '##SECONDARY_TYPE##' => $tipodata["types"][$pokemon['secondary_type_id']],
        '##CATEGORY##' => $pokemon['category'],
        '##COLOR##' => $pokemon['color'],
        '##GROUP##' => $pokemon['group'],
        '##HEIGHT_M##' => $pokemon['height_m'],
        '##WEIGHT_KG##' => $pokemon['weight_kg'],
        '##BODY_SHAPE##' => $pokemon['body_shape'],
        '##HABITAT##' => $pokemon['habitat'],
        '##GENDER##' => $pokemon['gender'],
        '##GENDER_RATIO##' => $pokemon['gender_ratio'],
        '##REGION##' => $regiondata["regions"][$pokemon['region_id']],
        '##ID##' => $pokemon['id']
     
        
    ];
    
    
    $item=$parte_pokemon;
    $item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
    
    $contenido_usuarios.=$item;
}

$web_final=$cabecera.$contenido_usuarios.$pie;
echo $web_final;
?>
