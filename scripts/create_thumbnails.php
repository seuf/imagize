<?php

require_once("lib/config.php");
require_once("lib/images.php");

$config = parse_ini('config.ini');

createThumbs($config['images_path'], $config['images_thumbs_path'], $config['thumb_size']);


?>
