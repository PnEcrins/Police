<? include "verification.php" ?>
<? include "header_front.php" ?>
	<title>Police du <? echo $etablissement_abv; ?></title>
</head>
<body>

	<? include "menu_general.php" ?>
				
			<?php
				//Declarer la requete listant les enregistrements de la table à lister,
				$query = "SELECT id_role,nom_role,prenom_role FROM utilisateurs.t_roles
				WHERE id_role = '".$_SESSION['xauthor']."'  ";
				//Executer la requete
				$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
				$val = pg_fetch_assoc($result);
				$nom = $val['nom_role'];
				$id = $val['id_role'];
				$prenom = $val['prenom_role'];
			?>
	
			<div id="news"><h1><?echo $prenom .' '.' '. $nom;?>, bienvenue dans l'application "Police".</h1>
				<?php
					//Declarer la requete listant les enregistrements de la table à lister,
					$query = "SELECT * FROM interventions.t_interventions";
					//Executer la requete
					$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
					//Compter le nombre d'enregistrements renvoyés par la requete
					$nombreint= pg_numrows($result);
				?>
						<p class="texte">
							Cette application a vocation &agrave; collecter toutes les interventions de police de quelque nature qu'elle soit (PV, TA, avertissement, ...).
						</p>
						<p class="texte">
							En cas de probl&egrave;me, contactez le r&eacute;f&eacute;rent r&eacute;glementation : <? echo $referent_police; ?>, 
							<a href="mailto:<? echo $referent_police_email; ?>"><? echo $referent_police_email; ?></a>, <? echo $referent_police_tel; ?>
						</p>
						<p class="texte">Elle inclut actuellement <a href="interventions_liste.php"><? echo "$nombreint"?> interventions</a>.</p>
				</div>
			<br/>
			<div class="clear"></div>
			
		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>