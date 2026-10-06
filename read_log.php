<?php
$f = file('storage/logs/laravel.log');
$exceptions = [];
foreach($f as $line) {
    if (strpos($line, 'Exception:') !== false || strpos($line, 'Error:') !== false || strpos($line, 'production.ERROR:') !== false) {
        $exceptions[] = $line;
    }
}
echo implode('', array_slice($exceptions, -10));
