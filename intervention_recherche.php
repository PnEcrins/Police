<? include "verification.php" ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Rechercher une infraction</title>
</head>
<?php
$source = $_GET['from']
?>

<body>

			<div id="filtre">
                <?php if($source == 'carte') { ?>
					<form action = "carto.php" method = "POST" name = "carto">
                <?php } else { ?>
					<form action = "intervention_recherche_resultat.php" method = "POST" name = "rechinfr">
                <?php } ?>
                
						Choisissez vos crit&egrave;res de recherche dans les listes d&eacute;roulantes. 
						Ces crit&egrave;res peuvent &ecirc;tre combin&eacute;s.<br/>
						<br/>
						<table>
							<tr>
								<td class="commentaire">
									N&deg; de l'intervention 
								</td>
								<td>
									<input type="text" name="rnum" value="<? echo $_GET[num]; ?>">
								</td>
							</tr>
							<tr>
								<td class="commentaire">Infraction </td>
								<td>
									<select name="rinfr">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_infr = "SELECT id_infraction , infraction
												FROM interventions.bib_infractions 
												ORDER BY infraction";
												$result = pg_query($sql_infr) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($result)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
										<option value="<?=$val['id_infraction'];?>" <?php if ($_GET[infraction] == $val['id_infraction']) : ?>selected <? endif ; ?>><?=$val['infraction'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="commentaire">Type d'intervention </td>
								<td>
									<select name="rtype">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_infr = "SELECT id_type_intervention, type_intervention
												FROM interventions.bib_types_interventions
												ORDER BY type_intervention";
												$result = pg_query($sql_infr) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($result)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
										<option value="<?=$val['id_type_intervention'];?>" <?php if ($_GET[type] == $val['id_type_intervention']) : ?>selected <? endif ; ?>><?=$val['type_intervention'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="commentaire">Secteur </td>
								<td>
									<select name="rsect">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_sect = "SELECT id_sect, secteur
												FROM layers.l_secteurs 
												ORDER BY secteur";
												$resultsect = pg_query($sql_sect) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($resultsect)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
										<option value="<?=$val['id_sect'];?>" <?php if ($_GET[secteur] == $val['id_sect']) : ?>selected <? endif ; ?>><?=$val['secteur'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="commentaire">Commune </td>
								<td>
									<select name="rcom">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_com = "SELECT id_commune, commune
												FROM layers.l_communes 
												ORDER BY commune";
												$resultcom = pg_query($sql_com) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($resultcom)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
											<option value="<?=$val['id_commune'];?>" <?php if ($_GET[commune] == $val['id_commune']) : ?>selected <? endif ; ?>><?=$val['commune'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="commentaire">Statut de la zone </td>
								<td>
									<select name="rstatut">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_statut = "SELECT id_statutzone, statutzone, ordre
												FROM interventions.bib_statutszone
												ORDER BY ordre";
												$resultstatut = pg_query($sql_statut) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($resultstatut)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
										<option value="<?=$val['id_statutzone'];?>" <?php if ($_GET[statut] == $val['id_statutzone']) : ?>selected <? endif ; ?>><?=$val['statutzone'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="commentaire">Agent pr&eacute;sent </td>
								<td>
									<select name="ragent">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_agent = "SELECT a.* FROM 
                                                    (
                                                        (SELECT u.id_role, u.nom_role, u.prenom_role
                                                        FROM utilisateurs.t_roles u
                                                        JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
                                                        JOIN utilisateurs.cor_role_menu crm ON crm.id_role = g.id_role_groupe
                                                        WHERE crm.id_menu = 14)
                                                        UNION
                                                        (SELECT u.id_role AS id_utilisateur, u.nom_role, u.prenom_role
                                                        FROM utilisateurs.t_roles u
                                                        JOIN utilisateurs.cor_role_menu crm ON crm.id_role = u.id_role
                                                        WHERE crm.id_menu = 14
                                                        AND u.groupe = false
                                                        )
                                                    ) a
                                                   ORDER BY a.nom_role";
												$resultagent = pg_query($sql_agent) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($resultagent)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->
										<option value="<?=$val['id_role'];?>" <?php if ($_GET[agent] == $val['id_role']) : ?>selected <? endif ; ?>><?=$val['nom_role'].' '.' '.$val['prenom_role'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="commentaire">Ann&eacute;e </td>
								<td>
									<select name="rdate">
										<option value="">...</option>
											<?
												//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
												$sql_an = "SELECT distinct extract(year from date) as annee
												FROM interventions.t_interventions
												ORDER BY extract(year from date)";
												$resultan = pg_query($sql_an) or die ("Erreur requête") ;
												while ($val = pg_fetch_assoc($resultan)){
											?>
											<!--  Stocker l'id correspondant à la valeur selectionnée. Selectionner par défaut la valeur correspondant à l'enregistrement à modifier  -->

										<option value="<?=$val['annee'];?>" <?php if ($_GET[annee] == $val['annee']) : ?>selected <? endif ; ?>><?=$val['annee'];?></option>
											<? } ?>
									</select>
								</td>
							</tr>
							<br/>
							<tr>
								<td colspan="2">
									<input name="Submit" type="submit" value="Filtrer">
								</td>
							</tr>
						</table>
					</form>
				</div>

<?
pg_close($dbconn);
?>
</body>
</html>