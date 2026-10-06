<?php
$c = file_get_contents('resources/views/livewire/admin/products.blade.php');
preg_match_all('/<div[^>]*>/i', $c, $m1);
preg_match_all('/<\/div>/i', $c, $m2);
echo "Open divs: " . count($m1[0]) . "\n";
echo "Close divs: " . count($m2[0]) . "\n";
