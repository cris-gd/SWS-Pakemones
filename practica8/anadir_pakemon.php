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
function mostrar_anadir($error='', $valores=[]){

	$filename1="../private/json/regiones.json";
	$data1=file_get_contents($filename1);
	$regiondata=json_decode($data1,true);

	$filename2="../private/json/tipos.json";
	$data2=file_get_contents($filename2);
	$tipodata=json_decode($data2,true);
	
	
	$web_plantilla =file_get_contents('../private/templates/anadir_pakemon.html');
	$web_final=$web_plantilla;



	$partes=explode('##INIT_OPTION_REGION##', $web_final);
	$cabecera=$partes[0];

	$resto=$partes[1];
	$partes2=explode('##END_OPTION_REGION##', $resto);
	$parte_usuario=$partes2[0];
	$pie=$partes2[1];

	$contenido_regiones='';

	foreach ($regiondata["regions"] as $key => $usuario) {
		if ($key !=0){
			$reemplazos = [
				'##VALUE##' => $key,
				'##REGION##' => $usuario
			];
			
			$item=$parte_usuario;
			$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
			
			$contenido_regiones.=$item;
		}

	}

	$web_final=$cabecera.$pie;
	
	
	$partes=explode('##INIT_OPTION_SELREGION##', $web_final);
	$cabecera=$partes[0];

	$resto=$partes[1];
	$partes2=explode('##END_OPTION_SELREGION##', $resto);
	$parte_usuario=$partes2[0];
	$pie=$partes2[1];
	
	
	$reemplazos = [
			'##VALUE##' => 0,
			'##REGION##' => $regiondata["regions"][0]
	];
	
	$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $parte_usuario);
	$web_final=$cabecera.$item.$contenido_regiones.$pie;


	$reemplazos = [
			'##NAME##' => $valores['name'],
			'##FORM##' => $valores['form'],
			'##CATEGORY##' => $valores['category'],
			'##COLOR##' => $valores['color'],
			'##GROUP##' => $valores['group'],
			'##BODY_SHAPE##' => $valores['body_shape'],
			'##HABITAT##' => $valores['habitat'],
			'##GENDER##' =>$valores['gender'],
			'##GENDER_RATIO##' => $valores['gender_ratio'],
			"##PAKEDEX_NUMBER##" => $valores['pakedex_number'],
			'##GENERATION##' => $valores['generation'],
			'##HEIGHT_M##' => $valores['height_m'],
			'##WEIGHT_KG##' => $valores['weight_kg']

	];
	$web_final=str_replace(array_keys($reemplazos), array_values($reemplazos), $web_final);
	



	$partes=explode('##INIT_OPTION1##', $web_final);
	$cabecera=$partes[0];

	$partir1 = explode('##END_OPTION1##', $partes[1]);
	$parte_usuario=$partir1[0];
	$pie=$partir1[1];
	$contenido_tipo1="";
	
	foreach ($tipodata["types"] as $key => $usuario) {
		if ($key !=0){
			$reemplazos = [
				'##VALUE##' => $key,
				'##TIPO##' => $usuario
			];
			
			$item=$parte_usuario;
			$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
			
			$contenido_tipo1.=$item;
		}
	}
	$web_final=$cabecera.$pie;
	
	
	$partes=explode('##INIT_SELOPTION1##', $web_final);
	$cabecera=$partes[0];

	$resto=$partes[1];
	$partes2=explode('##END_SELOPTION1##', $resto);
	$parte_usuario=$partes2[0];
	$pie=$partes2[1];
	
	
	$reemplazos = [
			'##VALUE##' => 0,
			'##TIPO##' => $tipodata["types"][0]
	];
	
	$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $parte_usuario);
	$web_final=$cabecera.$item.$contenido_tipo1.$pie;

	$partes=explode('##INIT_OPTION2##', $web_final);
	$cabecera=$partes[0];

	$partir1 = explode('##END_OPTION2##', $partes[1]);
	$parte_usuario=$partir1[0];
	$pie=$partir1[1];
	$contenido_tipo1="";
	
	foreach ($tipodata["types"] as $key => $usuario) {
		if ($key !=0){
			$reemplazos = [
				'##VALUE##' => $key,
				'##TIPO##' => $usuario
			];
			
			$item=$parte_usuario;
			$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
			
			$contenido_tipo1.=$item;
		}
	}
	$web_final=$cabecera.$pie;
	
	
	$partes=explode('##INIT_SELOPTION2##', $web_final);
	$cabecera=$partes[0];

	$resto=$partes[1];
	$partes2=explode('##END_SELOPTION2##', $resto);
	$parte_usuario=$partes2[0];
	$pie=$partes2[1];
	
	
	$reemplazos = [
			'##VALUE##' => 0,
			'##TIPO##' => $tipodata["types"][0]
	];
	
	$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $parte_usuario);
	$web_final=$cabecera.$item.$contenido_tipo1.$pie;

	if ($error != '') {
		$web_final .= '<script>alert("'.$error.'");</script>';
	}

	print($web_final);
}





$filename="../private/json/pakemones.json";
$data=file_get_contents($filename);
$pokedata=json_decode($data,true);

$campos_obligatorios = ["pakedex_number", "name", "generation", "region_id",
      "form", "primary_type_id", "category", "color",
      "group", "height_m", "weight_kg", "body_shape",
      "habitat", "gender", "gender_ratio"];


$formulario_valido=true;

foreach($campos_obligatorios as $campo){
	if(!isset($_POST[$campo]) || htmlspecialchars($_POST[$campo], ENT_QUOTES)===''){
		$formulario_valido=false;
		break;
	}
}
if (!is_uploaded_file($_FILES['photo']['tmp_name'])) {
    $formulario_valido = false;
}else{
	if(exif_imagetype($_FILES['photo']['tmp_name'])==false){
		$formulario_valido = false;
		$error='La foto no es de tipo imagen';
	}
}

if($formulario_valido===true){

	$pakedex_number=htmlspecialchars($_POST['pakedex_number'], ENT_QUOTES);
	$name=htmlspecialchars($_POST['name'], ENT_QUOTES);
	$generation=htmlspecialchars($_POST['generation'], ENT_QUOTES);
	$region_id=htmlspecialchars($_POST['region_id'], ENT_QUOTES);
	$form=htmlspecialchars($_POST['form'], ENT_QUOTES);
	$primary_type_id=htmlspecialchars($_POST['primary_type_id'], ENT_QUOTES);
	$secondary_type_id=htmlspecialchars($_POST['secondary_type_id'], ENT_QUOTES);
	$category=htmlspecialchars($_POST['category'], ENT_QUOTES);
	$color=htmlspecialchars($_POST['color'], ENT_QUOTES);
	$group=htmlspecialchars($_POST['group'], ENT_QUOTES);
	$height_m=htmlspecialchars($_POST['height_m'], ENT_QUOTES);
	$weight_kg=htmlspecialchars($_POST['weight_kg'], ENT_QUOTES);
	$body_shape=htmlspecialchars($_POST['body_shape'], ENT_QUOTES);
	$habitat=htmlspecialchars($_POST['habitat'], ENT_QUOTES);
	$gender=htmlspecialchars($_POST['gender'], ENT_QUOTES);
	$gender_ratio=htmlspecialchars($_POST['gender_ratio'], ENT_QUOTES);

	$photo = $_FILES['photo']['name'];
    $ruta = "images/pakemon/" . $photo;
    move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);

	$idmax=0;
	$pakemon_valido = true;
	foreach ($pokedata["pakemon"] as $usuario) {
		if(intval($usuario["id"])>$idmax){
			$idmax=$usuario["id"];
		}
		if ($usuario['name'] === $name){
			$error='El nombre de Pakemon ya existe';
			$pakemon_valido=false;
			break;
		} else if($usuario['pakedex_number'] == $pakedex_number) {
			$error='El número de pakedex ya existe';
			$pakemon_valido=false;
			break;
		}
	}
	$nuevo_id = $idmax+1;

	
	$nuevos_campos = [
			'id' => $nuevo_id,
			'pakedex_number' => $pakedex_number,
			'name' => $name,
			'generation' => $generation,
			'region_id' => $region_id,
			'form' => $form,
			'primary_type_id' => $primary_type_id,
			'secondary_type_id' => $secondary_type_id,
			'category' => $category,
			'color' => $color,
			'group' => $group,
			'height_m' => $height_m,
			'weight_kg' => $weight_kg,
			'body_shape' => $body_shape,
			'habitat' => $habitat,
			'gender' => $gender,
			'gender_ratio' => $gender_ratio,
			'photo' =>$photo
			
	];

	if($pakemon_valido === true){
		$pokedata['pakemon'][] = $nuevos_campos;
		$meter_datos = json_encode($pokedata, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
		file_put_contents($filename,$meter_datos);

		ve_listado();
	}
	mostrar_anadir($error, $nuevos_campos);
	exit;
}

mostrar_anadir($error);


?>
