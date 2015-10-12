<? include "verification.php" ?>
<?php

if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
	$idinterv = $_POST[finterv];
	$queryverif= "SELECT intervention_id, infraction_id, qualification_id FROM interventions.cor_interventions_infractions 
		WHERE intervention_id='$_POST[finterv]' AND infraction_id='$_POST[finfr]' AND qualification_id='$_POST[fqual]'";
		$resultverif = pg_query($queryverif) or die( "Erreur requete" );
		$verif = pg_numrows($resultverif);			
				
	if ($verif == '1')
	{
		header("Location: edit_intervention_infraction_ajout.php?id=$idinterv&message=1");
	}
	else 
	{
		$query= "INSERT INTO interventions.cor_interventions_infractions (intervention_id, infraction_id, qualification_id) 
		VALUES('$_POST[finterv]','$_POST[finfr]','$_POST[fqual]')";
		pg_query($query) or die( "Erreur requete" );
	
	pg_close($dbconn);
	header("Location: edit_intervention_infraction_ajout.php?id=$idinterv");
	}
}

?>

	<? include "header_front.php" ?>
	<script src="js/forms_verifications.js"></script> 
	<title>Police du <? echo $etablissement_abv; ?> - Ajouter une intervention</title>
</head>
<body>
<? $idinterv = $_GET[id]; ?>
<? include "menu_general.php" ?>
<?
//Declarer la requete listant les enregistrements de la table à lister,
$query1 = "SELECT id_intervention, date, commune FROM interventions.t_interventions
LEFT JOIN layers.l_communes ON id_commune = commune_id
WHERE id_intervention = '$idinterv'";
//Executer la requete
$result1 = pg_query($query1) or die ('Échec requête : ' . pg_last_error()) ;
//Compter le nombre d'enregistrements renvoyés par la requete
$val1 = pg_fetch_assoc($result1);
$idint = $val1['id_intervention'];
$date = $val1['date'];
$comm = $val1['commune'];
?>

			<div id="news">
				<h1>
					<img src="images/icones/ajouter.gif" alt="Ajouter une intervention" title="Ajouter une intervention" border="0" align="absmiddle"> 
					Ajouter les infractions de l'intervention <? echo $idint; ?> du <? echo $date; ?>
				</h1>
			</div>

<?
$query = "SELECT id_intervention, infraction, qualification FROM interventions.t_interventions
JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
LEFT JOIN interventions.bib_qualification ON id_qualification = qualification_id
WHERE id_intervention = '$idinterv'";
//Executer la requete
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
//Compter le nombre d'enregistrements renvoyés par la requete
$nombreinfr = pg_numrows($result);
?>	
			
			
<div id="liste">
		    Etape 1 - Renseignements généraux | <b>ETAPE 2 - INFRACTIONS</b> | Etape 3 - Agents
			<hr color="#dcdcdc" > 
			<table width="800px" border="0" cellspacing="5px" cellpadding="5px" align="center">
		        <?  if ($nombreinfr > 0){ ?>
					<tr>
						<td class="Col1liste" align="left">Type</td>
						<td class="Col1liste" align="left">Qualification</td>
					</tr>
				<?  
				while ($val = pg_fetch_assoc($result)) 
				{
				$type = $val['infraction'];
				$id = $val['id_intervention'];
				$qual = $val['qualification'];
				?>
					<tr>
						<td align="left"><?echo $type;?></td>
						<td align="left"><?echo $qual;?></td>
					</tr>
					<? } ?>
				<? }else{ ?>
					<tr>
						<td height="40" colspan="2" class="commentaire">Plusieurs infractions peuvent être renseignées pour une même intervention</td>
					</tr>
				<? } ?>
			
			<form action="edit_intervention_infraction_ajout.php" method="post" name="infraction" onsubmit="return VerifFormInfraction();">
				
				<tr>
					<td colspan="2" class="Col1liste">
						Ajouter une infraction pour cette intervention
					</td>
				</tr>
				<tr>
					<td width="20%">Type d'infraction</td>
					<td>
						<select name="finfr">
							<option value="">...</option>
								<?
									//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
									$sql_infr = "SELECT id_infraction, infraction
									FROM interventions.bib_infractions
									ORDER BY infraction";
									$result = pg_query($sql_infr) or die ("Erreur requête") ;
									while ($val = pg_fetch_assoc($result)){
								?>
								<!--  Stocker l'id correspondant à la valeur selectionnée. -->
							<option value="<?=$val['id_infraction'];?>"><?=$val['infraction']?></option>
								<? } ?>
						</select>		
					</td>
		        </tr>
				<tr>
					<td>Qualification</td>
					<td>
						<select name="fqual">
							<option value="">...</option>
								<?
									//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
									$sql_infr = "SELECT id_qualification, qualification
									FROM interventions.bib_qualification
									ORDER BY qualification";
									$result = pg_query($sql_infr) or die ("Erreur requête") ;
									while ($val = pg_fetch_assoc($result)){
								?>
								<!--  Stocker l'id correspondant à la valeur selectionnée. -->
							<option value="<?=$val['id_qualification'];?>"><?=$val['qualification']?></option>
								<? } ?>
						</select>
						<input type="hidden" name="finterv" value="<? echo $idinterv; ?>">
					</td>
		        </tr>
				<? $message = $_GET[message];
				if ($message == '1')
				{?>
				<tr>
					<td colspan = "2" class="alerte">Attention ! Doublon, cette infraction a déjà été ajoutée.</td>
				</tr>
				<?}
                if ($message == '2')
				{?>
				<tr>
					<td colspan = "2" class="alerte">Attention ! Vous devez saisir au moins une infraction.</td>
				</tr>
				<?}
				?>
				<tr>
					<td></td>
					<td align="left">Valider l'infraction
						<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/ajouter.gif" alt="Enregistrer" title="Enregistrer" border="0" align="absmiddle">
					</td>
				</tr>
			</form>
				<tr>
					<td align="right" colspan="2">
						<a href="edit_intervention_agent_ajout.php?id=<?echo $idinterv;?>"><img src="images/icones/suivant.gif" alt="Suivant" title="Suivant" border="0" align="absmiddle"> Etape suivante</a>
					</td>
				</tr>
			</table>

</div>

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