<?php
require_once('../vendor/autoload.php');
use Symfony\Component\HttpClient\HttpClient;

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
    $API_URL = 'https://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'check';

    $client = HttpClient::create([
        'max_redirects' => 3,
    ]);
    $response = $client->request(
        'POST',
        $API_URL . $ENDPOINT,
        [
            'query' => ['token' => $token],
            'json' => ["blocks" => [
                $primero,
                $segundo
              ]],
        ]
    );
    return $response->toArray()['message'];
}

function validaEncoded($token, $encoded){
    $API_URL = 'https://rooftop-career-switch.herokuapp.com/';
    $ENDPOINT = 'check';

    $client = HttpClient::create([
        'max_redirects' => 3,
    ]);
    
    $response = $client->request(
        'POST',
        $API_URL . $ENDPOINT,
        [
            'query' => ['token' => $token],
            'json' => ["encoded" => $encoded],
        ]
    );
    
    return $response->toArray()['message'];
}

/*
pruebas
bertranluciano@gmail.com

{"response_desc":{"valido":true,"encoded":"SqJZTljjoQ3RauQ69t3h5JSeqwaNlqtoAQWBBaSiGeGtsq26JUSNIx3Xf3rQRzIWWWes2STEeTPCDGuDJGeFST2441eQQzkkzOHNTjG1xLRm8qxvVkZHsZjISiNnBlyeST0ujaueye8ZcyqVc4Tl1r8cHmpZuurMUFSpQbsqxCtaYu34YQ1JDVfgkPaad107nds0H2eqz412G5GKxJJmDVEXFoQ5byTzxptfiLCDaz4nxUdla0jGPIyRoWdFIzgLhFqFTkJow4gc9Z6o3DZU7PFezz5cvP43nNHrTajEsbDqsTujRjJvytHTs7JNv3M3K14QUfWaOB2yoCWg3b82zO9y80T1N1J4mEtyEnKTzEIaINGobGGMxax4sdzL9P9nwqunsXD31Rx79QbXz9uUdqKQhgjYpJ58YSK9QGsiRr7ovL71CVpz5NY4ithdl3oBLEUCVVLFHtldGvfVWP7fGUmQT0Dpv5rCtFXR34SRWcjGgQtwCtkBdewgFacQ2czh6mCwFoqqsBWVuV7FdXewMD3TRPKu5UTtuh6FrnIgO2n8VG5OJsH2TYzVe94WGeUWwIsKiLrpMD29y5ay0u6WsLR8e41oYjoUBZsD8jSwGc2bSO1L6ed24B7WKEBbHSdphUtaIqflAtUPatpPkFSReua4I3HXp8kiq74exOHCra7FHXIpQ9d9aGcC1mrFY6eW15XdenID7ZPalqa5FtxOIUrxIzwiT81QcJxZuU6f23MdKOci4pkhKcTgBeXQIlOx12nEylgF7epoF2TUOWYEabEQwN1S63ktv5sJEWKcgZWHq8BEaLfpovtvT0ZFi9GFUjsDDKbtFjmP6hJ56igsPX0NegWBqH3hRizWyhbS36KEN9MKlzuzKdgIA3ldQ0xp42iD","arrayordenado":["SqJZTljjoQ3RauQ69t3h5JSeqwaNlqtoAQWBBaSiGeGtsq26JUSNIx3Xf3rQRzIWWWes2STEeTPCDGuDJGeFST2441eQQzkkzOHN","TjG1xLRm8qxvVkZHsZjISiNnBlyeST0ujaueye8ZcyqVc4Tl1r8cHmpZuurMUFSpQbsqxCtaYu34YQ1JDVfgkPaad107nds0H2eq","z412G5GKxJJmDVEXFoQ5byTzxptfiLCDaz4nxUdla0jGPIyRoWdFIzgLhFqFTkJow4gc9Z6o3DZU7PFezz5cvP43nNHrTajEsbDq","sTujRjJvytHTs7JNv3M3K14QUfWaOB2yoCWg3b82zO9y80T1N1J4mEtyEnKTzEIaINGobGGMxax4sdzL9P9nwqunsXD31Rx79QbX","z9uUdqKQhgjYpJ58YSK9QGsiRr7ovL71CVpz5NY4ithdl3oBLEUCVVLFHtldGvfVWP7fGUmQT0Dpv5rCtFXR34SRWcjGgQtwCtkB","dewgFacQ2czh6mCwFoqqsBWVuV7FdXewMD3TRPKu5UTtuh6FrnIgO2n8VG5OJsH2TYzVe94WGeUWwIsKiLrpMD29y5ay0u6WsLR8","e41oYjoUBZsD8jSwGc2bSO1L6ed24B7WKEBbHSdphUtaIqflAtUPatpPkFSReua4I3HXp8kiq74exOHCra7FHXIpQ9d9aGcC1mrF","Y6eW15XdenID7ZPalqa5FtxOIUrxIzwiT81QcJxZuU6f23MdKOci4pkhKcTgBeXQIlOx12nEylgF7epoF2TUOWYEabEQwN1S63kt","v5sJEWKcgZWHq8BEaLfpovtvT0ZFi9GFUjsDDKbtFjmP6hJ56igsPX0NegWBqH3hRizWyhbS36KEN9MKlzuzKdgIA3ldQ0xp42iD"],
    "contllamados":19}}

fabiangomez@gmail.com
{"response_desc":{"valido":true,"encoded":"1Xnj47Xz7Ge2KlFA8FTA12nXW8fHZePvq4JH6ok7dQebD0KX4MkGGEN9XkoLTkzXsDBy1puBwqxogGTvRmSzZWGv963gjDPRp7CC0514RTnenELwQNnV0tVTBekK3KAD10Olb3hpbwReMIFoW54EutYkGmQAimYHgIcNWx4rgZ2ZbyU39Y5YWG3QvGgj2b2Q2wK9DVwUB2x0qBA9MQ86o2kz0H94jSp2UjyplrEp633CneTND84FHYgbK6NMexxnXy7frdASo5OJ826Gw72tG9JZYdinNJYqpmsZoihvE5g0aw2JGuy4hgRMUfBSIovZA1rXhqjrc2Jj1mKPDcwtNlAMzPSRkuoak5BuZy5gJsoytM9dF3sl5EkVcpziO6tZtZz49eUr68HUScJ37j6QFZ7RExqXO2Zzfqqx2sd9RwPyDLdZedZoK2gsUzvUd7BvGdlXZpzyXFjobDiwoXk3Viaku2ghsf3kGKft0oVCUTlvUZ9PiNqIjA0zByoUUVf6gr4KjBymZtGoHArf5TlCmMOKnoaoxIU0wYLh3ZF2hiMvXvwvQLCut0H92eSQfQpvdnDram9n3JSAFnGLJvrHqtQ0pFB4d5D2ik0IDnLpb8qPqB9fWjDEJrJMFWrYBwIvOwAdiQGPRd39nyTVUZ9CVhiOVTJEBt6JsEI5xPcYcPHocej95XtpOfDbT900d2eRlYCVSyzgVRgbRNCV5otNvhjQULEFFayyg6eRiTYuPqok4DBc3vCDlSW4jG2v6kI6P9S1yxcp9kYf2Zj4O7L5Wp3fCbxblt3Pynh9FTZOJ7rv805O42ti6SvT7v8kZZMJjbTub94XBt6wGGH0c3Wtv0QKAE3qjzzkYwfTowsnYtMZDtytFAcThAItj9J1a9TdnzOZ","arrayordenado":["1Xnj47Xz7Ge2KlFA8FTA12nXW8fHZePvq4JH6ok7dQebD0KX4MkGGEN9XkoLTkzXsDBy1puBwqxogGTvRmSzZWGv963gjDPRp7CC","0514RTnenELwQNnV0tVTBekK3KAD10Olb3hpbwReMIFoW54EutYkGmQAimYHgIcNWx4rgZ2ZbyU39Y5YWG3QvGgj2b2Q2wK9DVwU","B2x0qBA9MQ86o2kz0H94jSp2UjyplrEp633CneTND84FHYgbK6NMexxnXy7frdASo5OJ826Gw72tG9JZYdinNJYqpmsZoihvE5g0","aw2JGuy4hgRMUfBSIovZA1rXhqjrc2Jj1mKPDcwtNlAMzPSRkuoak5BuZy5gJsoytM9dF3sl5EkVcpziO6tZtZz49eUr68HUScJ3","7j6QFZ7RExqXO2Zzfqqx2sd9RwPyDLdZedZoK2gsUzvUd7BvGdlXZpzyXFjobDiwoXk3Viaku2ghsf3kGKft0oVCUTlvUZ9PiNqI","jA0zByoUUVf6gr4KjBymZtGoHArf5TlCmMOKnoaoxIU0wYLh3ZF2hiMvXvwvQLCut0H92eSQfQpvdnDram9n3JSAFnGLJvrHqtQ0","pFB4d5D2ik0IDnLpb8qPqB9fWjDEJrJMFWrYBwIvOwAdiQGPRd39nyTVUZ9CVhiOVTJEBt6JsEI5xPcYcPHocej95XtpOfDbT900","d2eRlYCVSyzgVRgbRNCV5otNvhjQULEFFayyg6eRiTYuPqok4DBc3vCDlSW4jG2v6kI6P9S1yxcp9kYf2Zj4O7L5Wp3fCbxblt3P","ynh9FTZOJ7rv805O42ti6SvT7v8kZZMJjbTub94XBt6wGGH0c3Wtv0QKAE3qjzzkYwfTowsnYtMZDtytFAcThAItj9J1a9TdnzOZ"],
    "contllamados":14}}
*/
?>

