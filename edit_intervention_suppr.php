<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{

$query = "DELETE FROM interventions.t_interventions
		WHERE id_intervention = '$_POST[finterv]'";
	
pg_query($query) or die( "Erreur requete" );
pg_close($dbconn);

header("Location: interventions_liste.php");

}

else { 

}
?>

			<?php
				//Declarer la requete listant les enregistrements de la table à lister,
				$query = "SELECT *, to_char(date, 'dd/mm/yyyy') as dat FROM interventions.t_interventions int
				LEFT JOIN layers.l_communes com ON com.id_commune = int.commune_id
				LEFT JOIN layers.l_secteurs sect ON sect.id_sect = int.secteur_id
				WHERE int.id_intervention = '$_GET[id]'" ; 
				//Executer la requete
				$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

				$val = pg_fetch_array($result) ;
					$id = $val['id_intervention'];
					$date = $val['dat'];
					$comm = $val['commune'];
					$sect = $val['secteur'];
			?>
				
			

	<form action="edit_intervention_suppr.php" method="post" name="intervsuppr">

			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<div align="center">
							<b>Supprimer l'intervention <? echo $id; ?> ?</b>
							<input type="hidden" name="finterv" value="<? echo $id; ?>">
						</div>
						<br/>
						<table width="500" border="0" cellpadding="10" align="center">
							<tr>
								<td width="20%">Date :</span></td>
								<td><? echo $date; ?></td>
							</tr>
							<tr>
								<td>Commune :</span></td>
								<td><? echo $comm; ?></td>
							</tr>
							<tr>
								<td>Secteur :</span></td>
								<td><? echo $sect; ?></td>
							</tr>
							<tr>
								<td>Infractions :</span></td>
								<td>
								<?
								$query = "SELECT id_intervention, infraction, infraction_id FROM interventions.t_interventions
									LEFT JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
									LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
									WHERE id_intervention = '$id'
									ORDER BY infraction_id";
									//Executer la requete
									$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
									//Compter le nombre d'enregistrements renvoyés par la requete
									$nombreinfr = pg_numrows($result);
									while ($val = pg_fetch_assoc($result)) 
										{
											$type = $val['infraction'];
											$id = $val['id_intervention'];
										?>
											<?echo $type;?> / 
										<? } ?>
								</td>
							</tr>
							<br/>
							<tr>
								<td colspan="2">
									<table width="100%" border="0" cellspacing = "0" cellpadding="0" align="center">
										<tr>
											<td>
												<a href="interventions_liste.php"><img src="images/icones/retour.gif" alt="Annuler" title="Annuler" border="0" align="absmiddle"> Annuler</a>
											</td>
											<td align="right">Supprimer 
												<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/supprimer.gif" alt="Supprimer" title="Supprimer" border="0" align="absmiddle">
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

	</form>

<?
pg_close($dbconn);
?>
