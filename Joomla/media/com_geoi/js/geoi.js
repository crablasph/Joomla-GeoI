 
 var map, vector_layer,strategy
 function init(){ 
 
 //var gjson =$.load("http://localhost:8599/Joomla25sp/index.php?option=com_geoi&task=geojson");

	//var gjson = GetGeojson();
 
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
					displayProjection: new OpenLayers.Projection("EPSG:4326")
                    
                });
		var osm = new OpenLayers.Layer.OSM();
		var gmap = new OpenLayers.Layer.Google("Google Streets", {visibility: false});
		map.addLayers([osm, gmap]);
		var bounds = new OpenLayers.Bounds(-8279888.2058829,483769.94506356,-8203451.1776083,560206.9733381); 
        map.zoomToExtent(bounds);
		
                
        //GEOJSON
        //var featurecollection = GetGeojson();
		//alert(featurecollection);
       
        
        //var defaultStyle = new OpenLayers.Style({'pointRadius': 10,'externalGraphic': 'media/com_geoi/images/home.png'});
	    //var selectStyle = new OpenLayers.Style({'pointRadius': 20});
        //var defaultStyle = new OpenLayers.Style({'pointRadius': "${radius}",'externalGraphic': 'media/com_geoi/images/home.png'});
	    //var selectStyle = new OpenLayers.Style({'pointRadius': "${radius2}"});
		//var stylegeojson = new OpenLayers.StyleMap({'default': defaultStyle,'select': selectStyle});
		
		//vector_layer = new OpenLayers.Layer.Vector("Geojson",{styleMap: stylegeojson}); 
		strategy = new OpenLayers.Strategy.Cluster();
		strategy.distance=50;
		strategy.threshold = null;
		//var url =document.URL + '&task=geojson&extent='+map.getExtent();
		vector_layer = new OpenLayers.Layer.Vector("Ofertas", {	strategies: [strategy]	, minScale: 50000});
		//, maxScale: 10000, minScale: 50000
		var defaultStyle = new OpenLayers.Style({
            pointRadius: 10,
            label: "${type}",
            externalGraphic: 'media/com_geoi/images/home.png'
        }, {
        	context: 
        	{ type: function(vector_layer) {return vector_layer.attributes.count;}
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
		
		select = new OpenLayers.Control.SelectFeature(vector_layer);
        vector_layer.events.on({
                "featureselected": onFeatureSelect,
                "featureunselected": onFeatureUnselect,
				"moveend":reDrawGeojson
            });
		map.addControl(select);
		select.activate();   
		
}

function onPopupClose(evt) {
            select.unselectAll();

        }

function onFeatureSelect(event) {
            var feature = event.feature;
            // Since KML is user-generated, do naive protection against
            // Javascript.
            //var content = "<h2>"+feature.attributes.iesu + "</h2>";
            //if(feature.attributes.count==1){vector_layer.strategies.deactivate;};
            //map.clearCache();
			var content = "";
			var pjson = feature.attributes;
			for (var key in pjson) { 
			content = content + "<b>" +key+": </b>" + pjson[key];
			}
			
            if (content.search("<script") != -1) {
                content = "Content contained Javascript! Escaped content below.<br>" + content.replace(/</g, "&lt;");
            }
			vector_layer.events.un({"moveend":reDrawGeojson});
            popup = new OpenLayers.Popup.FramedCloud("chicken", 
                                     feature.geometry.getBounds().getCenterLonLat(),
                                     new OpenLayers.Size(50,50),
                                     content,
                                     null, true, onPopupClose);
            feature.popup = popup;
            map.addPopup(popup);

        }
        
function onFeatureUnselect(event) {
            var feature = event.feature;
            if(feature.popup) {
                map.removePopup(feature.popup);
                feature.popup.destroy();
                delete feature.popup;
				vector_layer.events.on({"moveend":reDrawGeojson	});
            }
			}
	
function GetGeojson(){
		
	 var extent = map.getExtent();
		
	 var url =document.URL + '&task=geojson&bbox='+extent.toGeometry();
	 return $.parseJSON($.ajax({
									url:  url,
									dataType: "json", 
									async: false
								}).responseText);
	
	}
	
function reDrawGeojson(event) {
	 				//vector_layer.getFeaturesByAttribute("id sub",)
                    vector_layer.removeAllFeatures();
					//var pjson = vector_layer.features.attributes;
					//vector_layer.eraseFeatures();
					var featurecollection = GetGeojson();
					var geojson_format = new OpenLayers.Format.GeoJSON();
					var read = geojson_format.parseFeature(featurecollection);
					
					var pjson = read.attributes;
					//alert (pjson);
					for (var key in pjson) { 
						var feature = vector_layer.getFeaturesByAttribute("id sub",pjson[key]);
						console.log(feature.fid);
					}
					//alert ("XXXXXXXXXX");
					//alert(geojson_read.properties.);
					var geojson_read=geojson_format.read(featurecollection);
					//alert(geojson_read);
					vector_layer.addFeatures(geojson_read);
					//alert(map.getExtent())
					
                }
