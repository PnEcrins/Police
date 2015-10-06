<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{

if ($_POST['fnbcontrev'] == "") //Si le champ 'nombre de contrevenants' est vide alors j'insere la valeur null
	{ $nbcontrev = 'null'; }
else { $nbcontrev = $_POST[fnbcontrev] ;
}

if ($_POST['fpartiecachee'] == "") //Si le champ caché 'partie civile' est vide alors j'insere la valeur null
	{ $partiecachee = 'null'; }
else { $partiecachee = $_POST[fpartiecachee] ;
}
if ($_POST['fpartiecivile'] == "") //Si le champ 'partie civile' est vide alors j'insere la valeur null
	{ $partieciv = $partiecachee; }
else { $partieciv = $_POST[fpartiecivile] ;
}

if ($_POST['favocatcachee'] == "") //Si le champ caché 'Avocat' est vide alors j'insere la valeur null
	{ $avocatcachee = 'null'; }
else { $avocatcachee = $_POST[favocatcachee] ;
}
if ($_POST['favocat'] == "") //Si le champ 'Avocat' est vide alors j'insere la valeur null
	{ $avocat = $avocatcachee; }
else { $avocat = $_POST[favocat] ;
}

if ($_POST['famende'] == "") //Si le champ 'montant de l'amende' est vide alors j'insere la valeur null
	{ $amende = 'null'; }
else { $amende = $_POST[famende] ;
}

if ($_POST['fdommages'] == "") //Si le champ 'montant des dommages et interets' est vide alors j'insere la valeur null
	{ $amendedommages = 'null'; }
else { $amendedommages = $_POST[fdommages] ;
}

if ($_POST['fdatelimite'] == "") //Si le champ 'date limite de prescription' est vide alors j'insere la valeur null
	{ $datelimite = 'null'; }
else { $datelimite = explode("/",$_POST[fdatelimite]) ;
	$datelimite = "'".$datelimite[2]."-".$datelimite[1]."-".$datelimite[0]."'" ;
}

if ($_POST['fdateconstitution'] == "") //Si le champ 'date de constitution' est vide alors j'insere la valeur null
	{ $dateconstitution = 'null'; }
else { $dateconstitution = explode("/",$_POST[fdateconstitution]) ;
	$dateconstitution = "'".$dateconstitution[2]."-".$dateconstitution[1]."-".$dateconstitution[0]."'" ;
}

if ($_POST['fdateaudience'] == "") //Si le champ 'date audience' est vide alors j'insere la valeur null
	{ $dateaudience = 'null'; }
else { $dateaudience = explode("/",$_POST[fdateaudience]) ;
	$dateaudience = "'".$dateaudience[2]."-".$dateaudience[1]."-".$dateaudience[0]."'" ;
}

//correction des magic_quotes_gpc (protection des chaînes de caractères)
$observation = pg_escape_string($_POST[fobs]);
$suivi_num_parquet = pg_escape_string($_POST[fnumparquet]);
$suivi_suite_donnee = pg_escape_string($_POST[fsuite]);
$suivi_commentaire = pg_escape_string($_POST[fcomment]);

$query= "UPDATE interventions.t_interventions SET
	date = to_date('$_POST[fdate]','dd/mm/yyyy'), 
	type_intervention_id = '$_POST[fintervention]', 
	commune_id = '$_POST[fcomm]', 
	secteur_id = '$_POST[fsect]', 
	coord_x = '$_POST[fx]', 
	coord_y = '$_POST[fy]', 
	the_geom = Transform(SETSRID(MakePoint('$_POST[fx]', '$_POST[fy]'),4326), $wms_proj),
	statutzone_id = '$_POST[fstatut]', 
	observation = '$observation',
	nbcontrevenants = $nbcontrev,
	suivi_date_limite = $datelimite,
	suivi_date_audience = $dateaudience,
	suivi_num_parquet = '$suivi_num_parquet',
	suivi_suite_donnee = '$suivi_suite_donnee',
	suivi_commentaire = '$suivi_commentaire',
	suivi_montant_amende = $amende,
	suivi_partie_civile = $partieciv,
	suivi_appel_avocat = $avocat,
	suivi_montant_dommages = $amendedommages,
	suivi_date_constitution = $dateconstitution
		WHERE id_intervention = '$_GET[id]'";


pg_query($query) or die( "Erreur requete" );

header("Location: interventions_liste.php");

}

else { 

}
?>

	<? include "header_front.php" ?>
		
		<!-- Librairie Jquery utilisée par Facebox et Datepicker -->
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		
		<!-- Facebox pour afficher des popups en javascript -->
		<link rel="stylesheet" href="js/facebox/facebox.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/facebox/facebox.js"></script>
		<script type="text/javascript">
		    jQuery(document).ready(function($) {
		      $('a[rel*=facebox]').facebox() 
		    })
		</script>
		
		<!-- Datepicker pour afficher un calendrier dans les champs dates -->
		<link type="text/css" href="js/datepicker/themes/base/ui.all.css" rel="stylesheet" />
		<script type="text/javascript" src="js/datepicker/ui/i18n/ui.datepicker-fr.js"></script>
		<script type="text/javascript" src="js/datepicker/ui/ui.datepicker.js"></script>
		<script type="text/javascript">
			$(function() {
				jQuery.datepicker.setDefaults(jQuery.datepicker.regional['fr']);
				$("#datepicker").datepicker({maxDate: '+0D'});
			});
		</script>
		<script type="text/javascript">
			$(function() {
				$("#datepicker2").datepicker($.datepicker.regional['fr']);
			});
		</script>
		
		<script type="text/javascript">
			$(function() {
				jQuery.datepicker.setDefaults(jQuery.datepicker.regional['fr']);
				$("#datepicker3").datepicker({maxDate: '+0D'});
			});
		</script>
		
		<script type="text/javascript">
			$(function() {
				$("#datepicker4").datepicker($.datepicker.regional['fr']);
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
			<script type="text/javascript" src="conf/parametres_wms.js"></script>
		<? } ?>
  
	<title>Police du <? echo $etablissement_abv; ?> - Modifier une intervention</title>
</head>

<?
$query = "SELECT *, 
		to_char(date, 'dd/mm/yyyy') as dat, 
		to_char(suivi_date_limite, 'dd/mm/yyyy') as suivi_dat_limite, 
		to_char(suivi_date_audience, 'dd/mm/yyyy') as suivi_dat_audience, 
		to_char(suivi_date_constitution, 'dd/mm/yyyy') as suivi_dat_constitution
	FROM interventions.t_interventions
	LEFT JOIN interventions.bib_types_interventions ON id_type_intervention = type_intervention_id
	LEFT JOIN interventions.bib_statutszone ON id_statutzone = statutzone_id
	LEFT JOIN layers.l_communes ON id_commune = commune_id
	WHERE id_intervention = '$_GET[id]'" ; 
	//Executer la requete
	$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

	$val = pg_fetch_array($result) ;
		$id = $val['id_intervention'];
		$date = $val['dat'];
		$interv = $val['type_intervention'];
		$type_id = $val['type_intervention_id'];
		// $comm = $val['commune'];
		$comm_id = $val['commune_id'];
		$secteur_id = $val['secteur_id'];
		// $sect = $val['secteur'];
		$statut = $val['statutzone'];
		$statut_id = $val['statutzone_id'];
		$obs = $val['observation'];
		$nbcontrev = $val['nbcontrevenants'];
		$x = $val['coord_x'];
		$y = $val['coord_y'];
		$datelimite = $val['suivi_dat_limite'];
		$dateaudience = $val['suivi_dat_audience'];
		$numparquet = $val['suivi_num_parquet'];
		$suite = $val['suivi_suite_donnee'];
		$comment = $val['suivi_commentaire'];
		$partie = $val['suivi_partie_civile'];
		if ($partie == "t") { $partiecivile = 'TRUE'; } elseif ($partie == "f") { $partiecivile = 'FALSE'; } else { $partiecivile = ''; }
		$avocat = $val['suivi_appel_avocat'];
		if ($avocat == "t") { $appelavocat = 'TRUE'; } elseif ($avocat == "f") { $appelavocat = 'FALSE'; } else { $appelavocat = ''; }	
		$dateconstitution = $val['suivi_dat_constitution'];
		$amende = $val['suivi_montant_amende'];
		$amendedommages = $val['suivi_montant_dommages'];

		// Si l'outil carto est OpenLayers alors il faut d'abord reprojeter les coord X et Y qui sont stockés en WGS84 dans la BdD 
		// vers la projection des fonds carto fournis par le WMS.
		if ($outil_carto == "ol") { 
		$reproj = "SELECT st_x(Transform(SETSRID(MakePoint(".$x.", ".$y."),4326), ".$wms_proj.")) AS xl2, 
		st_y(Transform(SETSRID(MakePoint(".$x.", ".$y."),4326), ".$wms_proj.")) AS yl2;";
		$result = pg_query($reproj) or die ('Échec requête : ' . pg_last_error()) ;
		$val = pg_fetch_array($result) ;

		$xl2 = $val['xl2'];
		$yl2 = $val['yl2'];
		}
?>

<? if ($outil_carto == "gm") { ?>
<!-- Si l'outil carto utilisé est OpenLayers alors charger ses fonctions javascripts à l'ouverture de la page -->
	<body onload="create_gm(<?=$y;?>,<?=$x;?>,13,'<?=$host_url;?>','<?=$racine;?>',true)" onunload="GUnload()">
<? } elseif ($outil_carto == "ol") { ?>
<!-- Sinon on charge celles de GoogleMaps -->
	<body onload="create_ol.init(<?=$xl2;?>,<?=$yl2;?>,'6','<?=$wms_url;?>','<?=$wms_proj;?>','<?=$min_x;?>','<?=$min_y;?>','<?=$max_x;?>','<?=$max_y;?>',true);">
<? } ?>


<? include "menu_general.php" ?>

			<div id="news">
				<h1>
					<img src="images/icones/modifier.gif" alt="Modifier une intervention" title="Modifier une intervention" border="0" align="absmiddle"> 
					Modifier l'intervention <? echo $id;?>
				</h1>
			</div>

	<form action = "edit_intervention_modif.php?id=<? echo $id;?>" method="post" name="intervention" onsubmit="return VerifFormIntervention();">
	
	
		<div id="liste">
			<div class="blocos">
				
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
				<tr>
					<td colspan="2">
						<span class="commentaire">INFORMATIONS A MODIFIER EN PREMIER LIEU.</span>
					</td>
				</tr>
				<tr>
					<td width="20%">Infraction(s)</td>
					<td>
						<table width="100%">
							<?
								$query = "SELECT id_intervention, infraction, id_infraction, qualification, id_qualification FROM interventions.t_interventions
								JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
								LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
								LEFT JOIN interventions.bib_qualification ON id_qualification = qualification_id
								WHERE id_intervention = '$id'";
								//Executer la requete
								$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
								//Compter le nombre d'enregistrements renvoyés par la requete
								$nombreinfr = pg_numrows($result);
							?>
							<?  if ($nombreinfr > 0){ ?>
								<tr>
									<td class="Col1liste" align="left">Type</td>
									<td class="Col1liste" align="left">Qualification</td>
									<td class="Col1liste" align="left">Options</td>
								</tr>
							<?  
							while ($val = pg_fetch_assoc($result)) 
							{
							$infr = $val['infraction'];
							$idinfr = $val['id_infraction'];
							$idinterv = $val['id_intervention'];
							$qual = $val['qualification'];
							$idqual = $val['id_qualification'];
							?>
								<tr>
									<td align="left"><?echo $infr;?></td>
									<td align="left"><?echo $qual;?></td>
									<td width="20%">
										<a href="popup_intervention_infraction_suppr.php?idint=<? echo $idinterv ?>&idinfr=<? echo $idinfr ?>&idqual=<? echo $idqual ?>" rel="facebox">
											<img src = "images/icones/supprimer.gif" title="Supprimer l'infraction" border="0" width="15px" align="absmiddle">
										</a> | 
										<a href="popup_intervention_infraction_modif.php?idint=<? echo $idinterv ?>&idinfr=<? echo $idinfr ?>&idqual=<? echo $idqual ?>" rel="facebox">
											<img src = "images/icones/modifier.gif" title="Modifier l'infraction" border="0" width="15px" align="absmiddle">
										</a>
									</td>
								</tr>
								<? } ?>
							<? }else{ ?>
								<tr>
									<td class="Col1liste" height="40" colspan="3">Aucune infraction renseignée pour cette intervention</td>
								</tr>
							<? } ?>
						</table>
					</td>
				</tr>
				<? $message = $_GET[message];
				if ($message == '1')
				{?>
				<tr>
					<td></td>
					<td colspan = "2" class="alerte">Attention ! Doublon, cette infraction a déjà été ajoutée.</td>
				</tr>
				<?}
				?>
				<tr>
					<td></td>
					<td colspan="2">
						<a href="popup_intervention_infraction_ajout.php?id=<?echo $id;?>" rel="facebox">
							<img src = "images/icones/ajouter.gif" alt="Ajouter une infraction" title="Ajouter une infraction" border="0" align="absmiddle">
							Ajouter une infraction
						</a>
					</td>
				</tr>
				<tr>
					<td class="Col1ajout" width="20%">Agent(s) présent(s)</td>
					<td class="Col2ajout">
						<table width="100%">
							<?
								$query = "SELECT i.id_intervention, u.nom_role, u.prenom_role, u.id_role FROM interventions.t_interventions i
								JOIN interventions.cor_interventions_agents cia ON cia.intervention_id = i.id_intervention
								JOIN utilisateurs.t_roles u ON u.id_role = cia.utilisateur_id
								WHERE i.id_intervention = '$id'
								ORDER BY u.nom_role";

								//Executer la requete
								$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
								//Compter le nombre d'enregistrements renvoyés par la requete
								$nombreagent = pg_numrows($result);	
							?>	
							<?  if ($nombreagent > 0){ ?>
								<tr>
									<td class="Col1liste" align="left">Agent(s) présent(s)</td>
									<td class="Col1liste" align="left">Options</td>
								</tr>
							<?  
							while ($val = pg_fetch_assoc($result)) 
							{
							$agent = $val['nom_role'].' '.$val['prenom_role'];
							$idagent = $val['id_role'];
							$idinterv = $val['id_intervention'];
							?>
								<tr>
									<td align="left"><?echo $agent;?></td>
									<td width="30%">
										<a href="popup_intervention_agent_suppr.php?idint=<? echo $idinterv ?>&idagent=<? echo $idagent ?>" rel="facebox">
											<img src = "images/icones/supprimer.gif" title="Supprimer l'infraction" border="0" width="15px">
										</a>
									</td>
								</tr>
								<? } ?>
							<? }else{ ?>
								<tr>
									<td class="Col1liste" height="40" colspan="2">Aucun agent renseigné pour cette intervention</td>
								</tr>
							<? } ?>
						</table>
					</td>
				</tr>
				<? $message = $_GET[message];
				if ($message == '2')
				{?>
				<tr>
					<td></td>
					<td colspan = "2" class="alerte">Attention ! Doublon, cet agent a déjà été ajouté.</td>
				</tr>
				<?}
				?>
				<tr>
					<td></td>
					<td>
						<a href="popup_intervention_agent_ajout.php?id=<?echo $id;?>" rel="facebox">
							<img src = "images/icones/ajouter.gif" alt="Ajouter une infraction" title="Ajouter une infraction" border="0" align="absmiddle">
							Ajouter un agent
						</a>
					</td>
				</tr>
				</table>
			</div>
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
				<tr>
					<td width="20%">Date de l'infraction</td>
					<td>
						<input type="text" id="datepicker" name="fdate" value="<? echo $date; ?>">
					</td>
		        </tr>
				<tr>
					<td>Type d'intervention</td>
					<td>
						<select name="fintervention">
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
							<option value="<?=$val['id_type_intervention'];?>" <?php if ($type_id == $val['id_type_intervention']) : ?>selected <? endif ; ?>><?=$val['type_intervention'];?></option>
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
							<? include "carto/localisation-modif.php" ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
				<tr>
					<td>Nombre de contrevenants</td>
					<td><input type="text" name="fnbcontrev" value="<? echo $nbcontrev; ?>"></td>
				</tr>
				<tr>
					<td>Observations</td>
					<td><textarea name="fobs" cols="50" rows="5" ><? echo $obs; ?></textarea></td>
					<input type="hidden" name="fpartiecachee" value="<? echo $partiecivile; ?>">
					<input type="hidden" name="favocatcachee" value="<? echo $appelavocat; ?>">
				</tr>
				</table>
			</div>

			<!--  Afficher les champs relatifs au suivi si l'utilisateur est référent ou modérateur  -->	
			<? if ($iddroit == "3" OR $iddroit == "6")
			{ ?>
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
				<tr>
					<td colspan="2">
						<span class="commentaire">SUIVI DE L'INTERVENTION - SAISIE SEULEMENT ACCESSIBLE AUX REFERENTS REGLEMENTATION.</span>
					</td>
				</tr>
				<tr>
					<td width="20%">Date limite de prescription</td>
					<td>
						<input type="text" id="datepicker2" name="fdatelimite" value="<? echo $datelimite; ?>">
					</td>
				</tr>
				<tr>
					<td width="20%">Numéro du parquet</td>
					<td><input type="text" name="fnumparquet" value="<? echo $numparquet; ?>"></td>
				</tr>
				<tr>
					<td width="20%">Date d'audience</td>
					<td>
						<input type="text" id="datepicker4" name="fdateaudience" value="<? echo $dateaudience; ?>">
					</td>
				</tr>
				<tr>
					<td>Appel à un avocat</td>
					<td>
						<input type="radio" name="favocat" value="TRUE" <?php if ($avocat == "t") : ?>checked<? endif ; ?>>Oui
						<input type="radio" name="favocat" value="FALSE" <?php if ($avocat == "f") : ?>checked<? endif ; ?>>Non	
					</td>
				</tr>
				<tr>
					<td>Suite donnée</td>
					<td><textarea name="fsuite" cols="50" rows="5" ><? echo $suite; ?></textarea></td>
		        </tr>
				<tr>
					<td>Montant de l'amende</td>
					<td>
						<input type="text" name="famende" value="<? echo $amende; ?>">
						<span class="commentaire"> euros</span/>
					</td>
		        </tr>
				
				<tr>
					<td>Constitution de partie civile</td>
					<td>
						<input type="radio" name="fpartiecivile" value="TRUE" <?php if ($partie == "t") : ?>checked<? endif ; ?>>Oui
						<input type="radio" name="fpartiecivile" value="FALSE" <?php if ($partie == "f") : ?>checked<? endif ; ?>>Non	
					</td>
				</tr>
				<tr>
					<td width="20%">Date de la constitution</td>
					<td>
						<input type="text" id="datepicker3" name="fdateconstitution" value="<? echo $dateconstitution; ?>">
					</td>
				</tr>
				<tr>
					<td>Montant des dommages et intérêts</td>
					<td>
						<input type="text" name="fdommages" value="<? echo $amendedommages; ?>">
						<span class="commentaire"> euros</span/>
					</td>
		        </tr>
				<tr>
					<td>Commentaire</td>
					<td><textarea name="fcomment" cols="50" rows="5" ><? echo $comment; ?></textarea></td>
		        </tr>
				</table>
			</div>
			<? } ?>
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

