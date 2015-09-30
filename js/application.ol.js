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
			,{layers: 'fonds'}
			,{isBaseLayer: true}
		);
        // this.orthotms = new OpenLayers.Layer.TMS(
			// "MTS pne"
			// ,'http://5.135.42.177/rastercache/tms/'
			// ,{layersname: 'Orthopne_EPSG2154'tileSize: new Openlayers.Size(256,256), type:'jpeg', }
			// ,{isBaseLayer: true}
		// );
		this.coeur = new OpenLayers.Layer.WMS(
			"coeur"
			,wmsUrl
			,{layers: 'coeur',transparent: 'true'}
			,{isBaseLayer: false}
		);
		this.reserves = new OpenLayers.Layer.WMS(
			"reserves"
			,wmsUrl
			,{layers: 'reserves',transparent: 'true'}
			,{isBaseLayer: false}
		);
		this.carte = new OpenLayers.Map('map_1',options);

		carte.addLayers([ fonds, reserves, coeur]);

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
		document.getElementById('longEnd').value = val3 ;
		document.getElementById('latEnd').value = val4 ;
	}
	

	return{
		init: function(xCentre,yCentre,zoom,wmsUrl,wmsProj,minX,minY,maxX,maxY,marqueur){
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
			var point = new OpenLayers.Geometry.Point(xCentre,yCentre);
			var pointFeature = new OpenLayers.Feature.Vector(point,null);
			this.intervention.addFeatures(pointFeature);
			
			// Si la variable marqueur est égale à true, alors le marqueur est déplacable.
			if (marqueur == true) {
				var drag = new OpenLayers.Control.DragFeature(this.intervention);
				carte.addControl(drag);
				drag.activate();
				
				drag.onComplete= function() {
					var x = pointFeature.geometry.x;
					var y = pointFeature.geometry.y;
					var qstr = 'x=' + escape(x) + '&y=' + escape(y);
					getTerritoire("ajax_return_territoire.php", qstr);
				}
			}

			carte.setCenter(new OpenLayers.LonLat(xCentre,yCentre),zoom,false,true);
		}
	}
	
}();