<?php
	session_start();
	include('logger.php');

	save_logging();

	if(isset($_SESSION['username']) && isset($_SESSION['type'])){
		if($_SESSION['type']==='user'){
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
			$web_plantilla =file_get_contents('../private/templates/user.html');
			$web_final=$web_plantilla;
			$web_final=str_replace('##USERNAME##', $_SESSION['username'], $web_final);
			print($web_final);
		}else{
			header('Location: index.php');
			exit;
		}
	}else{
		header('Location: index.php');
		exit;
	}
	
?>
