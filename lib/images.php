<?php

require_once('users.php');
require_once('config.php');



function list_dir($dir) {

    $files = array();
    $nb_files = 0;
    $nb_dirs = 0;


    $user = $_SESSION['user'];
    $u = get_user($user['login']);

    $config = parse_ini('config/config.ini');
    $base_path = $config['images_path'];

    if ($dh = opendir($dir)) {
        while (false !== ($entry = readdir($dh))) {
            if (preg_match('/^\./', $entry)) {
                continue;
            }
            $file = array();
            $file['name']  = $entry;
            $file['is_dir'] = is_dir($dir.'/'.$entry);
            if ($file['is_dir']) {
                $permission_dir = preg_replace('/'.$base_path.'\//', '', $dir.'/'.$entry);
                if (!in_array($permission_dir, $u['dirs'])) {
                    continue;
                }
                $file['first_img'] = get_directory_first_image($dir.'/'.$entry); 
                $nb_dirs++;
            } else {
                $info = pathinfo($dir.'/'.$entry);
                $file['extension'] = strtolower($info['extension']);
                if (preg_match('/jpg|jpeg|png/', $file['extension'])) {
                    $exif = exif_read_data ( $dir.'/'.$entry );
                    $transform = "";
                    switch ($exif['Orientation']) {
                        case 1: $transform = ''; break;
                        case 2: $transform = "flip-horizontal"; break;
                        case 3: $transform = "rotate-180"; break;
                        case 4: $transform = "flip-vertical"; break;
                        case 5: $transform = "transpose"; break;
                        case 6: $transform = "rotate-90"; break;
                        case 7: $transform = "transverse"; break;
                        case 8: $transform = "rotate-270"; break;
                        default: $transform = ""; break;
                    }
                    $file['transform'] = $transform;
                    $nb_files++;
                }
            }
            if ($file['first_img'] or $nb_files) {
                $files[] = $file;
            }
        }
        $files['nb_files'] = $nb_files;
        $files['nb_dirs'] = $nb_dirs;
        closedir($dh);
    } else {
        return false;
    }

    return $files;
}


function get_dirs($dir) {

    $folders = array();


    if ($dh = opendir($dir)) {
        while (false !== ($entry = readdir($dh))) {
            if (preg_match('/^\./', $entry)) {
                continue;
            }
            $file = array();
            $file['is_dir'] = is_dir($dir.'/'.$entry);
            if ($file['is_dir']) {
                $file['name']  = $entry;
                $sub_dirs = get_dirs($dir.'/'.$entry);
                foreach ($sub_dirs as $subdir) {
                    $subdir['name'] = $entry.'/'.$subdir['name'];
                    $folders[] = $subdir;
                }
                $folders[] = $file;
            }
        }
        closedir($dh);
    }

    return $folders;
}


function get_directory_first_image($dir) {

    $file = '';
    if ($dh = opendir($dir)) {
        // First pass to check if there is a regular image file in he directory
        while (false !== ($entry = readdir($dh))) {
            if (preg_match('/^\./', $entry)) {
                continue;
            }
            if (is_file($dir.'/'.$entry)) {
                $info = pathinfo($dir.'/'.$entry);
                $extension = strtolower($info['extension']);
                if (preg_match('/jpg$|png$/', $extension)) {
                    $file = $entry;
                    break;
                }
            }
        }
        if ($file == '') {
            // No image found. Checking in subdirs
            closedir($dh);
            $dh = opendir($dir);
            while (false !== ($entry = readdir($dh))) {
                if (preg_match('/^\./', $entry)) {
                    continue;
                }
                if (is_dir($dir.'/'.$entry)) {
                    $file = get_directory_first_image($dir.'/'.$entry);
                    if ($file != "") {
                        $file = $entry.'/'.$file;
                        break;
                    }
                }
            }
        }
            
    }
    return $file;
}

function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth) {
    // open the directory
    $dir = opendir( $pathToImages );
    if (!$dir) {
        echo "Can't opendir $pathToImages : $!";
        exit;
    }
    // loop through it, looking for any/all JPG files:
    while (false !== ($fname = readdir( $dir ))) {
        // Check if the thumb already exists
        if (is_file($pathToThumbs.'/'.$fname)) {
            //echo  "La vignette {$fname} existe déjà.\n";
        } else {
            if (is_dir($pathToImages.'/'.$fname)) {
                if (!preg_match('/^\./', $fname)) {
                    if (!is_dir($pathToThumbs.'/'.$fname)) {
                        mkdir($pathToThumbs.'/'.$fname);
                    }
                    createThumbs($pathToImages.'/'.$fname, $pathToThumbs.'/'.$fname, $thumbWidth);
                }
 
            } else {
               createThumbnail($pathToImages, $fname, $pathToThumbs, $thumbWidth);
            }
        }
    }
    // close the directory
    closedir( $dir );
}

function createThumbnail($pathToImages, $fname, $pathToThumbs, $thumbWidth) {

    // parse path for the extension
    $info = pathinfo($pathToImages .'/'. $fname);
    $extension = strtolower($info['extension']);
    // continue only if this is a JPEG image
    if ( preg_match('/jpg|png/', $extension) ) {
        echo "Creating thumbnail for $pathToImages/$fname\n";

        // load image and get image size
        if ($extension == 'jpg') {
            $img = imagecreatefromjpeg($pathToImages.'/'.$fname );
        } else {
            $img = imagecreatefrompng($pathToImages.'/'.$fname );
        }
        $width = imagesx( $img );
        $height = imagesy( $img );

        // calculate thumbnail size
        $new_width = $thumbWidth;
        $new_height = floor( $height * ( $thumbWidth / $width ) );

        // create a new temporary image
        $tmp_img = imagecreatetruecolor( $new_width, $new_height );

        // copy and resize old image into new image
        imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

        // save thumbnail into a file
        imagejpeg($tmp_img, $pathToThumbs.'/'.$fname);

    }
}



//$files = list_dir('/home/seuf/Images/Charlie');
//print_r($files);
//createThumbs('/home/seuf/Images/Charlie', 'cache/thumbnails', 250);

//$first_img = get_directory_first_image('/home/seuf/Images/Madames');
//echo "First img : $first_img\n";

?>
