<?php
$f = file_get_contents('storage/logs/laravel.log');
preg_match_all('/Exception.*?\[variants\].*?(?=#\d{2})/s', $f, $m);
if (!empty($m[0])) {
    echo substr($m[0][count($m[0])-1], 0, 2000);
}
