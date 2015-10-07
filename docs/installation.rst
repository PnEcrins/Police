===========
APPLICATION
===========

Configuration de la base de données PostgreSQL
==============================================

* Copier et renommer le fichier ``conf/settings.ini.sample`` en ``conf/settings.ini`` :

    :: 
	
	    cp conf/settings.ini.sample conf/settings.ini

* Mettre à jour la section PostgreSQL du fichier ``conf/settings.ini`` avec vos paramètres de connexion à la base de données :

    :: 
	
	    nano conf/settings.ini

Renseigner le nom de la base de données, les utilisateurs PostgreSQL et les mots de passe. Il est possible mais non conseillé de laisser les valeurs proposées par défaut. 

ATTENTION : Les valeurs renseignées dans ce fichier sont utilisées par le script d'installation de la base de données ``install_db.sh``. Les utilisateurs PostgreSQL doivent exister (et disposer de droits suffisants) ou être en concordance avec ceux créés lors de la dernière étape de l'installation du serveur (Création de 2 utilisateurs PostgreSQL). 


Création de la base de données
==============================

* Création de la base de données et chargement des données initiales

    ::
    
        cd /home/police/police
        sudo ./install_db.sh

* Si besoin, l'exemple des données SIG du Parc national des Ecrins pour les tables du schéma ``layers``
  
    ::

        export PGPASSWORD=monpassachanger;psql -h policedbhost -U policeuser -d policedb -f data/pne/policedb_data_sig_pne.sql 
        

Configuration de l'application
==============================
* Se loguer sur le serveur avec l'utilisateur ``police``

* Se placer dans le répertoire de l'application et lancer le script d'installation de l'application ``install_app.sh`` :

    :: 
	
	    cd /home/police/police
        ./install_app.sh
        
* Editer et vérifier dans le fichier ``conf/connecter.php`` que vos paramètres de connexion à la BDD sont corrects :
        
* Editer et mettre à jour le fichier ``conf/parametres.php`` avec vos paramètres :
        
* Si vous souhaitez mettre à disposition des utilisateurs des documents, éditer et mettre à jour le fichier ``documents_liste.php``.

Vos documents doivent être placés dans le répertoire ``documents`` il vous suffit ensuite de modifier le fichier ``documents_liste.php`` pour faire pointer les liens vers vos documents.

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
