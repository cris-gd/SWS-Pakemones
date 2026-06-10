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

function muestra_modificar($id,$pakedex_number,$name,$generation,$form,$category,$color,$group,$height_m,$weight_kg,$body_shape,$habitat,$gender,$gender_ratio, $region_id, $primary_type_id,$secondary_type_id){
	
	$filename1="../private/json/regiones.json";
	$data1=file_get_contents($filename1);
	$regiondata=json_decode($data1,true);

	$filename2="../private/json/tipos.json";
	$data2=file_get_contents($filename2);
	$tipodata=json_decode($data2,true);
	
	
	$web_plantilla =file_get_contents('../private/templates/modificar_pakemon.html');
	$web_final=$web_plantilla;
	$reemplazos = [
			'##PAKEID##' => $id,
			'##PAKEDX##' => $pakedex_number,
			'##NOMBRE##' => $name,
			'##GENERACION##' => $generation,
			'##FORMA##' => $form,
			'##CATEGORIA##' => $category,
			'##COLOR##' => $color,
			'##GRUPO##' => $group,
			'##ALTURA##' => $height_m,
			'##PESO##' => $weight_kg,
			'##FORMACORPORAL##' => $body_shape,
			'##HABITAT##' => $habitat,
			'##GENERO##' => $gender,
			'##GENERATOR##' => $gender_ratio
	];
	$web_final=str_replace(array_keys($reemplazos), array_values($reemplazos), $web_final);
	
	$partes=explode('##OPTION_INIT##', $web_final);
	$cabecera=$partes[0];
	$resto=$partes[1];
	$partes2=explode('##OPTION_END##', $resto);
	$parte_usuario=$partes2[0];

	$partes=explode('##OPTION_SELECTED_INIT##', $web_final);
	$resto=$partes[1];
	$partes2=explode('##OPTION_SELECTED_END##', $resto);
	$parte_seleccionada=$partes2[0];
	$pie=$partes2[1];

	$contenido_regiones='';

	foreach ($regiondata["regions"] as $key => $usuario) {
		$reemplazos = [
			'##ID##' => $key,
			'##REGION##' => $usuario
		];
		if($key == $region_id){
			$item=$parte_seleccionada;
		}else{
			$item=$parte_usuario;
		}
		
		$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
		
		$contenido_regiones.=$item;
	}

	$web_final=$cabecera.$contenido_regiones.$pie;
	
	

	$partes=explode('##TYPE1_INIT##', $web_final);
	$cabecera=$partes[0];
	$resto=$partes[1];
	$partes2=explode('##TYPE1_END##', $resto);
	$parte_usuario=$partes2[0];

	$partes=explode('##TYPE1_SELECTED_INIT##', $web_final);
	$resto=$partes[1];
	$partes2=explode('##TYPE1_SELECTED_END##', $resto);
	$parte_seleccionada=$partes2[0];
	$pie=$partes2[1];

	$contenido_tipos='';

	foreach ($tipodata["types"] as $key => $usuario) {
		$reemplazos = [
			'##ID##' => $key,
			'##TIPO##' => $usuario
		];
		if($key == $primary_type_id){
			$item=$parte_seleccionada;
		}else{
			$item=$parte_usuario;
		}
		
		$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
		
		$contenido_tipos.=$item;
	}

	$web_final=$cabecera.$contenido_tipos.$pie;



	$partes=explode('##TYPE2_INIT##', $web_final);
	$cabecera=$partes[0];
	$resto=$partes[1];
	$partes2=explode('##TYPE2_END##', $resto);
	$parte_usuario=$partes2[0];

	$partes=explode('##TYPE2_SELECTED_INIT##', $web_final);
	$resto=$partes[1];
	$partes2=explode('##TYPE2_SELECTED_END##', $resto);
	$parte_seleccionada=$partes2[0];
	$pie=$partes2[1];

	$contenido_tipos='';

	foreach ($tipodata["types"] as $key => $usuario) {
		$reemplazos = [
			'##ID##' => $key,
			'##TIPO##' => $usuario
		];
		if($key == $secondary_type_id){
			$item=$parte_seleccionada;
		}else{
			$item=$parte_usuario;
		}
		
		$item=str_replace(array_keys($reemplazos), array_values($reemplazos), $item);
		
		$contenido_tipos.=$item;
	}

	$web_final=$cabecera.$contenido_tipos.$pie;
	
	print($web_final);

}

function ve_listado(){

    header('Location: listado_pakemones.php');
	exit;

}
 


$filename="../private/json/pakemones.json";
$data=file_get_contents($filename);
$pokedata=json_decode($data,true);
$id = htmlspecialchars($_REQUEST['id'], ENT_QUOTES);

foreach($pokedata['pakemon'] as $usuario){
	if ($usuario['id']==$id){
		$pakedex_number=$usuario['pakedex_number'];
		$name=$usuario['name'];
		$generation=$usuario['generation'];
		$region_id=$usuario['region_id'];
		$form=$usuario['form'];
		$primary_type_id=$usuario['primary_type_id'];
		$secondary_type_id=$usuario['secondary_type_id'];
		$category=$usuario['category'];
		$color=$usuario['color'];
		$group=$usuario['group'];
		$height_m=$usuario['height_m'];
		$weight_kg=$usuario['weight_kg'];
		$body_shape=$usuario['body_shape'];
		$habitat=$usuario['habitat'];
		$gender=$usuario['gender'];
		$gender_ratio=$usuario['gender_ratio'];
		$photo=$usuario['photo'];
	}
}


$campos_obligatorios=['pakedex_number', 'name', 'generation', 'region_id', 'form', 'primary_type_id', 'secondary_type_id', 'category', 'color', 'group', 'height_m', 'weight_kg', 'body_shape', 'habitat', 'gender', 'gender_ratio'];
$formulario_valido=true;
$usuario_valido=true;

foreach($campos_obligatorios as $campo){
	if(!isset($_POST[$campo]) || htmlspecialchars($_POST[$campo], ENT_QUOTES)===''){
		$formulario_valido=false;
		break;
	}
}


if ($formulario_valido!==false){
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
	
	
	foreach ($pokedata["pakemon"] as $usuario) {
		if($usuario['id'] != $id){
			if ($usuario['name'] === $name || $usuario['pakedex_number'] == $pakedex_number) {
				$usuario_valido=false;
				break;
			}
		}else{
			$usuario_valido = true;
		}
		
    }
    
    if ($usuario_valido===true){

		if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
			if(exif_imagetype($_FILES['photo']['tmp_name'])!=false){
				$photo = $_FILES['photo']['name'];
				$ruta = "images/pakemon/" . $photo;
				move_uploaded_file($_FILES['photo']['tmp_name'], $ruta);
			}
		}
		$nuevos_campos = [
			'id' => $id,
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
			'photo' => $photo
			
		];
		
		foreach ($pokedata["pakemon"] as $key => $usuario) {
			if($usuario['id'] == $id){
				$pokedata["pakemon"][$key]=$nuevos_campos;
			
			}
		}
		
		
		
		$meter_datos = json_encode($pokedata);
		file_put_contents($filename,$meter_datos);
		
		ve_listado();
	}
}



muestra_modificar($id,$pakedex_number,$name,$generation,$form,$category,$color,$group,$height_m,$weight_kg,$body_shape,$habitat,$gender,$gender_ratio, $region_id, $primary_type_id,$secondary_type_id);

?>
