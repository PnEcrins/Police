function roundDecimal(nombre, precision){
    var precision = precision || 2;
    var tmp = Math.pow(10, precision);
    return Math.round( nombre*tmp )/tmp;
}
var ign_api_key = 'd0rd9bmgd4pk3ywnwvfnk3g6'; //clef site http://professionnels.ign.fr

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
initMap=function(){
    // on attend que les classes soient chargées
    if (checkApiLoading('initMap();',['OpenLayers','Geoportal','Geoportal.Catalogue'])===false) {
        return;
    }
    // on charge la configuration de la clef API, puis on charge l'application
    Geoportal.GeoRMHandler.getConfig([ign_api_key], null,null, {
        onContractsComplete: createMap()
    });
}
var carte;
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
                    // prefix: "Lambert 93 : x = "
                    suffix: " m"
                    ,separator: " m, y = "
                    // ,displayProjection: new OpenLayers.Projection("IGNF:LAMB93")
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
    carte.setCenter(new OpenLayers.LonLat(700000, 5594000), 15);
    
    return carte;
}
create_ol = function(xCentre,yCentre,zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY,marqueur) {
	function getTerritoire(strURL,qstr) {
	    var xmlHttpReq = false;
	    var self = this;
	    // Mozilla/Safari
	    if (window.XMLHttpRequest) {
	        self.xmlHttpReq = new XMLHttpRequest();
	    }
	    // IE
	    else if (window.ActiveXObject) {
	        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
	    }
	    self.xmlHttpReq.open('POST', strURL, true);
	    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	    self.xmlHttpReq.onreadystatechange = function() {
	        if (self.xmlHttpReq.readyState == 4) {
				 var root = self.xmlHttpReq.responseXML.documentElement;
				 var commune  = root.getElementsByTagName('val1')[0].getAttribute('valeur');
				 var statut_zone = root.getElementsByTagName('val2')[0].getAttribute('valeur');
				 var secteur = root.getElementsByTagName('val5')[0].getAttribute('valeur');
				 var valx = root.getElementsByTagName('val3')[0].getAttribute('valeur');
				 var valy = root.getElementsByTagName('val4')[0].getAttribute('valeur');
				updatePage(commune,secteur,statut_zone,valx,valy);	
	        }	
	    }
	    self.xmlHttpReq.send(qstr);
	}
 
	function updatePage(val1,val5,val2,val3,val4){
		//communes
        if(document.getElementById('fcomm1')}
            var l = document.getElementById('fcomm1').length;
            for (i=0; i<l; i++)
            {
              var svalue= document.getElementById('fcomm1').options[i].value;
              if (svalue == val1) {
                document.getElementById('fcomm1').selectedIndex=i;
              }
            }
        }
        //secteurs
        if(document.getElementById('fsect1')}
            var s = document.getElementById('fsect1').length;
            for (i=0; i<s; i++)
            {
              var svalue= document.getElementById('fsect1').options[i].value;
              if (svalue == val5) {
                document.getElementById('fsect1').selectedIndex=i;
              }
            }
        }
        //statut de la zone
        if(document.getElementById('fstatut1')}
            var l = document.getElementById('fstatut1').length;
            for (i=0; i<l; i++)
            {
              var svalue= document.getElementById('fstatut1').options[i].value;
              if (svalue == val2) {
                document.getElementById('fstatut1').selectedIndex=i;
              }
            }
        }
		if(document.getElementById('fcomm')){document.getElementById('fcomm').value = val1 ;}
		if(document.getElementById('fsect')){document.getElementById('fsect').value = val5 ;}
		if(document.getElementById('fstatut')){document.getElementById('fstatut').value = val2 ;}
        if(document.getElementById('longEnd')){document.getElementById('longEnd').value = roundDecimal(val3,6) ;}
		if(document.getElementById('latEnd')){document.getElementById('latEnd').value = roundDecimal(val4,6) ;}
        if(document.getElementById('commentairex')){document.getElementById('commentairex').innerHTML = ' - Saisir vos coordonnées en degrés décimaux ou cliquer sur la carte';}
        if(document.getElementById('commentairey')){document.getElementById('commentairey').innerHTML = ' - Saisir vos coordonnées en degrés décimaux  ou cliquer sur la carte' ;}
	}
	

	return{
		init: function(xCentre,yCentre,zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY,marqueur){
            this.testmove = true;
            
			initMap();
			var interventionsStyle= new OpenLayers.StyleMap({
				"default": new OpenLayers.Style({
					fillColor:'#8521b2',
					fillOpacity:'0.5',
					pointRadius: 8,
					strokeColor:'#00f',
					strokeWidth:2,
					graphicZIndex:1
				}),
				"select": new OpenLayers.Style({
					fillColor:'#ff0',
					fillOpacity:'0.7',
					pointRadius: 12,
					strokeColor:'#00f',
					strokeWidth:3,
					graphicZIndex:2
				})
			})
			this.intervention = new OpenLayers.Layer.Vector("intervention",{styleMap: interventionsStyle,rendererOptions: {zIndexing: true} });
			carte.addLayer(this.intervention);
            this.intervention.events.on({
                        featureadded: function(obj) {
                            var feature = obj.feature;
                            // if(map.getZoom()<15){
                                // this.intervention.removeFeatures(feature);
                                // return false; //la fonction s'arrête là
                            // }
                            if(create_ol.intervention.features[1]){create_ol.intervention.removeFeatures(create_ol.intervention.features[0])};//s'il y a déjà une géométrie, on la supprime pour ne garder que celle qui vient d'être ajoutée
                            var x = feature.geometry.x;
                            var y = feature.geometry.y;
                            var qstr = 'x=' + escape(x) + '&y=' + escape(y);
                            getTerritoire("ajax_return_territoire.php", qstr);
                        }
                        ,featuremodified: function(obj) {

                        }
                        ,featureremoved: function(obj) {

                        }
                    });
            
            OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control, {                
                defaultHandlerOptions: {
                    'single': true,
                    'double': false,
                    'pixelTolerance': 0,
                    'stopSingle': false,
                    'stopDouble': false
                },

                initialize: function(options) {
                    this.handlerOptions = OpenLayers.Util.extend(
                        {}, this.defaultHandlerOptions
                    );
                    OpenLayers.Control.prototype.initialize.apply(
                        this, arguments
                    ); 
                    this.handler = new OpenLayers.Handler.Click(
                        this, {
                            'click': this.trigger
                        }, this.handlerOptions
                    );
                }, 

                trigger: function(e) {
                    var lonlat = carte.getLonLatFromPixel(e.xy);
                    var point = new OpenLayers.Geometry.Point(lonlat.lon,lonlat.lat);
                    var pointFeature = new OpenLayers.Feature.Vector(point,null);
                    create_ol.intervention.addFeatures(pointFeature);
                }

            });
            var click = new OpenLayers.Control.Click();
            carte.addControl(click);
            click.activate();
            
            //ajouter le point de localisation d'une intervention existante
            if(marqueur){
                var initPoint = new OpenLayers.Geometry.Point(xCentre,yCentre);
                this.intervention.addFeatures(new OpenLayers.Feature.Vector(initPoint,null));
            }
            //centrer la carte avec le bon niveau de zoom
            carte.setCenter(new OpenLayers.LonLat(xCentre,yCentre),zoom,false,true);
		}
        ,putPoint: function(wms_proj,minX,minY,maxX,maxY){
            var proj_appli = new OpenLayers.Projection("EPSG:"+wms_proj);
            var projSource = new OpenLayers.Projection("EPSG:4326");
            var projDestination = carte.getProjectionObject();
            var monx = parseFloat(document.getElementById('longEnd').value);
            var mony = parseFloat(document.getElementById('latEnd').value);
            // on teste si les coordonnées fournie sont dans ls borne minX,minY,maxX,maxY fourni dans conf/paramatres.php
            var monPoint4326 = new OpenLayers.Geometry.Point(monx,mony);
            OpenLayers.Projection.transform(monPoint4326,projSource,proj_appli);
            if(monPoint4326.x < minX || monPoint4326.x > maxX || monPoint4326.x==null || isNaN(monPoint4326.x)){
                document.getElementById('commentairex').innerHTML = 'Coordonées en X non valide ou hors zone autorisée' ;
                return false;
            }
            if(monPoint4326.y < minY || monPoint4326.y > maxY || monPoint4326.y==null || isNaN(monPoint4326.y)){
                document.getElementById('commentairey').innerHTML = 'Coordonées en Y non valide ou hors zone autorisée' ;
                return false;
            }
            // on construit le point, on le transforme dans la proj de la carte et on l'ajout
            // l'évenement featureadded sur la layer gère la suppression du point éventuellement existant
            var point = new OpenLayers.Geometry.Point(monx,mony);
            OpenLayers.Projection.transform(point,projSource,projDestination);
            var pointFeature = new OpenLayers.Feature.Vector(point,null);
            create_ol.intervention.addFeatures(pointFeature);
            
        }
	}
	
}();