<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
	
$qverif = "SELECT utilisateur_id FROM interventions.cor_interventions_agents
		WHERE utilisateur_id = '$_POST[fagent]'";
$verif = pg_query($qverif) or die ('Échec requête : ' . pg_last_error()) ;
$nombreint = pg_numrows($verif);

if ($nombreint==0) {

$query = "DELETE FROM interventions.bib_agents
		WHERE id_utilisateur = '$_POST[fagent]'";
	
pg_query($query) or die( "Erreur requete" );
pg_close($dbconn);

header("Location: agents_liste.php?agentsuppr=$_POST[fprenomagent] $_POST[fnomagent]");

}
else
{

	header("Location: agents_liste.php?agentnosuppr=$_POST[fprenomagent] $_POST[fnomagent]");
	
}

}

else { 

}
?>

			<?php
				//Declarer la requete listant les enregistrements de la table à lister,
				$query = "SELECT * FROM interventions.bib_agents
				WHERE id_utilisateur = '$_GET[id]'" ; 
				//Executer la requete
				$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;

				$val = pg_fetch_array($result) ;
					$id = $val['id_utilisateur'];
					$nom = $val['nomutilisateur'];
					$prenom = $val['prenomutilisateur'];
			?>
				
			

	<form action="edit_agent_supprime.php" method="post" name="agentsuppr">

			<table cellpadding="0" cellspacing="0" border="0" align="center">
				<tr>
					<td>
						<div align="center">
							<b>Supprimer l'agent <? echo $prenom; ?> <? echo $nom; ?> ?</b>
							<input type="hidden" name="fagent" value="<? echo $id; ?>">
							<input type="hidden" name="fnomagent" value="<? echo $nom; ?>">
							<input type="hidden" name="fprenomagent" value="<? echo $prenom; ?>">
						</div>
						<br/>
						<span class="commentaire">Seuls les agents qui ne sont associ&eacute;s &agrave; aucune intervention peuvent &ecirc;tre supprim&eacute;s</span>
						<br/><br/>
						<span align="right">Supprimer 
							<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/supprimer.gif" alt="Supprimer" title="Supprimer" border="0" align="absmiddle">
						</span>
					</td>
				</tr>
			</table>

	</form>

<?
pg_close($dbconn);
?>
