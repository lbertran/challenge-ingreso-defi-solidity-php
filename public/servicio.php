<?php
use GuzzleHttp\Client;

class Servicio {

    public function comparaValores($token, $primero, $segundo){
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
    
        return boolval(json_decode($json)->message);
    }

    public function validaEncoded($token, $encoded){
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
    
        return boolval(json_decode($json)->message);
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


}
?>