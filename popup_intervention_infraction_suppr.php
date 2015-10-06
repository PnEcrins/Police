<? include "verification.php" ?>
<?php
if ($_GET[del] == "OK")
{

$idinterv = $_GET[idint];
$idinfr = $_GET[idinfr];
$idqual = $_GET[idqual];

$query= "DELETE FROM interventions.cor_interventions_infractions
	WHERE intervention_id = '$idinterv' AND infraction_id = '$idinfr' AND qualification_id = '$idqual'";
	pg_query($query) or die( "Erreur requete" );

pg_close($dbconn);

header("Location: edit_intervention_modif.php?id=$idinterv");

}
?>
		<? 
			$idinterv = $_GET[idint];
			$idinfr = $_GET[idinfr];
			$idqual = $_GET[idqual];
			$sqlinfr = "SELECT infraction_id, infraction, intervention_id, qualification_id, qualification
						FROM interventions.cor_interventions_infractions 
						LEFT JOIN interventions.bib_qualification ON id_qualification = qualification_id
						LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
						WHERE intervention_id = $idinterv AND infraction_id = $idinfr AND qualification_id = $idqual";
						$result = pg_query($sqlinfr) or die ("Erreur requête") ;
						$val = pg_fetch_assoc($result);
						$infr = $val['infraction'];
						$qual = $val['qualification'];
		?>
			<table>
				<tr>
					<td colspan="2">
						Supprimer l'infraction de cette intervention ?
					</td>
				</tr>
				<tr>
					<td class="Col1liste" width="50%">Type d'infraction</td>
					<td class="Col1liste">Qualification</td>
		        </tr>
				<tr>
					<td><? echo $infr ?></td>
					<td><? echo $qual ?></td>
		        </tr>

				<tr>
					<td align="right" colspan="2">
						<a href="popup_intervention_infraction_suppr.php?idint=<? echo $idinterv ?>&idinfr=<? echo $idinfr ?>&idqual=<? echo $idqual ?>&del=OK" title="Supprimer l'infraction">
						<img src = "images/icones/supprimer.gif" alt="Supprimer" title="Supprimer" border="0" align="absmiddle">  Supprimer l'infraction</a>
					</td>
				</tr>
			</table>
