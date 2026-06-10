<?php
    session_start();
    include('logger.php');

    save_logging();

    if(isset($_SESSION['username']) && isset($_SESSION['type'])){
        if($_SESSION['type']!=='user'){ //esto no se si hace falta
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

    $usuario=$_SESSION['id'];
    $id=htmlspecialchars($_GET['id'], ENT_QUOTES); //post?

    $imagenes = json_decode(file_get_contents('../private/json/images.json'), true);

    //coger bien datos
    if(is_uploaded_file($_FILES['foto']['tmp_name']) && exif_imagetype($_FILES['foto']['tmp_name'])!=false){
        move_uploaded_file($_FILES['foto']['tmp_name'], "images/fotos/".$_FILES['foto']['name']);
        $nueva_imagen=[
			'iduser' => $usuario,
			'idpakemon' => $id,
			'foto' => $_FILES['foto']['name']
		];
		//

		$imagenes[] = $nueva_imagen;
		file_put_contents('../private/json/images.json', json_encode($imagenes, JSON_PRETTY_PRINT));

		
    }
    header("Location: detalle_pakemon.php?id=$id");
	exit;

    
?>
