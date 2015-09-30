<?php

if ($_POST['button'] == "CONNEXION"){
    
	$login = $_POST['flogin'];
	$pass = $_POST['fpassword'];
	$passmd5 = md5($pass);
	require("conf/connecter.php");
    
	// $query = "SELECT * FROM interventions.bib_agents WHERE login = '".$login."' AND pass = '".$passmd5."'";
    $query = "SELECT a.id_role, a.nom_role, a.prenom_role,max(a.id_droit) as id_droit, a.id_application, a.id_unite
	FROM (
	(SELECT u.id_role, u.nom_role, u.prenom_role, c.id_droit, c.id_application, u.id_unite
	FROM utilisateurs.t_roles u
	JOIN utilisateurs.cor_role_droit_application c ON c.id_role = u.id_role
	WHERE u.identifiant = '".$login."' AND u.pass = '".$passmd5."' AND c.id_application = 3)
	union
	(SELECT g.id_role_utilisateur, u.nom_role, u.prenom_role, c.id_droit, c.id_application, u.id_unite
	FROM utilisateurs.t_roles u
	JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
	JOIN utilisateurs.cor_role_droit_application c ON c.id_role = g.id_role_groupe
	WHERE u.identifiant = '".$login."' AND u.pass = '".$passmd5."' AND c.id_application = 3)
	) as a
	GROUP BY a.id_role, a.nom_role, a.prenom_role,a.id_application,a.id_unite
	LIMIT 1";
	$sql = pg_query($query) or die ("Erreur requête01") ;
	$verif = pg_numrows($sql);
	$dat = pg_fetch_assoc($sql);
	$droits = $dat['id_droit'];
    
		if ($verif == "1" AND $droits > "0")
		{
		session_start();
			if (isset($_POST['flogin'])){
			$_SESSION['xlogin'] = $login;
			$_SESSION['xauthor'] = $dat['id_role'];
			$dernieracces = date("Y-m-d");
			// $query = ("UPDATE interventions.bib_agents SET session_police = '".session_id()."', dernieracces_police = '".$dernieracces."' WHERE id_utilisateur = '".$dat['id_role']."'");
			// $sql_update = pg_query($query) or die ("Erreur requête") ;
            $query = ("UPDATE utilisateurs.t_roles SET session_appli = '".session_id()."' WHERE id_role = '".$dat['id_role']."'");
			$sql_update = pg_query($query) or die ("Erreur requête") ;
			pg_close($dbconn);
			header("Location: accueil.php");
			}
		}
		
		else{
		$erreur='<img src="images/icones/supprimer.gif" alt="" align="absmiddle">&nbsp;Login ou mot de passe incorrect';
		}
}
else{
	session_start();
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
	    setcookie(session_name(), '', time()-42000, '/');
	}
	session_destroy();
}
?>

<?
	$an = date("Y");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<? require ("conf/parametres.php"); ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Police du <? echo $etablissement_abv; ?> - Identification</title>
<style type="text/css">
<!--
body {
	background-repeat: repeat-x;
	background-color: #D7E3B5;
	font-family: Trebuchet MS;
	font-weight: normal;
	font-size: 10pt;
}
-->
</style>
</head>
<body>
<form name="formlogin" method="post" action="index.php">
  <p>&nbsp;</p>
  <table width="300" border="0" cellspacing="0" cellpadding="0" bgcolor="#f0f0f0" valign="center" align="center">
<tr>
	<td colspan="2" align="center" bgcolor="#740160"><img src="images/logo_etablissement.png" alt="Parc national des Ecrins" border="1" style="border-color:#f0f0f0"></td>
</tr>
</table>
 <table width="300" border="0" cellspacing="2" cellpadding="10" bgcolor="#f0f0f0" align="center">
	<tr>
		<td colspan="2" bgcolor="#FCFDAF" align="center">
			<span class="Style1"><b>IDENTIFICATION</b></span>
		</td>
	</tr>
  <? if ($erreur){ ?>
  <tr><td colspan="2" class="Style1"><?=$erreur;?></td></tr>
  <? } ?>

  <tr>
    <td valign="top">Utilisateur</td>
    <td><span id="vlogin"><input type="text" class="Style2" id="login" name="flogin" value="<?=$login;?>" size="25"></span>
	</td>
  </tr>
  <tr>
    <td valign="top">Mot de passe</td>
    <td><span id="vpassword"><input type="password" class="Style2" id="password" name="fpassword" value="<?=$pass;?>" size="25"></span>
	</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="button" id="button" value="CONNEXION">    </td>
  </tr>
  <tr>
  	<td colspan="2" bgcolor="#A9A7A8" align="center"><span class="Style4">2009 - <? echo $an ?> - <?=$etablissement;?></span></td>
  </tr>
</table>
</form>
</body>
</html>