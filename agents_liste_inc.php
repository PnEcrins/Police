			<div id="liste">
				<table border="0" cellpadding="5" width="100%">
					<!-- Afficher un message d'information si une action vient d'etre réalisée sur un agent (ajout, modification, suppresssion, modification du mot de passe) -->
					<? if ($_GET[agentmodifie]!="") {?>
					<tr>
						<td bgcolor="red" colspan = "6">
							Les informations concernant l'agent <?=$_GET[agentmodifie];?> ont bien &eacute;t&eacute; modifi&eacute;es.
						</td>
					</tr>
					<? } ?>
					<? if ($_GET[agentajoute]!="") {?>
					<tr>
						<td bgcolor="red" colspan = "6">
							L'agent <?=$_GET[agentajoute];?> a bien &eacute;t&eacute; ajout&eacute;.
						</td>
					</tr>
					<? } ?>
					<? if ($_GET[agentsuppr]!="") {?>
					<tr>
						<td bgcolor="red" colspan = "6">
							L'agent <?=$_GET[agentsuppr];?> a bien &eacute;t&eacute; supprim&eacute;.
						</td>
					</tr>
					<? } ?>
					<? if ($_GET[agentnosuppr]!="") {?>
					<tr>
						<td bgcolor="red" colspan = "6">
							L'agent <?=$_GET[agentnosuppr];?> n'a pu &ecirc;tre supprim&eacute; car il est associ&eacute; &agrave; au moins une intervention.
						</td>
					</tr>
					<? } ?>
					<? if ($_GET[agentmdp]!="") {?>
					<tr>
						<td bgcolor="red" colspan = "6">
							Le mot de passe de l'agent <?=$_GET[agentmdp];?> a &eacute;t&eacute; r&eacute;initialis&eacute;.
						</td>
					</tr>
					<? } ?>
					<!-- S'il est permis au référent d'ajouter un agent (dans le fichier de conf), alors il faut lui afficher le bouton -->
					<? if ($ref_ajout_agent=="oui") {?>
					<tr>
						<td colspan = "6">
						<a href="edit_agent_ajout.php">
							<img src="images/icones/ajouter.gif" alt="Ajouter une intervention" title="Ajouter une intervention" border="0" align="absmiddle"> Ajouter un agent
						</a>
						</td>
					</tr>
					<? } ?>
					<tr class="entetetablofiche" height="35">
						<td width="20%" align="left">Agent</td>
						<td width="20%" align="left">Organisme</td>
						<td width="15%" align="left">Droit</td>
						<td width="20%" align="left">Derniere connexion</td>
						<td width="10%" align="left">Nombre d'interventions</td>
						<? if ($ref_edit_agent=="oui") {?>
						<td width="15%" align="left">Options</td>
						<? } ?>
					</tr>
					<!--  Déclarer les éléments de la table à afficher et boucler tant qu'il y a des résultats dans la requete   -->
					<?  
					while ($val = pg_fetch_assoc($resultliste)) 
					{
						$id = $val['id_utilisateur'];
						$prenom = $val['prenomutilisateur'];
						$nom = $val['nomutilisateur'];
						$email = $val['email'];
						$organisme = $val['organisme'];
						$droit = $val['droit'];
						$connexion = $val['dernieracces_police'];
						?>
						
						<tr>
							<td class="Col3liste">
								<a href="mailto:<?echo $email;?>" title="Envoyer un courriel a <?echo $prenom;?> <?echo $nom;?>">
									<?echo $nom;?> <?echo $prenom;?>
								</a>
							</td>
							<td class="Col3liste"><?echo $organisme;?></td>
							<td class="Col3liste"><?echo $droit;?></td>
							<td class="Col3liste"><?echo $connexion;?></td>
							<td class="Col3liste">
								<?php
									$querynombre = "SELECT intervention_id FROM interventions.cor_interventions_agents
									WHERE utilisateur_id = '$id' ";
									//Executer la requete
									$resultcompte = pg_query($querynombre) or die ('Echec requete02 : ' . pg_last_error()) ;
									//Compter le nombre d'enregistrements renvoyés par la requete
									$nombreinterventions = pg_numrows($resultcompte);
								?>
								<? if ($nombreinterventions == "0")
								{ ?>
									Aucune
								<? }else{ ?>
								<?echo $nombreinterventions;?>
								<? } ?>
							</td>
							<? if ($ref_edit_agent=="oui") {?>
							<td class="Col3liste" align="center">
								<? if ($ref_mdp_agent=="oui") {?>
									<a href="edit_agent_mdp.php?id=<?echo $id;?>" rel="facebox"><img src="images/icones/password.png" alt="Modifier le mot de passe" title="Reg&eacute;n&eacute;rer le mot de passe" border="0" align="absmiddle">
									</a> | 
								<? } ?>
								<a href="edit_agent_modif.php?id=<?echo $id;?>"><img src="images/icones/modifier.gif" alt="Modifier" title="Modifier l'intervention" border="0" align="absmiddle">
								</a>
								 | 
								<a href="edit_agent_supprime.php?id=<?echo $id;?>" rel="facebox"><img src="images/icones/supprimer.gif" alt="Supprimer" title="Supprimer l'intervention" border="0" align="absmiddle">
								</a>
							</td>
							<? } ?>
						</tr>
					<? 
					}
					//Fermer la connexion à la BD 
					pg_close($dbconn);  
					?> 
				</table>
			</div>