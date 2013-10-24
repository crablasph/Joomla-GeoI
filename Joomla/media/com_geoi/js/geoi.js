$( "#SearchTask" ).click(function() {
		$( "#SearchWindow" ).slideToggle();
		$( "#LoginWindow" ).hide();
	//$( "#SearchWindow" ).toggle();
			});

$( "#AuthTask" ).click(function() {
	$( "#LoginWindow" ).slideToggle();
	$( "#SearchWindow" ).hide();
		});


$( ".CloseWindow" ).on( "click", function() {
	$(this).parent().hide();
	$('#MultiValuesWindow').hide();
	idopen=$('.ShowValuesButton[open="open"]').attr("id");
	$('#'+idopen).attr('src','media/com_geoi/images/rightblue.png');
	document.getElementById(idopen).setAttribute('open','closed');
	});

$(".button").attr("type","image");
$(".button").attr("src","media/com_geoi/images/send.png");
$(".button:before").text("<br>");

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
$(".button").after("<br>");
$(".button").before("<br>");
//$('.button').attr('title', $('.button').value);

///fuente http://stackoverflow.com/questions/149055/how-can-i-format-numbers-as-money-in-javascript
Number.prototype.toMoney = function(decimals, decimal_sep, thousands_sep)
{ 
   var n = this,
   c = isNaN(decimals) ? 2 : Math.abs(decimals), //if decimal is zero we must take it, it means user does not want to show any decimal
   d = decimal_sep || '.', //if no decimal separator is passed we use the dot as default decimal separator (we MUST use a decimal separator)

   /*
   according to [http://stackoverflow.com/questions/411352/how-best-to-determine-if-an-argument-is-not-sent-to-the-javascript-function]
   the fastest way to check for not defined parameter is to use typeof value === 'undefined' 
   rather than doing value === undefined.
   */   
   t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, //if you don't want to use a thousands separator you can pass empty string as thousands_sep value

   sign = (n < 0) ? '-' : '',

   //extracting the absolute value of the integer part of the number and converting to string
   i = parseInt(n = Math.abs(n).toFixed(c)) + '', 

   j = ((j = i.length) > 3) ? j % 3 : 0; 
   return sign + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : ''); 
};
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

var map, vector_layer, select, popup, pollayer, poldrawsearchcontrol, pointSearch_layer, drawLayer, pointDrawControl;
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
	 
	 	var selectStyle = new OpenLayers.Style({pointRadius: "20"});
	 
	 	//ADD SEARCH DRAW POLYGON
	     pollayer = new OpenLayers.Layer.Vector( "PolygonSearch" );
	     map.addLayer(pollayer);
	     //var container = document.getElementById("SearchPolDiv");
	     poldrawsearchcontrol = new OpenLayers.Control.DrawFeature( pollayer , OpenLayers.Handler.Polygon );
	     map.layers[0].displayInLayerSwitcher = false;
	     map.addControl(poldrawsearchcontrol);
	     pollayer.events.on({   "featuresadded": onPolAdd     });
	     
	     
	     //ADD DRAW FEATURE y CONTROLS
	     defaultStyleDrawPoint = new OpenLayers.Style({'pointRadius': 15,
				'externalGraphic': parameters.ICON[parameters.ICON.length - 3]});
	     //drawPointStyle=new OpenLayers.StyleMap({'default':defaultStyleDrawPoint, 'selected':selectStyle});
	     drawPointStyle=new OpenLayers.StyleMap({'pointRadius': 15,
				'externalGraphic': parameters.ICON[parameters.ICON.length - 3]});
	     drawLayer = new OpenLayers.Layer.Vector( "DrawLayer", {styleMap:drawPointStyle});
	     map.addLayer(drawLayer);
	     pointDrawControl = new OpenLayers.Control.DrawFeature( drawLayer , OpenLayers.Handler.Point );
	     map.layers[1].displayInLayerSwitcher = false;
	     map.addControl(pointDrawControl);
	     pointModControl= new OpenLayers.Control.ModifyFeature(drawLayer);
	     map.addControl(pointModControl);
	     if(document.getElementById("InsertTask")){document.getElementById("InsertTask").onclick = toggleDraw;}
	     drawLayer.events.on({
	            "featureselected": onSelectMod,
	            "featureunselected": onUnselectMod,
	    	 	"featureadded":addPoint
	        });
	       	
	             
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
		
		strategysearch =new OpenLayers.Strategy.AttributeCluster({  attribute:'type' });
		strategysearch.distance=parameters.CLUSTER_DISTANCE;
		//strategy.distance=10000;
		strategysearch.threshold =parameters.CLUSTER_THRESHOLD;

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
                    	//console.log(parameters.SYMBOLOGY_VALUES.length+":_"+parameters.SYMBOLOGY_VALUES[0]);
                    	if(parameters.SYMBOLOGY_VALUES.length==0){
                    		return parameters.ICON[parameters.ICON.length - 1];
                    	}
                    	for(j=0;j<parameters.SYMBOLOGY_VALUES.length;j++){
	                    	var atn=parameters.SYMBOLOGY_VALUES[j];
	                    	var ico=parameters.ICON[j];
	                    	//console.log(parameters.SYMBOLOGY_VALUES[j]);
	                    	//if(ico=='undefined'){ico=parameters.ICON[parameters.ICON.length - 1];}
	               			//if (feature.cluster[i].attributes.type == ""){return parameters.ICON[parameters.ICON.length - 1];}
	            			if (feature.cluster[i].attributes.type.toLowerCase() == atn) {return ico;}
                    	}
                    }
                 }
                else if(feature.attributes.type==""){return parameters.ICON[parameters.ICON.length - 1];}
               }
            }
        });
		
		
		
		var stylegeojson = new OpenLayers.StyleMap({'default': defaultStyle,'select': selectStyle});
		
		vector_layer = new OpenLayers.Layer.Vector(parameters.LYR_NAME, {
			strategies: [strategy] ,
			styleMap:stylegeojson,
			minScale: parameters.MINSCALE});
       	map.addLayers([vector_layer]);
       	
       	
       	var defaultStyleSearch = new OpenLayers.Style({
       		pointRadius: "15",
            label: "${label}",
            fontColor:"blue",
            fontSize:"8",
            fontWeight: "bold",
            labelOutlineColor: "white",
            labelOutlineWidth: 3,
            externalGraphic:parameters.ICON[parameters.ICON.length - 2]
        }, {
        	context: 
        	{ label: function(vector_layer) {
        		if (typeof(vector_layer.attributes.count)=='undefined'){
        		return "";}
        		else
        			return vector_layer.attributes.count;
        		}}});
       	StyleMapSearch = new OpenLayers.StyleMap({'default': defaultStyleSearch,'select': selectStyle});
       	pointSearch_layer=new OpenLayers.Layer.Vector( "Search",{strategies: [strategysearch] ,styleMap:StyleMapSearch});
       	//pointSearch_layer.styleMap=StyleMapSearch;
       	//pointSearch_layer.style=defaultStyleSearch;
       	//console.log(pointSearch_layer.style);
       	map.addLayer(pointSearch_layer);
       	
        select = new OpenLayers.Control.SelectFeature(
                [pointSearch_layer, vector_layer, drawLayer],
                {
                    clickout: true, toggle: false,
                    multiple: false, hover: false,
                    toggleKey: "ctrlKey", // ctrl key removes from selection
                    multipleKey: "shiftKey" // shift key adds to selection
                }
            );
       	//map.layers[4].displayInLayerSwitcher = false;
       	//select2 = new OpenLayers.Control.SelectFeature(pointSearch_layer);

		//select = new OpenLayers.Control.SelectFeature(vector_layer,{hover:true});
       	//select = new OpenLayers.Control.SelectFeature(vector_layer);
        pointSearch_layer.events.on({
            "featureselected": onFeatureSelect,
            "featureunselected": onFeatureUnselect
        });
       	
		
        vector_layer.events.on({
            "featureselected": onFeatureSelect,
            "featureunselected": onFeatureUnselect,
			"moveend":reDrawGeojson
        });
        //map.addControl(select);
        map.addControl(select);
        select.activate(); 
       	//map.addLayer(pointSearch_layer);
       	//pointSearch_layer.setName('search_result');
       	//pointSearch_layer.name='search_result';
    	//pointSearch_layer.removeAllFeatures();
       	//map.layers[4].displayInLayerSwitcher = false;
       	//alert(	map.layers[4].name);
		
		

}
 
function toggleDraw() {
	var edit= document.getElementById("InsertTask").getAttribute('editing');
	alert("clickout:"+pointModControl.clickout+"\n toggle:"+pointModControl.toggle);
	if(edit=="true" ) {
		document.getElementById("InsertTask").setAttribute("editing","false");
		pointDrawControl.deactivate()
		drawLayer.removeAllFeatures();
		drawLayer.visibility=false;
		vector_layer.visibility=true;
		pointSearch_layer.visibility=true;
		pointModControl.deactivate();
		if(document.getElementById('ClosePopup')){document.getElementById('ClosePopup').click();}
	}else {
		document.getElementById("InsertTask").setAttribute("editing","true");
		pointDrawControl.activate();
		drawLayer.visibility=true;
		vector_layer.visibility=false;
		pointSearch_layer.visibility=false;
		vector_layer.removeAllFeatures();
		pointSearch_layer.removeAllFeatures();
		//testRequest();
	}
}

function addPoint(){
	//alert("XXXXX");
	pointDrawControl.deactivate();
	openInfo("","");
	pointModControl.activate();
}

function onSelectMod(){
	openInfo("","");
	//pointModControl.activate();
}
function onUnselectMod(){
	//alert("XXXXX");
	document.getElementById('ClosePopup').click();
}
 
 function onPolAdd() {
	 if(pollayer.features.length>1){
		 pollayer.removeFeatures(pollayer.features[0]);
	 }
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
				}
            }
    		

            //alert (attr[0].Precio);
			//alert(oids);
           /* content = content + "<br>";
            if (content.search("<script") != -1) {
                content = "Content contained Javascript! Escaped content below.<br>" + content.replace(/</g, "&lt;");
            }
            popup = new OpenLayers.Popup.FramedCloud("chicken", 
                                     feature.geometry.getBounds().getCenterLonLat(),
                                     new OpenLayers.Size(50,50),
                                     content,
                                     null, true, onPopupClose);
           
            feature.popup = popup;*/
            vector_layer.events.remove("moveend");
            delete vector_layer.events.listeners.moveend;
            //console.log(vector_layer.events);
            map.setCenter(new OpenLayers.LonLat(feature.geometry.x, feature.geometry.y));
            //map.addPopup(popup);
            openPopUp(oids);
            //vector_layer.events.un({"moveend":reDrawGeojson});
            map.controls[0].deactivate();
            //vector_layer.events.on({"moveend":reDrawGeojson	});
        }

function openPopUp(oids){
	//console.log(jsonsearch);
	var attr=Array();
	if(oids!=''){
		attr=getAttributesbyID(oids);
	}
    //alert (JSON.stringify(getAttributesbyID(oids)));
    var content = "";
    
    var price=0;
    var conta=0;
    for (var j in  jsonsearch){
    	if(jsonsearch[j][0]=="VALUE"){
    		var valuestring = jsonsearch[j][2];
    		//console.log(valuestring);
    		break;
    	}
    }
    
    for (var key in attr) { 
    		content = content +'<dt id="feature'+attr[key].oid+'">ID: <b>'+(attr[key].oid)+'</b>';
    		var content2 = '<dd id="ddfeature'+attr[key].oid+'"><br>';
    		$.each( attr[key], function(k, v){
    			if(k!="oid"){
    				content2=content2+ "<b>" + k + ":</b> " + v +"<br>";
    				if(k==valuestring){price=v;price=Number(price);}
    			}
        		});
    		content = content +' '+valuestring+':<b> $'+price.toMoney();
    		content = content + '</b><br></dt><br>' + content2;
    		content = content +"<br><br></dd>";
    	conta++;
    	
    }
    openInfo(content, attr);
}

function openInfo(content, attr){
    var div_popup
    if(document.getElementById('div_popup')){div_popup=document.getElementById('div_popup');div_popup.style.display="block";}
    else {div_popup= document.createElement("div");}
    var map_element=document.getElementById('map-id');
    map_width=map_element.offsetWidth;
    div_popup.id="div_popup";
    div_popup.className="BasicWindow";
    div_popup.style.top="14.7em"
   // div_popup.style.removeProperty("left");
    div_popup.style.float="right";
    div_popup.style.left=String(((map_width/3)*2))+"px";
    div_popup.style.right="0";
    div_popup.style.overflow="hidden";
    div_popup.style.maxHeight="48%";
    //div_popup.innerHTML='<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>';
    closebtn= document.createElement("img");
    closebtn.id="ClosePopup";
    closebtn.className="CloseWindow";
    closebtn.style.position=" relative";
    closebtn.src="media/com_geoi/images/close.png";
    //closebtn.onclick=closeWindow;
    div_popup.innerHTML='';
    div_popup.appendChild(closebtn);
    if(attr){
    	div_popup.innerHTML=div_popup.innerHTML+"<b>("+attr.length+") "+arrayText[0]+"</b><br><hr>";
    }
    //attr.length
    divbody= document.createElement("div");
    divbody.id="divbody";
    divbody.style.overflow="auto";
    map_element.appendChild(div_popup); 
    div_popup.appendChild(divbody);
    divbody.innerHTML=divbody.innerHTML+content;
    divbody.style.maxHeight=(div_popup.offsetHeight-30)+"px";
    //
    //alert(div_popup.offsetHeight);
    document.getElementById('ClosePopup').onclick = function(){ 
    				document.getElementById('div_popup').style.display="none";
    				select.unselectAll();
    	            map.controls[0].activate();
    	            //if(vector_layer.events.listeners.moveend){ remove
    	            vector_layer.events.remove("moveend");
    	            delete vector_layer.events.listeners.moveend;
    	            	vector_layer.events.on({"moveend":reDrawGeojson	});//}
    	            //}
    	            };
    var featuret=$( "[id^='feature']" );
    var featureb=$( "[id^='ddfeature']" );
    //alert(featuret.length);
    for(f=0;f<featureb.length;f++){
    	ffea=featureb[f];
    	ffet=featuret[f];
    	//featuret[f].onclick = dispBodyFeature(featuret[f].id);
    	idel=featuret[f].id;
    	ffea.style.display="none";
    	//console.log(document.getElementById('dd'+idel));
    	document.getElementById(idel).onclick = function(evtt){ 
    		//console.log(idel);
    		//console.log(evtt.currentTarget.id);
    		
    		var textf = new String(evtt.currentTarget.id);
    		//alert(textf);
    		var oidint=parseInt(textf.replace(/[^0-9]/gi, ''));
    		//alert(oidint);
    		//console.log(pointSearch_layer);
    		//pointSearch_layer.features;

    		/*funcion de buscar id en cluster */
    		//console.log(oidint);
    		var fss=selectSearchOIDinCluster(oidint);
    		//var fss=pointSearch_layer.getFeatureBy('oid',oidint);
    		//alert(fss.length);
    		///bounds
    		//select2.unselectAll();
    		select.unselectAll();
    		//pointSearch_layer.events.on({"featureselected": onFeatureSelect});
    		pointSearch_layer.events.un({"featureselected": onFeatureSelect})
    		if(document.getElementById('dd'+evtt.currentTarget.id).style.display=="none"){
    			//var br = document.createElement("br");
    			//document.getElementById('dd'+evtt.currentTarget.id).insertAdjacentHTML("afterend", "<br>");
    			//document.getElementById('div_popup').insertBefore(br, document.getElementById('dd'+evtt.currentTarget.id));
    			//document.getElementById('dd'+evtt.currentTarget.id).nextSibling.
    			document.getElementById('dd'+evtt.currentTarget.id).style.display="block";
    			//console.log(fss);
    			//map.controls[0].deactivate();
    			//console.log('dd'+evtt.currentTarget.id);
    			//console.log(document.getElementById('dd'+evtt.currentTarget.id).id);
    			map.zoomToExtent(fss.geometry.bounds);
        		map.setCenter(new OpenLayers.LonLat(fss.geometry.x, fss.geometry.y));
        		//
        		//"featureselected": onFeatureSelect
        		var fss2=selectSearchOIDinCluster(oidint);
        		
        		//fss2.renderIntent="select";
        		//console.log(fss2.attributes.oid);
        		//console.log(fss2);
        		select.select(fss2);
        		//console.log(oidint);
        		//console.log(evtt.currentTarget.id.replace(/[^0-9]/gi, ''));
        		
        		//vector_layer.events.un({"moveend":reDrawGeojson});
    		}else{
    			console.log('dd'+evtt.currentTarget.id);
    			console.log(document.getElementById('dd'+evtt.currentTarget.id).id);
    		document.getElementById('dd'+evtt.currentTarget.id).style.display="none";
    		delete pointSearch_layer.events.listeners.featureselected;
    		pointSearch_layer.events.on({"featureselected": onFeatureSelect});
    		//map.controls[0].activate();
    			}

    	}
    }
////   	            
}
function selectSearchOIDinCluster(oidint)
{
	
	for (var kk=0;kk<pointSearch_layer.features.length;kk++){
		if(pointSearch_layer.features[kk].cluster){
			for(var kv=0;kv<pointSearch_layer.features[kk].cluster.length;kv++){
				if(pointSearch_layer.features[kk].cluster[kv].attributes.oid==oidint){
					//console.log("cluster");
					//console.log(pointSearch_layer.features[kk].cluster[kv]);
					return pointSearch_layer.features[kk];
					}
			}
		}else{
			if(pointSearch_layer.features[kk].attributes.oid==oidint){
			//console.log("feature");
			//console.log(pointSearch_layer.features[kk]);
			return pointSearch_layer.features[kk];
			}
		}
	}
}

function onFeatureUnselect(event) {
            //var feature = event.feature;
			map.controls[0].deactivate();
            select.unselectAll();
            //select2.unselectAll();
            vector_layer.events.remove("moveend");
            delete vector_layer.events.listeners.moveend;
            vector_layer.events.on({"moveend":reDrawGeojson	});
            delete pointSearch_layer.events.listeners.featureselected;
            pointSearch_layer.events.on({"featureselected": onFeatureSelect});
            map.controls[0].activate();
            /*
            if (typeof feature != 'undefined'){
	            if(feature.popup) {
	                map.removePopup(feature.popup);
	                feature.popup.destroy();
	                delete feature.popup;
					//vector_layer.events.on({"moveend":reDrawGeojson	});
	            }
	            
	            
	            
            }*/
			}
	
function getGeojson(){
		
	 var extent = map.getExtent();
	 var type =parameters.SYMBOLOGY_FIELD;
	 var url =document.getElementById ("baseURL").href+'index.php?option=com_geoi&task=geojson&type='+type+'&bbox='+extent.toGeometry();
	 request.push($.parseJSON($.ajax({
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  request[request.length-1];
	
	}

function getAttributesbyID(idList){
	 var extent = map.getExtent();
	 var url =document.getElementById ("baseURL").href+'index.php?option=com_geoi&task=geojson&task=GetAttributes';
	 var dataids="idlist="+idList;
	 var req;
	 req=($.parseJSON($.ajax({
		 type: "POST",
		 data:dataids,
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  req;
	}

function getMapParameters(){
	var url =document.getElementById ("baseURL").href+'index.php?option=com_geoi&task=GetMapParameters';
	var req;
	 req=($.parseJSON($.ajax({
			url:  url,
			dataType: "json", 
			async: false
		}).responseText));
	 return  req;
}

function testRequest(){
	var url =document.getElementById ("baseURL").href+'index.php?option=com_geoi&task=test';
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
					map.controls[0].deactivate();
					vector_layer.removeAllFeatures();
					vector_layer.addFeatures(geojson_read);
					map.controls[0].activate();
					//console.log(vector_layer.events);
                }

function popupClear() {
    while( map.popups.length ) {
         map.removePopup(map.popups[0]);
    }
}

function ClearPoints(){
	select.unselectAll();
	//select2.unselectAll();
    map.controls[0].activate();
    pointSearch_layer.visibility=false;
	pointSearch_layer.removeAllFeatures();
	document.getElementById('div_popup').style.display="none";
	vector_layer.visibility=true;
}

function SearchPoints(arr){
	$("#map-id").css("cursor", "wait");
	var search_data=[];
	var cont_pol=0, db_pol=0;
	var geom_string="";
	for(i=0;i<arr.length;i++){
		current=arr[i];
		//valor="";
		
		if (String(current[1])==String("CAT")){
			valor=document.getElementById(current[0]);
			//alert(typeof(valor));
			if(valor){
				search_data[i]=[];
				search_data[i][0]=String(current[0]);
				search_data[i][1]=String(current[1]);
				search_data[i][2]=[];
				 for (var j = 0; j < valor.options.length; j++) {
					 if(valor.options[j].selected ==true){
						 search_data[i][2].push(valor.options[j].value);
						 //if(j!= Number(valor.options.length)){search_data[i][2]=search_data[i][2]+",";}
					 }
				 }
			}
		}else if(String(current[1])==String("INT")){
			//alert(current[0]+","+current[1]+", minbox"+current[0]);
			///alert('minbox'+current[0]+':'+$('minbox'+current[0]).val());
			var min=document.getElementById('minbox'+current[0]);
			var max=document.getElementById('maxbox'+current[0]);
			//alert("X:"+min.value+","+max.value);
			if(min && max){
				min=min.value;
				max=max.value;
				search_data[i]=[];
				search_data[i][0]=String(current[0]);
				search_data[i][1]=String(current[1])+".";
				search_data[i][2]=min+','+max;
				//alert(search_data[i][2]);
			}
		}else if (String(current[1])==String("POL")){
			//console.log("POLXlog");
			db_pol=db_pol+1;
			if(poldrawsearchcontrol.active){
				cont_pol=cont_pol+1;
				if(cont_pol==1){
						search_data[i]=[];
						search_data[i][0]="POLDRAW";
						search_data[i][1]="POLDRAW";
						geom_search=pollayer.features;
						for(j=0;j<geom_search.length;j++){
							geom_string=geom_string+geom_search[j].geometry;
							if(j!=Number(geom_search.length-1)){geom_string=geom_string+",";}}
						search_data[i][2]=geom_string;
					}
			}else{
				//alert("XXXXX");
				cont_pol=0;
				valor2=document.getElementById(current[0]);
				if(valor2){
					search_data[i]=[];
					search_data[i][0]=String(current[0]);
					search_data[i][1]=String(current[1]);
					search_data[i][2]=[];
					 for (var j = 0; j < valor2.options.length; j++) {
						 if(valor2.options[j].selected ==true){
							 search_data[i][2].push(valor2.options[j].value);
							 //search_data[i][2]=search_data[i][2]+valor2.options[j].value;
							 //if(j!=Number( valor2.options.length-1)){search_data[i][2]=search_data[i][2]+",";}
						 }
					 }
				}
				
			}
		}
	}
	
	if(db_pol==0 && poldrawsearchcontrol.active){
		var tmp_arr=[];
		tmp_arr.push("POLDRAW");
		tmp_arr.push("POLDRAW");
		geom_search=pollayer.features;
		for(j=0;j<geom_search.length;j++){
			geom_string=geom_string+geom_search[j].geometry;
			if(j!=Number(geom_search.length-1)){geom_string=geom_string+",";}}
		tmp_arr.push(geom_string);
		search_data.push(tmp_arr);
	}
	
	search_datadef=[];
	for(i=0;i<search_data.length;i++){
		if(search_data[i]){
			if(search_data[i][1]=='CAT' && search_data[i][2].length>0){	search_datadef.push(clone(search_data[i])); }
			else if(search_data[i][1]=='INT.'){	search_datadef.push(clone(search_data[i])); }
			else if(search_data[i][1]=='POLDRAW' && search_data[i][2].length>0){	search_datadef.push(clone(search_data[i])); }
			else if(search_data[i][1]=='POL' && search_data[i][2].length>0){	search_datadef.push(clone(search_data[i])); }
			//console.log(search_data[i]);
			}
		}
	if(search_datadef.length>0){
		//console.log(search_datadef);
		var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi&task=SearchPoints";
		var obj_data={"searchdata":search_datadef};
		var req;
		 req=($.parseJSON($.ajax({
			 	type: "POST",
				url:  url,
				data:obj_data,
				dataType: "json",
				async: false
			}).responseText));
		 //console.log( req);
			vector_layer.visibility=false;
			//vector_layer.display=false;
			vector_layer.removeAllFeatures();
			////ADD MAP LAYER , GEOMETRY PROPERTIES
			var WKT_format = new OpenLayers.Format.WKT();
			pointSearch_layer.removeAllFeatures();
			pointSearch_layer.visibility=true;
			///
			var vector_search=[];
			var oids="";
			for (var r in req){
				//console.log(req[r]);
					///WKT_format.read(req[r].geom);
					vector_search.push( new OpenLayers.Feature.Vector(
							OpenLayers.Geometry.fromWKT(req[r].geom),
							req[r]));
					oids=oids+req[r].oid;
					if(req[r]!=req[req.length-1]){
						oids=oids+",";
					}
			}
			//console.log(oids);
			//vector_layer.events.un({"moveend":reDrawGeojson});
			var selico=document.getElementById("SearchPolygon").getAttribute("selected");
			if(selico=="true"){polButtonClick();}
			//select2.activate();
			select.activate();
			pointSearch_layer.addFeatures(vector_search);
			openPopUp(oids)
			pointSearch_layer.redraw();
			//map.zoomToExtent(pointSearch_layer.getDataExtent());
			///
	}else {alert(arrayText[1]);}
	//console.log(req);
	$("#map-id").css("cursor", "default");

}

function clone(obj) {
	  return JSON.parse(JSON.stringify(obj));
	}
//// FUNCIONES INTERFAZ



function polButtonClick(){
	var selico=document.getElementById("SearchPolygon").getAttribute("selected");
	if(selico=="false"){
		document.getElementById("SearchPolygon").setAttribute("src","media/com_geoi/images/pol_on.png");
		document.getElementById("SearchPolygon").setAttribute("selected","true");
		arrpol=$('select[id*="POL"]');
		if(arrpol){
			for(i=0;i<arrpol.length;i++){
				idonly=arrpol[i].id;
				$("#"+idonly).prop('disabled', true);
				}
		}
		poldrawsearchcontrol.activate();
	}
	else{
		document.getElementById("SearchPolygon").setAttribute("src","media/com_geoi/images/pol_off.png");
		document.getElementById("SearchPolygon").setAttribute("selected","false");
		///$("#POL*").prop('disabled', false);
		arrpol=$('select[id*="POL"]');
		if(arrpol){
			for(i=0;i<arrpol.length;i++){
				idonly=arrpol[i].id;
				$("#"+idonly).prop('disabled', false);
			}
		}
		poldrawsearchcontrol.deactivate();
		pollayer.removeAllFeatures();
	}
}

function showHide(show, hide){	$(show).show();	$(hide).hide();}

function setRangeTittle(name){
	minn=document.getElementById('min'+name);
	tipminn = new Tips('#min'+name,{
        onShow: function(tip, el){
            tip.setStyles({
                display: 'block'
            }).fade('in');
        }
    });
	tipminn.setText((Number(minn)).toMoney());
	//tipminn.show();
	//minn.setAttribute('title', (Number(minn)).toMoney());
	minb=document.getElementById('minbox'+name);
	tipminb = new Tips('#minbox'+name,{
        onShow: function(tip, el){
            tip.setStyles({
                display: 'block'
            }).fade('in');
        }
    });
	tipminb.setText((Number(minb)).toMoney());
	tipminb.show();
	//minb.setAttribute('title', (Number(minb)).toMoney());
	maxn=document.getElementById('max'+name);
	tipmaxn = new Tips('#max'+name,{
        onShow: function(tip, el){
            tip.setStyles({
                display: 'block'
            }).fade('in');
        }
    });
	tipmaxn.setText((Number(maxn)).toMoney());
	tipmaxn.show();
	//maxn.setAttribute('title', (Number(maxn)).toMoney());
	maxb=document.getElementById('maxbox'+name);
	tipmaxb = new Tips('#maxbox'+name,{
        onShow: function(tip, el){
            tip.setStyles({
                display: 'block'
            }).fade('in');
        }
    });
	tipmaxb.setText((Number(maxb)).toMoney());
	tipmaxb.show();
	//maxb.setAttribute('title', (Number(maxb)).toMoney());
};
function setRangeMin(name, errormsg){
	//setRangeTittle(name);
	minr=document.getElementById('min'+name).value;
	//minall=document.getElementById('minbox'+name).getAttribute('min\');
	minall=document.getElementById('minbox'+name).getAttribute('min');
	min=document.getElementById('minbox'+name).value;
	max=document.getElementById('maxbox'+name).value;
	if(Number(min)>Number(max)||Number(min)<Number(minall)){
		document.getElementById('minbox'+name).value=minr;
		alert(errormsg);
	}else{document.getElementById('min'+name).value=min;}

}

function setRangeMax(name, errormsg){
	//setRangeTittle(name);
	maxr=document.getElementById('max'+name).value;
	maxall=document.getElementById('maxbox'+name).getAttribute('max');
	min=document.getElementById('minbox'+name).value;
	max=document.getElementById('maxbox'+name).value;
	if(Number(max)<Number(min)||Number(max)>Number(maxall)){
		document.getElementById('maxbox'+name).value=maxr;
		alert(errormsg);
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
		if (name.match(/POL.*/)&&poldrawsearchcontrol.active) {
			html_content=html_content+'<select class="SelectList" id="'+name+'" multiple="multiple" disabled>';
			}
		else{html_content=html_content+'<select class="SelectList" id="'+name+'" multiple="multiple" onchange="reSelect(\''+name+'\')">';}
			if(content_arr.length>=1 && content_arr[0]!=""){
				for (i=0;i<content_arr.length;i++){ html_content=html_content+'<option value="'+content_arr[i]+'">'+content_arr[i]+'</option> ';}
			}
			html_content=html_content+"</select>";
	}else if(type=='int'){
		///alert (content_arr[0]+content_arr[1]);
		if(content_arr[1]=="" ){
			content_arr[0]=0;
			content_arr[1]=1;
		}
		var html_content=html_content+'<div class="SliderContainer" id="'+name+'">';
		html_content=html_content+' <span class="RangeText">min:</span><input type="number" id="minbox'+name+'" class="MinBox" value="'+content_arr[0]+'"';
		html_content=html_content+' min="'+content_arr[0]+'" max="'+content_arr[1]+'" onclick="showHide( \'#min'+name+'\', \'#max'+name+'\')"';
		html_content=html_content+' onchange="setRangeMin(\''+name+'\',\''+arrayText[2]+'\')">';
		html_content=html_content+' <span class="RangeText">max:</span><input type="number" id="maxbox'+name+'" class="MaxBox" value="'+content_arr[1]+'"';
		html_content=html_content+' min="'+content_arr[0]+'" max="'+content_arr[1]+'" onclick="showHide( \'#max'+name+'\', \'#min'+name+'\')"';
		html_content=html_content+' onchange="setRangeMax(\''+name+'\',\''+arrayText[2]+'\')"><br>';
		html_content=html_content+' <input type="range" class="MinSlider" value="'+content_arr[0]+'" id="min'+name+'" min="'+content_arr[0]+'" max="'+content_arr[1]+'" onchange="setMinBox(\''+name+'\')">';
		html_content=html_content+' <input type="range" class="MaxSlider" value="'+content_arr[1]+'" id="max'+name+'" min="'+content_arr[0]+'" max="'+content_arr[1]+'" onchange="setMaxBox(\''+name+'\')">';
		html_content=html_content+' </div>';
	}
	html_content=html_content+'</div>'
	var div = document.getElementById('DataContainer');
	if(!(document.getElementById('container_'+name))){	div.innerHTML = div.innerHTML + html_content;}
	
}

function reSelect(name){
	valor=document.getElementById(name);
	if(valor){
		 for (var j = 0; j < valor.options.length; j++) {
			 if(valor.options[j].selected ==true){
				 $('#'+name+' option[value="'+valor.options[j].value+'"]').attr("selected","selected");
			 }
		 }
	}
}

function closeWindow(){
	alert (this.id);
}
