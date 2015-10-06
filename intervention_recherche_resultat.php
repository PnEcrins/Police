<? include "verification.php" ?>
<? include "header_front.php" ?>
<? $aucuntri = true; //Pour ne pas permettre de trier par colonne quand on affiche une liste de resultat filtrée par recherche ?> 
		<link rel="stylesheet" href="js/facebox/facebox.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="js/facebox/facebox.js"></script>
		<script type="text/javascript">
		    jQuery(document).ready(function($) {
		      $('a[rel*=facebox]').facebox() 
		    })
		</script>
	<title>Police du <? echo $etablissement_abv; ?> - Liste des interventions</title>
</head>
<body>
<? include "menu_general.php" ?>

<? include "intervention_recherche_where.php";
//Declarer la requete listant les enregistrements selon les criteres de recherche :
	$sqliste = "SELECT DISTINCT id_intervention, to_char(date, 'DD/MM/YYYY') as date, date as datetri, secteur, commune, secteur_id, suivi_num_parquet, suivi_suite_donnee, type_intervention
	FROM interventions.t_interventions int
	LEFT JOIN layers.l_communes com ON com.id_commune = int.commune_id
	LEFT JOIN layers.l_secteurs sect ON sect.id_sect = int.secteur_id
	LEFT JOIN interventions.cor_interventions_infractions cor ON cor.intervention_id = int.id_intervention
	LEFT JOIN interventions.cor_interventions_agents ag ON ag.intervention_id = int.id_intervention
	LEFT JOIN interventions.bib_types_interventions ti ON ti.id_type_intervention = int.type_intervention_id
	$where
	ORDER BY datetri DESC";
	//Executer la requete
	$resultliste = pg_query($sqliste) or die ('Échec requête : ' . pg_last_error()) ;
	//Compter le nombre d'enregistrements renvoyés par la requete
	$nombreint = pg_numrows($resultliste);

?>

			<div id="news">
				<h1>
					Liste des interventions trouv&eacute;es (<? echo "$nombreint"?>) | 
					<a href="interventions_liste.php">
						<img src="images/icones/retour.gif" align="absmiddle" alt="Retour &agrave; la liste compl&egrave;te des interventions" title = "Retour &agrave; la liste compl&egrave;te des interventions" border = "0"/>
					</a>
				</h1>
			</div>
			
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
				<span class="commentaire">Annee :</span> <? echo "$date"?>
			<?}?>

			<? include "interventions_liste_inc.php" ?>
		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>
