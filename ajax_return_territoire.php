<?php
$x = $_POST['x'];
$y = $_POST['y'];
require("conf/connecter.php");
require ("conf/parametres.php");

//récupération des X et Y dans la projection du wms de la carte du projet et conversion en epsg 4326 (dégrés décimaux) pour les écrire dans la base
$query="
SELECT ST_x(ST_Transform(ST_SetSrid(ST_MakePoint(".$x.", ".$y."),".$wms_proj."), 4326)) AS xwgs84, 
ST_y(ST_Transform(ST_SetSrid(ST_MakePoint(".$x.", ".$y."),".$wms_proj."), 4326)) AS ywgs84;";
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
$val = pg_fetch_assoc($result);
$xwgs84 = $val['xwgs84'];
$ywgs84 = $val['ywgs84'];

//intersection avec la couche COMMUNE
$query="SELECT id_commune FROM layers.l_communes
WHERE ST_Intersects(st_transform(the_geom,".$wms_proj."),ST_PointFromText('POINT(".$x." ".$y.")',".$wms_proj."));";
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
$val = pg_fetch_assoc($result);
$id_commune = $val['id_commune'];
$verif = pg_numrows($result);
if ($verif != "1"){$id_commune=999;}

//intersection avec la couche SECTEUR
$query="SELECT id_sect FROM layers.l_secteurs
WHERE ST_Intersects(st_transform(the_geom,".$wms_proj."),ST_PointFromText('POINT(".$x." ".$y.")',".$wms_proj."));";
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
$val = pg_fetch_assoc($result);
$id_sect = $val['id_sect'];
$verif = pg_numrows($result);
if ($verif != "1"){$id_sect=99;}

//intersection avec la couche de STATUT DE LA ZONE
$query="SELECT ordre, id_statut_zone FROM layers.l_statut_zone
WHERE ST_Intersects(st_transform(the_geom,".$wms_proj."),ST_PointFromText('POINT(".$x." ".$y.")',".$wms_proj.")) 
ORDER BY ordre desc limit 1;";
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
$val = pg_fetch_assoc($result);
$id_statut_zone = $val['id_statut_zone'];
$verif = pg_numrows($result);
if ($verif != "1"){$id_statut_zone=3;}

pg_close($dbconn);

header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?>'
   . '<root>'
   . '<val1 valeur="'.$id_commune.'" />'
   . '<val2 valeur="'.$id_statut_zone.'" />'
   . '<val3 valeur="'.$xwgs84.'" />'
   . '<val4 valeur="'.$ywgs84.'" />'
   . '<val5 valeur="'.$id_sect.'" />'
   . '</root>';
?>