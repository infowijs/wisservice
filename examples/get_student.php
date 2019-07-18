<?php

/**
 * Copyright 2019 Infowijs.
 * Created by Thomas Schoffelen.
 */

require('../vendor/autoload.php');

$options = json_decode(file_get_contents('../credentials.json'), true);

$client = \Somtoday\WISService::create($options);

$data = $client->getLeerling([
    'leerlingNummer' => 407479
]);

echo 'Student data:' . PHP_EOL;
print_r($data);
