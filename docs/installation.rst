===========
APPLICATION
===========

Création de la base de données
==============================
TODO

Configuration de l'application
==============================
* Se loguer sur le serveur avec l'utilisateur propriétaire du répertoire de l'application

* Se placer dans le répertoire de l'application

* Copier et renommer le fichier ``conf/connecter.php.sample`` en ``conf/connecter.php`` :

    :: 
	
	    cp conf/connecter.php.sample conf/connecter.php
        
* Copier et renommer le fichier ``conf/connecter.php.sample`` en ``conf/parametres.php`` :

    :: 
	
	    cp conf/parametres.php.sample conf/parametres.php

* Mettre à jour le fichier ``conf/connecter.php`` avec vos paramètres de connexion à la BDD :

    :: 
	
	    nano conf/connecter.php
        
* Mettre à jour le fichier ``conf/parametres.php`` avec vos paramètres de connexion à la BDD :

    :: 
	
	    nano conf/parametres.php
        

   

* Installation et configuration de l'application

* Pour tester, se connecter à l'application via http://mon-domaine.fr/police avec l'utilisateur et mot de passe : ``admin/admin``


Mise à jour de l'application
----------------------------

* Suivre les instructions disponibles dans la doc de la version choisie (https://github.com/PnEcrins/Police/releases)


Clé IGN
=======
Si vous êtes un établissement public, commandez une clé IGN de type : Licence géoservices IGN pour usage grand public - gratuite
Avec les couches suivantes : 

* WMTS-Géoportail - Orthophotographies

* WMTS-Géoportail - Parcelles cadastrales

* WMTS-Géoportail - Cartes IGN

Pour cela, il faut que vous disposiez d'un compte IGN pro. (http://professionnels.ign.fr)
Une fois connecté au site: 

* aller dans "Nouvelle commande"

* choisir "Géoservices IGN : Pour le web" dans la rubrique "LES GÉOSERVICES EN LIGNE"

* cocher l'option "Pour un site internet grand public"

* cocher l'option "Licence géoservices IGN pour usage grand public - gratuite"

* saisir votre url. Attention, l'adresse doit être précédée de ``http://`` (même si il s'agit d'une IP)

* Finir votre commande en selectionnant les couches d'intéret et en acceptant les différentes conditions.


Une fois que votre commande est prête, saisissez la valeur de la clé IGN reçue dans le fichier ``conf/parametres.php``.
