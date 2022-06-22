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
   
    //$token  = "c7a041f1-351e-4f1f-81b1-04373d4a501d"; // fabiangomez@gmail.com

    $token = getToken($email);

    $blocks = getBlocks($token);

    $servicio = new Servicio();

    $check = new Check($servicio);

    $arrayordenado = $check->check($blocks, $token);

    $valido = $check->validate($arrayordenado, $token);

    return ['valido'=> $valido, 'arrayordenado' => $arrayordenado]; 
}

function getToken($email){
    $API_URL = 'http://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'token';

    $requestData = [
        'query' => [
            'email' => $email
        ],
    ];
    
    $client = new Client([
        'base_uri' => $API_URL,
    ]);
    
    $response = $client->request('GET', $API_URL . $ENDPOINT, $requestData);
    
    $json = $response->getBody()->getContents();

    return json_decode($json)->token;
}

function getBlocks($token){
    $API_URL = 'http://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'blocks';

    $requestData = [
        'query' => [
            'token' => $token
        ],
    ];
    
    $client = new Client([
        'base_uri' => $API_URL,
    ]);
    
    $response = $client->request('GET', $API_URL . $ENDPOINT, $requestData);
    
    $json = $response->getBody()->getContents();

    return json_decode($json)->data;
}
?>