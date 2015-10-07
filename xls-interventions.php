<? include "verification.php";
$where = $_GET['where'];
//Premiere ligne = nom des champs (sans accents car ça posait un pb de codage)
$csv_output = "N° intervention\tDate\tJour\tInfractions\tType d'intervention\tAgents présents\tCommune\tSecteur\tStatut de la zone\tX (WGS84)\tY (WGS84)\tObservation\tNombre de contrevenants\tDate limite de prescription\tNuméro de parquet\tDate d'audience\tAppel à un avocat\tSuite donnée\tCommentaire de suiv\tMontant de l'amende\tConstitution de partie civile\tDate de la constitution\tMontant des dommages et intérêts";
//Aller à la ligne
$csv_output .= "\n";

//Requete SQL
$query = "
SELECT interv.id_intervention, 
		interv.date, 
		to_char(date, 'Day') as jour, 
		comm.commune, 
		sect.secteur, 
		stat.statutzone, 
		typeint.type_intervention, 
		interv.observation, 
		interv.coord_x, 
		interv.coord_y, 
		interv.nbcontrevenants, 
		interv.suivi_date_limite, 
		interv.suivi_num_parquet, 
		interv.suivi_date_audience, 
		interv.suivi_appel_avocat, 
		interv.suivi_suite_donnee, 
		interv.suivi_commentaire, 
		interv.suivi_montant_amende, 
		interv.suivi_partie_civile, 
		interv.suivi_date_constitution, 
		interv.suivi_montant_dommages
FROM interventions.t_interventions interv
    LEFT JOIN interventions.cor_interventions_infractions corinf ON corinf.intervention_id = interv.id_intervention
    LEFT JOIN interventions.bib_statutszone stat ON stat.id_statutzone = interv.statutzone_id
    LEFT JOIN interventions.bib_types_interventions typeint ON typeint.id_type_intervention = interv.type_intervention_id
    LEFT JOIN interventions.cor_interventions_agents ag ON ag.intervention_id = interv.id_intervention
    LEFT JOIN layers.l_communes comm ON comm.id_commune = interv.commune_id
    LEFT JOIN layers.l_secteurs sect ON sect.id_sect = interv.secteur_id
$where
GROUP BY interv.id_intervention, 
		interv.date, 
		comm.commune, 
		sect.secteur, 
		stat.statutzone, 
		typeint.type_intervention, 
		interv.observation, 
		interv.coord_x, 
		interv.coord_y, 
		interv.nbcontrevenants, 
		interv.suivi_date_limite, 
		interv.suivi_num_parquet, 
		interv.suivi_date_audience, 
		interv.suivi_appel_avocat, 
		interv.suivi_suite_donnee, 
		interv.suivi_commentaire, 
		interv.suivi_montant_amende, 
		interv.suivi_partie_civile, 
		interv.suivi_date_constitution, 
		interv.suivi_montant_dommages
ORDER BY interv.date;
";
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

//Boucle sur les resultats
while($row = pg_fetch_assoc($result)) {
	//Remplace tous les ; des champs texte par , (car ils seraient interprétés comme un changement de cellule par Excel)
	//Puis remplacer tous les sauts de ligne par un espace (car ils seraient interprétés comme un changement de cellule par Excel)
	$obs = str_replace(";", ",", $row[observation]); 
	$obs = str_replace("\r\n", " ", $obs);
	$suite = str_replace(";", ",", $row[suivi_suite_donnee]); 
	$suite = str_replace("\r\n", " ", $suite);
	$commentsuivi = str_replace(";", ",", $row[suivi_commentaire]); 
	$commentsuivi = str_replace("\r\n", " ", $commentsuivi);
	$jour = date_fr($row['jour']);
	if ($row[suivi_partie_civile] == 't') { $partie = 'Oui'; } elseif ($row[suivi_partie_civile] == 'f')  { $partie = 'Non'; } else { $partie = ''; } 
	if ($row[suivi_appel_avocat] == 't') { $avocat = 'Oui'; } elseif ($row[suivi_appel_avocat] == 'f')  { $avocat = 'Non'; } else { $avocat = ''; } 
	//Boucle sur les n agents présents lors de l'intervention
	$agent = ""; // Réinitialiser la variable 
	$queryagent = "
		SELECT u.id_role, u.nom_role, u.prenom_role, interv.id_intervention
		FROM interventions.t_interventions interv
		LEFT JOIN interventions.cor_interventions_agents corag ON corag.intervention_id = interv.id_intervention
		LEFT JOIN utilisateurs.t_roles u ON u.id_role = corag.utilisateur_id
		WHERE interv.id_intervention = $row[id_intervention];
	";
	$resultagent = pg_query($queryagent) or die ('Échec requête : ' . pg_last_error()) ;
	while($rowagent = pg_fetch_assoc($resultagent)) {
		$agent .= "$rowagent[nom_role] $rowagent[prenom_role] - ";
	}
	//Boucle sur les n infractions de l'intervention
	$infr = ""; // Réinitialiser la variable 
	$queryinfr = "
		SELECT inf.infraction, qualif.qualification, interv.id_intervention
		FROM interventions.t_interventions interv
		LEFT JOIN interventions.cor_interventions_infractions corinf ON corinf.intervention_id = interv.id_intervention
		LEFT JOIN interventions.bib_infractions inf ON inf.id_infraction = corinf.infraction_id
		LEFT JOIN interventions.bib_qualification qualif ON qualif.id_qualification = corinf.qualification_id
		WHERE interv.id_intervention = $row[id_intervention];
	";
	$resultinfr = pg_query($queryinfr) or die ('Échec requête : ' . pg_last_error()) ;
	while($rowinfr = pg_fetch_assoc($resultinfr)) {
		$infr .= "$rowinfr[infraction] ($rowinfr[qualification]) - ";
	}
//Génération de la ligne correspondant à l'intervention
$csv_output .= "$row[id_intervention]\t$row[date]\t$jour\t$infr\t$row[type_intervention]\t$agent\t$row[commune]\t$row[secteur]\t$row[statutzone]\t$row[coord_x]\t$row[coord_y]\t$obs\t$row[nbcontrevenants]\t$row[suivi_date_limite]\t$row[suivi_num_parquet]\t$row[suivi_date_audience]\t$avocat\t$suite\t$commentsuivi\t$row[suivi_montant_amende]\t$partie\t$row[suivi_date_constitution]\t$row[suivi_montant_dommages]\n";
}

header("Content-type: application/vnd.ms-excel; charset=utf-8\n\n");
header("Content-disposition: attachment; filename=Interventions_" . date("Y-m-d_His").".xls");

//Convertir les données de UTF-8

print utf8_decode($csv_output);
exit;
?>