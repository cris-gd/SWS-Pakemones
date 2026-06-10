<?php
$filename = "../private/json/users.json";
$data = file_get_contents($filename);
$userdata = json_decode($data, true);

foreach ($userdata["users"] as $key => $usuario) {
    //si no está ya hasheado
    if (!preg_match('/^[0-9a-f]{40}$/', $usuario["password"])) {
        $userdata["users"][$key]["password"] = sha1($usuario["password"]);
    }
}

$resultado = json_encode($userdata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents($filename, $resultado);
?>
