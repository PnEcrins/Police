<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
$cartoannee= $_POST[fcartoannee];
header("Location: carto.php?an=$cartoannee");
}
?>	
		<? include "header_front.php" ?>
		<link rel="stylesheet" href="facebox/facebox.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="facebox/facebox.js"></script>
		<script type="text/javascript">
		    jQuery(document).ready(function($) {
		      $('a[rel*=facebox]').facebox() 
		    })
		</script>
		<? if ($outil_carto == "gm") { ?>
			<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<? echo $googlekeymap; ?>"></script>
		<? } elseif ($outil_carto == "ol") { ?>
			<script type="text/javascript" src="js/openlayers/OpenLayers.js"></script>
		<? } ?>
	<title>Police du <? echo $etablissement_abv; ?> - Localisation des interventions</title>
</head>

<? if ($outil_carto == "gm") { ?>
<!-- Si l'outil carto utilisé est OpenLayers alors charger ses fonctions javascripts à l'ouverture de la page -->
<body onload="load()" onunload="GUnload()">
<? } else { ?>
<!-- Sinon on charge celles de GoogleMaps -->
<body>
<? } ?>

<? include "menu_general.php" ?>

<?php
//Declarer la requete listant les enregistrements de la table à lister,
	$sqliste = "SELECT id_intervention, extract(year from date) as annee
	FROM interventions.t_interventions
	WHERE extract(year from date)= '$_GET[an]'";
	//Executer la requete
	$resultliste = pg_query($sqliste) or die ('Échec requête : ' . pg_last_error()) ;
	//Compter le nombre d'enregistrements renvoyés par la requete
	$nombreint = pg_numrows($resultliste);
	$annee = $_GET[an];
?>

		<form action = "carto.php" method = "POST" name = "carto">
			<div id="news"><h1>Localisation des <? echo "$nombreint"?> interventions de <? echo "$annee"?></h1>
				<p class="texte">Afficher une autre ann&eacute;e :
					<select name="fcartoannee">
						<option value="">...</option>
							<?
								//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
								$sql_an = "SELECT distinct extract(year from date) as annee
								FROM interventions.t_interventions
								ORDER BY extract(year from date)";
								$resultan = pg_query($sql_an) or die ("Erreur requête") ;
								while ($val = pg_fetch_assoc($resultan)){
							?>
												<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
						<option value="<?=$val['annee'];?>"><?=$val['annee']?></option>
						<? } ?>
					</select>
					<input name="Submit" type="submit" value="OK" >
				</p>
			</div>
		</form>
			<div class="clear"></div>

				<? include "carto/localisation-all.php" ?>
				
				<? if ($outil_carto == "gm") { ?>
					<p>
						Survolez chaque marqueur pour obtenir des informations sur l'intervention.
					</p>
				<? } else { ?>
					<p>
						Cliquez sur les interventions pour obtenir plus d'informations.
					</p>
				<? } ?>

		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>
