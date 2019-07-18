<?php

/**
 * Copyright 2019 Infowijs.
 * Created by Thomas Schoffelen.
 */

require('../vendor/autoload.php');

$options = json_decode(file_get_contents('../credentials.json'), true);

$client = \Somtoday\WISService::create($options);

$data = $client->getCijferOverzicht([
    'leerlingNummer' => 407479,
    'peilDatum' => '2017-03-31T17:43:03+01:00'
    //'peilDatum' => date('c'),
    //'adviesKolommenJaNee' => true
]);

foreach($data as $subject) {
    $grades = [];
    foreach($subject->resultaten as $grade) {
        if($grade->weging < 1 || !$grade->cijfer) {
            // skip grades with weight 0
            continue;
        }
        $grades[] = $grade->cijfer . ' (x' . $grade->weging . ')';
    }

    echo $subject->vak->naam . ':' . PHP_EOL;
    echo ($grades ? join(', ', $grades) : 'no grades') . PHP_EOL . PHP_EOL;
}
