<? include "verification.php" ?>
<?php
//if ($_POST['Submit'] == "OK")
if (isset($_POST['Submit']) || isset($_POST['Submit_x']))
{
	$idinterv = $_POST[finterv];
	$queryverif= "SELECT intervention_id, utilisateur_id FROM interventions.cor_interventions_agents
		WHERE intervention_id='$_POST[finterv]' AND utilisateur_id='$_POST[fagent]'";
		$resultverif = pg_query($queryverif) or die( "Erreur requete" );
		$verif = pg_numrows($resultverif);			
				
	if ($verif == '1')
	{
		header("Location: edit_intervention_agent_ajout.php?id=$idinterv&message=1");
	}
	else 
	{
		$query= "INSERT INTO interventions.cor_interventions_agents (intervention_id, utilisateur_id) 
		VALUES('$_POST[finterv]','$_POST[fagent]')";
		pg_query($query) or die( "Erreur requete" );
	
	pg_close($dbconn);
	header("Location: edit_intervention_agent_ajout.php?id=$idinterv");
	}
}

?>

	<? include "header_front.php" ?>
	<script src="js/forms_verifications.js"></script> 
	<title>Police du <? echo $etablissement_abv; ?> - Ajouter une intervention</title>
</head>
<body>
<? $idinterv = $_GET[id]; ?>
<? include "menu_general.php" ?>
<?
//Declarer la requete listant les enregistrements de la table à lister,
$query1 = "SELECT id_intervention, date, commune FROM interventions.t_interventions
LEFT JOIN layers.l_communes ON id_commune = commune_id
WHERE id_intervention = '$idinterv'";
//Executer la requete
$result1 = pg_query($query1) or die ('Échec requête : ' . pg_last_error()) ;
//Compter le nombre d'enregistrements renvoyés par la requete
$val1 = pg_fetch_assoc($result1);
$idint = $val1['id_intervention'];
$date = $val1['date'];
$comm = $val1['commune'];
?>

			<div id="news">
				<h1>
					<img src="images/icones/ajouter.gif" alt="Ajouter une intervention" title="Ajouter une intervention" border="0" align="absmiddle"> 
					Ajouter les agents de l'intervention <? echo $idint; ?> du <? echo $date; ?>
				</h1>
			</div>

<?
//modifier lors de la migration vers usershub
$query = "SELECT i.id_intervention, u.nom_role AS nom_role, u.prenom_role AS prenom_role
FROM interventions.t_interventions i
JOIN interventions.cor_interventions_agents cia ON cia.intervention_id = i.id_intervention
JOIN utilisateurs.t_roles u ON u.id_role = cia.utilisateur_id
WHERE i.id_intervention = '$idinterv'
ORDER BY u.nom_role";
//Executer la requete
$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
//Compter le nombre d'enregistrements renvoyés par la requete
$nombreagent = pg_numrows($result);	
?>		
			
			
<div id="liste">
		    Etape 1 - Renseignements g&eacute;n&eacute;raux | Etape 2 - Infractions | <b>ETAPE 3 - AGENTS</b>
			<hr color="#dcdcdc" > 
			<table width="800px" border="0" cellspacing="5px" cellpadding="5px" align="center">
		        <?  if ($nombreagent > 0){ ?>
					<tr>
						<td class="Col1liste" width="100%" colspan="2" align="left">Agent(s) pr&eacute;sent(s)</td>
					</tr>
				<?  
				while ($val = pg_fetch_assoc($result)) 
				{
				$agent = $val['nom_role'].' '.$val['prenom_role'];
				$id = $val['id_intervention'];
				?>
					<tr>
						<td width="100%" align="left" colspan="2"><?echo $agent;?></td>
					</tr>
					<? } ?>
				<? }else{ ?>
					<tr>
						<td height="40" colspan="2" class="commentaire">Plusieurs agents peuvent &ecirc;tre pr&eacute;sents pour une m&ecirc;me intervention</td>
					</tr>
				<? } ?>
			
			<form action="edit_intervention_agent_ajout.php" method="post" name="agent" onsubmit="return VerifFormAgent();">
				
				<tr>
					<td colspan="2" class="Col1liste">
						Ajouter un agent pour cette intervention
					</td>
				</tr>
				<tr>
					<td width="20%">Agent</td>
					<td>
						<select name="fagent">
							<option value="">...</option>
								<?
                                    //modifier lors de la migration vers usershub
									$sql_agent = "SELECT a.* FROM 
                                                    (
                                                        (SELECT u.id_role AS id_utilisateur, u.nom_role, u.prenom_role
                                                        FROM utilisateurs.t_roles u
                                                        JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
                                                        JOIN utilisateurs.cor_role_menu crm ON crm.id_role = g.id_role_groupe
                                                        WHERE crm.id_menu = 14)
                                                        UNION
                                                        (SELECT u.id_role AS id_utilisateur, u.nom_role, u.prenom_role
                                                        FROM utilisateurs.t_roles u
                                                        JOIN utilisateurs.cor_role_menu crm ON crm.id_role = u.id_role
                                                        WHERE crm.id_menu = 14
                                                        AND u.groupe = false
                                                        )
                                                    ) a
                                                    ORDER BY a.nom_role";
									$result = pg_query($sql_agent) or die ("Erreur requête") ;
									while ($val = pg_fetch_assoc($result)){
								?>
							<option value="<?=$val['id_utilisateur'];?>"><?=$val['nom_role'].' '.' '.$val['prenom_role'];?></option>
								<? } ?>
						</select>
						<input type="hidden" name="finterv" value="<? echo $idinterv; ?>">						
					</td>
		        </tr>
				<? $message = $_GET[message];
				if ($message == '1')
				{?>
				<tr>
					<td colspan = "2" class="alerte">Attention ! Doublon, cet agent a d&eacute;j&agrave; &eacute;t&eacute; ajout&eacute;.</td>
				</tr>
				<?}
				?>
				<tr>
					<td></td>
					<td align="left">Ajouter l'agent
						<input name="Submit" id="Submit" value="OK" type="image" src = "images/icones/ajouter.gif" alt="Enregistrer" title="Enregistrer" border="0" align="absmiddle">
					</td>
				</tr>
				<tr>
					<td align="right" colspan="2">
						<a href="interventions_liste.php"><img src="images/icones/suivant.gif" alt="Terminer" title="Terminer" border="0" align="absmiddle"> Terminer</a>
					</td>
				</tr>
				</form>
			</table>

</div>

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