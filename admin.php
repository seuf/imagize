<?php

require_once 'lib/config.php';
require_once 'lib/images.php';
require_once 'lib/users.php';
require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
    'auto_reload' => true,
));

$template = $twig->loadTemplate('admin.html');

$config = parse_ini('config/config.ini');
$messages = array();
$errors = array();
$users = array();
$dirs = array();

session_start();
$user = $_SESSION['user'];
$dir = '';
$parent = '';

if (isset($user['login']) and $user['login'] != '') {
    if (isset($user['admin']) and $user['admin']) {
        $dirs = get_dirs($config['images_path']);

        $users = get_users();
    } else {
        $errors[] = "Hep Hep Hep, tu vas où là ?";
    }

}


echo $template->render(array(
    'title'     => $config['title'],
    'user'      => $_SESSION['user'],
    'base_path' => $config['images_path'],
    'dirs'      => $dirs,
    'users'     => $users,
    'cache_path' => $config['images_thumbs_path'],
));

?>
