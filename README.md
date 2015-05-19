# Imagize

## Description

Imagize is a simple light web image gallery written in PHP.
It is based on the the [materializecss](http://materializecss.com) library.
No database backend.

## Installation

### with git

Checkout the github repository into your web server diretory (i.e /var/www/ ):
```
git clone https://github.com/seuf/imagize.git
```
### With a Tarball

Download the imagize package from github, and extract it to your web server directory (/var/www)
```
cd /var/www
wget https://github.com/seuf/imagize/releases/imagize-1.0.tar.gz
tar -xzvf imagize-1.0.tar.gz
```

### Configuration

Create a config/config.ini file with the following elements :

```
title = Imagize Rocks !
admin_user = seuf
images_path = data
images_thumbs_path = cache/thumbnails
thumb_size = 250
```

### Permissions

Create a symlink to your Images Directory :
```
ln -s /path/to/Albums data
```

Give write access to www-data (to allow image rotation when clicking on arrow icons on images cards..):
```
sudo chgrp -R www-data data
sudo chmod -R g+w data
```

create a cache directory with writing permission for your web server :
```
mkdir -p cache/thumbnails
sudo chgrp -R www-data cache
sudo chmod -R g+w cache
```

### Create Thumbnails

Thumbnails are not created automatically.
Once you have configured your images paths in the config.ini file, you can lanch the script that create the thumbs :
```
php scripts/create_thumbnails.php
```

You should launch this script as the www-data user, and put it in your crontab ;)


## Users

To create a new user, go to the register page, then .. register !
This will create a .passwd_waiting_for_approval file.
Then Copy the line with the login corresponding to your account into the .passwd file.

Here is an example of line to copy in the .passwd file :
```
seuf||Thierry||Sallé||seuf@aperogeek.fr||485c903c9c1fdff5c55e68555a2a6eef
```

## Users permissions

ToDO...
