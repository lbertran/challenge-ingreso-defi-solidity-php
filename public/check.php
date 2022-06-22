<?php
require_once('../vendor/autoload.php');
use GuzzleHttp\Client;

function check($blocks, $token){

    $cantidadbloques = count($blocks);
    $contllamados = 0;

    $arrayordenado = [];
    $arrayordenado[0] = $blocks[0];


    unset($blocks[0]); 
    $blocks = array_values($blocks);

    $pocisionbuscada = 1;

    while($pocisionbuscada < $cantidadbloques){
        
        $chequeo = false;
        foreach ($blocks as $key => $block) {
            if(!$chequeo){
                $chequeo = comparaValores($token, $arrayordenado[$pocisionbuscada-1],$block);
                $contllamados += 1;

                if($chequeo){
                    $arrayordenado[$pocisionbuscada] = $block;

                    unset($blocks[$key]); 
                    $blocks = array_values($blocks);
                    $pocisionbuscada = $pocisionbuscada + 1;
                };
            }
        }
    }

   

    $encoded = '';
    foreach ($arrayordenado as $block) {
        $encoded .= $block;
    }    
    
    $valido = validaEncoded($token, $encoded);

    return ['valido'=>$valido, 'encoded'=>$encoded, 'arrayordenado'=>$arrayordenado, 'contllamados'=>$contllamados];

    //return $arrayordenado;

}

function comparaValores($token, $primero, $segundo){
    $API_URL = 'http://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'check';

    $requestData = [
        'query' => [
            'token' => $token
        ],
        'json' => ["blocks" => [
            $primero,
            $segundo
          ]],
    ];
    
    $client = new Client([
        'base_uri' => $API_URL,
    ]);
    
    $response = $client->request('POST', $API_URL . $ENDPOINT, $requestData);
    
    $json = $response->getBody()->getContents();

    return json_decode($json)->message;
}

function validaEncoded($token, $encoded){
    $API_URL = 'http://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'check';

    $requestData = [
        'query' => [
            'token' => $token
        ],
        'json' => ["encoded" => $encoded],
    ];
    
    $client = new Client([
        'base_uri' => $API_URL,
    ]);
    
    $response = $client->request('POST', $API_URL . $ENDPOINT, $requestData);
    
    $json = $response->getBody()->getContents();

    return json_decode($json)->message;
}


?>

