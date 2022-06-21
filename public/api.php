<?php

require_once('../vendor/autoload.php');
use Symfony\Component\HttpClient\HttpClient;

if (isset($_GET['email']) && $_GET['email']!="") {
	include('check.php');
	$resp = ordenador($_GET['email']);
    response($resp);
} else {
	response("Debe ingresar un email valido");
}

function response($response_desc){
	$response = $response_desc;
	$json_response = json_encode($response);
	echo $json_response;
}

function ordenador($email){

    //$token  = "c7a041f1-351e-4f1f-81b1-04373d4a501d"; // fabiangomez@gmail.com

    $token = getToken($email);

    $blocks = getBlocks($token);

    $arrayordenado = check($blocks, $token);

    return $arrayordenado;
}

function getToken($email){
    $API_URL = 'https://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'token';

    $client = HttpClient::create([
        'max_redirects' => 3,
    ]);
    
    $response = $client->request(
        'GET',
        $API_URL . $ENDPOINT,
        [
            'query' => ['email' => $email]
        ]
    );
    
    return $response->toArray()['token'];

    
}

function getBlocks($token){
    $API_URL = 'https://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'blocks';

    $client = HttpClient::create([
        'max_redirects' => 3,
    ]);
    
    $response = $client->request(
        'GET',
        $API_URL . $ENDPOINT,
        [
            'query' => ['token' => $token]
        ]
    );
    
    return $response->toArray()['data'];

    
}
?>