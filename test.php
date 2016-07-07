<?php
echo 'testing';
$f = fopen('testout.txt', "a+");
fwrite($f, 'hi');
fclose($f);

