<?php
session_start();
require("fonctions.php");
require("conf/connecter.php");
require ("conf/parametres.php");
//requete permettant d'extraire l'utilisateur avec son niveau de droit maximum qu'il soit dans un role-groupe et/ou en tant qu'utilisateur seul 
$requete = "SELECT a.id_role, a.nom_role, a.prenom_role, max(a.id_droit) as id_droit, a.id_application, a.id_unite
FROM
	(
        SELECT u.id_role, u.nom_role, u.prenom_role, c.id_droit, c.id_application, u.id_unite
        FROM utilisateurs.t_roles u
        JOIN utilisateurs.cor_role_droit_application c ON c.id_role = u.id_role
        WHERE u.identifiant = '".$_SESSION['xlogin']."' AND u.session_appli = '".session_id()."' AND c.id_application = 3
        union
        (SELECT g.id_role_utilisateur, u.nom_role, u.prenom_role, c.id_droit, c.id_application, u.id_unite
        FROM utilisateurs.t_roles u
        JOIN utilisateurs.cor_roles g ON g.id_role_utilisateur = u.id_role
        JOIN utilisateurs.cor_role_droit_application c ON c.id_role = g.id_role_groupe
        WHERE u.identifiant = '".$_SESSION['xlogin']."' AND u.session_appli = '".session_id()."' AND c.id_application = 3)
    ) as a
GROUP BY a.id_role, a.nom_role, a.prenom_role,a.id_application,a.id_unite
LIMIT 1";
$result = pg_query($requete) or die ("Erreur requête") ;
$verif = pg_numrows($result);
if ($verif != "1"){
	//redirection vers la page d'accueil
	header("Location: index.php");
}
else{
	while ($val = pg_fetch_assoc($result)){
		$droits = $val['id_droit'];
		$secteur = $val['id_unite'];
		$nom_observateur = $val['nom_role'];
		$prenom_observateur = $val['prenom_role'];
		$id_observateur = $val['id_role'];
	}
}
?>