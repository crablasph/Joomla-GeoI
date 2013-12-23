$( "#SearchTask" ).click(function() {
		$( "#SearchWindow" ).slideToggle();
		$( "#LoginWindow" ).hide();
		$( "#LayerDisplayControl" ).hide();
	//$( "#SearchWindow" ).toggle();
			});

$( "#AuthTask" ).click(function() {
	$( "#LoginWindow" ).slideToggle();
	$( "#SearchWindow" ).hide();
	$( "#LayerDisplayControl" ).hide();
		});

$( "#LayerS" ).click(function() {
	$( "#LayerDisplayControl" ).slideToggle();
	$( "#SearchWindow" ).hide();
	$( "#LoginWindow" ).hide();
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
		$( this ).attr('title',arrayText[7]);
		$('#'+id_nextdiv).slideToggle();
		document.getElementById(idelemento).setAttribute('open','open');
	}else{
		$( this ).css("left","0px");
		$( this ).css("float","left");
		$('#'+id_nextdiv).slideToggle();
		$( this ).css("color","white");
		$( this ).attr('title',arrayText[3]);
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

var map, vector_layer, select, popup, pollayer, poldrawsearchcontrol, pointSearch_layer, drawLayer, pointDrawControl, restrictions;
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
                        //new OpenLayers.Control.ZoomPanel({'div':OpenLayers.Util.getElement('zoompanel')}),
                        //new OpenLayers.Control.PanPanel({'div':OpenLayers.Util.getElement('panpanel')}), 
                        ///new OpenLayers.Control.Scale(),
                        new OpenLayers.Control.LayerSwitcher({'div':OpenLayers.Util.getElement('ContainerDisplayControl'),'ascending':false}),
                        new OpenLayers.Control.KeyboardDefaults(),
                        new OpenLayers.Control.TouchNavigation
                    ],
                    projection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DATA),
					displayProjection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DISP),
	                    eventListeners: {
	                    	"zoomend":popupClear,
	                    	
	                   }
                });
	 
	 	//map.events.on({"moveend":alert(map.getScale()),"zoomend":alert(map.getScale())});
	 
	 	var selectStyle = new OpenLayers.Style({pointRadius: "20"});
	 
	 	//ADD SEARCH DRAW POLYGON
	     pollayer = new OpenLayers.Layer.Vector( "PolygonSearch" );
	     map.addLayer(pollayer);
	     //var container = document.getElementById("SearchPolDiv");
	     poldrawsearchcontrol = new OpenLayers.Control.DrawFeature( pollayer , OpenLayers.Handler.Polygon );
	     map.layers[0].displayInLayerSwitcher = false;
	     map.addControl(poldrawsearchcontrol);
	     pollayer.events.on({   "featuresadded": onPolAdd     });
	     
	     
	     //ADD BASE LAYER	           	
	             
		var osm = new OpenLayers.Layer.OSM();
		var gmap = new OpenLayers.Layer.Google("Google Streets", {visibility: false});
		//var ags = new OpenLayers.Layer.ArcGISCache("Mapa Referencia Bogota", "http://imagenes.catastrobogota.gov.co/arcgis/rest/services/CM/CommunityMap/MapServer",{visibility: false});
		//map.addLayers([osm, gmap, ags]);
		map.addLayers([osm, gmap]);
		
		//ZUM TO..
		var str = parameters.BOUNDS;
		str=str.split(",");
		var bounds = new OpenLayers.Bounds(str[0],str[1],str[2],str[3]);
		//var proj = new OpenLayers.Projection("EPSG:3857");
		//bounds.transform(proj, map.getProjectionObject());
        map.zoomToExtent(bounds);
        
        ///CREATE STRATEGIES
        strategy =new OpenLayers.Strategy.AttributeCluster({  attribute:'type' });
		strategy.distance=parameters.CLUSTER_DISTANCE;
		strategy.threshold =parameters.CLUSTER_THRESHOLD;
		
		strategysearch =new OpenLayers.Strategy.AttributeCluster({  attribute:'type' });
		strategysearch.distance=parameters.CLUSTER_DISTANCE;
		strategysearch.threshold =parameters.CLUSTER_THRESHOLD;
		
		
		//DEFINE STYLES
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
        		//console.log(parameters.ICON);
                if (feature.attributes.type && isNaN(feature.attributes.type) ) {
                	for(i=0;i<parameters.ICON.length;i++){
            			var atn=parameters.ICON[i].SYMVALUE;
            			atn=atn.toLowerCase();
            			var ico=parameters.ICON[i].PATH;
            			if(feature.attributes.type.toLowerCase()==atn){return ico;}
            		}
                } else if(feature.cluster) {
                    for (var i = 0; i < feature.cluster.length; i++) {
                    	if(parameters.SYMBOLOGY_VALUES.length==0){
                    		return parameters.ICON[parameters.ICON.length - 1].PATH;
                    	}
                    	for(j=0;j<parameters.ICON.length;j++){
	                    	var atn=parameters.ICON[j].SYMVALUE;
	                    	atn=atn.toLowerCase();
	                    	var ico=parameters.ICON[j].PATH;
	            			if (feature.cluster[i].attributes.type.toLowerCase() == atn && isNaN(atn)) {return ico;}
	            			else if(feature.cluster[i].attributes.type.toLowerCase()=="") return parameters.ICON[parameters.ICON.length - 1].PATH;
	            			else if(!isNaN(atn)) return parameters.ICON[parameters.ICON.length - 1].PATH;
	            			//else return parameters.ICON[parameters.ICON.length - 1].PATH;
                    	}
                    }
                 }
                else{return parameters.ICON[parameters.ICON.length - 1].PATH;}
               }
            }
        });
		
		
		
		var stylegeojson = new OpenLayers.StyleMap({'default': defaultStyle,'select': selectStyle});
		
		vector_layer = new OpenLayers.Layer.Vector(parameters.LYR_NAME, {
			"strategies": [strategy] ,
			"styleMap":stylegeojson,
			"maxResolution":parameters.MAXRESOLUTION,
			"projection": new OpenLayers.Projection("EPSG:"+parameters.EPSG_DATA)
			});
       	map.addLayers([vector_layer]);
       	
       	
       	var defaultStyleSearch = new OpenLayers.Style({
       		pointRadius: "15",
            label: "${label}",
            fontColor:"blue",
            fontSize:"8",
            fontWeight: "bold",
            labelOutlineColor: "white",
            labelOutlineWidth: 3,
            externalGraphic:getIconPath("search")
        }, {
        	context: 
        	{ label: function(pointSearch_layer) {
        		if (typeof(pointSearch_layer.attributes.count)=='undefined'){
        		return "";}
        		else
        			return pointSearch_layer.attributes.count;
        		}}});
       	StyleMapSearch = new OpenLayers.StyleMap({'default': defaultStyleSearch,'select': selectStyle});
       	
       	///ADD DRAW POINT FEATURES

	     drawPointStyle=new OpenLayers.Style({
	    	 pointRadius: "15", 
	    	 label: "${label}",
	         fontColor:"blue",
	         fontSize:"8",
	         fontWeight: "bold",
	         labelOutlineColor: "white",
	         labelOutlineWidth: 3,
	    	 externalGraphic: getIconPath("editsymbol")}, {
		        	context: 
		        	{ label: function(drawLayer) {
		        		if (typeof(drawLayer.attributes.count)=='undefined'){
		        		return "";}
		        		else
		        			return drawLayer.attributes.count;
		        		}}});
	     drawPointStyleMap = new OpenLayers.StyleMap({'default': drawPointStyle,'select': selectStyle});
	     strategydraw =new OpenLayers.Strategy.AttributeCluster({  attribute:'type' });
		 strategydraw.distance=parameters.CLUSTER_DISTANCE;
		 strategydraw.threshold =parameters.CLUSTER_THRESHOLD;
	     drawLayer = new OpenLayers.Layer.Vector( arrayText[15], 
	    		 {
	    	 strategies: [strategydraw],
	    	 styleMap:drawPointStyleMap
	    	 });
	     map.addLayer(drawLayer);
	     
	     var optionsdrawpoint = { 
	    		 callbacks: { done: insertPoint2Layer } ,
	             handlerOptions: { 
	                 style: { 
	        	    	 externalGraphic: getIconPath("editsymbol"),
	                     pointRadius: 8 
	                 } 
	             } 
	         }; 
	     pointDrawControl = new OpenLayers.Control.DrawFeature( drawLayer , OpenLayers.Handler.Point, optionsdrawpoint );
	     //pointDrawControl.events.on({"featureadded":addPoint});
	     if(userdataarray[0]=='0'){
	     map.layers[4].displayInLayerSwitcher = false;
	     }else{	 
	    	 SearchPoints(jsonsearch,userdataarray[0]);
	    	 vector_layer.visibility=false;
		     restrictions = getRestrictions();
	    	 }
	     map.addControl(pointDrawControl);
	     pointModControl= new OpenLayers.Control.ModifyFeature(drawLayer);
	     map.addControl(pointModControl);
	     if(document.getElementById("InsertTask")){document.getElementById("InsertTask").onclick = toggleDraw;}
	     drawLayer.events.on({
	            "featureselected": onSelectMod,
	            "featureunselected": onUnselectMod,
	    	 	//"featureadded":addPoint
	        });
	    //drawLayer.onFeatureInsert=addPoint;
	    drawLayer.redraw();
	    /////ADD DRAW POLYGON LAYER 
       	
       	pointSearch_layer=new OpenLayers.Layer.Vector( arrayText[16],{
       							strategies: [strategysearch] ,
       							styleMap:StyleMapSearch,
       							projection: new OpenLayers.Projection("EPSG:"+parameters.EPSG_DATA)
       							});
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

        pointSearch_layer.events.on({
            "featureselected": onFeatureSelect,
            "featureunselected": onFeatureUnselect
        });
       	
		
        vector_layer.events.on({
            "featureselected": onFeatureSelect,
            "featureunselected": onFeatureUnselect,
			"moveend":reDrawGeojson
        });
        //reDrawGeojson();
        //console.log(vector_layer.events);
        //map.addControl(select);
        map.addControl(select);
        select.activate(); 

}
 
function toggleDraw() {
	var edit= document.getElementById("InsertTask").getAttribute('editing');
	//alert("clickout:"+pointModControl.clickout+"\n toggle:"+pointModControl.toggle);
	if(edit=="true" ) {
		document.getElementById("InsertTask").setAttribute("editing","false");
		pointDrawControl.deactivate();
		//drawLayer.removeAllFeatures();
		drawLayer.visibility=false;
		vector_layer.visibility=true;
		pointSearch_layer.visibility=true;
		//pointModControl.activate();
		if(document.getElementById('ClosePopup')){document.getElementById('ClosePopup').click();}
	}else {
		document.getElementById("InsertTask").setAttribute("editing","true");
		drawLayer.removeAllFeatures();
		SearchPoints(jsonsearch,userdataarray[0]);
   	 	pointDrawControl.activate();
		drawLayer.visibility=true;
		vector_layer.visibility=false;
		pointSearch_layer.visibility=false;
		vector_layer.removeAllFeatures();
		pointSearch_layer.removeAllFeatures();
		//testRequest();
	}
}

function validateData(){
	var valcont=0;
	//console.log(restrictions[0]);
	for (i=0;i<restrictions.length; i++ ){
		var lin=restrictions[i].VAL.search("-");
		var comma=restrictions[i].VAL.search(",");
		var valdata=document.getElementById("input"+restrictions[i].PARAM).value;
		var eledata=document.getElementById("input"+restrictions[i].PARAM);
		eledata.style.borderColor="white";
		//console.log(restrictions[i].PARAM,lin,comma);
		if(lin==-1){
			var splitc=restrictions[i].VAL.split(",");
			//alert(splitc);
			if(restrictions[i].PARAM=="TYPEP" || restrictions[i].PARAM=="TYPEO"){
				if(valdata==null || valdata==""){ 
					//console.log(restrictions[i].PARAM, splitc, typeof(splitc), valdata);
					valcont++; 
					eledata.style.borderColor="red";
				}
			}
			if(valdata!=null && valdata!=""){
				if(splitc.indexOf(valdata.toLowerCase())==-1) {
					//console.log(restrictions[i].PARAM, splitc, typeof(splitc), valdata);
					valcont++;  
					eledata.style.borderColor="red";
				}
			}
		}
		if(comma==-1){
			valdata=Number(valdata);
			var splitl=restrictions[i].VAL.split("-");
			if(restrictions[i].PARAM=="VALUE"){
				if(valdata==null || valdata=="") {
					//console.log(restrictions[i].PARAM, splitl, typeof(splitl), valdata);
					valcont++; 
					eledata.style.borderColor="red";
				}	
			}if(valdata!=null && valdata!=""){
				if(valdata<splitl[0]||valdata>splitl[1]||isNaN(valdata)){
					//console.log(restrictions[i].PARAM, splitl, typeof(splitl), valdata);
					valcont++;	
					eledata.style.borderColor="red";
				}
			}
		}
	}
	if(valcont!=0) return false;
	else return true;
	
}

function insertPoint2Layer(pto) {
	var clonedfeatures=Array();
		for (i=0;i<drawLayer.features.length; i++ ){
			if(drawLayer.features[i].cluster){
				for (j=0;j<drawLayer.features[i].cluster.length; j++ ){
					clonedfeatures.push(drawLayer.features[i].cluster[j].clone());
				}
			}else{
			clonedfeatures.push(drawLayer.features[i].clone());
			}
		} 
	
	drawLayer.removeAllFeatures();
	//SearchPoints(jsonsearch,userdataarray[0]);
	point = new OpenLayers.Feature.Vector(pto);
	point.attributes.oid="'"+createUUID()+"'";
	clonedfeatures.push(point);
	drawLayer.addFeatures(clonedfeatures);
	drawLayer.redraw();
	pointDrawControl.deactivate();
	getForm('', point.attributes.oid);
} 

function getIconPath(name){
	for(i=0;i<parameters.ICON.length;i++){
		if(parameters.ICON[i].SYMVALUE.toLowerCase()==name.toLowerCase())
			return parameters.ICON[i].PATH.toLowerCase();
	}
}

function onSelectMod(ftr){
	//openInfo("","");
	//buildForm();
	//console.log(ftr);
	if(!ftr.feature.cluster){
		var prop=getAttributesbyID(ftr.feature.attributes.oid);
		getForm(prop, ftr.feature.attributes.oid);
	}
	//console.log(prop);
	
	//pointModControl.activate();
}
function onUnselectMod(){
	//alert("XXXXX");
	pointModControl.deactivate();
	//select.unselectAll();
	//console.log(document.getElementById('ClosePopup'));
	document.getElementById('ClosePopup').click();
	if(document.getElementById('ClosePopup')){document.getElementById('ClosePopup').click();}

}
 
 function onPolAdd() {
	 if(pollayer.features.length>1){
		 pollayer.removeFeatures(pollayer.features[0]);
	 }
 }
 
 function getcommaArray(name){
	 for(i=0;i<restrictions.length;i++){
		 var splitc=restrictions[i].VAL.split(",");
		 var comma=restrictions[i].VAL.search(",");
		 if(comma>-1){
				 if(name.toLowerCase()==restrictions[i].PARAM.toLowerCase())
					 return splitc;
		 }
	 }
 }
 
function buildForm(prop){
	var D = document;
    var maxh= Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
    var maxhe=maxh-0.10*maxh;
	var frm = "";
	frm =frm +'<div style="overflow: hidden; max-height: '+maxhe+'px;width:100%;position: relative;">';
	frm =frm +'<img id="SaveData" title="'+arrayText[8]+'" src="'+getIconPath("save")+'" style="width:30px;heigth:30px;" class="ImgEditTask" sfeatureid=""></img>';
	frm =frm +'<img id="DeleteFeature" title="'+arrayText[9]+'" src="'+getIconPath("delete")+'" style="width:30px;heigth:30px;" class="ImgEditTask" dfeatureid=""></img>';
	frm =frm +'<img id="EditFeature" title="'+arrayText[10]+'" src="'+getIconPath("modify")+'" style="width:30px;heigth:30px;" class="ImgEditTask" ufeatureid=""></img>';
	frm =frm +'<table style="position: relative;width:100%">';
	frm =frm +'<tbody style="max-height:'+(maxhe-5)+'px;overflow:auto;display:block;width:100%;position: relative;">';
	for(var i=0;i<parameters.FIELDS_FORM.length;i++){
		var aliasx=parameters.FIELDS_FORM[i].ALIAS;
		if(parameters.FIELDS_FORM[i].NAME!='EMAIL'&&parameters.FIELDS_FORM[i].NAME!='USERNAME'){
			if(prop!=''){
				frm=frm+"<tr><td><a>"+parameters.FIELDS_FORM[i].ALIAS+"</a></td>" ;
				if(parameters.FIELDS_FORM[i].NAME=="TYPEP"||parameters.FIELDS_FORM[i].NAME=="TYPEO"){
					frm=frm+"<td><select id='input"+parameters.FIELDS_FORM[i].NAME+"'>";
					var splitc=getcommaArray(parameters.FIELDS_FORM[i].NAME);
					for(x in splitc){
						if(splitc[x].toLowerCase()==prop[0][aliasx]) frm=frm+"<option value='"+splitc[x]+"' selected>"+toTitleCase(splitc[x])+"</option>";
						else	frm=frm+"<option value='"+splitc[x]+"'>"+toTitleCase(splitc[x])+"</option>";
					}
					frm=frm+"</select></td></tr>\n";
				}
				else {frm=frm+"<td><input id='input"+parameters.FIELDS_FORM[i].NAME+"' type='text' class='InputForm' value='"+prop[0][aliasx]+"'></input></td></tr>\n";}
			}else{
				frm=frm+"<tr><td><a>"+parameters.FIELDS_FORM[i].ALIAS+"</a></td>";
				if(parameters.FIELDS_FORM[i].NAME=="TYPEP"||parameters.FIELDS_FORM[i].NAME=="TYPEO"){
					frm=frm+"<td><select id='input"+parameters.FIELDS_FORM[i].NAME+"'>";
					var splitc=getcommaArray(parameters.FIELDS_FORM[i].NAME);
					for(x in splitc){
						frm=frm+"<option value='"+splitc[x]+"'>"+toTitleCase(splitc[x])+"</option>";
					}
					frm=frm+"</select></td></tr>\n";
				}
				else{frm=frm+"<td><input id='input"+parameters.FIELDS_FORM[i].NAME+"' type='text' class='InputForm'></input></td></tr>\n";}
				}

		}	    
	}
	frm =frm +'<tr><td><a>'+arrayText[18]+'</a></td><td><form id="imageform"><input type="file" id="files" name="imageuploads[]" multiple></form></td></tr>';
	frm =frm +'<tr><td><a id="deletePictures">'+arrayText[22]+'</a></td></tr>';
	frm =frm +"<tr><td><a class='openSlide' id='openSlide'>"+arrayText[17]+"</a></td></tr>" ;

	frm =frm +'</tbody></table></div>';
	//var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi&task=UploadImages";
	///frm =frm +'<iframe id="hiddenFrame" style="display:none;" src="'+url+'" ></iframe>';
	return frm;
} 
function getForm(prop, ids){
	openInfo(buildForm(prop),"");
		document.getElementById('DeleteFeature').setAttribute("dfeatureid",ids);
		document.getElementById('SaveData').setAttribute("sfeatureid",ids);
		document.getElementById('EditFeature').setAttribute("ufeatureid",ids);
		document.getElementById('DeleteFeature').onclick = function(){
		$("#map-id").css("cursor", "wait");
		$( "#Loading" ).show();
		document.getElementById("InsertTask").setAttribute("editing","false");
		//select.unselectAll(); 
		//pointModControl.deactivate();
		 pointDrawControl.deactivate();
		 drawLayer.visibility=true;
		 vector_layer.visibility=false;
		 pointSearch_layer.visibility=false;
		 var delid = document.getElementById('DeleteFeature').getAttribute("dfeatureid");
		 var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi&task=DeletePoints";
		 var obj_data={"deletedata":delid};
		 var req;
			 req=($.parseJSON($.ajax({
				 	type: "POST",
					url:  url,
					data:obj_data,
					dataType: "json",
					async: false
				}).responseText));
			 if(req==""){
				 drawLayer.removeFeatures(selectSearchOIDinCluster(delid, drawLayer));
				 if(document.getElementById('ClosePopup')){document.getElementById('ClosePopup').click();}
				 drawLayer.redraw();
				 alert(arrayText[12]);
			 }
			 else alert(JSON.stringify(req));
			$("#map-id").css("cursor", "default");
			$( "#Loading" ).hide();
			//pointModControl.activate();
		 //drawLayer.removeAllFeatures();
	 };
	 document.getElementById('SaveData').onclick = function(){
		 $("#map-id").css("cursor", "wait");
		 $( "#Loading" ).show();
		 //select.unselectAll();
		 //select.activate();
		 
		 var validation=validateData();
		 //alert(":"+validation);
		 if (validation==false){
			 alert(arrayText[13]);
			 $("#map-id").css("cursor", "default");
			 $( "#Loading" ).hide();
			 return;
		 }
		 var savid = document.getElementById('SaveData').getAttribute("sfeatureid");
		 var udata =[]; 
		 //var kcont=0;
		 for(var i=0;i<parameters.FIELDS_FORM.length;i++){
			if(parameters.FIELDS_FORM[i].NAME!='EMAIL'&&parameters.FIELDS_FORM[i].NAME!='USERNAME'){
				var arrd=[];
				arrd.push(parameters.FIELDS_FORM[i].NAME);
				arrd.push(document.getElementById("input"+parameters.FIELDS_FORM[i].NAME).value);
				udata.push(arrd);
			}
		 }
		 udata.push(['oid',savid]);
		 var fdata=selectSearchOIDinCluster(savid, drawLayer);
		 var geomdata=fdata.geometry.toString();
		 udata.push(['geom',geomdata]);
		 ///nan son los insertados con puntico
		 if (isNaN(savid)){
			 //console.log('nan',savid);
			 var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi";
			 var obj_d={"insertdata":udata, "task":"InsertPoints" };
			 var req;
				 req=($.parseJSON($.ajax({
					 	type: "POST",
						url:  url,
						data:obj_d,
						dataType: "json",
						async: false
					}).responseText));
			if(req[0].oid){
				 var savedf = selectSearchOIDinCluster(savid, drawLayer);
				 savedf.attributes.oid= req[0].oid;
				 drawLayer.redraw();
				 sendFiles(req[0].oid);
				 if(document.getElementById('ClosePopup')){document.getElementById('ClosePopup').click();}
				 alert(arrayText[14]);
			}else alert(JSON.stringify(req));
			 
			 }
		 else{
			 var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi";
			 var obj_d={"updatedata":udata, "task":"UpdatePoints" };
			 var req;
				 req=($.parseJSON($.ajax({
					 	type: "POST",
						url:  url,
						data:obj_d,
						dataType: "json",
						async: false
					}).responseText));
			if(req==""){
				drawLayer.redraw();
				sendFiles(savid);
				//if(document.getElementById('ClosePopup')){document.getElementById('ClosePopup').click();}
				alert(arrayText[11]);
			}else alert(JSON.stringify(req));
			
			$("#map-id").css("cursor", "default");
			$( "#Loading" ).hide();
			//console.log(req);
		 }
		 	//select.activate();
			document.getElementById("InsertTask").setAttribute("editing","false");
			$("#map-id").css("cursor", "default");
			$( "#Loading" ).hide();

	 }
	 document.getElementById('EditFeature').onclick = function(){
		 var upid = document.getElementById('EditFeature').getAttribute("dfeatureid");
		 pointModControl.deactivate();
		 pointModControl.activate();
		 //console.log(pointModControl);
	 }
	 document.getElementById('files').onchange = function(){
		 var fileElement = document.getElementById('files');
		 for (var i = 0; i < fileElement.files.length; ++i) {
		        var fileExtension = "";
		        if (fileElement.files[i].name.lastIndexOf(".") > 0) {
		        	
		            fileExtension = fileElement.files[i].name.substring(fileElement.files[i].name.lastIndexOf(".") + 1, fileElement.files[i].name.length);
		            //console.log(fileExtension);
		        }
		        if (fileExtension.toLowerCase() == "gif"||
		        	fileExtension.toLowerCase() == "jpeg"||
		        	fileExtension.toLowerCase() == "jpg"||	
		        	fileExtension.toLowerCase() == "bmp"||
		        	fileExtension.toLowerCase() == "tiff"||
		        	fileExtension.toLowerCase() == "tif"||
		        	fileExtension.toLowerCase() == "png") {
		            //return true
		        }
		        else {
		            alert(arrayText[19]);
		            fileElement.value="";
		            return false;
		        }
		    }
	 }
	 
	 document.getElementById('openSlide').onclick=function(){openSlide(ids);};
	 document.getElementById('deletePictures').onclick=function(){
		 var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi";
		 var obj_d={"fid":ids, "task":"DeletePictures" };
		 var req;
			 req=($.parseJSON($.ajax({
				 	type: "POST",
					url:  url,
					data:obj_d,
					dataType: "json",
					async: false
				}).responseText));
			 //console.log(req);
		if(req!=""){alert(req);}
		else{alert(arrayText[23]);}
	 };
		 
	 
	
}

function sendFiles(fid){
	var form = document.getElementById('imageform');
	var iFiles = document.getElementById('files');
	var iPage = document.getElementById('hiddenFrame');
	iPage.innerHTML="";
	if(iFiles.files.length>0){
		//var form=document.createElement("form");
		var action_url =document.getElementById ("baseURL").href+"index.php?option=com_geoi&task=UploadImages";
		//form.id="hiddenForm";
	    form.setAttribute("action", action_url);
	    form.setAttribute("target", "hiddenFrame");
	    form.setAttribute("method", "post");
	    form.setAttribute("enctype", "multipart/form-data");
	    //form.setAttribute("enctype", "text/plain");
	    //form.setAttribute("encoding", "multipart/form-data");
	    var idinput=document.createElement("input");
	    idinput.setAttribute("type","hidden");
	    idinput.setAttribute("id","fid");
	    idinput.setAttribute("value",fid);
	    idinput.setAttribute("name", "fid");
	    form.appendChild(idinput);
	    //console.log("1:",form);
	    form.submit();
	    //iPage.appendChild(form);
	    //var iForm=document.getElementById('hiddenForm');
	    
	    //var copy =iFiles.cloneNode(true);
	    //iForm.appendChild(copy);
	    //console.log(iForm,iFiles,iPage,iFiles.cloneNode(true).files);
	    //iForm.submit();
	    //}
	}
}

function responseUploads(){

	var iPage2 = document.getElementById('hiddenFrame');

	if (iPage2.contentDocument) {
	    sresp = iPage2.contentDocument.body.innerHTML;
	} else if (iframeId.contentWindow) {
		sresp = iPage2.contentWindow.document.body.innerHTML;
	} else if (iPage2.document) {
		sresp = iPage2.document.body.innerHTML;
	}
	//var iFrameD= iPage2.contentDocument || iPage2.contentWindow;
	//if(iFrameD){
	var sresp1 = String(sresp).valueOf();
	sresp1 = sresp1.substring(5);
	sresp1 = sresp1.substring(0,sresp1.length-6);
	//sresp1 = sresp1.replace('</pre>','');
	//sresp1 = sresp1.replace('<pre>','');
	
	var truestring=String("true").valueOf();
	if(sresp1.length>4){
		if(sresp1.indexOf(truestring)==-1)  alert(sresp1);
	}
		//console.log("1:",sresp1,typeof(sresp1));
}

function openSlide(idfeature){
	sWindow=window.open('','','width=500,height=350')
	photoarray=getPhotos(idfeature);
	//console.log(sWindow);
	wincont="<html><head>";
	wincont=wincont+"<base id='baseURL' href='"+document.getElementById ('baseURL').href+"'>";
	wincont=wincont+"<script src='"+arrayText[21]+"'></script>";
	wincont=wincont+"<link rel='stylesheet' href='"+arrayText[20]+"' type='text/css'>";
	wincont=wincont+"<script>";
	wincont=wincont+"var imgArr = new Array(";
	if(photoarray.length>0){
		 for (var i = 0; i < photoarray.length; i++) {
				//console.log(photoarray[i],photoarray[i].picpath);
			 wincont=wincont+"'"+photoarray[i].picpath+"'";
			 if(i!=photoarray.length-1) wincont=wincont+",";
			 
			 }
	}else{ wincont=wincont+"'media/com_geoi/images/photo.png'";photoarray=Array();photoarray[0]=photoarray[1]="'media/com_geoi/images/photo.png'";}
	 wincont=wincont+"); \n";
	 wincont=wincont+"var count = imgArr.length; var position = 0;\n";
	 wincont=wincont+"function slidingImages() {\n";
	 wincont=wincont+   "if ( document.images ) {\n";
	 wincont=wincont+           "if ( position < count ) {\n";
	 wincont=wincont+               "imageSrc(position); \n";
	 wincont=wincont+               "position += 1;\n";
	 wincont=wincont+           "} else {\n";
	 wincont=wincont+              "position = 0;\n";
	 wincont=wincont+               "imageSrc(position);\n";
	 wincont=wincont+           "}\n}\n";
	 //wincont=wincont+"document.getElementById('imgSlideControlprev').onclick=prevpic";
	 //wincont=wincont+"document.getElementById('imgSlideControlnext').onclick=nextpic";
	 wincont=wincont+        "setTimeout(slidingImages, 15000); \n";
	 wincont=wincont+ "}\n";
	 wincont=wincont+"function prevpic() {if ( (position-1) < count && position >0  ) position=position-1;else position=count-1;document.pics.src = imgArr[position];}\n";
	 wincont=wincont+"function nextpic() {if ( (position+1) < count && position >=0  ) position=position+1;else position=0;document.pics.src = imgArr[position];}\n";
	 wincont=wincont+"function imageSrc(position) {document.pics.src = imgArr[position];}\n";
	 wincont=wincont+"</script>";
	//wincont=wincont+"<script src='"+arrayText[20]+"'></script>";
	//wincont=wincont+"<script src='"+arrayText[21]+"'></script>";
	wincont=wincont+"</head><body onload='slidingImages()'>";
	wincont=wincont+"<img id='imgSlideControlprev' src='"+getIconPath("prevpic")+"' onclick='prevpic()' style='width: 60px; left: 50px;position: relative;float:left;top: 50%;z-index:0;'/>"
	wincont=wincont+"<img id='imgSlideControlnext' src='"+getIconPath("nextpic")+"' onclick='nextpic()' style='width: 60px; right: 50px;position: relative;float:right;top: 50%;z-index:0;' />"
	wincont=wincont+"<div id='divimg'>"
	//wincont=wincont+"<div onload='$('#sliderphoto').anythingSlider()'>"
	//wincont=wincont+'<ul id="sliderphoto" class="anythingBase horizontal" style="width: 180px; left: -80px;">'
	wincont=wincont+"<img id='imgSlider' src='"+photoarray[0].picpath+"' name='pics' />"
	wincont=wincont+'</div><body></html>';
	//wincont=wincont+'</ul></div><body></html>';	
	//console.log(wincont);
	sWindow.document.write(wincont);
	//$('#sliderphoto').anythingSlider();
	sWindow.focus();
	sWindow.document.close(); ;
}

function createUUID() {
    // http://www.ietf.org/rfc/rfc4122.txt
    var s = [];
    var hexDigits = "0123456789abcdef";
    for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
    }
    s[14] = "4";  // bits 12-15 of the time_hi_and_version field to 0010
    s[19] = hexDigits.substr((s[19] & 0x3) | 0x8, 1);  // bits 6-7 of the clock_seq_hi_and_reserved to 01
    s[8] = s[13] = s[18] = s[23] = "-";

    var uuid = s.join("");
    return uuid;
}

function onPopupClose() {
	select.unselectAll();
	
        }

function onFeatureSelect(event) {
	        var feature = event.feature;
            var cfeatures = feature.cluster;
            var cluster = event.feature.cluster;
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
		    	for (i=0;i<cfeatures.length; i++ ) { 
		    		var pjson2=cfeatures[i].attributes;
		    		for (var key in pjson2) { 
		    			if(key=="oid"){
		    				if(cfeatures[i]==cfeatures[cfeatures.length-1]){oids=oids+pjson2[key];}
		    				else{	oids=oids+pjson2[key]+",";	}
		    				}
						}
				}
            }
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
	var D = document;
    var maxh= Math.max(
        D.body.scrollHeight, D.documentElement.scrollHeight,
        D.body.offsetHeight, D.documentElement.offsetHeight,
        D.body.clientHeight, D.documentElement.clientHeight
    );
    var percenth=0.11*maxh;
    //alert(percenth);
    ///maxh-percenth
    var content = '<div style="overflow: auto;position: relative;display: block; max-height:'+(maxh-percenth)+'px;">';
    
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
    		var content2 = '<dd id="ddfeature'+attr[key].oid+'"><br><table>';
    		$.each( attr[key], function(k, v){
    			if(k!="oid"){
    				content2=content2+ "<tr><td><b>" + k + ":</b></td><td> " + v +"</td></tr>";
    				if(k==valuestring){price=v;price=Number(price);}
    			}
        		});
    		content2=content2+"</table>";
    		content = content +' '+valuestring+':<b> $'+price.toMoney();
    		content = content + '</b><br></dt><br>' + content2;
    		content = content +"<a class='openSlide' onclick='openSlide("+attr[key].oid+")'>"+arrayText[17]+"</a><br><br></dd>" ;
    	    //////ABRIR FOTOS
    	    //content = content +"";
    	conta++;
    	
    }
    content = content +"</div>"
    openInfo(content, attr);
}

function openInfo(content, attr){
    var div_popup
    if(document.getElementById('div_popup')){div_popup=document.getElementById('div_popup');div_popup.style.display="block";}
    else {div_popup= document.createElement("div");}
    var map_element=document.getElementById('map-id');
    map_width=map_element.offsetWidth;
    div_popup.id="div_popup";
    //div_popup.className="BasicWindow";
    div_popup.style.top="0";
    	/*if(userdataarray[0]!='0'){
    		div_popup.style.top="16.5em";
    	}*/
   // div_popup.style.removeProperty("left");
    div_popup.style.float="right";
    div_popup.style.left=String(((map_width/3)*2))+"px";
    div_popup.style.right="0";
    div_popup.style.overflow="hidden";
    div_popup.style.height="98%";
    div_popup.style.maxHeight="98%";
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
    	div_popup.innerHTML=div_popup.innerHTML+"<b>("+attr.length+") "+arrayText[0]+"</b>";
    }
    div_popup.innerHTML=div_popup.innerHTML+"<br><hr>";
    //attr.length
    divbody= document.createElement("div");
    divbody.id="divbody";
    divbody.style.overflow="hidden";
    map_element.appendChild(div_popup); 
    div_popup.appendChild(divbody);
    divbody.innerHTML=divbody.innerHTML+content;
    divbody.style.maxHeight="93%"
    //divbody.style.maxHeight=(div_popup.offsetHeight-10)+"px";
    divbody.style.width="inherit";
    divbody.style.position="relative";
    //
    //alert(div_popup.offsetHeight);
    document.getElementById('ClosePopup').onclick = function(){ 
    				document.getElementById('div_popup').style.display="none";
    				//pointModControl.deactivate();
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
    		var fss=selectSearchOIDinCluster(oidint, pointSearch_layer);
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
        		var fss2=selectSearchOIDinCluster(oidint,pointSearch_layer);
        		
        		//fss2.renderIntent="select";
        		//console.log(fss2.attributes.oid);
        		//console.log(fss2);
        		select.select(fss2);
        		//console.log(oidint);
        		//console.log(evtt.currentTarget.id.replace(/[^0-9]/gi, ''));
        		
        		//vector_layer.events.un({"moveend":reDrawGeojson});
    		}else{
    			//console.log('dd'+evtt.currentTarget.id);
    			//console.log(document.getElementById('dd'+evtt.currentTarget.id).id);
    		document.getElementById('dd'+evtt.currentTarget.id).style.display="none";
    		delete pointSearch_layer.events.listeners.featureselected;
    		pointSearch_layer.events.on({"featureselected": onFeatureSelect});
    		//map.controls[0].activate();
    			}

    	}
    }
////   	            
}
function selectSearchOIDinCluster(oidint, lyr)
{
	//oidint=String(oidint);
	for (var kk=0;kk<lyr.features.length;kk++){
		if(lyr.features[kk].cluster){
			for(var kv=0;kv<lyr.features[kk].cluster.length;kv++){
				//console.log("cluster",lyr.features[kk].cluster[kv].attributes.oid, oidint);
				if(String(lyr.features[kk].cluster[kv].attributes.oid)==oidint){
					//console.log("cluster");
					//console.log(lyr.features[kk].cluster[kv]);
					return lyr.features[kk];
					}
			}
		}else{
			//console.log("features",lyr.features[kk].attributes.oid, oidint);
			if(String(lyr.features[kk].attributes.oid)==oidint){
			//console.log("feature");
			//console.log(lyr.features[kk]);
			return lyr.features[kk];
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
function getPhotos(idfeature){
		var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi";
		var obj_d={"fid":idfeature, "task":"GetPhotos" };
		var req;
			 req=($.parseJSON($.ajax({
				 	type: "POST",
					url:  url,
					data:obj_d,
					dataType: "json",
					async: false
				}).responseText));
		return  req;
	 }

function getGeojson(){
	var extent = map.getExtent();
	if(parameters.EPSG_DATA!="3857"){
		var proj = new OpenLayers.Projection("EPSG:3857");
		var proj2 = new OpenLayers.Projection("EPSG:4326");
		extent = extent.transform(proj, proj2);
	}
	//console.log(extent);
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

function getRestrictions(){
	var url =document.getElementById ("baseURL").href+'index.php?option=com_geoi&task=GetRestrictions';
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
	
function reDrawGeojson() {
					//console.log("Write");
					//alert(map.getResolution());
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
    drawLayer.visibility=true;
    pointSearch_layer.visibility=false;
	pointSearch_layer.removeAllFeatures();
	document.getElementById("CloseMultiValuesWindow").click();
	document.getElementById('DataContainer').innerHTML="";
	document.getElementById('div_popup').style.display="none";
	vector_layer.visibility=true;
}

function SearchPoints(arr,userid){
	$("#map-id").css("cursor", "wait");
	$( "#Loading" ).show();
	//document.getElementById("Loading").display="block";
	document.getElementById("CloseMultiValuesWindow").click();
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
							var geom_box= geom_search[j];
							if(parameters.EPSG_DATA!="3857"){
								var proj = new OpenLayers.Projection("EPSG:3857");
								var proj2 = new OpenLayers.Projection("EPSG:4326");
								//extent = extent.transform(proj, proj2);
								//geom_box = geom_box.geometry.clone();
								geom_box.geometry.transform(proj, proj2);
							}
							geom_string=geom_string+geom_box.geometry;
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
			var geom_box= geom_search[j];
			if(parameters.EPSG_DATA!="3857"){
				var proj = new OpenLayers.Projection("EPSG:3857");
				var proj2 = new OpenLayers.Projection("EPSG:4326");
				//extent = extent.transform(proj, proj2);
				//geom_box = geom_box.geometry.clone();
				geom_box.geometry.transform(proj, proj2);
			}
			geom_string=geom_string+geom_box.geometry;
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
	if(userid!=''){
		search_datadef.push(userarray);
	}
	if(search_datadef.length>0){
		//console.log(search_datadef);
		drawLayer.visibility=false;
		var url =document.getElementById ("baseURL").href+"index.php?option=com_geoi&task=SearchPoints";
		var obj_data={"searchdata":search_datadef};
		//console.log(obj_data);
		var req;
		 req=($.parseJSON($.ajax({
			 	type: "POST",
				url:  url,
				data:obj_data,
				dataType: "json",
				async: false
			}).responseText));
		 if(userid==''){ loadFindPoints(req);}
		 else{loadUserPoints(req);}
		 
	}else {alert(arrayText[1]);}
	//console.log(req);
	$("#map-id").css("cursor", "default");
	$( "#Loading" ).hide();

}

function loadFindPoints(req)
{
	drawLayer.visibility=false;
	drawLayer.removeAllFeatures();
	vector_layer.visibility=false;
	vector_layer.removeAllFeatures();
	if(document.getElementById("InsertTask")) document.getElementById("InsertTask").setAttribute("editing","false");
	pointDrawControl.deactivate();
	
	////ADD MAP LAYER , GEOMETRY PROPERTIES
	var WKT_format = new OpenLayers.Format.WKT({
			  'internalProjection': new OpenLayers.Projection("EPSG:900913"),
			  'externalProjection': new OpenLayers.Projection("EPSG:"+parameters.EPSG_DATA)});
	pointSearch_layer.removeAllFeatures();
	pointSearch_layer.visibility=true;
	///
	var vector_search=[];
	var oids="";
	for (var r in req){
			vector_search.push( new OpenLayers.Feature.Vector(
					OpenLayers.Geometry.fromWKT(req[r].geom),
					req[r]));
			oids=oids+req[r].oid;
			if(req[r]!=req[req.length-1]){
				oids=oids+",";
			}
	}
	var selico=document.getElementById("SearchPolygon").getAttribute("selected");
	if(selico=="true"){polButtonClick();}
	select.activate();
	pointSearch_layer.addFeatures(vector_search);
	openPopUp(oids)
	pointSearch_layer.redraw();

}

function loadUserPoints(req){

	var WKT_format = new OpenLayers.Format.WKT();
	drawLayer.removeAllFeatures();
	drawLayer.visibility=true;
	///
	var vector_search=[];
	var oids="";
	for (var r in req){
			vector_search.push( new OpenLayers.Feature.Vector(
					OpenLayers.Geometry.fromWKT(req[r].geom),
					req[r]));
			oids=oids+req[r].oid;
			if(req[r]!=req[req.length-1]){
				oids=oids+",";
			}
	}
	if(select){select.activate();}
	drawLayer.addFeatures(vector_search);
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
		//document.getElementById('ShowValues'+contentname).setAttribute('title',arrayText[7]);
		$('#ShowValues'+name).attr('title',arrayText[7]);
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
		//document.getElementById('ShowValues'+contentname).setAttribute('title',arrayText[3]);
		$('#ShowValues'+name).attr('title',arrayText[3]);
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
				for (i=0;i<content_arr.length;i++){ html_content=html_content+'<option value="'+content_arr[i]+'">'+toTitleCase(content_arr[i])+'</option> ';}
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

///http://www.dotnetpickles.com/2012/04/convert-string-to-titlecase-using.html
function toTitleCase(str) {
    return str.replace(/\w\S*/g, function (txt) { return txt.charAt(0).toUpperCase() +                                                                txt.substr(1).toLowerCase(); });
}

