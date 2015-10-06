<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
	$idinterv = $_POST[finterv];
	$queryverif= "SELECT intervention_id, utilisateur_id FROM interventions.cor_interventions_agents
		WHERE intervention_id='$_POST[finterv]' AND utilisateur_id='$_POST[fagent]'";
		$resultverif = pg_query($queryverif) or die( "Erreur requete" );
		$verif = pg_numrows($resultverif);			
				
	if ($verif == '1')
	{
		header("Location: edit_intervention_modif.php?id=$idinterv&message=2");
	}
	else 
	{
		$query= "INSERT INTO interventions.cor_interventions_agents (intervention_id, utilisateur_id) 
		VALUES('$_POST[finterv]','$_POST[fagent]')";
		pg_query($query) or die( "Erreur requete" );

	pg_close($dbconn);
	header("Location: edit_intervention_modif.php?id=$idinterv");
	}
}
?>
		<? $idinterv = $_GET[id];?>
		<script src="js/forms_verifications.js"></script> 
		<form action="popup_intervention_agent_ajout.php" method="post" name="agent" onsubmit="return VerifFormAgent();">
			<table width="100%">
				<tr>
					<td colspan="2" class="Col1liste">
						Ajouter un agent pour cette intervention
					</td>
				</tr>
				<br/>
				<tr>
					<td class="Col1ajout" width="20%">Agent</td>
					<td class="Col2ajout" width="80%">
						<select name="fagent" >
							<option value="">...</option>
								<?
									$sql_agent = "SELECT id_role, nom_role, prenom_role
									FROM interventions.vue_agents
									ORDER BY nom_role";
									$result = pg_query($sql_agent) or die ("Erreur requête") ;
									while ($val = pg_fetch_assoc($result)){
								?>
							<option value="<?=$val['id_role'];?>"><?=$val['nom_role'].' '.' '.$val['prenom_role'];?></option>
								<? } ?>
						</select>
						<input type="hidden" name="finterv" value="<? echo $idinterv; ?>">						
					</td>
		        </tr>
				<br/>
				<tr>
					<td align="right" colspan="2">
						<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/ajouter.gif" alt="Ajouter" title="Ajouter" border="0" align="absmiddle"> Ajouter l'agent</a>
					</td>
				</tr>
			</table>
		</form>