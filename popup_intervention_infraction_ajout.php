<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
	$idinterv = $_POST[finterv];
	$queryverif= "SELECT intervention_id, infraction_id, qualification_id FROM interventions.cor_interventions_infractions 
		WHERE intervention_id='$_POST[finterv]' AND infraction_id='$_POST[finfr]' AND qualification_id='$_POST[fqual]'";
		$resultverif = pg_query($queryverif) or die( "Erreur requete" );
		$verif = pg_numrows($resultverif);			
				
	if ($verif == '1')
	{
		header("Location: edit_intervention_modif.php?id=$idinterv&message=1");
	}
	else 
	{
		$query= "INSERT INTO interventions.cor_interventions_infractions (intervention_id, infraction_id, qualification_id) 
		VALUES('$_POST[finterv]','$_POST[finfr]','$_POST[fqual]')";
		pg_query($query) or die( "Erreur requete" );
	
		pg_close($dbconn);
		header("Location: edit_intervention_modif.php?id=$idinterv");
	}
}
?>
		<? $idinterv = $_GET[id];?>
		<script src="js/forms_verifications.js"></script> 
		<form action="popup_intervention_infraction_ajout.php" method="post" name="infraction" onsubmit="return VerifFormInfraction();">
			<table>
				<tr>
					<td colspan="2" class="Col1liste">
						Ajouter une infraction pour cette intervention
					</td>
				</tr>
				<tr>
					<td class="Col1ajout" width="20%">Type d'infraction</td>
					<td class="Col2ajout">
						<select name="finfr" class="controleformulaire">
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
					<td class="Col1ajout">Qualification</td>
					<td class="Col2ajout">
						<select name="fqual" class="controleformulaire">
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
						<input type="hidden" class="controleformulaire" name="finterv" value="<? echo $idinterv; ?>">
					</td>
		        </tr>

				<tr>
					<td align="right" colspan="2">
						<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/ajouter.gif" alt="Ajouter" title="Ajouter" border="0" align="absmiddle"> Ajouter l'infraction</a>
					</td>
				</tr>
			</table>
		</form>