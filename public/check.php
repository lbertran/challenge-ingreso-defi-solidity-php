<?php


class Check{
    private $servicio;

    public function __construct($servicio) {
        $this->servicio = $servicio;
    }

    public function check($blocks,$token){
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
                    $chequeo = $this->servicio->comparaValores($token, $arrayordenado[$pocisionbuscada-1],$block);
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
        $valido = $this->servicio->validaEncoded($token, $encoded);
        return $valido; 
    }
}








?>

