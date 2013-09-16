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
	$('#MultiValuesWindow').hide();
	});

$( ".SubTitleWindow" ).click(function() {
	///
	var idelemento=$( this ).attr('id');
	element=document.getElementById(idelemento);
	var opens = document.getElementById(idelemento).getAttribute('open');
	id_nextdiv=element.nextSibling.nextSibling.nextSibling.nextSibling.id;
	//alert (id_nextdiv);
	//$( ".conattr" ).hide();
	if(opens=='closed'){
		$( this ).css("right","0px");
		$( this ).css("float","right");
		$( this ).css("color","#707070");
		$('#'+id_nextdiv).slideToggle();
		document.getElementById(idelemento).setAttribute('open','open');
	}else{
		$( this ).css("left","0px");
		$( this ).css("float","left");
		$('#'+id_nextdiv).slideToggle();
		$( this ).css("color","white");
		document.getElementById(idelemento).setAttribute('open','closed');
	}
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
	 
	 //crear escala numerica
	 ///
	 //var scaletext = document.createElement("span");
	 //scaletext.setAttribute("id", "scaletext");
	 //document.getElementById("scaletext").style.position="absolute";
	 //document.getElementById("scaletext").style.bottom=0;
	 //document.getElementById("scaletext").style.left=0;
	 //document.getElementById("scaletext").style.position=absolute;
	 //document.getElementById("scaletext").style.position=absolute;
	 map = new OpenLayers.Map('map-id',{
                    controls: [
                        new OpenLayers.Control.Navigation(),
                        new OpenLayers.Control.ZoomPanel(),
                        new OpenLayers.Control.PanPanel(), 
                        ///new OpenLayers.Control.Scale(),
                        new OpenLayers.Control.LayerSwitcher({'ascending':false}),
                        new OpenLayers.Control.KeyboardDefaults()
                    ],
                    projection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DATA),
					displayProjection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DISP), 
	                    eventListeners: {
	                    	"zoomend":popupClear
	                    }
                });
	 
	 	//ADD SEARCH DRAW POLYGON
	     pollayer = new OpenLayers.Layer.Vector( "PolygonSearch" );
	     map.addLayer(pollayer);
	     var container = document.getElementById("SearchPolDiv");
	     poldrawsearchcontrol = new OpenLayers.Control.DrawFeature( pollayer , OpenLayers.Handler.Polygon );
	     map.layers[0].displayInLayerSwitcher = false;
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

function SearchPoints(arr){
	var search_data=[];
	for(i=0;i<arr.length;i++){
		current=arr[i];
		//valor="";
		if (current[1]=='CAT'){
			valor=document.getElementById(current[0]);
			if(valor){
				search_data[i]=[];
				search_data[i][0]=current[0];
				search_data[i][1]=current[1];
				search_data[i][2]="";
				 for (var j = 0; j < valor.options.length; j++) {
					 if(valor.options[j].selected ==true){
					      //alert(valor.options[j].value);
						 search_data[i][2]=search_data[i][2]+valor.options[j].value;
					 }
				 }
			}
		}else if(current[1]=='INT'){
			///alert('minbox'+current[0]+':'+$('minbox'+current[0]).val());
			var min=document.getElementById('minbox'+current[0]);
			var max=document.getElementById('maxbox'+current[0]);
			if(min && max){
				min=min.value;
				max=max.value;
				search_data[i]=[];
				search_data[i][0]=current[0];
				search_data[i][1]=current[1];
				search_data[i][2]=min+','+max;
				//alert(search_data[i][2]);
			}
		}else if (current[1]=='POL'){
			
		}
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
	minall=document.getElementById('minbox'+name).getAttribute('min');
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
		alert(errormsg);
		document.getElementById('maxbox'+name).value=maxr;
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

function showValues(name, stringvalues, type){
	var position = $('#ShowValues'+name).offset();
	position['left']=position['left']+30;
	position['top']=position['top']-10;
	///alert (position.toSource());
	$( "#DataContainer > div" ).css( "display", "none" );
	$('#MultiValuesWindow').css(position);
	$('#MultiValuesWindow').hide();
	var contentname=document.getElementById('MultiValuesWindow').getAttribute('data-content');
	$('container_'+contentname).hide();
	var open = document.getElementById('ShowValues'+name).getAttribute('open');
	if(contentname!=""){
		if(contentname!=name ){
			$('#ShowValues'+contentname).attr('src','media/com_geoi/images/rightblue.png');
			//$('#ShowValues'+contentname).attr('open','closed');
			document.getElementById('ShowValues'+contentname).setAttribute('open','closed');
			$('container_'+contentname).css("display","none");
			//$('container_'+contentname).hide();
			//alert ("YYYYYYYY");
		}
	}
	if(open!="open"){
		$('#container_'+name).css("display","block");
		$('#MultiValuesWindow').attr('data-content',name);
		$('#ShowValues'+name).attr('src','media/com_geoi/images/rightgreen.png');		
		$('#ShowValues'+name).attr('open','open');
		//if(document.getElementById('container_'+name)){$('container_'+name).css("display","block");}
		$('#MultiValuesWindow').show();
		//break;
	}else {
		//alert ("XXX");
		//$('#DataContainer').css("visibility","hidden");
		$('container_'+name).css("display","none");
		//$('container_'+name).hide();
		$('#ShowValues'+name).attr('src','media/com_geoi/images/rightblue.png');		
		//$('#ShowValues'+name).attr('open','closed');
		document.getElementById('ShowValues'+name).setAttribute('open','closed');
		$('#MultiValuesWindow').attr('data-content',"");
		//break;
	}
	
	///mostrar contenido
	var html_content='<div id="container_'+name+'" style="display:block;">';
	var content_arr=stringvalues.split(",");
	if(type=='cat'){
			html_content=html_content+'<select class="SelectList" id="'+name+'" multiple="multiple">'
			for (i=0;i<content_arr.length;i++){ html_content=html_content+'<option value="'+content_arr[i]+'" selected>'+content_arr[i]+'</option> ';}
			html_content=html_content+"</select>";
	}else if(type=='int'){
		///alert (content_arr[0]+content_arr[1]);
		var html_content=html_content+'<div class="SliderContainer" id="'+name+'">'
		html_content=html_content+' <span class="RangeText">min:</span><input type="number" id="minbox'+name+'" class="MinBox" value="'+content_arr[0]+'"';
		html_content=html_content+' min="'+content_arr[0]+'" max="'+content_arr[1]+'" onclick="showHide( \'#min'+name+'\', \'#max'+name+'\')"';
		html_content=html_content+' onchange="setRangeMin(\''+name+'\',\'INVALID VALUE\')">';
		html_content=html_content+' <span class="RangeText">max:</span><input type="number" id="maxbox'+name+'" class="MaxBox" value="'+content_arr[1]+'"';
		html_content=html_content+' min="'+content_arr[0]+'" max="'+content_arr[1]+'" onclick="showHide( \'#max'+name+'\', \'#min'+name+'\')"';
		html_content=html_content+' onchange="setRangeMax(\''+name+'\',\'INVALID VALUE\')"><br>';
		html_content=html_content+' <input type="range" class="MinSlider" id="min'+name+'" min="'+content_arr[0]+'" max="'+content_arr[1]+'" onchange="setMinBox(\''+name+'\')">';
		html_content=html_content+' <input type="range" class="MaxSlider" id="max'+name+'" min="'+content_arr[0]+'" max="'+content_arr[1]+'" onchange="setMaxBox(\''+name+'\')">';
		html_content=html_content+' </div>';
		
////////AÑADIR EN EL CLICK AGREGAR VALORES  AL DIV DE ABAJO
		//echo '<div class="SliderContainer" id="'.$search[0].'"> ';
		//$valerror=JTEXT::_('COM_GEOI_SEARCH_VAL_ERROR');
		//echo '<span class="RangeText">min:</span><input type="number" id="minbox'.$search[0].'" class="MinBox" value="'.
		//$search[3][0].'" min="'.$search[3][0].'" max="'.$search[3][1].'" onclick="showHide(\'#min'.$search[0].'\', \'#max'.$search[0].'\')" onchange="setRangeMin(\''.$search[0].'\', \''.JTEXT::_('COM_GEOI_SEARCH_VAL_ERROR').'\')">';
		//echo '<span class="RangeText">max:</span><input type="number" id="maxbox'.$search[0].'" class="MaxBox" value="'.
		//$search[3][1].'" min="'.$search[3][0].'" max="'.$search[3][1].'" onclick="showHide(\'#max'.$search[0].'\', \'#min'.$search[0].'\')" onchange="setRangeMax(\''.$search[0].'\', \''.JTEXT::_('COM_GEOI_SEARCH_VAL_ERROR').'\')">';
		//echo '<br>';
		//echo '<input type="range" class="MinSlider" id="min'.$search[0].'" min="'.$search[3][0].'" max="'.$search[3][1].'" value="'.$search[3][0].'" onchange="setMinBox(\''.$search[0].'\')"> ';
		//echo '<input type="range" class="MaxSlider" id="max'.$search[0].'" min="'.$search[3][0].'" max="'.$search[3][1].'" value="'.$search[3][1].'" onchange="setMaxBox(\''.$search[0].'\')"> ';
		//echo '</div>';
		//echo '<br>';
		/////////////
	}
	html_content=html_content+'</div>'
	var div = document.getElementById('DataContainer');
	if(!(document.getElementById('container_'+name))){	div.innerHTML = div.innerHTML + html_content;}
	
}


