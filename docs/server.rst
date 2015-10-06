.. image:: http://geotrek.fr/images/logo-pne.png
    :target: http://www.ecrins-parcnational.fr
    
=======
SERVEUR
=======


Pr�requis
=========

* Ressources minimum serveur :

Un serveur disposant d'au moins de 1 Go RAM et de 10 Go d'espace disque.


* disposer d'un utilisateur linux nomm� ``police``. Le r�pertoire de cet utilisateur ``police`` doit �tre dans ``/home/police``

Il est possible de faire l'installation de l'application sur un serveur existant. Si l'utilisateur ``police`` n'existe pas vous avez le choix entre cr�er cet utilisateur ou adapter la documentation avec le nom de votre utilisateur

    :: 
    
        sudo adduser --home /home/police police


* r�cup�rer le zip de l'application sur le Github du projet

    ::
    
        cd /tmp
        wget https://github.com/PnEcrins/Police/archive/master.zip
        unzip master.zip
        cp -R Police-master /home/police/police
        cd /home/police


Installation et configuration du serveur
========================================

Installation pour Debian 7.

:notes:

    Cette documentation concerne une installation sur Debian. Pour tout autre environemment les commandes sont � adapter.

.

:notes:

    Durant toute la proc�dure d'installation, travailler avec l'utilisateur ``police``. Ne changer d'utilisateur que lorsque la documentation le sp�cifie.

.

  ::
  
    su - 
    apt-get install apache2 php5 libapache2-mod-php5 php5-gd libapache2-mod-wsgi php5-pgsql cgi-mapserver sudo gdal-bin
    usermod -g www-data police
    usermod -a -G root police
    adduser police sudo
    exit
    
    Fermer la console et la r�ouvrir pour que les modifications soient prises en compte
    

* Ajouter un alias du serveur de base de donn�es dans le fichier /etc/hosts

  ::  
        
        sudo sh -c 'echo "127.0.1.1       policedbhost" >> /etc/hosts'
        sudo apache2ctl restart

:notes:

    Cet alias ``policedbhost`` permet d'identifier sur quel host l'application doit rechercher la base de donn�es PostgreSQL
    
    Par d�faut, PostgreSQL est en localhost (127.0.1.1)
    
    Si votre serveur PostgreSQL est sur un autre host (par exemple sur ``50.50.56.27``), vous devez modifier la chaine de carat�res ci-dessus comme ceci ``50.50.56.27   policedbhost``

* V�rifier que le r�pertoire ``/tmp`` existe et que l'utilisateur ``www-data`` y ait acc�s en lecture/�criture

Installation et configuration de PosgreSQL
==========================================

* Sur Debian 7, configuration des d�pots pour avoir les derni�res versions de PostgreSQL (9.3) et PostGIS (2.1)
(http://foretribe.blogspot.fr/2013/12/the-posgresql-and-postgis-install-on.html)

  ::  
  
        sudo sh -c 'echo "deb http://apt.postgresql.org/pub/repos/apt/ wheezy-pgdg main" >> /etc/apt/sources.list'
        sudo wget --quiet -O - https://www.postgresql.org/media/keys/ACCC4CF8.asc | sudo apt-key add -
        sudo apt-get update

* Installation de PostreSQL/PostGIS 

    ::
    
        sudo apt-get install postgresql-9.3 postgresql-client-9.3
        sudo apt-get install postgresql-9.3-postgis-2.1
        sudo adduser postgres sudo
        
* configuration PostgreSQL - permettre l'�coute de toutes les ip

    ::
    
        sed -e "s/#listen_addresses = 'localhost'/listen_addresses = '*'/g" -i /etc/postgresql/9.3/main/postgresql.conf
        sudo sed -e "s/# IPv4 local connections:/# IPv4 local connections:\nhost\tall\tall\t0.0.0.0\/32\t md5/g" -i /etc/postgresql/9.3/main/pg_hba.conf
        /etc/init.d/postgresql restart

* Cr�ation de 2 utilisateurs PostgreSQL

    ::
    
        sudo su postgres
        psql
        CREATE ROLE policeuser WITH LOGIN PASSWORD 'monpassachanger';
        CREATE ROLE policeadmin WITH SUPERUSER LOGIN PASSWORD 'monpassachanger';
        \q
        exit
        
L'utilisateur ``policeuser`` sera le propri�taire de la base de donn�es ``policedb`` et sera utilis� par l'application pour se connecter � celle-ci.

L'utilisateur ``policeadmin`` est super utilisateur de PostgreSQL.

L'application fonctionne avec par default le mot de passe ``monpassachanger`` mais il est conseill� de le modifier !

Ces mots de passe, ainsi que les utilisateurs postgres cr��s ci-dessous ``policeuser`` et ``policeadmin`` sont des valeurs par d�faut utilis�es � plusieurs reprises dans l'application. Ils peuvent cependant �tre chang�s dans : conf/settings.ini