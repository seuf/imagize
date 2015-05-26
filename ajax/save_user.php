<?php

require_once('../lib/users.php');

$user = $_POST['user'];
$dir  = $_POST['dir'];
$access = $_POST['access'];

$u = get_user($user);

if (isset($u['dirs'])) {
    $dirs = $u['dirs'];
} else {
    $dirs = array();
}

if ($access == "true") {
    $dirs = array_merge($dirs, array($dir));
} else {
    $dirs = array_merge(array_diff($dirs, array($dir)));
    print_r($dirs);
}

$u['dirs'] = $dirs;
save_user($u);

?>
