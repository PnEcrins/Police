			<div id="liste">
				<table border="0" cellpadding="5" width="100%">
					<tr>
						<? if ($iddroit > "1")
						{ ?>
						<a href="edit_intervention_ajout.php">
							<img src="images/icones/ajouter.gif" alt="Ajouter une intervention" title="Ajouter une intervention" border="0" align="absmiddle"> Ajouter une intervention
						</a>
						 | 
						<? } ?>
						<a href="intervention_recherche.php?num=<?=$num;?>&infraction=<?=$infr;?>&type=<?=$type;?>&secteur=<?=$sect;?>&commune=<?=$com;?>&statut=<?=$statut;?>&agent=<?=$agent;?>&annee=<?=$date;?>" rel="facebox">
							<img src="images/icones/rechercher.gif" alt="Rechercher une ou des intervention(s)" title="Rechercher une intervention" border="0" align="absmiddle"> Rechercher une ou des intervention(s)
						</a>

						<!--  Afficher l'option EXPORT XLS si l'utilisateur est r�f�rent ou mod�rateur  -->	
						<? if ($iddroit == "3" OR $iddroit == "6")
						{ ?>
							 | 
							<a href="popup_xls.php" title="Export vers Excel" rel="facebox">
								<img src="images/icones/excel.png" alt="Exporter la liste" border="0" align="absmiddle"> Exporter vers Excel
							</a>
						<? } ?>

						
						
						<p>
						Page : <?php paginateur($total,$debut,$limite,$orderby); ?>
						</p>
					</tr>
					<? if ($aucuntri == true) {?> <!--  Si on est sur une liste de r�sultat filtr�e -->	
                      <tr class="entetetablofiche" height="35">
						<td width="5%" align="left">N&deg; unique</td>
						<td width="18%" align="left">Types d'intervention</td>
						<td width="15%" align="left">Types d'infractions</td>
						<td width="9%" align="left">Dates</td>
						<td width="16%" align="left">Secteurs</td>
						<td width="25%" align="left">Suite donn&eacute;e</td>
						<td width="12%" align="center">Options</td>
					</tr>
                    <? } else { ?> <!--  Si on est sur la liste compl�te -->	
                    <tr class="entetetablofiche" height="35">
						<td width="5%" align="left"><a href="interventions_liste.php?orderby=id_intervention DESC">N&deg; unique</a></td>
						<td width="18%" align="left"><a href="interventions_liste.php?orderby=type_intervention ASC">Types d'intervention</a></td>
						<td width="15%" align="left">Types d'infractions</td>
						<td width="9%" align="left"><a href="interventions_liste.php?orderby=datetri DESC">Dates</a></td>
						<td width="16%" align="left"><a href="interventions_liste.php?orderby=secteur ASC">Secteurs</a></td>
						<td width="25%" align="left">Suite donn&eacute;e</td>
						<td width="12%" align="center">Options</td>
					</tr>
                    <? } ?>
                    
					<!--  D�clarer les �l�ments de la table � afficher et boucler tant qu'il y a des r�sultats dans la requete   -->
					<?  
					while ($val = pg_fetch_assoc($resultliste)) 
					{
						$id = $val['id_intervention'];
						$date = $val['date'];
						$jour = date_fr($val['jour']);
						$sect = $val['secteur'];
						$comm = $val['commune'];
						$numparquet = $val['suivi_num_parquet'];
						$suite = $val['suivi_suite_donnee'];
						$type_intervention = $val['type_intervention'];
						?>
						
						<tr>
							<? if ($orderby == "id_intervention DESC") { ?>
								<td class="Col4liste"><?echo $id;?></td>
							<? } 
							else { ?>
								<td class="Col3liste"><?echo $id;?></td>
							<? } ?>
							<? if ($orderby == "type_intervention ASC") { ?>
								<td class="Col4liste"><?echo $type_intervention;?></td>
							<? } 
							else { ?>
								<td class="Col3liste"><?echo $type_intervention;?></td>
							<? } ?>
							<td class="Col3liste" height="35">
							<?
								$query = "SELECT id_intervention, infraction, infraction_id FROM interventions.t_interventions
								LEFT JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
								LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
								WHERE id_intervention = '$id'
								ORDER BY infraction_id";
								//Executer la requete
								$result = pg_query($query) or die ('�chec requ�te : ' . pg_last_error()) ;
								//Compter le nombre d'enregistrements renvoy�s par la requete
								$nombreinfr = pg_numrows($result);
							while ($val = pg_fetch_assoc($result)) 
							{
								$type = $val['infraction'];
								$id = $val['id_intervention'];
							?>
								<?echo $type;?> / 
							<? } ?>
							</td>

							<? if ($orderby == "datetri DESC") { ?>
								<td class="Col4liste"><?echo $date;?><br/><span class="commentaire"><?echo $jour;?></span></td>
							<? } 
							else { ?>
								<td class="Col3liste"><?echo $date;?><br/><span class="commentaire"><?echo $jour;?></span></td>
							<? } ?>
							<? if ($orderby == "secteur ASC") { ?>
								<td class="Col4liste"><?echo $sect;?> (<?echo $comm;?>)</td>
							<? } 
							else { ?>
								<td class="Col3liste"><?echo $sect;?> (<?echo $comm;?>)</td>
							<? } ?>
							<td class="Col3liste"><?echo $suite;?></td>
							<td class="Col3liste" align="center">
								<a href="fiche_intervention.php?id=<?echo $id;?>" rel="facebox"><img src="images/icones/info.gif" alt="Afficher" title="Afficher la fiche compl&egrave;te de l'intervention" border="0" align="absmiddle">
								</a>
							<!--  Afficher l'option MODIFIER si l'utilisateur est au moins CONCEPTEUR -->	
							<? if ($iddroit > "1")
							{ ?>
								| 
								<a href="edit_intervention_modif.php?id=<?echo $id;?>"><img src="images/icones/modifier.gif" alt="Modifier" title="Modifier l'intervention" border="0" align="absmiddle">
								</a>
							<? } ?>
							<!--  Afficher l'option SUPPRIMER si l'utilisateur est r�f�rent ou mod�rateur  -->	
							<? if ($iddroit == "3" OR $iddroit == "6")
							{ ?>
								 | 
								<a href="edit_intervention_suppr.php?id=<?echo $id;?>" rel="facebox"><img src="images/icones/supprimer.gif" alt="Supprimer" title="Supprimer l'intervention" border="0" align="absmiddle">
								</a>
							<? } ?>
							</td>
						</tr>
					<? 
					}
					//Fermer la connexion � la BD 
					pg_close($dbconn);  
					?> 
				</table>
				<p>
					Page : <?php paginateur($total,$debut,$limite,$orderby); ?>
				</p>
			</div>