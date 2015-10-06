<? include "verification.php" ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<title>Fiche infraction</title>
	
</head>

<body>

<?php

	//Declarer la requete listant les enregistrements de la table à lister,
	$query = "SELECT *, 
			to_char(date, 'dd/mm/yyyy') as dat, 
			to_char(suivi_date_limite, 'dd/mm/yyyy') as datlimite, 
			to_char(date, 'Day') as jour, 
			to_char(suivi_date_constitution, 'dd/mm/yyyy') as suivi_dat_constitution,
			to_char(suivi_date_audience, 'dd/mm/yyyy') as suivi_dat_audience
	FROM interventions.t_interventions
	LEFT JOIN interventions.bib_types_interventions ON id_type_intervention = type_intervention_id
	LEFT JOIN interventions.bib_statutszone ON id_statutzone = statutzone_id
	LEFT JOIN layers.l_communes ON id_commune = commune_id
	LEFT JOIN layers.l_secteurs ON id_sect = secteur_id
	WHERE id_intervention = '$_GET[id]'" ; 
	//Executer la requete
	$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

	$val = pg_fetch_array($result) ;
		$id = $val['id_intervention'];
		$date = $val['dat'];
		$jour = date_fr($val['jour']);
		$interv = $val['type_intervention'];
		$comm = $val['commune'];
		$sect = $val['secteur'];
		$statut = $val['statutzone'];
		$obs = $val['observation'];
		$nbcontrev = $val['nbcontrevenants'];
		$x = $val['coord_x'];
		$y = $val['coord_y'];
		$datelimite = $val['datlimite'];
		$parquet = $val['suivi_num_parquet'];
		$suite = $val['suivi_suite_donnee'];
		$commentaire = $val['suivi_commentaire'];
		$partie = $val['suivi_partie_civile'];
		$avocat = $val['suivi_appel_avocat'];
		$dateconstitution = $val['suivi_dat_constitution'];
		$dateaudience = $val['suivi_dat_audience'];
		$amende = $val['suivi_montant_amende'];
		$amendedommages = $val['suivi_montant_dommages'];
?>

	<h2>
		<? echo $interv ?> <? echo $id ?> dans la commune de <? echo $comm ?>.
	</h2>
	<p>
		<span class="commentaire">Date de l'intervention :</span> <? echo $jour ?> <? echo $date ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Type d'intervention :</span> <? echo $interv ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Infraction(s) :</span>
		<?
			$query = "SELECT id_intervention, infraction, qualification FROM interventions.t_interventions
			JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
			LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
			LEFT JOIN interventions.bib_qualification ON id_qualification = qualification_id
			WHERE id_intervention = '$id'";
			//Executer la requete
			$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
			//Compter le nombre d'enregistrements renvoyés par la requete
			$nombreinfr = pg_numrows($result);
		?>
			<table border="0" width="100%" cellspacing="4px" cellpadding="4px" align="center">
		        <?  if ($nombreinfr > 0){ ?>
					<tr class="Col1liste" height="30px">
						<td align="left">Type</td>
						<td align="left">Qualification</td>
					</tr>
				<?  
				while ($val = pg_fetch_assoc($result)) 
				{
				$type = $val['infraction'];
				$id = $val['id_intervention'];
				$qual = $val['qualification'];
				?>
					<tr class="entetetablo">
						<td align="left"><?echo $type;?></td>
						<td align="left"><?echo $qual;?></td>
					</tr>
					<? } ?>
				<? }else{ ?>
					<tr>
						<td colspan="2">Aucune infraction renseignée pour cette intervention</td>
					</tr>
				<? } ?>
			</table>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<?
			$query = "SELECT i.id_intervention, u.nom_role AS nomutilisateur, u.prenom_role AS prenomutilisateur 
            FROM interventions.t_interventions i
			JOIN interventions.cor_interventions_agents cia ON cia.intervention_id = i.id_intervention
			JOIN utilisateurs.t_roles u ON u.id_role = cia.utilisateur_id
			WHERE i.id_intervention = '$id'
			ORDER BY u.nom_role";
			//Executer la requete
			$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
			//Compter le nombre d'enregistrements renvoyés par la requete
			$nombreagent = pg_numrows($result);	
		?>	
			<table width="100%" border="0" cellspacing="4px" cellpadding="4px" align="center">
		        <?  if ($nombreagent > 0){ ?>
					<tr class="Col1liste" height="30px">
						<td align="left" class="commentaire">Agent(s) présent(s)</td>
					</tr>
				<?  
				while ($val = pg_fetch_assoc($result)) 
				{
				$agent = $val['nom_role'].' '.$val['prenom_role'];
				$id = $val['id_intervention'];
				?>
					<tr>
						<td align="left" ><?echo $agent;?></td>
					</tr>
					<? } ?>
				<? }else{ ?>
					<tr>
						<td>Aucun agent renseigné pour cette intervention</td>
					</tr>
				<? } ?>
			
			</table>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Secteur :</span> <? echo $sect ?><br>
		<span class="commentaire">Commune :</span> <? echo $comm ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">X :</span> <? echo $x ?><br>
		<span class="commentaire">Y :</span> <? echo $y ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Statut de la zone :</span> <? echo $statut ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Nombre de contrevenants :</span> <? echo $nbcontrev ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Observations :</span> <? echo $obs ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Date limite de prescription :</span> <? echo $datelimite ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Numéro du parquet :</span> <? echo $parquet ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Date d'audience :</span> <? echo $dateaudience ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Appel à un avocat :</span> 
			<?  if ($avocat == "t"){ ?>
				Oui
			<? }elseif ($avocat == "f"){ ?>
				Non
			<? } ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Suite donnée :</span> <? echo $suite ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Montant de l'amende (euros) :</span> <? echo $amende ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Constitution de partie civile :</span> 
			<?  if ($partie == "t"){ ?>
				Oui
			<? }elseif ($partie == "f"){ ?>
				Non
			<? } ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Date de la constitution :</span> <? echo $dateconstitution ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Montant des dommages et intérêts (euros) :</span> <? echo $amendedommages ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Commentaire :</span> <? echo $commentaire ?>
	</p>
	<p>
		<a href="fiche_intervention_carto.php?id=<? echo $id ?>" target = "_blank">
			<img src = "images/icones/map.png" width="30px" align="absmiddle"/> Afficher la fiche et la carte de l'intervention (imprimable - nouvelle fen&ecirc;tre)
		</a>
	</p>
<?
pg_close($dbconn);
?>
</body>
</html>