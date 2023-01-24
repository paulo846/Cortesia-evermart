<?php

$dir = 'assets/txt';
$files = scandir($dir);

foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo '<a href="'.$dir.'/'.$file.'">'.$file.'</a><br>';
    }
}

?>
