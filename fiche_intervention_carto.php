<? include "verification.php" ?>
<? include "header_front.php" ?>
	<title>Police du <? echo $etablissement_abv; ?> - Fiche intervention</title>
	<? if ($outil_carto == "gm") { ?>
		<script type="text/javascript" src="js/application.gm.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=<? echo $googlekeymap; ?>"></script>
	<? } elseif ($outil_carto == "ol") { ?>
		<script type="text/javascript" src="js/application.ol.js"></script>
		<script type="text/javascript" src="js/openlayers/OpenLayers.js"></script>
        <script type="text/javascript" src="conf/parametres_wms.js"></script>
	<? } ?>
</head>

<?php
	//Declarer la requete listant les enregistrements de la table � lister,
	$query = "SELECT *, to_char(date, 'dd/mm/yyyy') as dat, to_char(suivi_date_limite, 'dd/mm/yyyy') as datlimite, 
		to_char(date, 'Day') as jour, to_char(suivi_date_constitution, 'dd/mm/yyyy') as suivi_dat_constitution 
	FROM interventions.t_interventions
	LEFT JOIN interventions.bib_types_interventions ON id_type_intervention = type_intervention_id
	LEFT JOIN interventions.bib_statutszone ON id_statutzone = statutzone_id
	LEFT JOIN layers.l_communes ON id_commune = commune_id
	LEFT JOIN layers.l_secteurs ON id_sect = secteur_id
	WHERE id_intervention = '$_GET[id]'" ; 
	//Executer la requete
	$result = pg_query($query) or die ('�chec requ�te : ' . pg_last_error()) ;

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
		$dateconstitution = $val['suivi_dat_constitution'];
		$amende = $val['suivi_montant_amende'];
		$amendedommages = $val['suivi_montant_dommages'];
		
		// Si l'outil carto est OpenLayers alors il faut d'abord reprojeter les corrd X et Y qui sont stock�s en WGS84 dans la BdD 
		// vers la projection des fonds carto fournis par le WMS.
		if ($outil_carto == "ol") { 
		$reproj = "SELECT st_x(Transform(SETSRID(MakePoint(".$x.", ".$y."),4326), ".$wms_proj.")) AS xl2, 
		st_y(Transform(SETSRID(MakePoint(".$x.", ".$y."),4326), ".$wms_proj.")) AS yl2;";
		$result = pg_query($reproj) or die ('�chec requ�te : ' . pg_last_error()) ;
		$val = pg_fetch_array($result) ;

		$xl2 = $val['xl2'];
		$yl2 = $val['yl2'];
		}
?>

<? if ($outil_carto == "gm") { ?>
<!-- Si l'outil carto utilis� est Google Maps alors charger ses fonctions javascripts � l'ouverture de la page -->
	<body onload="create_gm(<?=$y;?>,<?=$x;?>,13,'<?=$host_url;?>','<?=$racine;?>',false)" onunload="GUnload()">
<? } elseif ($outil_carto == "ol") { ?>
<!-- Sinon on charge celles de GoogleMaps -->
	<body onload="create_ol.init(<?=$xl2;?>,<?=$yl2;?>,'6','<?=$wms_url;?>','<?=$wms_proj;?>','<?=$min_x;?>','<?=$min_y;?>','<?=$max_x;?>','<?=$max_y;?>',false)">
<? } ?>

<div style="margin: 0 auto; padding: 10px; width: 800px;">
	<p>
		<img src="images/logo_etablissement.png" align="absmiddle"> 
	</p>
	<h2>
		<? echo $interv ?> <? echo $id ?> dans la commune de <? echo $comm ?>
	</h2>
	<p>
		<? include "carto/localisation-detail.php" ?>
	</p>
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
			$query = "SELECT id_intervention, infraction, qualification 
            FROM interventions.t_interventions
			JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
			LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
			LEFT JOIN interventions.bib_qualification ON id_qualification = qualification_id
			WHERE id_intervention = '$id'";
			//Executer la requete
			$result = pg_query($query) or die ('�chec requ�te : ' . pg_last_error()) ;
			//Compter le nombre d'enregistrements renvoy�s par la requete
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
						<td colspan="2">Aucune infraction renseign&eacute;e pour cette intervention</td>
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
			$result = pg_query($query) or die ('�chec requ�te : ' . pg_last_error()) ;
			//Compter le nombre d'enregistrements renvoy�s par la requete
			$nombreagent = pg_numrows($result);	
		?>	
			<table width="100%" border="0" cellspacing="4px" cellpadding="4px" align="center">
		        <?  if ($nombreagent > 0){ ?>
					<tr class="Col1liste" height="30px">
						<td align="left" class="commentaire">Agent(s) pr&eacute;sent(s)</td>
					</tr>
				<?  
				while ($val = pg_fetch_assoc($result)) 
				{
				$agent = $val['nomutilisateur'].' '.$val['prenomutilisateur'];
				$id = $val['id_intervention'];
				?>
					<tr>
						<td align="left" ><?echo $agent;?></td>
					</tr>
					<? } ?>
				<? }else{ ?>
					<tr>
						<td>Aucun agent renseign&eacute; pour cette intervention</td>
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
	<!-- Ne pas afficher le champ Contrevenant en attendant la declaration CNIL
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Contrevenant(s) :</span> 
	</p>
	-->
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
		<span class="commentaire">Num&eacute;ro du parquet :</span> <? echo $parquet ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Suite donn&eacute;e :</span> <? echo $suite ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Montant de l'amende (euros) :</span> <? echo $amende ?> euros
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Constitution de partie civile :</span> 
			<?  if ($partie == "t"){ ?>
				Oui
			<? }else{ ?>
				Non
			<? } ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Date de la constitution :</span> <? echo $dateconstitution ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Montant des dommages et int&eacute;r&ecirc;ts (euros) :</span> <? echo $amendedommages ?>
	</p>
	<hr color="#dcdcdc" > 
	<p>
		<span class="commentaire">Commentaire :</span> <? echo $commentaire ?>
	</p>
	<? $ajd = date("d/m/Y"); ?>
	<?php
		//Declarer la requete listant les enregistrements de la table � lister,
			$query = "SELECT * FROM utilisateurs.t_roles u
			LEFT JOIN interventions.bib_droits ON id_droit = droit_id
			WHERE id_role = '".$_SESSION['xauthor']."'  ";
            $query = "			
                    SELECT b.* FROM
                    (
                        SELECT a.id_role, a.nom_role, a.prenom_role, max(a.id_droit) as id_droit
                        FROM 
                        (
                        SELECT u.id_role, u.nom_role, u.prenom_role, c.id_droit
                        FROM utilisateurs.t_roles u
                        JOIN utilisateurs.cor_role_droit_application c ON c.id_role = u.id_role
                        WHERE u.id_role = ".$_SESSION['xauthor']." AND c.id_application = ".$id_application."
                        union
                        SELECT u.id_role, u.nom_role, u.prenom_role, c.id_droit
                        FROM utilisateurs.t_roles u
                        JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
                        JOIN utilisateurs.cor_role_droit_application c ON c.id_role = g.id_role_groupe
                        WHERE u.id_role = ".$_SESSION['xauthor']." AND c.id_application = ".$id_application."
                        ) as a
                        GROUP BY a.id_role, a.nom_role, a.prenom_role
                    ) AS b";
			//Executer la requete
			$result = pg_query($query) or die ('�chec requ�te : ' . pg_last_error()) ;
			$val = pg_fetch_assoc($result);
			$nom = $val['nom_role'];
			$prenom = $val['prenom_role'];
	?>
	<hr color="#dcdcdc" > 
	<p class="droite">
		<span>Imprim&eacute; le <? echo $ajd ?> par <?echo $prenom .' '.' '. $nom;?>.
	</p>
</div>
<?
pg_close($dbconn);
?>
</body>
</html>