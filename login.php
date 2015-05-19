<?php


require_once 'lib/config.php';
require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
    'auto_reload' => true,
));

$template = $twig->loadTemplate('login.html');

$errors = array();
$user   = array();
$messages = array();


if (isset($_POST['login']) and $_POST['login'] != '' and isset($_POST['password']) and $_POST['password'] != '') {
    $login_success = false;
    $fh = fopen('config/users/.passwd', 'r');
    while ($line = fgets($fh)) {
        $line = rtrim($line);
        list($login, $first_name, $last_name, $mail, $password) = explode('||', $line);
        $md5_password = md5($_POST['password']);
        if ($login == $_POST['login'] and $password == $md5_password) {
            $user['login'] = $login;
            $user['first_name'] = $first_name;
            $user['last_name']  = $last_name;
            $user['mail'] = $mail;

            $config = parse_ini('config/config.ini');
            if ($login == $config['admin_user']) {
                $user['admin'] = true;
            }

            $login_success = true;
            $messages[] = 'Authentification réussie !';

            session_start();
            $_SESSION['user'] = $user;
            $_SESSION['messages'] = $messages;
            
            header('Location: index.php');
            exit; 
        }
    }
    fclose($fh);
    if (!$login_success) {
        $errors[] = "Login ou mot de passe incorrect";
    }
}

echo $template->render(array(
        'title'    => 'Imagize',
        'errors'   => $errors,
        'messages' => $messages,
        'user'     => $user,
    )
);


?>
