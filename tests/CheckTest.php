<?php
use PHPUnit\Framework\TestCase;

include('public/check.php');
include('public/serviciomock.php');
require_once('vendor/autoload.php');

class CheckTest extends TestCase{

    public function testCheck(): void
    {
        $expected = ["qwer","zcvf", "uhgt", "asdf",  "erty"];

        $token = 'c7a041f1-351e-4f1f-81b1-04373d4a501d';

        $blocks = ["qwer", "asdf", "zcvf", "erty", "uhgt"];

        $servicio = new Serviciomock();

        $check = new Check($servicio);

        $resultado = $check->check($blocks, $token);

        print_r($resultado);
        
        $this->assertEquals($expected, $resultado);
    }
}


?>