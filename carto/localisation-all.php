<!-- Fichier inséré dans la page carto.php affichant les interventions par année. 
Positionne un marqueur à l'emplacement de chaque intervention de l'année selectionnée (année en cours par défaut) -->

	<div id="map_1" style="margin: 0 auto; border: 1px solid rgb(69, 69, 85); padding: 10px; width: 750px; height: 650px; background-color: rgb(229, 227, 223); color: rgb(32, 183, 255); font-size: 16px; font-weight: bold; vertical-align: middle; position: relative;">
	</div>
	
<script type="text/javascript">
//<![CDATA[
//////////////
//////////////
//GOOGLEMAPS//
//////////////
//////////////
<? if ($outil_carto == "gm") { ?>
/* fonction intialisatrice de la création de la carte */
function load() {
	
	var map1 = new GMap2(document.getElementById("map_1"));

				   map1.setUIToDefault();
				   map1.addControl(new GMapTypeControl());
				   map1.addMapType(G_PHYSICAL_MAP);
				   map1.setMapType(G_PHYSICAL_MAP) ;
				   map1.enableScrollWheelZoom();

	var center = new GLatLng(44.81107025326003, 6.317138671875);
	map1.setCenter(center, 10);
	
	<?
	// Lister les interventions de l'année en question
	$query = "SELECT *, to_char(date, 'dd/mm/yyyy') as dat  FROM interventions.t_interventions
    $where" ; 
	//Executer la requete
	$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
	$i = '1'
	?>
	
	// Creer un marqueur par intervention
	<? while ($val = pg_fetch_assoc($result)) 
		{
		$date = $val['dat'];
		$id = $val['id_intervention'];
		$xx = $val['coord_x'];
		$yy = $val['coord_y'];
		$idint = $val['id_intervention'];
			// Declarer la requete permettant d'afficher la ou les infractions des interventions cartographiees en plus de la date
			$queryinfr = "SELECT id_intervention, infraction, infraction_id FROM interventions.t_interventions
			LEFT JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
			LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
			WHERE id_intervention = '$idint'
			ORDER BY infraction_id";
			//Executer la requete
			$resultinfr = pg_query($queryinfr) or die ('Échec requête : ' . pg_last_error()) ;
		?>		
	var intervention<? echo $i; ?> = new GLatLng(<? echo $yy; ?>, <? echo $xx; ?>);
	var marker<? echo $i; ?> = new GMarker(intervention<? echo $i; ?>, 
		{title:"Intervention no <? echo $id; ?> du <? echo $date; ?> - <? while ($val = pg_fetch_assoc($resultinfr)) {$type = $val['infraction'];?><?echo $type;?> - <? } ?>"});
	
	map1.addOverlay(marker<? echo $i; ?>);
	// Incrementer la variable $i pour nommer chaque marqueur (marker1, marker 2, ...) correspondant chacun a une intervention.
	<? $i++ ?>
	<? } ?>
	// Afficher le coeur du PnE (fichier KML)
	var coeurXML;
	var url= "http://cartotest.ecrins-parcnational.fr/police/carto/gm-kml/coeur-pne-wgs84.kml";
	coeurXML = new google.maps.GeoXml(url);
	map1.addOverlay(coeurXML);
	// Afficher l'AOA du PnE (fichier KML)
	var aoaXML;
	var url= "http://cartotest.ecrins-parcnational.fr/police/carto/gm-kml/aoa-pne-wgs84.kml";
	aoaXML = new google.maps.GeoXml(url);
	map1.addOverlay(aoaXML);
	// Afficher les réserves du PnE (fichier KML)
	var reservesXML;
	var url= "http://cartotest.ecrins-parcnational.fr/police/carto/gm-kml/reserves-pne-wgs84.kml";
	reservesXML = new google.maps.GeoXml(url);
	map1.addOverlay(reservesXML);
}
//////////////
//////////////
//OPENLAYERS//
//////////////
//////////////
<? } elseif ($outil_carto == "ol") {
	$prefix = '{"type": "FeatureCollection","features":['; 
	$debut='{"geometry":';
	$fin=',"type": "Feature","properties": {}}';
	$suffix=']}';
	//selectionner toutes les intervantions avec leur géometries
	$query = "SELECT *, st_asgeojson(st_transform(SETSRID(MakePoint(coord_x, coord_y),4326), '$wms_proj')) as geojson,to_char(date, 'dd/mm/yyyy') as dat  
		FROM interventions.t_interventions int
		LEFT JOIN interventions.bib_types_interventions typ ON typ.id_type_intervention = int.type_intervention_id 
        $where" ;
		// WHERE extract(year from int.date)= '$cartoannee'" ; 
		$result = pg_query($query) or die ('Échec requête : ' . pg_last_error()) ;
	//bouble sur les interventions
	 while ($val = pg_fetch_assoc($result)){
		$compt++;
		$date = $val['dat'];
		$typeintervention = $val['type_intervention'];
		if ($val['suivi_suite_donnee'] =="") { $suivi_suite = "Non renseign&eacute;"; } 
		else { $suivi_suite = "Oui, voir d&eacute;tails"; }
		$xx = $val['coord_x'];
		$yy = $val['coord_y'];
		$idint = $val['id_intervention'];
		// Declarer la requete permettant d'afficher la ou les infractions des infractions cartographiees en plus de la date
		$queryinfr = "SELECT id_intervention, infraction, infraction_id FROM interventions.t_interventions
		LEFT JOIN interventions.cor_interventions_infractions ON intervention_id = id_intervention
		LEFT JOIN interventions.bib_infractions ON id_infraction = infraction_id
		WHERE id_intervention = '$idint'
		ORDER BY infraction_id";
		//Executer la requete
		$resultinfr = pg_query($queryinfr) or die ('Échec requête : ' . pg_last_error()) ;
		$nb = pg_numrows($result);
		$c=0;$virg="";$infractions = "";
		//boucle 2 sur laou les infractions de chaque intervention
		while ($val1 = pg_fetch_assoc($resultinfr)) {
			$c++;
			$inf = $val1['infraction'];
			if ($c!=1){$virg = ", ";}
			$infractions = $infractions.$virg.$inf;
		}
		$fin=',"type": "feature","id": '.$idint.',"properties": {"date": "'.$date.'","id_intervention":'.$idint.',"typeintervention": "'.$typeintervention.'","suivi_suite_donnee": "'.$suivi_suite.'","infractions": "'.$infractions.'"}}';
		$str = $debut.$val['geojson'].$fin;
		if ($compt >1){$virgule = ",";}
		$geojson = $geojson.$virgule.$str;
	}
	$features = $prefix.$geojson.$suffix;
	?>
	var createMap = function(wmsUrl,wmsProj,minX,minY,maxX,maxY) {
		//Variable pour les options de la map
		//Avec la taille (maxExtent), la projection
		var options = {
			maxResolution: "auto",
			numZoomLevels:16,
			projection: new OpenLayers.Projection("epsg:"+wmsProj),
			displayProjection:new OpenLayers.Projection("epsg:"+wmsProj),
			maxExtent: new OpenLayers.Bounds(minX,minY,maxX,maxY),
			units: 'm'
		};		
		this.fonds = new OpenLayers.Layer.WMS(
			"fonds"
			,wmsUrl
			,{layers: wms_fonds}
			,{isBaseLayer: true}
		);
		this.coeur = new OpenLayers.Layer.WMS(
			"coeur"
			,wmsUrl
			,{layers: wms_coeur,transparent: 'true'}
			,{isBaseLayer: false}
		);
		this.reserves = new OpenLayers.Layer.WMS(
			"reserves"
			,wmsUrl
			,{layers: wms_reserves,transparent: 'true'}
			,{isBaseLayer: false}
		);

		//var coordControl = new OpenLayers.Control.MousePosition ({suffix: ' mètres', prefix:'Coordonnées x & y en Lambert2 étendu : '}, {displayProjection:'EPSG:4326' });
		
		this.carte = new OpenLayers.Map('map_1',options);
		//carte.addControl(coordControl);
		carte.addLayers([fonds, coeur, reserves]);
		return carte;
	};	
	createMap('<?=$wms_url;?>','<?=$wms_proj;?>','<?=$min_x;?>','<?=$min_y;?>','<?=$max_x;?>','<?=$max_y;?>');
	var interventionsStyle= new OpenLayers.StyleMap({
					"default": new OpenLayers.Style({
						fillColor:'#8521b2',
						fillOpacity:'0.5',
						pointRadius:5,
						strokeColor:'#00f',
						strokeWidth:2,
						graphicZIndex:1
					}),
					"select": new OpenLayers.Style({
						fillColor:'#ff0',
						fillOpacity:'0.7',
						pointRadius: 7,
						strokeColor:'#00f',
						strokeWidth:3,
						graphicZIndex:2
					})
				})
	this.interventions = new OpenLayers.Layer.Vector("interventions",{styleMap: interventionsStyle,rendererOptions: {zIndexing: true} });
	carte.addLayer(this.interventions);	
	var featurecollection = <?=$features;?>;
	var geojson_format = new OpenLayers.Format.GeoJSON();
	this.interventions.addFeatures(geojson_format.read(featurecollection));
	carte.setCenter(new OpenLayers.LonLat(<?=$ol_x_center;?>,<?=$ol_y_center;?>),0);

	//---------------------------------------------gestion des popup's----------------------------------------------------
	// Instanciation du control selectFeature
	var options = {       
		hover: false,
		// Fait reference a la fonction popUp
		onSelect: popUP,
		onUnselect: destroyPopUP,
		//selectStyle :feature_style
	};     
	var sf = new OpenLayers.Control.SelectFeature(this.interventions, options)
	carte.addControl(sf);
	sf.activate();

	function destroyPopUP(e) {
		 if(typeof popup!='undefined'){
			 popup.destroy();
		}
	};
	 
	function popUP(e) {
		//je definis les params de mon popup
		var htmlContent = 
			"<div class='popup'><b>N&deg; :</b> "+e.attributes.id_intervention+" - <b>date :</b> "+e.attributes.date+"<br /><b>Type :</b> "+e.attributes.typeintervention+"<br /> <b>Infractions :</b> "+e.attributes.infractions+"<br /> <b>Suite donn&eacute;e :</b> "+e.attributes.suivi_suite_donnee+'<br /> <a href="fiche_intervention_carto.php?id='+e.attributes.id_intervention+'" target="_blank"><b>Afficher la fiche d&eacute;taill&eacute;e</b></a></div> ';       
		var size = new OpenLayers.Size(30,60);
		var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
		toto = OpenLayers.Class(OpenLayers.Popup.FramedCloud, {
			'contentDisplayClass': 'toto'
		});
		//j'instancie mon popup
		popup = new OpenLayers.Popup.FramedCloud(
			 e.fid,
			 e.geometry.getBounds().getCenterLonLat(),
			 null,
			 htmlContent,
			 null,
			 false,
			  null,
			  {contentDisplayClass: toto}
		);

		//Je l'ajoute a la carte
		carte.addPopup(popup); 
	}
	//----------------------------------------------fin de gestion des popup's----------------------------------------------------

<? } ?>
//]]>
</script>



	
	