=========
CHANGELOG
=========


2.2.0 (2015-10-06)
------------------

**Note de version**

* La gestion des utilisateurs a été externalisée dans l'outil UsersHub (https://github.com/PnEcrins/UsersHub). Ainsi les tables utilisateurs intégrées dans la BDD Police ont été supprimées (``bib_agents`` et ``bib_droits``) et sont remplacées par les tables dans le schéma ``utilisateurs`` alimentés par la BDD de UsersHub. Avant de mettre à jour l'application, il faut donc installer UsersHub, y intégrer dans la table ``t_roles`` la liste des agents présents actuellement dans la table ``bib_agents``, y créer l'application Police, donner des droits aux agents dans l'application Police (idéalement en ayant préalablement créé des groupes d'utilisateurs pour ne pas donner des droits dans Police agent par agent mais plutôt en fonction des groupes auxquels ils appartiennent) et y créer une liste des agents (ou groupes) pouvant être associés à une intervention.
* La base de données a subi plusieurs modifications entre la V2.1.0 et cette V2.2.0. L'ensemble des modifications peuvent être réalisées en éxecutant le fichier ``data/migration_bdd_police.sql`` (https://github.com/PnEcrins/Police/blob/master/data/migration_bdd_police.sql).
* Le fonctionnement avec GoogleMaps (au lieu de OpenLayers 2) ne fonctionne plus, car leur API a évolué depuis mais n'a pas été mise à jour dans Police.
* De nouveaux paramètres ont été ajoutés à l'application. Après avoir récupéré le fichier de conf ``conf/parametres.php`` de votre version 2.1.0, ajoutez les manuellement (``id_application``, ``id_menu`` et ``acces_agents``).

**Changements**

* Amélioration du pointage CARTO. Il n'y a plus maintenant un point par défaut au milieu de la carte que l'on doit déplacer mais un fonctionnement plus classique. On se localise sur la zone de l'intervention et on clique pour positionner celle-ci. Si je reclique ailleurs, cela déplace le point à ce nouvel emplacement.
* Saisie des coordonnées. Au lieu de localiser l'intervention sur la carte, on peut directement saisir les coordonnées X et Y (en WGS84). L'application vérifie que les coordonnées saisies sont bien dans l'étendue globale du territoire définie dans les paramètres.
* Les agents ne peuvent désormais modifier QUE les interventions pour lesquelles ils étaient présents.
* Ajout des champs ``DATE AUDIENCE`` et ``APPEL AVOCAT`` (dans les formulaire d'ajout/modification, dans les fiches de visualisation d'une intervention et dans les export XLS).
* La recherche que l'on pouvait déjà faire dans la liste des interventions est maintenant aussi possible dans l'onglet CARTO (reste quelques ajustements à faire sur cette page).
* Les exports correspondent aux résultats de la recherche et non plus à la liste de toutes les interventions (fixes (fixes https://github.com/PnEcrins/Police/issues/2)
* L'application est désormais compatible avec PostGIS 2 (https://github.com/PnEcrins/Police/commit/ca9ecaf511016bb1f11f8e7d63a54c1f82585488)
* Documentation et automatisation de l'installation de l'application et de la BDD (https://github.com/PnEcrins/Police/tree/master/docs)

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
