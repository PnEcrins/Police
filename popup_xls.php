<? include "verification.php";

//préconstruction du where avec une clause toujours vraie pour n'ajouter que des 'AND'
$where = "WHERE interv.id_intervention IS NOT null";
if(isset($_GET['num']) && $_GET['num']!=null && $_GET['num']!=''){$where.= " AND interv.id_intervention = ".$_GET['num'];}
if(isset($_GET['infr']) && $_GET['infr']!=null && $_GET['infr']!=''){$where.= " AND corinf.infraction_id = ".$_GET['infr'];}
if(isset($_GET['date']) && $_GET['date']!=null && $_GET['date']!=''){$where.= " AND EXTRACT(YEAR FROM interv.date) = ".$_GET['date'];}
if(isset($_GET['sect']) && $_GET['sect']!=null && $_GET['sect']!=''){$where.= " AND interv.secteur_id = ".$_GET['sect'];}
if(isset($_GET['com']) && $_GET['com']!=null && $_GET['com']!=''){$where.= " AND interv.commune_id = ".$_GET['com'];}
if(isset($_GET['type']) && $_GET['type']!=null && $_GET['type']!=''){$where.= " AND interv.type_intervention_id = ".$_GET['type'];}
if(isset($_GET['agent']) && $_GET['agent']!=null && $_GET['agent']!=''){$where.= " AND ag.utilisateur_id = ".$_GET['agent'];}
if(isset($_GET['statut']) && $_GET['statut']!=null && $_GET['statut']!=''){$where.= " AND interv.statutzone_id = ".$_GET['statut'];}

?>
<p>
	Une intervention peut contenir plusieurs infractions.
</p>
<p>
	<a href="xls-interventions.php?where=<? echo $where;?>" title="Enregistrer la liste des interventions au format XLS">
		<img src="images/icones/excel.png" alt="Exporter la liste" border="0" align="absmiddle"> Exporter les INTERVENTIONS vers Excel (une intervention = une ligne)
	</a>
</p>
<p>
	<a href="xls-infractions.php?where=<? echo $where;?>" title="Enregistrer la liste des infractions au format XLS">
		<img src="images/icones/excel.png" alt="Exporter la liste" border="0" align="absmiddle"> Exporter les INFRACTIONS vers Excel (une infraction = une ligne)
	</a>
</p>
