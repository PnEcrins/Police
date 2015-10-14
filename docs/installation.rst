===========
APPLICATION
===========

Configuration de la base de données PostgreSQL
==============================================

* Copier et renommer le fichier ``conf/settings.ini.sample`` en ``conf/settings.ini`` :

    :: 
	
	    cp conf/settings.ini.sample conf/settings.ini

* Mettre à jour la section PostgreSQL du fichier ``conf/settings.ini`` avec vos paramètres de connexion à la base de données ainsi que la section ``paramétrage de l'application`` :

    :: 
	
	    nano conf/settings.ini

Renseigner le nom de la base de données, les utilisateurs PostgreSQL et les mots de passe. Il est possible mais non conseillé de laisser les valeurs proposées par défaut. 

ATTENTION : Les valeurs renseignées dans ce fichier sont utilisées par le script d'installation de la base de données ``install_db.sh``. Les utilisateurs PostgreSQL doivent exister (et disposer de droits suffisants) ou être en concordance avec ceux créés lors de la dernière étape de l'installation du serveur (Création de 2 utilisateurs PostgreSQL). 


Création de la base de données
==============================

* Création automatisée de la base de données (``data/policedb.sql``) et chargement des données initiales (``data/policedb_data.sql`` et ``policedb_data_sig_pne.sql``) :

    ::
    
        cd /home/police/Police
        sudo ./install_db.sh
        
* Vous pouvez consulter le fichier ``log/install_db.log`` afin de vérifier si des errreurs se sont produites lors de l'installation de la base de données

* Si besoin, l'exemple des données SIG du Parc national des Ecrins pour les tables du schéma ``layers``
  
    ::

        export PGPASSWORD=monpassachanger;psql -h policedbhost -U policeuser -d policedb -f data/pne/policedb_data_sig_pne.sql 

La base de données est composée de 4 schémas : 
* Public contenant les fonctions natives PostgreSQL et PostGIS ainsi que la table geometry_columns listant les tables géoréférencées ainsi que leur systeme de projection respectives
* Interventions contenant l’ensemble des interventions et de leurs attributs 
* Layers contenant toutes les tables géographiques (communes, zones réglementées, cœur du Parc, ...)
* Utilisateurs contenant la liste des utilisateurs ainsi que leurs droits (ce schéma ne doit pas être modifié, il est mise à jour par l'application UsersHub et la répercusion automatique de toutes les modifications faites dans la BDD de UsersHub.

Les couches géographiques à intégrer :
* Communes dans la table ``layers.l_communes``
* Secteurs dans la table ``layers.l_secteurs``
* Zones à statut (cœur, aire d’adhésion, réserves) dans la table ``layers.l_statut_zone``. En lien avec la bibliothèque des types de zones ``interventions.bib_statutszone``. Le champ ``ordre`` de la table ``layers.l_statut_zone`` permet d’ordonner l’importance des zones pour savoir laquelle sera retenue en cas de superposition de plusieurs zones.

Les autres bibliothèques peuvent aussi être modifiées directement dans la base de données (liste des types d’infractions, des types de zones à statut, ...). Ces tables utilisent le préfixe bib_    

Configuration de l'application
==============================
* Se loguer sur le serveur avec l'utilisateur ``police``

* Se placer dans le répertoire de l'application et lancer le script d'installation de l'application ``install_app.sh`` qui va générer les fichiers de configuration et modifier les droits de certains répertoires :

    :: 
	
	    cd /home/police/Police
        ./install_app.sh
        
* Editer et vérifier dans le fichier ``conf/connecter.php`` que vos paramètres de connexion à la BDD sont corrects.
        
* Editer et mettre à jour le fichier ``conf/parametres.php`` avec vos paramètres. 

* Editer et mettre à jour le fichier ``conf/conf_carto.js`` avec vos paramètres. Vous devez notamment fournir la clé de l'API IGN (voir dernière section de ce document). Vous devez aussi configurer les valeurs des emprises de votre territoire.
        
* Si vous souhaitez mettre à disposition des utilisateurs des documents (PDF, DOC, XLS...), éditer et mettre à jour le fichier ``documents_liste.php``.

Vos documents doivent être placés dans le répertoire ``documents``, il vous suffit ensuite de modifier le fichier ``documents_liste.php`` pour faire pointer les liens vers vos documents.

* Pour tester, se connecter à l'application via http://mon-domaine.fr/Police avec l'utilisateur et mot de passe : ``admin/ admin``


Mise à jour de l'application
============================

Les différentes versions sont disponibles sur le Github du projet (https://github.com/PnEcrins/Police/releases).

* Télécharger et extraire la version souhaitée dans un répertoire séparé (où ``X.Y.Z`` est à remplacer par le numéro de la version que vous installez). 

.. code-block:: bash

    cd /home/police/
    wget https://github.com/PnEcrins/Police/archive/vX.Y.Z.zip
    unzip vX.Y.Z.zip
    cd Police-X.Y.Z/

* Lire attentivement les notes de chaque version si il y a des spécificités (https://github.com/PnEcrins/Police/releases). Suivre ces instructions avant de continuer la mise à jour.

* Copier les anciens fichiers de configuration et les charger dans le nouveau répertoire de l'application (``version-precedente`` est à modifier par le nom du répertoire où était installé votre application Police).

::

    # Fichiers de configuration
    cp ../version-precedente/conf/parametres.php conf/parametres.php
    cp ../version-precedente/conf/connecter.php conf/connecter.php
    cp ../version-precedente/conf/settings.ini conf/settings.ini
    cp ../version-precedente/conf/parametres_wms.js conf/parametres_wms.js

    # Logo et pied de page
    cp ../version-precedente/images/logo_etablissement.png images/logo_etablissement.png
    cp ../version-precedente/images/footer.jpg images/footer.jpg
    
    # Documents (si vous en avez ajouté)
    cp -r ../version-precedente/documents/* /documents/

* Renommer l'ancien répertoire de l'application Police (/Police_OLD/ par exemple) puis celui de la nouvelle version (/Police/ par exemple) pour que le serveur pointe sur la nouvelle version


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


Une fois votre commande terminée, saisissez la valeur de la clé IGN reçue dans le fichier ``conf/conf_carto.js``.
