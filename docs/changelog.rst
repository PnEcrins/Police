=========
CHANGELOG
=========


2.2.0 (2015-10-06)
------------------

**Note de version**

* La BDD a subi plusieurs modifications entre la V2.1.0 et cette V2.2.0. L'ensemble des modifications peuvent être réalisées avec le fichier `xxxxx.sql`.
* La gestion des utilisateurs a été externalisée dans l'outil UsersHub (https://github.com/PnEcrins/UsersHub). Ainsi les tables utilisateurs intégrées dans la BDD Police ont été supprimées (`bib_agents` et `bib_droits`) et sont remplacées par les tables dans le schéma `utilisateurs` alimentés par la BDD de UsersHub.

**Changements**

* Amélioration du pointage CARTO. Il n'y a plus maintenant un point par défaut au milieu de la carte que l'on doit déplacer mais un fonctionnement plus classique. On se localise sur la zone de l'intervention et on clique pour positionner celle-ci. Si je reclique ailleurs, cela déplace le point à ce nouvel emplacement.
* Saisie des coordonnées. Au lieu de localiser l'intervention sur la carte, on peut directement saisir les coordonnées X et Y (en WGS84). L'application vérifie que les coordonnées saisies sont bien dans l'étendue globale du territoire définie dans les paramètres.
* Les agents ne peuvent désormais modifier QUE les interventions pour lesquelles ils étaient présents.
* Ajout des champs `DATE AUDIENCE` et `APPEL AVOCAT` (dans les formulaire d'ajout/modification, dans les fiches de visualisation d'une intervention et dans les export XLS).
* La recherche que l'on pouvait déjà faire dans la liste des interventions est maintenant aussi possible dans l'onglet CARTO (reste quelques ajustements à faire sur cette page).

**Correction de bugs

* Les problèmes d'accent et d'apostrophes dans les champs textes ont été réglés
* Les fichiers PHP ont été convertis en UTF8


2.1.0 (Décembre 2011)
---------------------

Modification de la BDD pour pouvoir gérer les secteurs indépendamment des communes (certaines communes étant sur 2 secteurs dans certains parcs marins)


2.0.0 (Janvier 2010)
--------------------

Versions portable et dépersonnalisée pour le déployer dans d'autres parcs nationaux.


1.0.0 (Février 2009)
--------------------

Application de suivi des infractions.

Réalisée à partir des fichiers Excel gérées dans chaque secteur du PnEcrins.
