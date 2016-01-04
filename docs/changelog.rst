=========
CHANGELOG
=========

2.4.0.dev1
------------------

2.3.2 (2016-01-04)
------------------

**Corrections de bugs**

* Intégration de la librairie OpenLayers.JS en local car le lien distant vers le serveur de OL ne fonctionnait plus. (Fixes https://github.com/PnEcrins/Police/issues/18).

**Documentation**

* Correction de typos dans la documentation


2.3.1 (2015-10-13)
------------------

**Corrections de bugs**

* Ajout des triggers permettant de renseigner automatiquement la date de création et la date de mise à jour d'une intervention.
* Ajout d'un agent présent lors d'une intervention en mode modification

**Documentation**

* L'installation indique désormais de télécharger une version de l'application et non le master.


2.3.0 (2015-10-13)
------------------

**Note de version**

Avant mise à jour : 

* Les fonds carto ne sont plus affichés à partir d'un flux WMS mais à partir de l'API Geoportail IGN. Il vous faut donc une clé API Geoportail IGN correspondant à l'URL de votre serveur (https://github.com/PnEcrins/Police/blob/master/docs/installation.rst).

Après mise à jour :

* Un fichier ``conf/conf_carto.js`` a été ajouté à l'application. Générez le votre à partir du fichier ``conf/conf_carto.js.sample`` et renseignez-y les paramètres de votre clé IGN ainsi que l'emprise de votre territoire en 3857.
* Modifiez aussi l'étendue du territoire dans le fichier ``conf/parametres.php`` pour qu'elle soit aussi en 3857 (projection API Geoportail).
* Les fonds provenant de l'API Google Maps ne fonctionnaient plus dans les versions précédentes car l'API n'avait pas été mise à jour dans Police. Les lignes de code qui faisaient référence à cette API Google Maps ont été supprimées pour nettoyer le code. 
* Les paramètres correspondants à l'usage de l'API Google Maps ont été supprimés du fichier ``conf/parametres.php``. Après avoir récupéré ce fichier de votre version 2.2.0, vous pouvez y retirer la partie correspondant à Google Maps (voir ``conf/parametre.php.sample`` dans https://github.com/PnEcrins/Police/commit/748b8ae079446f3959af54ea13e32dbc5f55e0b3).

**Changements**

* Passage de la carto sur l'API IGN Geoportail et changement de projection pour les rasters et le champs``the_geom`` dans PostGIS. Cette modification de la BDD peut être réalisée en éxecutant la partie correspondante dans le fichier ``data/migration_bdd_police.sql`` (https://github.com/PnEcrins/Police/blob/master/data/migration_bdd_police.sql).
* Message d'erreur si l'utilisateur essaie d'enregistrer une intervention sans infraction et/ou sans agent présent
* Compléments dans la documentation d'installation (https://github.com/PnEcrins/Police/blob/master/docs/installation.rst)
* Permettre la saisie des coordonnées d'une intervention en modification.

**Correction de bugs**

* Correction des bugs de la 2.2.0 concernant la recherche carto et l'affichage des agents présents dans les fiches des interventions


2.2.0 (2015-10-06)
------------------

**Note de version**

* La gestion des utilisateurs a été externalisée dans l'outil UsersHub (https://github.com/PnEcrins/UsersHub). Ainsi les tables utilisateurs intégrées dans la BDD Police ont été supprimées (``bib_agents`` et ``bib_droits``) et sont remplacées par les tables dans le schéma ``utilisateurs`` alimentés par la BDD de UsersHub. Avant de mettre à jour l'application, il faut donc installer UsersHub, y intégrer dans la table ``t_roles`` la liste des agents présents actuellement dans la table ``bib_agents``, y créer l'application Police, donner des droits aux agents dans l'application Police (idéalement en ayant préalablement créé des groupes d'utilisateurs pour ne pas donner des droits dans Police agent par agent mais plutôt en fonction des groupes auxquels ils appartiennent) et y créer une liste des agents (ou groupes) pouvant être associés à une intervention.
* Par ailleurs pour les listes déroulantes des agents pouvant être présents sur une intervention, la version 2.1.0 se basait sur les champs booléens ``assermenté`` et ``enposte`` dans la table ``bib_agents``. Désormais cette liste est basée sur une liste dans UsersHub dont l'identifiant doit être indiquée dans le paramètre ``id_menu`` du fichier ``conf/parametres.php``.
* La base de données a subi plusieurs modifications entre la V2.1.0 et cette V2.2.0. L'ensemble des modifications peuvent être réalisées en éxecutant la partie correspondante dans le fichier ``data/migration_bdd_police.sql`` (https://github.com/PnEcrins/Police/blob/master/data/migration_bdd_police.sql).
* Le fonctionnement avec GoogleMaps (au lieu de OpenLayers 2) ne fonctionne plus, car leur API a évolué depuis mais n'a pas été mise à jour dans Police.
* De nouveaux paramètres ont été ajoutés à l'application. Après avoir récupéré le fichier de conf ``conf/parametres.php`` de votre version 2.1.0, ajoutez les manuellement (``id_application``, ``id_menu``, ``acces_agents`` et ``acces_documents``).

**Changements**

* Amélioration du pointage CARTO. Il n'y a plus maintenant un point par défaut au milieu de la carte que l'on doit déplacer mais un fonctionnement plus classique. On se localise sur la zone de l'intervention et on clique pour positionner celle-ci. Si je reclique ailleurs, cela déplace le point à ce nouvel emplacement.
.. image :: img/police-saisie-2-2.gif
* Saisie des coordonnées. Au lieu de localiser l'intervention sur la carte, on peut directement saisir les coordonnées X et Y (en WGS84). L'application vérifie que les coordonnées saisies sont bien dans l'étendue globale du territoire définie dans les paramètres.
* Les agents ne peuvent désormais modifier QUE les interventions pour lesquelles ils étaient présents.
* Ajout des champs ``DATE AUDIENCE`` et ``APPEL AVOCAT`` (dans les formulaire d'ajout/modification, dans les fiches de visualisation d'une intervention et dans les export XLS).
* La recherche que l'on pouvait déjà faire dans la liste des interventions est maintenant aussi possible dans l'onglet CARTO (reste quelques ajustements à faire sur cette page).
* Les exports correspondent aux résultats de la recherche et non plus à la liste de toutes les interventions (fixes https://github.com/PnEcrins/Police/issues/2).
* L'application est désormais compatible avec PostGIS 2 (https://github.com/PnEcrins/Police/commit/ca9ecaf511016bb1f11f8e7d63a54c1f82585488).
* Documentation et automatisation de l'installation de l'application et de la BDD (https://github.com/PnEcrins/Police/tree/master/docs).
* La gestion des utilisateurs ayant été externalisée dans l'application UsersHub, il n'est plus possible de gérer ceux-ci dans l'onglet AGENTS de l'application.
* Les onglets DOCUMENTS et AGENTS peuvent être masqués depuis les paramètres.

**Correction de bugs**

* Les problèmes d'accent et d'apostrophes dans les champs textes ont été réglés
* Les fichiers PHP ont été convertis en UTF8
* Correction de l'affichage de la liste des utilisateurs dans l'export XLS (fixes https://github.com/PnEcrins/Police/issues/1)


2.1.0 (Décembre 2011)
---------------------

Modification de la BDD pour pouvoir gérer les secteurs indépendamment des communes (certaines communes étant sur 2 secteurs dans certains parcs marins)


2.0.0 (Janvier 2010)
--------------------

Versions portable et dépersonnalisée pour le déployer dans d'autres parcs nationaux.


1.0.0 (Février 2009)
--------------------

Application de suivi des infractions.

Réalisée à partir des fichiers Excel gérées dans chaque secteur du Parc nation des Ecrins.
