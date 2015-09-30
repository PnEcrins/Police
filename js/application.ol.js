

create_ol = function(xCentre,yCentre,zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY,marqueur) {
    
	var createMap = function(zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY) {
        
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
		this.carte = new OpenLayers.Map('map_1',options);

		carte.addLayers([fonds, reserves, coeur]);

		return carte;
	};
	
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
        var l = document.getElementById('fcomm1').length;
		for (i=0; i<l; i++)
		{
		  var svalue= document.getElementById('fcomm1').options[i].value;
		  if (svalue == val1) {
			document.getElementById('fcomm1').selectedIndex=i;
		  }
		}
        //secteurs
        var s = document.getElementById('fsect1').length;
		for (i=0; i<s; i++)
		{
		  var svalue= document.getElementById('fsect1').options[i].value;
		  if (svalue == val5) {
			document.getElementById('fsect1').selectedIndex=i;
		  }
		}
        //statut de la zone
		var l = document.getElementById('fstatut1').length;
		for (i=0; i<l; i++)
		{
		  var svalue= document.getElementById('fstatut1').options[i].value;
		  if (svalue == val2) {
			document.getElementById('fstatut1').selectedIndex=i;
		  }
		}
		document.getElementById('fcomm').value = val1 ;
		document.getElementById('fsect').value = val5 ;
		document.getElementById('fstatut').value = val2 ;
		// document.getElementById('longEnd').value = val3 ;
		// document.getElementById('latEnd').value = val4 ;
        document.getElementById('longEnd').value = val3 ;
		document.getElementById('latEnd').value = val4 ;
        document.getElementById('commentairex').innerHTML = ' - Saisir vos coordonnées en degrés décimaux ou cliquer sur la carte' ;
        document.getElementById('commentairey').innerHTML = ' - Saisir vos coordonnées en degrés décimaux  ou cliquer sur la carte' ;
	}
	

	return{
		init: function(xCentre,yCentre,zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY,marqueur){
            this.testmove = true;
            
			createMap(zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY);
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
                    // if(this.intervention.features[1]){this.intervention.removeFeatures(this.intervention.features[0])};//s'il y a déjà une géométrie, on la supprime pour ne garder que celle qui vient d'être ajoutée
                    
                    // alert("You clicked near " + lonlat.lat + " N, " +
                                              // + lonlat.lon + " E");
                }

            });
            var click = new OpenLayers.Control.Click();
            carte.addControl(click);
            click.activate();
            
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
                document.getElementById('commentairey').innerHTML = 'Coordonées en X non valide ou hors zone autorisée' ;
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