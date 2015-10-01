<? include "verification.php" ?>
<? include "conf/parametres.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
//correction des magic_quotes_gpc (protection des chaînes de caractères)
$prenomutilisateur = pg_escape_string($_POST[fprenom]);
$nomutilisateur = pg_escape_string($_POST[fnom]);
$organisme = pg_escape_string($_POST[forganisme]);
$query= "UPDATE utilisateurs.t_roles SET
	prenom_role = '$prenomutilisateur', 
	nom_role = '$nomutilisateur', 
	organisme = '$organisme', 
	email = '$_POST[femail]', 
	identifiant = '$_POST[flogin]'
    WHERE id_role = '$_GET[id]'";
pg_query($query) or die( "Erreur requete" );
//suppression de la donnée dans la table de correspondance de gestion des droits
$query= "DELETE FROM utilisateurs.cor_role_droit_application 
         WHERE id_role = $_GET[id] AND id_application = $id_application ";
pg_query($query) or die( "Erreur requete" );

//ajout de la donnée dans la table de correspondance de gestion des droits
$query= "INSERT INTO utilisateurs.cor_role_droit_application(id_role,id_droit,id_application) 
         VALUES($_GET[id],$_POST[fdroits],$id_application)";
pg_query($query) or die( "Erreur requete" );

pg_close($dbconn);

header("Location: agents_liste.php?agentmodifie=$_POST[fprenom] $_POST[fnom]");

}

else { 

}
?>

	<? include "header_front.php" ?>
				
		<!-- Javascript permettant de vérifier que les champs obligatoires sont bien remplis -->
		<script type="text/javascript" src="js/forms_verifications.js"></script> 
		
		  
	<title>Police du <? echo $etablissement_abv; ?> - Modifier un agent</title>
</head>
<?
$query = "SELECT *
	FROM interventions.vue_agents
	WHERE id_role = '$_GET[id]'" ; 
	//Executer la requete
	$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

	$val = pg_fetch_array($result) ;
		$id = $val['id_role'];
		$prenom = $val['prenom_role'];
		$nom = $val['nom_role'];
		$organisme = $val['organisme'];
		$email = $val['email'];
		$login = $val['identifiant'];
		$droit = $val['id_droit_police'];

?>

	
	<? include "menu_general.php" ?>

			<div id="news">
				<h1>
					<img src="images/icones/modifier.gif" alt="Modifier une intervention" title="Modifier une intervention" border="0" align="absmiddle"> 
					Modifier un agent
				</h1>
			</div>

	<? $ajd = date("d/m/Y"); ?>

	<form action="edit_agent_modif.php?id=<? echo $id;?>" method="post" name="gestionagent" onsubmit="return VerifFormGestionAgent();">
		<div id="liste">
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">

					<tr>
						<td width="25%">
							Pr&eacute;nom
						</td>
						<td>
							<input type="text" name="fprenom" size="30" value="<?=$prenom;?>">
						</td>
					</tr>
					<tr>
						<td>
							Nom
						</td>
						<td>
							<input type="text" name="fnom" size="30" value="<?=$nom;?>">
						</td>
					</tr>
					<tr>
						<td>
							Organisme
						</td>
						<td>
							<input type="text" name="forganisme" value="<?=$organisme;?>" size="30">
						</td>
					</tr>
					<tr>
						<td>
							Email
						</td>
						<td>
							<input type="text" name="femail" size="30" value="<?=$email;?>">
						</td>
					</tr>
					<tr>
						<td>
							Nom d'utilisateur
						</td>
						<td>
							<input type="text" name="flogin" size="30" value="<?=$login;?>"> 
							<span class="commentaire">prenom.nom est conseill&eacute; (sans accent et sans majuscule).</span>
						</td>
					</tr>
					<tr>
						<td>Droits</td>
						<td>
							<select name="fdroits">
								<option value="">...</option>
									<?
										//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
										$sql_infr = "SELECT id_droit, nom_droit
										FROM utilisateurs.bib_droits
										ORDER BY id_droit";
										$result = pg_query($sql_infr) or die ("Erreur requête") ;
										while ($valeur = pg_fetch_assoc($result)){
									?>
									<!--  Stocker l'id correspondant à la valeur selectionnée. -->
								<option value="<?=$valeur['id_droit'];?>" <?php if ($droit == $valeur['id_droit']) : ?>selected <? endif ; ?>><?=$valeur['nom_droit'];?>
								</option>
									<? } ?>
									

							</select>		
						</td>
					</tr>
				</table>
			</div>
			<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">
				<tr>
					<td colspan="2">
							<table width="100%" border="0"  cellspacing = "0" cellpadding="2" align="center">
								<tr>
									<td>
										<a href="agents_liste.php"><img src="images/icones/retour.gif" alt="Annuler" title="Annuler" border="0" align="absmiddle"> Annuler</a>
									</td>
									<td align="right">Enregistrer 
										<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/suivant.gif" alt="Enregistrer" title="Enregistrer" border="0" align="absmiddle">
									</td>
								</tr>
							</table>
					</td>
				</tr>
			</table>
		</div>
	</form>

<?
pg_close($dbconn);
?>
		<!-- Fermer le div contenu -->
		</div>
		<div id="bottom"></div>
	<!-- Fermer le div conteneur -->
	</div>
	
<? include "menu_pied.php" ?>
</body>
</html>