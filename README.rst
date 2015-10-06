POLICE
======

Police est une application développée par le Parc national des Ecrins en 2010. 

Elle permet d'inventorier les interventions de police (PV, TA, avertissement, rapport...) réalisées par les agents de l'établissement. 

Il est ensuite possible de consulter ou exporter l'inventaire des interventions sous forme de carte ou de liste, rechercher, et imprimer une intervention.
 
Le référent peut aussi renseigner les informations concernant la suite donnée à chaque intervention (numero du parquet, amende, dommages et intérêts...).

.. image :: docs/img/police-appli-presentation.jpg

Technologies
------------

- Langages : PHP, HTML, JS (un peu), CSS
- BDD : PostgreSQL, PostGIS
- Serveur : Debian ou Ubuntu
- Framework carto : Openlayers 2.0 ou API GoogleMaps (ancienne version de l'API, ne fonctionne plus)
- Fonds rasters : Geoportail, Google Maps, WMS

Gestion des utilisateurs
------------------------

La gestion des utilisateurs est déportée dans l'application UsersHub (https://github.com/PnEcrins/UsersHub).
Celle-ci permet de centraliser les utilisateurs et observateurs, de les ajouter dans un groupe et d'hériter directement de droits dans l'ensemble des applications (GeoNature, Faune, Flore, Geotrek...).

Installation
------------

Consulter la documentation :  `<http://police.rtfd.org>` --> TODO

License
-------

* OpenSource - BSD
* Copyright (c) 2015 - Parc National des Écrins


.. image:: http://pnecrins.github.io/GeoNature/img/logo-pne.jpg
    :target: http://www.ecrins-parcnational.fr
