<?

$num = $_POST["rnum"];
$infr = $_POST["rinfr"];
$date = $_POST["rdate"];
$sect = $_POST["rsect"];
$com = $_POST["rcom"];
$type = $_POST["rtype"];
$agent = $_POST["ragent"];
$statut = $_POST["rstatut"];

//construction de la clause where pour la recherche multicritères
// sur l'annee
if ($date != null){
	$where = "Where extract(year from date) = '$date'";
}
// sur le numero
if ($num != null){
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where id_intervention = $num";
	else
		$where = $where .' '.'and'.' '."id_intervention = $num";
}
// sur le secteur
if ($sect != null){
	// recuperer le nom du secteur pour l'afficher dans le recapitulatif de recherche
	$querysect = "SELECT * FROM layers.l_secteurs WHERE id_sect = '$sect'";
	$result = pg_query($querysect) or die ('Échec requête : ' . pg_last_error()) ;
	$val = pg_fetch_assoc($result);
	$nomsecteur = $val['secteur'];
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where secteur_id = $sect";
	else
		$where = $where .' '.'and'.' '."secteur_id = $sect";
}
// sur la commune
if ($com != null){
	// recuperer le nom de la commune pour l'afficher dans le recapitulatif de recherche
	$querycom = "SELECT * FROM layers.l_communes WHERE id_commune = '$com'";
	$result = pg_query($querycom) or die ('Échec requête : ' . pg_last_error()) ;
	$val = pg_fetch_assoc($result);
	$nomcom = $val['commune'];
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where commune_id = $com";
	else
		$where = $where .' '.'and'.' '."commune_id = $com";
}
// sur le type d'infraction
if ($infr != ""){
	// recuperer le nom de l'infraction pour l'afficher dans le recapitulatif de recherche
	$queryinfr = "SELECT * FROM interventions.bib_infractions WHERE id_infraction = '$infr'";
	$result = pg_query($queryinfr) or die ('Échec requête : ' . pg_last_error()) ;
	$val = pg_fetch_assoc($result);
	$nominfraction = $val['infraction'];
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where infraction_id = ('$infr')";
	else
		$where = $where .' '.'and'.' '."infraction_id = ('$infr')";
}
// sur les agents
if ($agent != ""){
	// recuperer le nom de l'agent pour l'afficher dans le recapitulatif de recherche
	$queryinfr = "SELECT * FROM utilisateurs.vue_agents WHERE id_role = '$agent'";
	$result = pg_query($queryinfr) or die ('Échec requête : ' . pg_last_error()) ;
	$val = pg_fetch_assoc($result);
	$nomagent = $val['nom_role'].' '.' '.$val['prenom_role'];
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where utilisateur_id = ('$agent')";
	else
		$where = $where .' '.'and'.' '."utilisateur_id = ('$agent')";
}
// sur le type d'intervention
if ($type != ""){
	// recuperer le nom du type pour l'afficher dans le recapitulatif de recherche
	$querytype = "SELECT * FROM interventions.bib_types_interventions WHERE id_type_intervention = '$type'";
	$result = pg_query($querytype) or die ('Échec requête : ' . pg_last_error()) ;
	$val = pg_fetch_assoc($result);
	$typeint = $val['type_intervention'];
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where type_intervention_id = ('$type')";
	else
		$where = $where .' '.'and'.' '."type_intervention_id = ('$type')";
}
// sur le statut de la zone
if ($statut != ""){
	// recuperer le nom du type pour l'afficher dans le recapitulatif de recherche
	$querytype = "SELECT * FROM interventions.bib_statutszone WHERE id_statutzone = '$statut'";
	$result = pg_query($querytype) or die ('Échec requête : ' . pg_last_error()) ;
	$val = pg_fetch_assoc($result);
	$nomstatut = $val['statutzone'];
	//construire le where de la recherche
	if ($where == "") 
		$where = "Where statutzone_id = ('$statut')";
	else
		$where = $where .' '.'and'.' '."statutzone_id = ('$statut')";
}
?>