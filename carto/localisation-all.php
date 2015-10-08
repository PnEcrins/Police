<!-- Fichier inséré dans la page carto.php affichant les interventions par année. 
Positionne un marqueur à l'emplacement de chaque intervention de l'année selectionnée (année en cours par défaut) -->

	<div id="map_1" style="margin: 0 auto; border: 1px solid rgb(69, 69, 85); padding: 10px; width: 750px; height: 650px; background-color: rgb(229, 227, 223); color: rgb(32, 183, 255); font-size: 16px; font-weight: bold; vertical-align: middle; position: relative;">
	</div>
	
<script type="text/javascript">
//<![CDATA[
<?

	$prefix = '{"type": "FeatureCollection","features":['; 
	$debut='{"geometry":';
	$fin=',"type": "Feature","properties": {}}';
	$suffix=']}';
	//selectionner toutes les intervantions avec leur géometries
	$query = "SELECT *, ST_Asgeojson(ST_Transform(ST_SetSrid(ST_MakePoint(coord_x, coord_y),4326), '$wms_proj')) as geojson,to_char(date, 'dd/mm/yyyy') as dat  
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
		if ($val['suivi_suite_donnee'] =="") { $suivi_suite = "Non renseigné"; } 
		else { $suivi_suite = "Oui, voir détails"; }
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
var carte;
var ign_api_key = 'd0rd9bmgd4pk3ywnwvfnk3g6'; //clef site http://professionnels.ign.fr
var centre_map = new OpenLayers.LonLat(700000, 5594000)
if (window.__Geoportal$timer===undefined) {
    var __Geoportal$timer= null;
}
/**
 * Function: checkApiLoading
 * Assess that needed classes have been loaded.
 *
 * Parameters:
 * retryClbk - {Function} function to call if any of the expected classes
 * is missing.
 * clss - {Array({String})} list of classes to check.
 *
 * Returns:
 * {Boolean} true when all needed classes have been loaded, false otherwise.
 */
function checkApiLoading(retryClbk,clss) {
    var i;
    if (__Geoportal$timer!=null) {
        //clearTimeout: annule le minuteur "__Geoportal$timer" avant sa fin
        window.clearTimeout(__Geoportal$timer);
         __Geoportal$timer= null;
    }
    /**
    * Il se peut que l'init soit exécuté avant que l'API ne soit chargée
    * Ajout d'un code temporisateur qui attend 300 ms avant de relancer l'init
    */
    var f;
    for (i=0, l= clss.length; i<l; i++) {
        try {f= eval(clss[i]);} 
        catch (e) {f= undefined;}
        if (typeof(f)==='undefined') {
             __Geoportal$timer= window.setTimeout(retryClbk, 300);
            return false;
        }
    }
    return true;
}
init=function(){
    // on attend que les classes soient chargées
    if (checkApiLoading('init();',['OpenLayers','Geoportal','Geoportal.Catalogue'])===false) {
        return;
    }
    // on charge la configuration de la clef API, puis on charge l'application
    Geoportal.GeoRMHandler.getConfig([ign_api_key], null,null, {
        onContractsComplete: createMap()
    });
}
var createMap = function() {
    var ign_resolutions=[156543.03392804103,78271.5169640205,39135.75848201024,19567.879241005125,9783.939620502562,4891.969810251281,2445.9849051256406,1222.9924525628203,611.4962262814101,305.74811314070485,152.87405657035254,76.43702828517625,38.218514142588134,19.109257071294063,9.554628535647034,4.777314267823517,2.3886571339117584,1.1943285669558792,0.5971642834779396,0.29858214173896974,0.14929107086948493,0.07464553543474241];
    //-------extent pne ---------------
    var extent_max = new OpenLayers.Bounds(600000, 5500000,760000, 5720000);
    var resolution_max = 305.74811309814453;
    var i;
    var wm= new OpenLayers.Projection("EPSG:3857");
    
    carte = new OpenLayers.Map('map_1' 
        ,{
            projection: wm
            ,units: wm.getUnits()
            ,resolutions: ign_resolutions
            ,maxResolution: resolution_max
            ,maxExtent: extent_max
            ,controls:[
                new Geoportal.Control.TermsOfService()
                ,new Geoportal.Control.PermanentLogo()
                ,new OpenLayers.Control.ScaleLine()
                ,new OpenLayers.Control.MousePosition({
                    suffix: " m"
                    ,separator: " m, y = "
                    ,numDigits: 0
                    ,emptyString: ''
                })
                ,new OpenLayers.Control.KeyboardDefaults()
                ,new OpenLayers.Control.Attribution()
                ,new OpenLayers.Control.Navigation()
            ]
        }
    );
    maMap = carte;//debug

    var createBaseLayer = function() {
        var i;
        var matrixIds3857= new Array(22);
        for (i= 0; i<22; i++) {
            matrixIds3857[i]= {
                identifier    : i.toString(),
                topLeftCorner : new OpenLayers.LonLat(-20037508,20037508)
            };
        }
        var l0= new Geoportal.Layer.WMTS(
            'Cartes ign',
            'https://gpp3-wxs.ign.fr/'+ign_api_key+'/geoportail/wmts',
            {
              layer: 'GEOGRAPHICALGRIDSYSTEMS.MAPS',
              style: 'normal',
              matrixSet: "PM",
              matrixIds: matrixIds3857,
              format:'image/jpeg',
              exceptions:"text/xml"
            },
            {
              tileOrigin: new OpenLayers.LonLat(0,0),
              isBaseLayer: true,
              maxResolution: resolution_max,
              alwaysInRange: true,
              opacity : 1,
              projection: wm,
              maxExtent: extent_max,
              units: wm.getUnits(),
              attribution: 'provided by IGN'
            }
        );
        carte.addLayer(l0);
    };
    createBaseLayer();
    carte.setCenter(centre_map, 9);
    
    return carte;
}	
	init();
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
	carte.setCenter(centre_map, 9);

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
//]]>
</script>



	
	