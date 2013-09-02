var map, vector_layer, select, popup;
 var request=[];
 function init(){ 
 
 //var gjson =$.load("http://localhost:8599/Joomla25sp/index.php?option=com_geoi&task=geojson");

	//var gjson = getGeojson();
 
 map = new OpenLayers.Map('map-id',{
                    controls: [
                        new OpenLayers.Control.Navigation(),
                        new OpenLayers.Control.PanZoomBar(),
                        new OpenLayers.Control.LayerSwitcher({'ascending':false}),
                        new OpenLayers.Control.ScaleLine(),
                        new OpenLayers.Control.MousePosition(),
                        new OpenLayers.Control.OverviewMap(),
                        new OpenLayers.Control.KeyboardDefaults()
                    ],
                    numZoomLevels: 10,
                    projection: new OpenLayers.Projection("EPSG:3857"),
					displayProjection: new OpenLayers.Projection("EPSG:4326"), 
	                    eventListeners: {
	                    	"zoomend":popupClear
	                        //"zoomend": mapEvent,
	                        //"changelayer": mapLayerChanged,
	                        //"changebaselayer": mapBaseLayerChanged
	                    }
                    
                });
		var osm = new OpenLayers.Layer.OSM();
		var gmap = new OpenLayers.Layer.Google("Google Streets", {visibility: false});
		map.addLayers([osm, gmap]);
		var bounds = new OpenLayers.Bounds(-8279888.2058829,483769.94506356,-8203451.1776083,560206.9733381); 
        map.zoomToExtent(bounds);
		        

		strategy = new OpenLayers.Strategy.Cluster();
		strategy.distance=50;
		strategy.threshold = 2;

		vector_layer = new OpenLayers.Layer.Vector("Ofertas", {	strategies: [strategy]	, minScale: 50000});
		//, maxScale: 10000, minScale: 50000
		
		var defaultStyle = new OpenLayers.Style({
            pointRadius: 10,
            label: "${type}",
            fontColor:"blue",
            fontSize:"12",
            fontWeight: "bold",
            labelOutlineColor: "white",
            labelOutlineWidth: 3,
            externalGraphic: 'media/com_geoi/images/home.png'
        }, {
        	context: 
        	{ type: function(vector_layer) {
        		if (isNaN(vector_layer.attributes.count)){
        		return "";}
        		else
        			return vector_layer.attributes.count;
        		}
            }
        });
		
		var selectStyle = new OpenLayers.Style({pointRadius: "20"});
		
		var stylegeojson = new OpenLayers.StyleMap({'default': defaultStyle,'select': selectStyle});

		vector_layer.styleMap= stylegeojson;
		var geojson_format = new OpenLayers.Format.GeoJSON();
        //vector_layer.addFeatures(geojson_format.read(featurecollection));
       	map.addLayers([vector_layer]);
		//reDrawGeojson();
		//vector_layer.drawFeature();
		//vector_layer.refresh();
		//var proj=map.getProjection();
		//OpenLayers.Util.getElement("prj").innerHTML = proj;
		
		//select = new OpenLayers.Control.SelectFeature(vector_layer,{hover:true});
       	select = new OpenLayers.Control.SelectFeature(vector_layer);
        vector_layer.events.on({
                "featureselected": onFeatureSelect,
                "featureunselected": onFeatureUnselect,
				"moveend":reDrawGeojson
            });
		map.addControl(select);
		select.activate(); 
 		//map.events.register('zoomstart', {center:map.center,zoom:map.zoom}, onZoomStart);
		//alert(map.controls[7].id.toString());
		
}

function onPopupClose() {
	select.unselectAll();
	
        }

function onFeatureSelect(event) {
	        var feature = event.feature;
            var cfeatures = feature.cluster;
            var cluster = event.feature.cluster;
            //alert (cluster.length);
			var content = "";
			//alert (getAttributesbyID("1,2,3"));
			var pjson = feature.attributes;
			var oids="";
			if(!feature.cluster) // if not cluster
		    {
				for (var key in pjson) { 
					if(key=="oid"){
						oids=oids+pjson[key];
					}
				}
		    } 
		    else
		    {        
		    	content = content + '<dl class="accordion">';
		    	for (i=0;i<cfeatures.length; i++ ) { 
		    		var pjson2=cfeatures[i].attributes;
		    		//content = content +'<dt><b>'+(i+1)+'</b></dt><dd>';
		    		for (var key in pjson2) { 
		    			if(key=="oid"){
		    				if(cfeatures[i]==cfeatures[cfeatures.length-1]){oids=oids+pjson2[key];}
		    				else{	oids=oids+pjson2[key]+",";	}
		    				
		    				}
						}
		    		//content = content +"</dd>";

				}
		    	//content = content + '</dl>';
            }
    		
            var attr=getAttributesbyID(oids);
            //alert (JSON.stringify(getAttributesbyID(oids)));
            var conta=0;
            for (var key in attr) { 
            	//content = content + "<b>" +key+": </b>" + pjson2[key]+"<br>";	
            	if (attr.length>1){
            		content = content +'<dt><b>'+(conta+1)+'</b></dt><dd>';
            		//content = content + "<b>" +key+": </b>" + attr2[key]+"<br>";
            		$.each( attr[key], function(k, v){
	            		content=content+ "<b>" + k + "</b>: " + v +"<br>";
	            		});
            		//content=content+"<br>"
            		content = content +"</dd>";
            	}
            	else{
            		$.each( attr[key], function(k, v){
            			content=content+ "<b>" + k + "</b>: " + v +"<br>";
	            		});
            	}
            	conta++;
            	
            }
            //alert (attr[0].Precio);
			//alert(oids);
            content = content + "<br>";
            if (content.search("<script") != -1) {
                content = "Content contained Javascript! Escaped content below.<br>" + content.replace(/</g, "&lt;");
            }
            popup = new OpenLayers.Popup.FramedCloud("chicken", 
                                     feature.geometry.getBounds().getCenterLonLat(),
                                     new OpenLayers.Size(50,50),
                                     content,
                                     null, true, onPopupClose);
           
            feature.popup = popup;
            vector_layer.events.un({"moveend":reDrawGeojson});
            map.addPopup(popup);
            map.controls[0].deactivate();
            //vector_layer.events.on({"moveend":reDrawGeojson	});
        }
        
function onFeatureUnselect(event) {
            var feature = event.feature;
            if (typeof feature != 'undefined'){
	            if(feature.popup) {
	                map.removePopup(feature.popup);
	                feature.popup.destroy();
	                delete feature.popup;
					//vector_layer.events.on({"moveend":reDrawGeojson	});
	            }
	            select.unselectAll();
	            map.controls[0].activate();
	            vector_layer.events.on({"moveend":reDrawGeojson	});
	            
            }
			}
	
function getGeojson(){
		
	 var extent = map.getExtent();
		
	 var url =document.URL + '&task=geojson&bbox='+extent.toGeometry();
	 request.push($.parseJSON($.ajax({
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  request[request.length-1];
	
	}

function getAttributesbyID(idList){
	
	 var extent = map.getExtent();
		
	 var url =document.URL + '&task=geojson&task=GetAttributes&idlist='+idList;
	 req=($.parseJSON($.ajax({
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  req;
	
	}
	
function reDrawGeojson(event) {
	 				//vector_layer.getFeaturesByAttribute("id sub",)
                    
					//var pjson = vector_layer.features.attributes;
					//vector_layer.eraseFeatures();
					var featurecollection = getGeojson();
					var geojson_format = new OpenLayers.Format.GeoJSON();
					//var read = geojson_format.parseFeature(featurecollection);
					//alert (typeof(featurecollection));
					//alert(vector_layer.strategies[0].features.length);
/*					var oldreq = [];
					var oldgeoson=request[request.length-2];
					var newreq=[];
					for(i=0;i<featurecollection.features.length;i++){
						newreq[i]=featurecollection.features[1].properties.oid;
					}
					if(typeof(oldgeoson)!="undefined"){
						for(i=0;i<oldgeoson.features.length;i++){
							 oldreq[i]=oldgeoson.features[1].properties.oid;
						}
					}
					//alert ("old:"+oldreq.length);
					//alert ("new:"+newreq.length);
					///FEATURE IGUALES
					var add = [];
					var same = [];
					var del = [];
					var cont = 0;
					for(i=0;i<newreq.length;i++){
						for(j=0;j<oldreq.length;j++){
						if (oldreq[j]==newreq[i]){
							//alert(oldreq[i]);
							same[cont]=newreq[i];
							cont ++;
							break;
						
						}
						}
					}
					cont = 0;
					for (i=0;i<oldreq.length;i++){
						for(j=0;j<same.length;j++){
							if (oldreq[i]!=same[j]){
								del[cont]=oldreq[i];
								cont ++;
								break;
							}
						}						
					}
*/
					//alert ("borrar:"+del.length);
					//alert("nuevos:"+newreq.length+" viejos:"+oldreq.length+" iguales:"+same.length+" borrar:"+del.length);
					//alert(featurecollection.type);
					//var pjson = vector_layer.features.attributes;

					//alert ("XXXXXXXXXX");
					//alert(geojson_read.properties.);
					var geojson_read=geojson_format.read(featurecollection);
					//alert(geojson_read);
					vector_layer.removeAllFeatures();
					vector_layer.addFeatures(geojson_read);
					//alert(map.getExtent())
					
                }

function popupClear() {
    //alert('number of popups '+map.popups.length);
    while( map.popups.length ) {
         map.removePopup(map.popups[0]);
    }
    //var x="";
    //for(i=0;i<map.controls.length;i++){
    //	x=x+" , "+map.controls[i].id.toString();
    //}
    //alert(x);

    
}

function UnselectAllFeatures(){
	//alert ("XXXXX");
    if(typeof(vector_layer)!="undefined"){
    	//alert ("xx"+vector_layer.selectedFeatures.length);
	    if (vector_layer.selectedFeatures.length>0){
			    if(typeof(map.controls[7])!="undefined"){
			    	map.controls[7].unselectAll();
			    	vector_layer.events.on({"moveend":reDrawGeojson	});
			    }
	    }
    }
}


/*<script type="text/javascript">
$(document).ready(function($) {
    
	  var allPanels = $('.accordion > dd').hide();
	    
	  $('.accordion > dt > a').click(function() {
	    allPanels.slideUp();
	    $(this).parent().next().slideDown();
	    return false;
	  });

	})(jQuery); 
//</script> 
*/
