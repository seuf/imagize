<?php


require_once 'lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem('views');
$twig = new Twig_Environment($loader, array(
    'cache' => 'cache',
    'auto_reload' => true,
));

$template = $twig->loadTemplate('register.html');

$errors     = array();
$first_name = '';
$last_name  = '';
$mail       = '';
$password   = '';

if (isset($_POST['login']) and $_POST['login'] != '') {
    $login = $_POST['login'];

    if (isset($_POST['first_name']) and $_POST['first_name'] != '') {
        $first_name = $_POST['first_name'];
    } else {
        $errors[] = "Veuillez renseigner un prénom.";
    }
    if (isset($_POST['last_name']) and $_POST['last_name'] != '') {
        $last_name = $_POST['last_name'];
    } else {
        $errors[] = "Veuillez renseigner un nom.";
    }
    if (isset($_POST['mail']) and $_POST['mail'] != '') {
        $mail = $_POST['mail'];
    } else {
        $errors[] = "Veuillez renseigner un E-mail.";
    }
    if (isset($_POST['password']) and $_POST['password'] != '') {
        $password = md5($_POST['password']);
    } else {
        $errors[] = "Veuillez renseigner un mot de passe.";
    }

    if ($first_name != '' and $last_name != '' and $mail != '' and $password != '') {
        $fh = fopen('.passwd_waiting_for_approval', 'a');
        fwrite($fh, "$login||$first_name||$last_name||$mail||$password\n");
        fclose($fh);
        $message[] = "Inscription effectuée avec succès..";
        $message[] = "Vous recevrez un mail lorsque votre inscription sera validée.";
        $message[] = "A bientôt !";
        
        $template = $twig->loadTemplate('index.html');
    }
}



echo $template->render(array(
        'title'    => 'Imagize',
        'errors'   => $errors,
        'messages' => $message,
    )
);


?>
