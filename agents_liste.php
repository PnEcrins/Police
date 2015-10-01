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
	<title>Police du <? echo $etablissement_abv; ?> - Liste des agents</title>
</head>
<body>
<? include "menu_general.php" ?>

<?php
//Declarer la requete listant les enregistrements de la table à lister,
	$sqliste = "SELECT id_role, prenom_role, nom_role, organisme, email, nom_droit
	FROM interventions.vue_agents
	LEFT JOIN utilisateurs.bib_droits ON id_droit = id_droit_police
	ORDER BY nom_role, prenom_role";
	//Executer la requete
	$resultliste = pg_query($sqliste) or die ('Échec requête : ' . pg_last_error()) ;
	//Compter le nombre d'enregistrements renvoyés par la requete
	$nombreagents = pg_numrows($resultliste);
?>

			<div id="news"><h1>Liste des agents (<? echo "$nombreagents"?>)</h1></div>

				<? include "agents_liste_inc.php" ?>

		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>
