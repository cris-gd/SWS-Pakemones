<?php
session_start();
include('logger.php');

save_logging();

 function muestra_login(){

    print(file_get_contents("../private/templates/login.html"));

 }
 function muestra_fail(){

    print(file_get_contents("../private/templates/userfail.html"));

 }
 function muestra_usuario(){

    header('Location: user.php');
    exit;

 }
 function muestra_admin(){

    header('Location: admin.php');
    exit;

 }
 
 function credenciales_validas($username,$password){

	$filename="../private/json/users.json";
	$data=file_get_contents($filename);
	$userdata=json_decode($data,true);
	
    foreach ($userdata["users"] as $usuario) {
        if ($usuario['username'] === $username && $usuario['password'] === sha1($password)) {
            $_SESSION['username'] = $username;
            $_SESSION['type'] = $usuario['type'];
            $_SESSION['id'] = $usuario['id'];
            break;
        }
    }

    if (isset($_SESSION['username'])&&isset($_SESSION['type'])) {       
        if ($_SESSION['type'] === 'admin') {
			muestra_admin();
        } else {
            muestra_usuario();
        }
    }else{
		muestra_fail();
	}
    exit;

 }


if(htmlspecialchars($_GET['logout'], ENT_QUOTES) == 'true'){
    session_destroy(); 
}else{
    if (isset($_SESSION['username'])&&isset($_SESSION['type'])) {       
        if ($_SESSION['type'] === 'admin') {
            muestra_admin();
        } else {
            muestra_usuario();
        }
        exit;
    }
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    
    $username=htmlspecialchars($_POST['username'], ENT_QUOTES);
    $password=htmlspecialchars($_POST['password'], ENT_QUOTES);
    
    credenciales_validas($username,$password);
    
   
}
session_destroy();
muestra_login();

?>
