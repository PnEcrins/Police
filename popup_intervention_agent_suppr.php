<? include "verification.php" ?>
<?php
if ($_GET[del] == "OK")
{

$idinterv = $_GET[idint];
$idagent = $_GET[idagent];

$query= "DELETE FROM interventions.cor_interventions_agents
	WHERE intervention_id = '$idinterv' AND utilisateur_id = '$idagent'";
	pg_query($query) or die( "Erreur requete" );

pg_close($dbconn);

header("Location: edit_intervention_modif.php?id=$idinterv");

}
?>
		<? 
			$idinterv = $_GET[idint];
			$idagent = $_GET[idagent];
			$sqlinfr = "SELECT cia.intervention_id, cia.utilisateur_id, u.prenom_role, u.nom_role
                        FROM interventions.cor_interventions_agents cia
                        LEFT JOIN utilisateurs.t_roles u ON u.id_role = cia.utilisateur_id
						WHERE cia.intervention_id = $idinterv AND cia.utilisateur_id = $idagent";
						$result = pg_query($sqlinfr) or die ("Erreur requête") ;
						$val = pg_fetch_assoc($result);
						$agent = $val['nom_role'].' '.$val['prenom_role'];
		?>
			<table>
				<tr>
					<td width="100%">
						Supprimer l'agent de cette intervention ?
					</td>
				</tr>
				<tr>
					<td class="Col1liste">Agent present</td>
		        </tr>
				<tr>
					<td><?echo $agent;?></td>
		        </tr>

				<tr>
					<td align="right">
						<a href="popup_intervention_agent_suppr.php?idint=<? echo $idinterv ?>&idagent=<? echo $idagent ?>&del=OK" title="Supprimer l'agent">
						<img src = "images/icones/supprimer.gif" alt="Supprimer" title="Supprimer" border="0" align="absmiddle"> Supprimer l'agent</a>
					</td>
				</tr>
			</table>
