<? include "verification.php" ?>
<? include "header_front.php" ?>
		<link rel="stylesheet" href="js/facebox/facebox.css" media="screen" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="js/facebox/facebox.js"></script>
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
// G�n�rer une variable ORDER BY pour trier les r�sultats si l'utilisateur a cliqu� sur une des colonnes pour trier sur celle-ci
if (isset($_GET['orderby']))
{
$orderby = $_GET['orderby'];
}
else{
$orderby = 'datetri DESC, id_intervention DESC';
}



// PAGINATION - V�rifier si on est sur la premiere page ou sur une page suivante et compter le nombre total d'enregistrements pour d�terminer ensuite le nombre de pages.
if (empty($_GET['debut'])){ $debut=0; }else{ $debut = $_GET['debut']; }
$i=0;
$qtotal = requete("SELECT id_intervention FROM interventions.t_interventions");
$total = pg_numrows($qtotal);

//Declarer la requete listant les enregistrements de la table � lister,
	$sqliste = "SELECT id_intervention, to_char(date, 'DD/MM/YYYY') as date, date as datetri, secteur, commune, suivi_num_parquet, suivi_suite_donnee, type_intervention, to_char(date, 'Day') as jour
	FROM interventions.t_interventions int
	LEFT JOIN layers.l_communes com ON com.id_commune = int.commune_id
	LEFT JOIN layers.l_secteurs sect ON sect.id_sect = int.secteur_id
	LEFT JOIN interventions.bib_types_interventions ti ON ti.id_type_intervention = int.type_intervention_id
	ORDER BY $orderby
	LIMIT ".$limite." offset ".$debut."";
	//Executer la requete
	$resultliste = pg_query($sqliste) or die ('�chec requ�te : ' . pg_last_error()) ;
	
	$sqlcompte = "SELECT id_intervention
	FROM interventions.t_interventions";
	//Executer la requete
	$resultcompte = pg_query($sqlcompte) or die ('�chec requ�te : ' . pg_last_error()) ;
	//Compter le nombre d'enregistrements renvoy�s par la requete
	$nombreint = pg_numrows($resultcompte);
?>

			<div id="news">
				<h1>
					Liste des interventions (<? echo "$nombreint"?>)
				</h1>
			</div>

				<? include "interventions_liste_inc.php" ?>

		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>
