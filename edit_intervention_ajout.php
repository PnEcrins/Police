<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{

if ($_POST['fnbcontrev'] == "") //Si le champ 'nombre de contrevenants' est vide alors j'insere la valeur null
	{ $nbcontrev = 'null'; }
else { $nbcontrev = $_POST[fnbcontrev] ;
}
//correction des magic_quotes_gpc (protection des chaînes de caractères)
$observation = pg_escape_string($_POST[fobs]);
$query= "INSERT INTO interventions.t_interventions (date, type_intervention_id, commune_id, secteur_id, coord_x, coord_y, statutzone_id, observation, nbcontrevenants) 
VALUES(to_date('$_POST[fdate]','dd/mm/yyyy'),'$_POST[fintervention]','$_POST[fcomm]', '$_POST[fsect]','$_POST[fx]','$_POST[fy]','$_POST[fstatut]','$observation', $nbcontrev)";
		
pg_query($query) or die( "Erreur requete" );

$queryid = "SELECT id_intervention from interventions.t_interventions
ORDER BY id_intervention DESC
LIMIT 1";
$result = pg_query($queryid) or die ('Échec requête : ' . pg_last_error()) ;
$val = pg_fetch_assoc($result);
$newid = $val['id_intervention'];

pg_close($dbconn);

header("Location: edit_intervention_infraction_ajout.php?id=$newid");

}

else { 

}
?>

	<? include "header_front.php" ?>
		
		<!-- Librairie Jquery utilisée par Datepicker -->
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		
		<!-- Datepicker pour afficher un joli calendrier dans les champs dates -->
		<link type="text/css" href="js/datepicker/themes/base/ui.all.css" rel="stylesheet" />
		<script type="text/javascript" src="js/datepicker/ui/i18n/ui.datepicker-fr.js"></script>
		<script type="text/javascript" src="js/datepicker/ui/ui.datepicker.js"></script>
		<script type="text/javascript">
			$(function() {
				jQuery.datepicker.setDefaults(jQuery.datepicker.regional['fr']);
				$("#datepicker").datepicker({maxDate: '+0D'});
			});
		</script>
		
		<!-- Javascript permettant de vérifier que les champs obligatoires sont bien remplis -->
		<script type="text/javascript" src="js/forms_verifications.js"></script> 
		
		<!-- Chargement des fichiers javascripts en fonction de l'outil carto choisi (GoogleMaps ou OpenLayers) -->
		<? if ($outil_carto == "gm") { ?>
		<script type="text/javascript" src="js/application.gm.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<? echo $googlekeymap; ?>"></script>
		<? } elseif ($outil_carto == "ol") { ?>
		<script type="text/javascript" src="js/application.ol.js"></script>
		<script type="text/javascript" src="js/openlayers/OpenLayers.js"></script>
		<? } ?>
		  
	<title>Police du <? echo $etablissement_abv; ?> - Ajouter une intervention</title>
</head>

<? if ($outil_carto == "gm") { ?>
<!-- Si l'outil carto utilisé est OpenLayers alors charger ses fonctions javascripts à l'ouverture de la page -->
<body onload="create_gm(<?=$gm_y_center;?>,<?=$gm_x_center;?>,10,'<?=$host_url;?>','<?=$racine;?>',true)" onunload="GUnload()">
<? } elseif ($outil_carto == "ol") { ?>
<!-- Sinon on charge celles de GoogleMaps -->
<body onload="create_ol.init(<?=$ol_x_center;?>,<?=$ol_y_center;?>,1,'<?=$wms_url;?>','<?=$wms_proj;?>','<?=$min_x;?>','<?=$min_y;?>','<?=$max_x;?>','<?=$max_y;?>',true)">
<? } ?>
	
	<? include "menu_general.php" ?>

			<div id="news">
				<h1>
					<img src="images/icones/ajouter.gif" alt="Ajouter une intervention" title="Ajouter une intervention" border="0" align="absmiddle"> 
					Ajouter une intervention
				</h1>
			</div>

	<? $ajd = date("d/m/Y"); ?>

	<form action="edit_intervention_ajout.php" method="post" name="intervention" onsubmit="return VerifFormIntervention();">
		<div id="liste">
			<p>
				<b>ETAPE 1 - RENSEIGNEMENTS GENERAUX</b> | Etape 2 - Infractions | Etape 3 - Agents
			</p>
			<hr color="#dcdcdc" > 
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">

					<tr>
						<td>
							Date de l'infraction
						</td>
						<td>
							<input type="text" id="datepicker" name="fdate" value="<? echo $ajd; ?>">
						</td>
					</tr>
					<tr>
						<td>Type d'intervention</td>
						<td>
							<select name="fintervention" >
								<option value="">...</option>
									<?
										//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
										$sql_infr = "SELECT id_type_intervention, type_intervention
										FROM interventions.bib_types_interventions
										ORDER BY type_intervention";
										$result = pg_query($sql_infr) or die ("Erreur requête") ;
										while ($val = pg_fetch_assoc($result)){
									?>
									<!--  Stocker l'id correspondant à la valeur selectionnée. -->
								<option value="<?=$val['id_type_intervention'];?>"><?=$val['type_intervention']?></option>
									<? } ?>
							</select>		
						</td>
					</tr>
				</table>
			</div>
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
					<tr>
						<td>
							Localisation :<br/>
							<? include "carto/localisation-ajout.php" ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
					<!-- Ne pas afficher le champ Contrevenant en attendant la declaration CNIL
					<tr>
						<td>Contrevenants</td>
						<td><input type="text" name="fcontrev" size="66"></td>
					</tr>
					-->
					<tr>
						<td>Nombre de contrevenants</td>
						<td><input type="text" name="fnbcontrev"></td>
					</tr>
					<tr>
						<td>Observations</td>
						<td><textarea name="fobs" cols="50" rows="5"></textarea></td>
					</tr>
				</table>
			</div>
			<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
				<tr>
					<td colspan="2">
							<table width="100%" border="0"  cellspacing = "0" cellpadding="2" align="center">
								<tr>
									<td>
										<a href="interventions_liste.php"><img src="images/icones/retour.gif" alt="Annuler" title="Annuler" border="0" align="absmiddle"> Annuler</a>
									</td>
									<td align="right">Enregistrer 
										<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/suivant.gif" alt="Enregistrer" title="Enregistrer" border="0" align="absmiddle">
									</td>
								</tr>
							</table>
					</td>
				</tr>
			</table>
		</div>
	</form>

<?
pg_close($dbconn);
?>
		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>