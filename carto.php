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
<? include "menu_general.php" ?>

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
			
			<div class="clear"></div>
			
			<a href="intervention_recherche.php?from=carte&num=<?=$num;?>&infraction=<?=$infr;?>&type=<?=$type;?>&secteur=<?=$sect;?>&commune=<?=$com;?>&statut=<?=$statut;?>&agent=<?=$agent;?>&annee=<?=$date;?>" rel="facebox">
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
