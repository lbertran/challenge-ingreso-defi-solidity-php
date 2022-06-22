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

class Serviciomock {

    public function comparaValores($token, $primero, $segundo){
        $response = $this->getMockedClient()->request('POST', '/check?blocks='.$primero.$segundo);
        $body     = (string) $response->getBody();
        $message  = json_decode($body, true)['message'] === 'true'? true: false;
        
        return $message;
    }

    public function validaEncoded($token, $encoded){
        $response = $this->getMockedClient()->request('POST', '/check?token='.$token.'&encoded='.$encoded);
        $body     = (string) $response->getBody();
        $message  = json_decode($body, true)['message'] === 'true'? true: false;
        
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
                ->withPath('/check')
                ->withConditionalResponse('blocks=qwerzcvf', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('blocks=zcvfuhgt', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('blocks=uhgtasdf', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('blocks=asdferty', new Response(200, [], '{"message":"true"}'))
                ->withConditionalResponse('encoded=qwerzcvfuhgtasdferty', new Response(200, [], '{"message":"true"}'))
                ->withDefaultResponse(new Response(200, [],'{"message":"false"}'))
                ->build()
        );

        $clientBuilder = new MockedGuzzleClientBuilder($handlerBuilder);

        return $clientBuilder->build();
    }


}
?>