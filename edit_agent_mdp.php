<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{

$query = "UPDATE interventions.bib_agents
		SET pass = md5('$_POST[fpass]')
		WHERE id_utilisateur = '$_POST[fidut]'";
	
pg_query($query) or die( "Erreur requete" );
pg_close($dbconn);

header("Location: agents_liste.php?agentmdp=$_POST[fprenomagent] $_POST[fnomagent]");

}

else { 

}
?>

			<?php
				//Declarer la requete listant les enregistrements de la table à lister,
				$query = "SELECT id_utilisateur, nomutilisateur, prenomutilisateur FROM interventions.bib_agents
				WHERE id_utilisateur = '$_GET[id]'" ; 
				//Executer la requete
				$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

				$val = pg_fetch_array($result) ;
					$id = $val['id_utilisateur'];
					$nom = $val['nomutilisateur'];
					$prenom = $val['prenomutilisateur'];
			?>
				
			

	<form action="edit_agent_mdp.php" method="post" name="agentmdp">

			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<div align="center">
							<b>Reg&eacute;n&eacute;rer le mot de passe de <? echo $prenom; ?> <? echo $nom; ?></b>
							<input type="hidden" name="fidut" value="<? echo $id; ?>">
							<input type="hidden" name="fnomagent" value="<? echo $nom; ?>">
							<input type="hidden" name="fprenomagent" value="<? echo $prenom; ?>">
						</div>
						<p class="commentaire">
							Les mots de passe sont crypt&eacute;s dans la base de donn&eacute;es et ne peuvent donc pas etre affich&eacute;s.<br/>
							Vous pouvez cependant reg&eacute;n&eacute;rer le mot de passe d'un utilisateur ce qui &eacute;crasera son ancien MDP.
						</p>
						
						<table width="500" border="0" cellpadding="10" align="center">
							<tr>
								<td width="40%">Nouveau mot de passe :</td>
								<td><input type="text" name="fpass"></td>
							</tr>
						</table>
						<br/>
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
					</td>
				</tr>
			</table>

	</form>

<?
pg_close($dbconn);
?>
