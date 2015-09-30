//------------------fonctions ajax de mise � jour automatique de la commune sous le pointeur d�plac�---------------------------
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
 
function getxyMarqueur() {
    var form = document.forms['intervention'];
    var valx = form.fx.value;
    var valy = form.fy.value;
    qstr = 'x=' + escape(valx) + '&y=' + escape(valy);
    // Remarque: pas de '?' avant la cha�ne de requ�te
    return qstr;
}

function updatePage(val1,val2){
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

/* Fonction intialisatrice de la cr�ation de la carte GoogleMaps avec les X et Y pour centrer le carte et positionner le marqueur ainsi que 
le facteur de zoom en parametre*/	
function create_gm(xCentre,yCentre,zoom,hostUrl,racine,marqueur,vecteur) {
	
	var map1 = new GMap2(document.getElementById("map_1"));
				// Parametres de la carte (outil de navigation, raster Google relief par d�faut, roulette de la souris activ� ...)
				  map1.setUIToDefault();
				   map1.addControl(new GMapTypeControl());
				   map1.addMapType(G_PHYSICAL_MAP);
				   map1.setMapType(G_PHYSICAL_MAP) ;
				   map1.enableScrollWheelZoom();
	// Centrage par d�faut au centre du Parc
	var center = new GLatLng(xCentre, yCentre);
	map1.setCenter(center, zoom);
	//Cr�� un marqueur d�placable
	var marker = new GMarker(center, {draggable: marqueur});

	//Ecouter les modifications � chaque d�placement du curseur et mettre � jour les champs X et Y dont d�pendent Commune et Zone.
	GEvent.addListener(marker, "dragend", function() {
		  var p = marker.getPoint();
		  x = p.x;
		  y = p.y;
		  var qstr = 'x=' + escape(x) + '&y=' + escape(y);
          getTerritoire("ajax_return_territoire.php", qstr);
	  });
	// Afficher le marqueur cr�� plus haut
	map1.addOverlay(marker);
	// Afficher le coeur du PnE (fichier KML)
		var coeurXML;
		var url= hostUrl+racine+"carto/gm-kml/coeur-wgs84.kml";
		coeurXML = new google.maps.GeoXml(url);
		map1.addOverlay(coeurXML);
		// Afficher l'AOA du PnE (fichier KML)
		var aoaXML;
		var url= hostUrl+racine+"carto/gm-kml/aoa-wgs84.kml";
		aoaXML = new google.maps.GeoXml(url);
		map1.addOverlay(aoaXML);
		// Afficher les r�serves du PnE (fichier KML)
		var reservesXML;
		var url= hostUrl+racine+"carto/gm-kml/reserves-wgs84.kml";
		reservesXML = new google.maps.GeoXml(url);
		map1.addOverlay(reservesXML);
}


