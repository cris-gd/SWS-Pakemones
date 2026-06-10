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

    $id_imagen=htmlspecialchars($_POST['post_id'], ENT_QUOTES);
    $comentario=htmlspecialchars($_POST['comentario'], ENT_QUOTES);

    $comentarios = json_decode(file_get_contents('../private/json/comentarios.json'), true);

    //coger bien datos

    $nuevo_comentario=[
		'iduser' => $usuario,
        'idfoto' => $id_imagen,
        'comentario' => $comentario
    ];
    //

    $comentarios[] = $nuevo_comentario;
    file_put_contents('../private/json/comentarios.json', json_encode($comentarios, JSON_PRETTY_PRINT));

    header("Location: pake_twittermon.php");
    exit;
?>
