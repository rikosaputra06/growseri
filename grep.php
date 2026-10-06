<?php
$f = file('storage/logs/laravel.log');
foreach($f as $line) {
    if (strpos($line, 'Exception:') !== false) {
        echo $line;
    }
}
