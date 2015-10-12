<? include "verification.php" ; ?>
<? include "intervention_recherche_where.php" ; ?>

		<? include "header_front.php" ; ?>
		<link rel="stylesheet" href="js/facebox/facebox.css" media="screen" type="text/css" />
        <link href="http://api.ign.fr/geoportail/api/js/2.0.0/theme/geoportal/style.css" rel="stylesheet" type="text/css" media="screen" />
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="js/facebox/facebox.js"></script>
		<script type="text/javascript">
		    jQuery(document).ready(function($) {
		      $('a[rel*=facebox]').facebox() 
		    })
		</script>
        <script type="text/javascript" src="conf/conf_carto.js"></script>
        <script type="text/javascript" src="http://dev.openlayers.org/releases/OpenLayers-2.11/OpenLayers.js"></script>
        <script type="text/javascript" src="http://api.ign.fr/geoportail/api/js/2.0.0/GeoportalMin.js"></script>
	<title>Police du <? echo $etablissement_abv; ?> - Localisation des interventions</title>
</head>
<body>
<? 
    include "menu_general.php";

	$prefix = '{"type": "FeatureCollection","features":['; 
	$debut='{"geometry":';
	$fin=',"type": "Feature","properties": {}}';
	$suffix=']}';
	//selectionner toutes les intervantions avec leur géometries
	$query = "SELECT int.id_intervention, ti.type_intervention, int.suivi_suite_donnee, ST_Asgeojson(ST_Transform(ST_SetSrid(ST_MakePoint(coord_x, coord_y),4326), '$wms_proj')) as geojson,to_char(date, 'dd/mm/yyyy') as dat  
		FROM interventions.t_interventions int
		LEFT JOIN layers.l_communes com ON com.id_commune = int.commune_id
        LEFT JOIN layers.l_secteurs sect ON sect.id_sect = int.secteur_id
        LEFT JOIN interventions.cor_interventions_infractions cor ON cor.intervention_id = int.id_intervention
        LEFT JOIN interventions.cor_interventions_agents ag ON ag.intervention_id = int.id_intervention
        LEFT JOIN interventions.bib_types_interventions ti ON ti.id_type_intervention = int.type_intervention_id 
        $where
        GROUP BY int.id_intervention,int.coord_x, int.coord_y, int.date, int.suivi_suite_donnee, ti.type_intervention" ;
		// WHERE extract(year from int.date)= '$cartoannee'" ; 
		$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
	//bouble sur les interventions
	while ($val = pg_fetch_assoc($result)){
		$compt++;
		$date = $val['dat'];
		$typeintervention = $val['type_intervention'];
		if ($val['suivi_suite_donnee'] =="") { $suivi_suite = "Non renseigné"; } 
		else { $suivi_suite = "Oui, voir détails"; }
		$xx = $val['coord_x'];
		$yy = $val['coord_y'];
		$idint = $val['id_intervention'];
		// Declarer la requete permettant d'afficher la ou les infractions des infractions cartographiees en plus de la date
		$queryinfr = "SELECT id_intervention, infraction, infraction_id FROM interventions.t_interventions
		LEFT JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
		LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
		WHERE id_intervention = '$idint'
		ORDER BY infraction_id";
		//Executer la requete
		$resultinfr = pg_query($queryinfr) or die ('Échec requête : ' . pg_last_error()) ;
		$nb = pg_numrows($result);
		$c=0;$virg="";$infractions = "";
		//boucle 2 sur la ou les infractions de chaque intervention
		while ($val1 = pg_fetch_assoc($resultinfr)) {
			$c++;
			$inf = $val1['infraction'];
			if ($c!=1){$virg = ", ";}
			$infractions = $infractions.$virg.$inf;
		}
		$fin=',"type": "feature","id": '.$idint.',"properties": {"date": "'.$date.'","id_intervention":'.$idint.',"typeintervention": "'.$typeintervention.'","suivi_suite_donnee": "'.$suivi_suite.'","infractions": "'.$infractions.'"}}';
		$str = $debut.$val['geojson'].$fin;
		if ($compt >1){$virgule = ",";}
		$geojson = $geojson.$virgule.$str;
	}
	$features = $prefix.$geojson.$suffix;
?>
           	<div id="news">
				<h1>
					Liste des interventions
                    <?
                    if (isset($_GET['search'])){
                        echo " trouvées (".$nb.")";
                    }else{ echo "(".$nb.")";}
                    ?> | 
					<a href="interventions_liste.php">
						<img src="images/icones/retour.gif" align="absmiddle" alt="Retour &agrave; la liste compl&egrave;te des interventions" title = "Retour &agrave; la liste compl&egrave;te des interventions" border = "0"/>
					</a>
				</h1>
			</div>
			
            <?if (isset($_GET['search'])){?>
                Votre recherche : 
                <?if ($num != null){ ?>
                    <span class="commentaire">N&deg; de l'intervention :</span> <? echo "$num"?> - 
                <?}?>
                <?if ($infr != null){ ?>
                    <span class="commentaire">Type d'infraction :</span> <? echo "$nominfraction"?> - 
                <?}?>
                <?if ($type != null){ ?>
                    <span class="commentaire">Type d'intervention :</span> <? echo "$typeint"?> - 
                <?}?>
                <?if ($sect != null){ ?>
                    <span class="commentaire">Secteur :</span> <? echo "$nomsecteur"?> - 
                <?}?>
                <?if ($com != null){ ?>
                    <span class="commentaire">Commune :</span> <? echo "$nomcom"?> - 
                <?}?>
                <?if ($statut != null){ ?>
                    <span class="commentaire">Statut de la zone :</span> <? echo "$nomstatut"?> - 
                <?}?>
                <?if ($agent != null){ ?>
                    <span class="commentaire">Agent :</span> <? echo "$nomagent"?> - 
                <?}?>
                <?if ($date != null){ ?>
                    <span class="commentaire">Annee :</span> <? echo "$year"?>
                <?}?>
            <?}?>
			<div class="clear"></div>
			
			<a href="intervention_recherche.php?from=carte&num=<?=$num;?>&infraction=<?=$infr;?>&type=<?=$type;?>&secteur=<?=$sect;?>&commune=<?=$com;?>&statut=<?=$statut;?>&agent=<?=$agent;?>&annee=<?=$year;?>" rel="facebox">
				<img src="images/icones/rechercher.gif" alt="Rechercher une ou des intervention(s)" title="Rechercher une intervention" border="0" align="absmiddle"> Rechercher une ou des intervention(s)
			</a>
				
			<? include "carto/localisation-all.php" ; ?>
				
			<p>Cliquez sur les interventions pour obtenir plus d'informations.</p>

		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>
