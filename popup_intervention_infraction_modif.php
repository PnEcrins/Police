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
		$idintervorig = $_POST[finterv];
		$idinfrorig = $_POST[finfroriginal];
		$idqualorig = $_POST[fqualoriginal];
		$query= "UPDATE interventions.cor_interventions_infractions 
		SET intervention_id = '$_POST[finterv]',
		infraction_id = '$_POST[finfr]',
		qualification_id = '$_POST[fqual]'
		WHERE intervention_id = $idintervorig AND infraction_id = $idinfrorig AND qualification_id = $idqualorig
		";

		pg_query($query) or die( "Erreur requete" );
	
		pg_close($dbconn);
		header("Location: edit_intervention_modif.php?id=$idinterv");
	}
}
?>
		<?
		$idinterv = $_GET[idint];
		$idinfr = $_GET[idinfr];
		$idqual = $_GET[idqual];
		?>
		
		<script src="js/forms_verifications.js"></script> 
		<form action="popup_intervention_infraction_modif.php" method="post" name="infraction" onsubmit="return VerifFormInfraction();">
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
							<option value="<?=$val['id_infraction'];?>" <?php if ($idinfr == $val['id_infraction']) : ?>selected <? endif ; ?>><?=$val['infraction'];?></option>
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
							<option value="<?=$val['id_qualification'];?>" <?php if ($idqual == $val['id_qualification']) : ?>selected <? endif ; ?>><?=$val['qualification'];?></option>
								<? } ?>
						</select>
						<input type="hidden" class="controleformulaire" name="finterv" value="<? echo $idinterv; ?>">
						<input type="hidden" class="controleformulaire" name="finfroriginal" value="<? echo $idinfr; ?>">
						<input type="hidden" class="controleformulaire" name="fqualoriginal" value="<? echo $idqual; ?>">
					</td>
		        </tr>

				<tr>
					<td align="right" colspan="2">
						<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/modifier.gif" alt="Modifier" title="Modifier" border="0" align="absmiddle"> Enregistrer les modifications</a>
					</td>
				</tr>
			</table>
		</form>