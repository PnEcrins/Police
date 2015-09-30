<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
//correction des magic_quotes_gpc (protection des chaînes de caractères)
$prenomutilisateur = pg_escape_string($_POST[fprenom]);
$nomutilisateur = pg_escape_string($_POST[fnom]);
$organisme = pg_escape_string($_POST[forganisme]);
$query= "INSERT INTO interventions.bib_agents (prenomutilisateur, nomutilisateur, organisme, email, login, pass, droit_id, assermentes, enposte) 
VALUES('$prenomutilisateur','$nomutilisateur','$organisme','$_POST[femail]','$_POST[futilisateur]',md5('$_POST[fpass]'),'$_POST[fdroits]','$_POST[fassermente]','$_POST[fenposte]')";
		
pg_query($query) or die( "Erreur requete" );

pg_close($dbconn);

header("Location: agents_liste.php?agentajoute=$_POST[fprenom] $_POST[fnom]");

}

else { 

}
?>

	<? include "header_front.php" ?>
				
		<!-- Javascript permettant de vérifier que les champs obligatoires sont bien remplis -->
		<script type="text/javascript" src="js/forms_verifications.js"></script> 
		
		  
	<title>Police du <? echo $etablissement_abv; ?> - Ajouter un agent</title>
</head>


	
	<? include "menu_general.php" ?>

			<div id="news">
				<h1>
					<img src="images/icones/ajouter.gif" alt="Ajouter une intervention" title="Ajouter une intervention" border="0" align="absmiddle"> 
					Ajouter un agent
				</h1>
			</div>

	<? $ajd = date("d/m/Y"); ?>

	<form action="edit_agent_ajout.php" method="post" name="gestionagent" onsubmit="return VerifFormGestionAgent();">
		<div id="liste">
			<div class="blocos">
				<table width="100%" border="0" cellspacing="5px" cellpadding="5px" align="center">

					<tr>
						<td width="25%">
							Pr&eacute;nom
						</td>
						<td>
							<input type="text" name="fprenom" size="30">
						</td>
					</tr>
					<tr>
						<td>
							Nom
						</td>
						<td>
							<input type="text" name="fnom" size="30">
						</td>
					</tr>
					<tr>
						<td>
							Organisme
						</td>
						<td>
							<input type="text" name="forganisme" value="<?=$etablissement;?>" size="30">
						</td>
					</tr>
					<tr>
						<td>
							Email
						</td>
						<td>
							<input type="text" name="femail" size="30">
						</td>
					</tr>
					<tr>
						<td>
							Nom d'utilisateur
						</td>
						<td>
							<input type="text" name="futilisateur" size="30"> <span class="commentaire">prenom.nom est conseill&eacute; (sans accent et sans majuscule).</span>
						</td>
					</tr>
					<tr>
						<td>
							Mot de passe
						</td>
						<td>
							<input type="text" name="fpass" size="30">
						</td>
					</tr>
					<tr>
						<td>Droits</td>
						<td>
							<select name="fdroits">
								<option value="">...</option>
									<?
										//Declarer et executer une requete permettant de lister les enregistrements d'une table secondaire liée pour renseigner la liste déroulante
										$sql_infr = "SELECT id_droit, droit
										FROM interventions.bib_droits
										ORDER BY id_droit";
										$result = pg_query($sql_infr) or die ("Erreur requête") ;
										while ($val = pg_fetch_assoc($result)){
									?>
									<!--  Stocker l'id correspondant à la valeur selectionnée. -->
								<option value="<?=$val['id_droit'];?>"><?=$val['droit']?></option>
									<? } ?>
							</select>		
						</td>
					</tr>
					<tr>
						<td>Asserment&eacute;</td>
						<td>
							<input type="radio" name="fassermente" value="TRUE" checked>Oui
							<input type="radio" name="fassermente" value="FALSE">Non
						</td>
					</tr>
					<tr>
						<td>En poste</td>
						<td>
							<input type="radio" name="fenposte" value="TRUE" checked>Oui
							<input type="radio" name="fenposte" value="FALSE">Non
							<br/>
							<span class="commentaire">Permet de garder un agent dans la base sans le faire afficher dans les listes d&eacute;roulantes des auteurs</span>
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