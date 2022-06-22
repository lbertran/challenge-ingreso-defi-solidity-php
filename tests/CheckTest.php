<?php

use DoppioGancio\MockedClient\HandlerBuilder;
use DoppioGancio\MockedClient\MockedGuzzleClientBuilder;
use DoppioGancio\MockedClient\Route\ConditionalRouteBuilder;
use DoppioGancio\MockedClient\Route\RouteBuilder;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\TestCase;

class CheckTest extends TestCase{

    public function testApi(): void
    {
        $expected = ['qwer','zcvf','uhgt','asdf','erty'];
        
        $token = 'c7a041f1-351e-4f1f-81b1-04373d4a501d';

        
        $response = $this->getMockedClient()->request('GET', '/blocks?token=' . $token);
        $body     = (string) $response->getBody();
        $blocks  = json_decode($body, true)['data'];
        /*
        $response = $this->getMockedClient()->request('GET', '/check',$requestData);
        $body     = (string) $response->getBody();
        $message  = json_decode($body, true)['message'];
        
        print_r($message); */
        $arreglo_ordenado = $this->check($blocks, $token);

        $this->assertEquals($expected, $arreglo_ordenado);

    }

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
                    $chequeo = $this->comparaValores($token, $arrayordenado[$pocisionbuscada-1],$block);
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
        
        $valido = $this->validaEncoded($token, $encoded);
    
        return $arrayordenado;
    
    }
    
    function comparaValores($token, $primero, $segundo){
        $message = false;
        ($primero == 'qwer' && $segundo == 'zcvf')? $message = true : '';
        ($primero == 'zcvf' && $segundo == 'uhgt')? $message = true : '';
        ($primero == 'uhgt' && $segundo == 'asdf')? $message = true : '';
        ($primero == 'asdf' && $segundo == 'erty')? $message = true : '';
    
        return $message;
    }
    
    function validaEncoded($token, $encoded){
        ($encoded == 'qwerzcvfuhgtasdferty')? $message = true : $message = false;    
        return $message;
    }

    

    private function getMockedClient(): Client
    {
        $handlerBuilder = new HandlerBuilder(
            Psr17FactoryDiscovery::findServerRequestFactory(),
        );

        $cb = new ConditionalRouteBuilder(
            Psr17FactoryDiscovery::findResponseFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
        );

        $rb = new RouteBuilder(
            Psr17FactoryDiscovery::findResponseFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
        );

        $handlerBuilder->addRoute(
            $rb->new()
                ->withMethod('GET')
                ->withPath('/blocks')
                ->withResponse(new Response(200, [], '{"data": ["qwer", "asdf", "zcvf", "erty", "uhgt"],"chunkSize": 4,"length": 32}'))
                ->build()
        );

        $handlerBuilder->addRoute(
            $cb->new()
                ->withMethod('POST')
                ->withPath('/check/false')
                ->withConditionalResponse('{"blocks":["qwer","zcvf"]}', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('{"blocks":["zcvf","uhgt"]}', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('{"blocks":["uhgt","asdf"]}', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('{"blocks":["asdf","erty"]}', new Response(200, [], '{"message":"true"}'))
                ->withDefaultResponse(new Response(200, [],'{"message":"false"}'))
                ->build()
        );

        $handlerBuilder->addRoute(
            $cb->new()
                ->withMethod('GET')
                ->withPath('/country/')
                ->withConditionalResponse('code=de', new Response(200, [], '{"id":"+49","code":"DE","name":"Germany"}'))
                ->withConditionalResponse('code=it', new Response(200, [], '{"id":"+39","code":"IT","name":"Italy"}'))
                ->build()
        );

        $handlerBuilder->addRoute(
            $rb->new()
                ->withMethod('GET')
                ->withPath('/country/IT')
                ->withResponse(new Response(200, [], '{"id":"+39","code":"IT","name":"Italy"}'))
                ->build()
        );

        

        $handlerBuilder->addRoute(
            $rb->new()
                ->withMethod('GET')
                ->withPath('/admin/dashboard')
                ->withResponse(new Response(401))
                ->build()
        );

        $handlerBuilder->addRoute(
            $rb->new()
                ->withMethod('GET')
                ->withPath('/slow/api')
                ->withException(new ConnectException(
                    'Timed out after 30 seconds',
                    new Request('GET', '/slow/api')
                ))
                ->build()
        );

        $clientBuilder = new MockedGuzzleClientBuilder($handlerBuilder);

        return $clientBuilder->build();
    }
}
?>