<?php

use GuitarHero\GuitarString;

require_once './vendor/autoload.php';

$init = [0.2,0.4,0.5,0.3,-0.2,0.4,0.3,0.0,-0.1,-0.3];
$gs = (new GuitarString())->createFromArray($init);

for ($i = 0; $i < $gs->length(); $i++) {
    $gs->tic();
}

var_dump($gs);