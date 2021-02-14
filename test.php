<?php

$x = 58; 
$y = 869381.33333333;
$res = bcdiv($x, $y, 6);;


$x = 0.0005;
$x = round($x, 2);

echo $x;
echo $x == 0 ? 'true' : 'false';
