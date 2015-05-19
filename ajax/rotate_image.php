<?php

$filename = $_GET['filename'];
$angle = $_GET['angle'];

// parse path for the extension
$info = pathinfo($filename);
$extension = strtolower($info['extension']);
// continue only if this is a JPEG image
define('ROOT_DIR', dirname('.'));
if ($extension == 'jpg') {
    echo "rotating image $filename\n";
    $source = imagecreatefromjpeg('../'.$filename);
    $rotate = imagerotate ($source, $angle, 0);
    imagejpeg($rotate, '../'.$filename);
} elseif ($extension == 'png') {
    $source = imagecreatefrompng($filename);
    $rotate = imagerotate ($source, $angle, 0);
    imagepng($rotate, $filename);
}

require_once('../lib/config.php');
require_once('../lib/images.php');

$config   = parse_ini('../config/config.ini');
$subdirs  = explode('/', $filename);
$file     = array_pop($subdirs);
$filename_path = join('/', $subdirs);
$thumb_path = preg_replace('/'.$config['images_path'].'/', '/'.$config['images_thumbs_path'].'/', $filename_path);

createThumbnail('../'.$filename_path, $file, '../'.$thumb_path, $config['thumb_size']);


?>
