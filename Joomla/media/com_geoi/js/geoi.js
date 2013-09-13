$( "#SearchTask" ).click(function() {
		$( "#SearchWindow" ).slideToggle();
		$( "#LoginWindow" ).hide();
	//$( "#SearchWindow" ).toggle();
			});

$( "#AuthTask" ).click(function() {
	$( "#LoginWindow" ).slideToggle();
	$( "#SearchWindow" ).hide();
		});


$( ".CloseWindow" ).click(function() {
	$(this).parent().hide();
	});


$(".SelectList").css("height", parseInt($(".SelectList option").length) *7);
$(".SelectList").css("width", parseInt($(".SelectList option").length) *15);



///CREAR CLASE DE CLUSTER POR ATRIBUTOS

OpenLayers.Strategy.AttributeCluster = OpenLayers.Class(OpenLayers.Strategy.Cluster, {
    attribute: null,
    shouldCluster: function(cluster, feature) {
        var cc_attrval = cluster.cluster[0].attributes[this.attribute];
        var fc_attrval = feature.attributes[this.attribute];
        var superProto = OpenLayers.Strategy.Cluster.prototype;
        return cc_attrval === fc_attrval && 
               superProto.shouldCluster.apply(this, arguments);
    },
    CLASS_NAME: "OpenLayers.Strategy.AttributeCluster"
});

var map, vector_layer, select, popup, pollayer, poldrawsearchcontrol;
 var request=[];
 var parameters=getMapParameters();
 function init(){ 
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
                    projection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DATA),
					displayProjection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DISP), 
	                    eventListeners: {
	                    	"zoomend":popupClear
	                    }
                });
	 
	 	//ADD DRAW POLYGON
	     pollayer = new OpenLayers.Layer.Vector( "PolygonSearch" );
	     map.addLayer(pollayer);
	     var container = document.getElementById("SearchPolDiv");
	     poldrawsearchcontrol = new OpenLayers.Control.DrawFeature( pollayer , OpenLayers.Handler.Polygon );
	     map.addControl(poldrawsearchcontrol);
     

        
		var osm = new OpenLayers.Layer.OSM();
		var gmap = new OpenLayers.Layer.Google("Google Streets", {visibility: false});
		map.addLayers([osm, gmap]);
		var str = parameters.BOUNDS;
		str=str.split(",");
		var bounds = new OpenLayers.Bounds(str[0],str[1],str[2],str[3]); 
        map.zoomToExtent(bounds);
        
        strategy =new OpenLayers.Strategy.AttributeCluster({  attribute:'type' });
		strategy.distance=parameters.CLUSTER_DISTANCE;
		//strategy.distance=10000;
		strategy.threshold =parameters.CLUSTER_THRESHOLD;

		var defaultStyle = new OpenLayers.Style({
            pointRadius: "15",
            label: "${label}",
            fontColor:"blue",
            fontSize:"8",
            fontWeight: "bold",
            labelOutlineColor: "white",
            labelOutlineWidth: 3,
            externalGraphic:"${getIco}"
        }, {
        	context: 
        	{ label: function(vector_layer) {
        		if (typeof(vector_layer.attributes.count)=='undefined'){
        		return "";}
        		else
        			return vector_layer.attributes.count;
        		},
        	getIco: function(feature){
                if (feature.attributes.type ) {
                	for(i=0;i<parameters.SYMBOLOGY_VALUES.length;i++){
            			var atn=parameters.SYMBOLOGY_VALUES[i];
            			var ico=parameters.ICON[i];
            			if(feature.attributes.type.toLowerCase()==atn){return ico;}
            		}
                } else if(feature.cluster) {
                    for (var i = 0; i < feature.cluster.length; i++) {
                    	for(j=0;j<parameters.SYMBOLOGY_VALUES.length;j++){
	                    	var atn=parameters.SYMBOLOGY_VALUES[j];
	            			var ico=parameters.ICON[j];
	            			if (feature.cluster[i].attributes.type == ""){return parameters.ICON[parameters.ICON.length - 1];}
	            			else if (feature.cluster[i].attributes.type.toLowerCase() == atn) {return ico;}
                    	}
                    }
                 }
                else {return parameters.ICON[parameters.ICON.length - 1];}
               }
            }
        });
		
		var selectStyle = new OpenLayers.Style({pointRadius: "20"});
		
		var stylegeojson = new OpenLayers.StyleMap({'default': defaultStyle,'select': selectStyle});
		vector_layer = new OpenLayers.Layer.Vector(parameters.LYR_NAME, {
			strategies: [strategy] ,
			styleMap:stylegeojson,
			minScale: parameters.MINSCALE});
       	map.addLayers([vector_layer]);
		//select = new OpenLayers.Control.SelectFeature(vector_layer,{hover:true});
       	select = new OpenLayers.Control.SelectFeature(vector_layer);
        vector_layer.events.on({
                "featureselected": onFeatureSelect,
                "featureunselected": onFeatureUnselect,
				"moveend":reDrawGeojson
            });
		map.addControl(select);
		select.activate(); 
		
		

}
 
function onPopupClose() {
	select.unselectAll();
	
        }

function onFeatureSelect(event) {
	        var feature = event.feature;
            var cfeatures = feature.cluster;
            var cluster = event.feature.cluster;
			var content = "";
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
		    	content = content + '<dl id="IndentifyAccordion" class="accordion">';
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
	 var type =parameters.SYMBOLOGY_FIELD;
	 var url =document.URL + '&task=geojson&type='+type+'&bbox='+extent.toGeometry();
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
	 var req;
	 req=($.parseJSON($.ajax({
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  req;
	
	}

function getMapParameters(){
	var url =document.URL + '&task=GetMapParameters';
	var req;
	 req=($.parseJSON($.ajax({
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  req;
}
	
function reDrawGeojson(event) {
					var featurecollection = getGeojson();
					var geojson_format = new OpenLayers.Format.GeoJSON();
					var geojson_read=geojson_format.read(featurecollection);
					vector_layer.removeAllFeatures();
					vector_layer.addFeatures(geojson_read);
                }

function popupClear() {
    while( map.popups.length ) {
         map.removePopup(map.popups[0]);
    }
}

//// FUNCIONES INTERFAZ

function polButtonClick(){
	var selico=document.getElementById("SearchPolygon").getAttribute("selected");
	if(selico=="false"){
		document.getElementById("SearchPolygon").setAttribute("src","media/com_geoi/images/pol_on.png");
		document.getElementById("SearchPolygon").setAttribute("selected","true");
		$(".SelectListPOL").prop('disabled', true);
		poldrawsearchcontrol.activate();
		
	}
	else{
		document.getElementById("SearchPolygon").setAttribute("src","media/com_geoi/images/pol_off.png");
		document.getElementById("SearchPolygon").setAttribute("selected","false");
		$(".SelectListPOL").prop('disabled', false);
		poldrawsearchcontrol.deactivate();
		pollayer.removeAllFeatures();
	}
}

function showHide(show, hide){	$(show).show();	$(hide).hide();}

function setRangeMin(name, errormsg){
	minr=document.getElementById('min'+name).value;
	//minall=document.getElementById('minbox'+name).getAttribute('min\');
	minall=this.min;
	min=document.getElementById('minbox'+name).value;
	max=document.getElementById('maxbox'+name).value;
	if(Number(min)>Number(max)||Number(min)<Number(minall)){
		alert(errormsg);
		document.getElementById('minbox'+name).value=minr;
	}else{document.getElementById('min'+name).value=min;}

}

function setRangeMax(name, errormsg){
	maxr=document.getElementById('max'+name).value;
	maxall=document.getElementById('maxbox'+name).getAttribute('max');
	min=document.getElementById('minbox'+name).value;
	max=document.getElementById('maxbox'+name).value;
	if(Number(max)<Number(min)||Number(max)>Number(maxall)){
		alert(''.$valerror);document.getElementById('maxbox'+name).value=maxr;
	}else{document.getElementById('max'+name).value=max;}
	
}

function setMinBox(name){
	$('#minbox'+name).val($('#min'+name).val()); 
	$('#minbox'+name).attr('value',$('#min'+name).val());
	//$('#max'+name).attr('value',$('#min'+name).val());
}

function setMaxBox(name){
	$('#maxbox'+name).val($('#max'+name).val()); 
	$('#maxbox'+name).attr('value',$('#max'+name).val());
	//$('#min'+name).attr('value',$('#max'+name).val());
}

function showValues(name, array){
	var position = $('#ShowValues'+name).offset();
	position['left']=position['left']+30;
	position['top']=position['top']-10;
	///alert (position.toSource());
	$('#MultiValuesWindow').css(position);
	$('#MultiValuesWindow').hide();
	document.getElementById('DataContainer').innerHTML = "";
	//$('#ShowValues'+name).toggle();
	//alert ($('#ShowValues'+name).data('open'));
	//var contentname=$('#MultiValuesWindow').data('content');
	var contentname=document.getElementById('MultiValuesWindow').getAttribute('data-content');
	var open = document.getElementById('ShowValues'+name).getAttribute('open');
	//alert (name+':'+open);
	//alert(contentname);
	if(contentname!=""){
		if(contentname!=name ){
			$('#ShowValues'+contentname).attr('src','media/com_geoi/images/rightblue.png');
			//$('#ShowValues'+contentname).attr('open','closed');
			document.getElementById('ShowValues'+contentname).setAttribute('open','closed');
			//alert ("YYYYYYYY");
		}
	}
	if(open!="open"){
		$('#MultiValuesWindow').attr('data-content',name);
		$('#ShowValues'+name).attr('src','media/com_geoi/images/rightgreen.png');		
		$('#ShowValues'+name).attr('open','open');
		$('#MultiValuesWindow').show();
		//break;
	}else {
		//alert ("XXX");
		$('#ShowValues'+name).attr('src','media/com_geoi/images/rightblue.png');		
		//$('#ShowValues'+name).attr('open','closed');
		document.getElementById('ShowValues'+name).setAttribute('open','closed');
		$('#MultiValuesWindow').attr('data-content',"");
		//break;
	}
	var content_arr=array.split(",");
	var html_content='<select class="SelectList" id="'+name+'" multiple="multiple">'
	for (i=0;i<content_arr.length;i++){ html_content=html_content+'<option value="'+content_arr[i]+'" selected>'+content_arr[i]+'</option> ';}
	html_content=html_content+"</select>";
	var div = document.getElementById('DataContainer');

	div.innerHTML = div.innerHTML + html_content;
	
}



