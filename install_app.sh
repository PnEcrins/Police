#!/bin/bash

. conf/settings.ini

# Donner les droits n�cessaires pour le bon fonctionnement de l'application (adapter les chemins � votre serveur)
echo "Configuration des droits des r�pertoires de l'application..."
sudo chmod -R 777 log

echo "Cr�er les fichiers de configurations en lien avec la base de donn�es..."
cp conf/connecter.php.sample conf/connecter.php
cp conf/parametres.php.sample conf/parametres.php
cp documents_liste.php.sample documents_liste.php

echo "configuration du fichier conf/connecter.php..."
sed -i "s/user ='policeuser';/user ='$user_pg';/g" conf/connecter.php
sed -i "s/passe='monpassachanger';/passe='$user_pg_pass';/g" conf/connecter.php
sed -i "s/base='policedb';/base='$db_name';/g" conf/connecter.php

echo "configuration du fichier conf/parametres.php..."
sed -i "s/id_application = 3;/id_application = $id_application;/g" conf/parametres.php
sed -i "s/id_menu = 1;/id_menu = $id_menu;/g" conf/parametres.php
sed -i "s/host_url = 'http://mondomaine.fr';/host_url = '$host_url';/g" conf/parametres.php

echo "Suppression des fichier de log de l'installation..."
rm log/*.log

echo "Configuration du r�pertoire web de l'application..."
sudo ln -s ${PWD}/ /var/www/police
echo "Vous devez maintenant v�rifier le fichier de connexion � la base de donn�es : conf/connecter.php et l'adapter � votre besoin"
echo "Vous devez maintenant v�rifier le fichier de param�trage de l'application : conf/parametres.php et l'adapter � votre contexte"
echo "Vous pouvez aussi �diter/adapter le fichier de configuration des documents consultables : documents_liste.php et l'adapter � votre besoin"
