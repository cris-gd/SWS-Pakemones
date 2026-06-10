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

    $web_plantilla =file_get_contents('../private/templates/detalle_pakemon.html');
	$web_final=$web_plantilla;

    $filename2="../private/json/pakemones.json";
    $data2=file_get_contents($filename2);
    $pokedata=json_decode($data2,true);

    $filename3="../private/json/images.json";
    $data3=file_get_contents($filename3);
    $postdata=json_decode($data3,true);

    $filename4="../private/json/comentarios.json";
    $data4=file_get_contents($filename4);
    $commentdata=json_decode($data4,true);

	if(isset($_GET['id'])){/* igual hay que cambiar esto para que guarde el valor de id */
        foreach($pokedata['pakemon'] as $pokemon){
            if($pokemon['id'] == htmlspecialchars($_GET['id'], ENT_QUOTES)){

                $web_final = str_replace('##NAME##', $pokemon['name'], $web_final);
                $web_final = str_replace('##ID##', $pokemon['id'], $web_final);
                
            }
        }
    }

    $partes=explode('##INIT_POSTS##', $web_final);
	$cabecera=$partes[0];
    $partes=explode('##END_POSTS##', $partes[1]);
    $pie=$partes[1];
    $middle=$partes[0];
    $contenido_posts="";

    $tags = [
        '##POST_IMAGE##',
        '##POST_ID##'
    ];

    $tags2 = [
        '##COMMENT_USER##',
        '##COMMENT_TEXT##'
    ];

    foreach($postdata as $key => $post){
        /* igual hay que cambiar el tema del usuario -> posts de todos */
        if(($post['idpakemon'] == htmlspecialchars($_GET['id'], ENT_QUOTES))&&($post['iduser'] == $_SESSION['id'])){
            $cambiar = array('images/fotos/'.$post['foto'], $key);
            $item = str_replace($tags, $cambiar, $middle);

            $partes=explode('##INIT_COMMENTS##', $item);
            $header=$partes[0];
            $partes=explode('##END_COMMENTS##', $partes[1]);
            $footer=$partes[1];
            $center=$partes[0];
            $contenido_comentarios="";

            foreach($commentdata as $comentario){
                if($comentario['idfoto'] == $key){
                    $cambiar = array($userdata['users'][$comentario['iduser']]['username'], $comentario['comentario']);
                    $contenido_comentarios.=str_replace($tags2, $cambiar, $center);
                }
            }

            $item = $header.$contenido_comentarios.$footer;
            $contenido_posts.=$item;
        }
    }



    $web_final = $cabecera.$contenido_posts.$pie;
    print($web_final);
	
?>
