<!--  <div id="logo"></div>  -->
<!-- <div id="bannertop"></div> -->

<?
$an = date("Y");
require("conf/parametres.php");
?>

<div id="conteneur">
	<table width="100%">
		<tr>
			<td width="50%" align="left">
				<img src="<?= $racine;?>images/logo_etablissement.png">
			</td>
			<td width="50%" align="right" valign="middle">
				<?php
					//Declarer la requete permettant de déterminer l'utilisateur connecté et ses droits
					$query = "			
                            SELECT b.*, d.nom_droit FROM
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
                            ) AS b
                            JOIN utilisateurs.bib_droits d ON d.id_droit = b.id_droit";
					//Executer la requete
					$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
					$valreq = pg_fetch_assoc($result);
					$nomagent = $valreq['nom_role'];
					$prenomagent = $valreq['prenom_role'];
					$droitagent = $valreq['nom_droit'];
					$iddroit = $valreq['id_droit'];
				?>
				<?echo $prenomagent .' '.' '. $nomagent;?> (<?echo $droitagent;?>)<br/>
				<a href="index.php">DECONNEXION<img src="<?= $racine; ?>images/disconnect.png" align="absmiddle" title="Deconnexion" border="0" width="30px"/>
			</td>
		</tr>
	</table>
	<div id="menu">
		<ul>
			<li><a href="<?= $racine; ?>accueil.php" class="rouge">Accueil</a></li>
			<li><a href="<?= $racine; ?>interventions_liste.php" class="orange">Interventions</a></li>
			<li><a href="<?= $racine; ?>carto.php?an=<? echo "$an" ?>" class="rouge">Carto</a></li>
			<li><a href="<?= $racine; ?>documents_liste.php" class="orange">Documents</a></li>
			<!--  Afficher l'onglet AGENTS si l'utilisateur est référent ou modérateur  -->	
			<? if ($iddroit == "3" OR $iddroit == "6")
			{ ?>
				<li><a href="<?= $racine; ?>agents_liste.php" class="rouge">Agents</a></li>
			<? } ?>
		</ul>
	</div>
	<div id="contenu">

