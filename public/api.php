<?php

require_once('../vendor/autoload.php');
use GuzzleHttp\Client;

if (isset($_GET['email']) && $_GET['email']!="") {
	$response = ordenador($_GET['email']);
    response($response);
} else {
	response("Debe ingresar un email valido");
}

function response($response){
	$json_response = json_encode($response);
	echo $json_response;
}

function ordenador($email){
    include('check.php');
    include('servicio.php');
   
    $servicio = new Servicio();

    $check = new Check($servicio);

    $token = $check->getToken($email);

    $blocks = $check->getBlocks($token);

    $arrayordenado = $check->check($blocks, $token);

    $valido = $check->validate($arrayordenado, $token);

    return ['valido'=> $valido, 'arrayordenado' => $arrayordenado]; 
}
?>