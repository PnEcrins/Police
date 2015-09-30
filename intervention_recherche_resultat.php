<? include "verification.php" ?>
<? include "header_front.php" ?>
<? $aucuntri = true; //Pour ne pas permettre de trier par colonne quand on affiche une liste de resultat filtrée par recherche ?> 
		<link rel="stylesheet" href="facebox/facebox.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="facebox/facebox.js"></script>
		<script type="text/javascript">
		    jQuery(document).ready(function($) {
		      $('a[rel*=facebox]').facebox() 
		    })
		</script>
	<title>Police du <? echo $etablissement_abv; ?> - Liste des interventions</title>
</head>
<body>
<? include "menu_general.php" ?>

<?php

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
	$queryinfr = "SELECT * FROM utilisateurs.t_roles WHERE id_role = '$agent'";
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


//Declarer la requete listant les enregistrements selon les criteres de recherche :
	$sqliste = "SELECT DISTINCT id_intervention, to_char(date, 'DD/MM/YYYY') as date, date as datetri, secteur, commune, secteur_id, suivi_num_parquet, suivi_suite_donnee, type_intervention
	FROM interventions.t_interventions int
	LEFT JOIN layers.l_communes com ON com.id_commune = int.commune_id
	LEFT JOIN layers.l_secteurs sect ON sect.id_sect = int.secteur_id
	LEFT JOIN interventions.cor_interventions_infractions cor ON cor.intervention_id = int.id_intervention
	LEFT JOIN interventions.cor_interventions_agents ag ON ag.intervention_id = int.id_intervention
	LEFT JOIN interventions.bib_types_interventions ti ON ti.id_type_intervention = int.type_intervention_id
	$where
	ORDER BY datetri DESC";
	//Executer la requete
	$resultliste = pg_query($sqliste) or die ('Échec requête : ' . pg_last_error()) ;
	//Compter le nombre d'enregistrements renvoyés par la requete
	$nombreint = pg_numrows($resultliste);

?>

			<div id="news">
				<h1>
					Liste des interventions trouv&eacute;es (<? echo "$nombreint"?>) | 
					<a href="interventions_liste.php">
						<img src="images/icones/retour.gif" align="absmiddle" alt="Retour &agrave; la liste compl&egrave;te des interventions" title = "Retour &agrave; la liste compl&egrave;te des interventions" border = "0"/>
					</a>
				</h1>
			</div>
			
			Votre recherche : 
			<?if ($num != null){ ?>
				<span class="commentaire">N&deg; de l'intervention :</span> <? echo "$num"?> - 
			<?}?>
			<?if ($infr != null){ ?>
				<span class="commentaire">Type d'infraction :</span> <? echo "$nominfraction"?> - 
			<?}?>
			<?if ($type != null){ ?>
				<span class="commentaire">Type d'intervention :</span> <? echo "$typeint"?> - 
			<?}?>
			<?if ($sect != null){ ?>
				<span class="commentaire">Secteur :</span> <? echo "$nomsecteur"?> - 
			<?}?>
			<?if ($com != null){ ?>
				<span class="commentaire">Commune :</span> <? echo "$nomcom"?> - 
			<?}?>
			<?if ($statut != null){ ?>
				<span class="commentaire">Statut de la zone :</span> <? echo "$nomstatut"?> - 
			<?}?>
			<?if ($agent != null){ ?>
				<span class="commentaire">Agent :</span> <? echo "$nomagent"?> - 
			<?}?>
			<?if ($date != null){ ?>
				<span class="commentaire">Annee :</span> <? echo "$date"?>
			<?}?>

			<? include "interventions_liste_inc.php" ?>
		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>
