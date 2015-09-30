<?php
// PAGINATION
function paginateur($nblignes,$debut,$limite,$orderby) {

	$pages = intval($nblignes/$limite);
	if ($nblignes%$limite) $pages++;

	if ($pages > 1) {
	if ($debut>=$limite) {
	$precdebut = $debut-$limite;
	echo " <a style=\"font-size:12px;font-weight:bold;\" href=\"".$_SERVER['PHP_SELF']."?debut=".$precdebut."#\"><img src=\"images/arrow-left.gif\" border=\"0\" alt=\"\" align=\"absmiddle\" border=\"0\"></a>&nbsp;";
	}

	for ($i=1;$i<=$pages;$i++) {
	$nouvdebut=$limite*($i-1);
	if ($nouvdebut == $debut) {
	echo "<a style=\"color:#ba0000;font-size:12px;font-weight:bold;\" href=\"".$_SERVER['PHP_SELF']."?debut=".$nouvdebut."\">".$i."</a>&nbsp;";
	} else { // numero avec lien
	echo " <a style=\"font-size:12px;font-weight:bold;\" href=\"".$_SERVER['PHP_SELF']."?orderby=".$orderby."&debut=".$nouvdebut."\">".$i."</a>&nbsp;";
	}
	}

	if ($debut!=$limite*($pages-1)) {
	$nouvdebut = $debut+$limite;
	echo " <a style=\"font-size:12px;font-weight:bold;\" href=\"".$_SERVER['PHP_SELF']."?orderby=".$orderby."&debut=".$nouvdebut."\"><img src=\"images/arrow-right.gif\" border=\"0\" alt=\"\" align=\"absmiddle\" border=\"0\"></a>";
	}

	}
}

// EXECUTER UNE REQUETE
function requete( $requete ) {
	 if($resultat = pg_query( $requete )) return $resultat ;
	 erreur( "Erreur dans la requete : $requete<br>" . pg_last_error() ) ;
}

// CONVERTIR LES JOURS EN FRANCAIS
function date_fr($date){
list ($jour) = split ('[ ]', $date);

	switch($jour) { 
		case 'Monday': $jour = 'Lundi'; break; 
		case 'Tuesday': $jour = 'Mardi'; break; 
		case 'Wednesday': $jour = 'Mercredi'; break; 
		case 'Thursday': $jour = 'Jeudi'; break; 
		case 'Friday': $jour = 'Vendredi'; break; 
		case 'Saturday': $jour = 'Samedi'; break; 
		case 'Sunday': $jour = 'Dimanche'; break; 
		default: $jour =''; break; 
	  } 
	  return $jour; 
}
?>