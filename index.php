<?php

require_once 'lib/config.php';
require_once 'lib/images.php';
require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
    'auto_reload' => true,
));

$template = $twig->loadTemplate('index.html');

$config = parse_ini('config.ini');
$messages = array();


session_start();
$user = $_SESSION['user'];
$dir = '';
$parent = '';

if (isset($user['login']) and $user['login'] != '') {

    $path = $config['images_path'];
    if (isset($_GET['dir']) and $_GET['dir'] != '') {
        $dir = $_GET['dir'];
        $path .= '/'.$dir;
//        createThumbs( $path, $config['images_thumbs_path'], 250); 
        $subdirs = explode('/', $dir);
        array_pop($subdirs);
        $parent = join('/', $subdirs);
    }
    $files = list_dir($path); 



}


echo $template->render(array(
    'title'     => $config['title'],
    'user'      => $_SESSION['user'],
    'files'     => $files,
    'dir'       => $dir,
    'parent'    => $parent,
    'base_path' => $config['images_path'],
    'cache_path' => $config['images_thumbs_path'],
));

?>
