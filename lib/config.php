<?php

function parse_ini($ini_file) {

    $config = array();

    $fh = fopen($ini_file, 'r');
    while ($line = fgets($fh)) {
        $line = rtrim($line);

        if (!preg_match('/^#/', $line) and $line != '') {
            list($key, $val) = explode('=', $line);
            $key = preg_replace('/^\s+/', '', $key);
            $key = preg_replace('/\s+$/', '', $key);
            $val = preg_replace('/^\s+/', '', $val);
            $val = preg_replace('/\s+$/', '', $val);
            $config[$key] = $val;
        }
    }
    fclose($fh);

    return $config;
}

?>
