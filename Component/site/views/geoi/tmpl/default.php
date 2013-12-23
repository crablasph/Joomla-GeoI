<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
//JHtml::_('behavior.tooltip');
?>
<!DOCTYPE html>
<html >
    <head>
    <?php JHTML::_('behavior.mootools'); ?>
    <?php	$user = JFactory::getUser(); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<base id="baseURL" href="<?php echo JURI::root()?>" >
	<link rel="icon"  type="image/png"  href="<?php echo utf8_encode(JURI::root()).$this->favicon;?>">
	<title><?php echo utf8_encode($this->title)?></title>
	<script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo JURI::root()."media/com_geoi/openlayers/OpenLayers.js" ?>"></script>
    <link rel="stylesheet" href="<?php echo JURI::root()."media/com_geoi/css/map.css" ?>" type="text/css">
    </head>
	<body onload="init()">
        <div id="map-id" >
	        	<div id="TaskBar">
		              <div class="TaskDiv"><img id="SearchTask" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_GO'));?>' class="ImageTask"  src="media/com_geoi/images/chart_search.png"></img></div>
		              <div class="TaskDiv"><img id="AuthTask" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_LOGIN'));?>' class="ImageTask"  src="media/com_geoi/images/Lock.png"></img></div>
		              <div class="TaskDiv"><img id="LayerS" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_LAYERSWITCHER'));?>' class="ImageTask"  src="media/com_geoi/images/layers_map.png"></img></div>
		             
		             <?php
			             if($user->id!=0){
				             echo '<div class="TaskDiv"><img id="InsertTask" editing="false" title="'.utf8_encode(JTEXT::_('COM_GEOI_INSERT')).'" class="ImageTask"  src="media/com_geoi/images/pencildraw.png"></img></div>';
			              }
			          ?>
	        	</div>
	        	<div id="SearchWindow" class="BasicWindow">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
					<label class="TitleWindow"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_TITLE')?></b></label><hr>
					<label id="AttrTitle" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_EXPAND'));?>' class="SubTitleWindow" open="closed"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_CAR')?></b></label><br><hr>
					<div id="contattr" class="conattr">
						<?php foreach ($this->search_array as $search){
							if ($search[1]=="CAT") {
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$values="";
								foreach ($search[3] as $se){$values=$values.$se;if(end($search[3])!=$se){$values=$values.',';}}
								echo '<input type="image" title="'.utf8_encode(JTEXT::_('COM_GEOI_SEARCH_EXPAND')).'" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'cat\')">';
								echo '<br>';
							}elseif($search[1]=="INT"){
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								if(count($search[3])==1) $min=0;
								else $min=$search[3][0];
								if(isset($search[3][1])) $max=$search[3][1];
								else $max=1;
								$values=$min.','.$max;
								echo '<input type="image" title="'.utf8_encode(JTEXT::_('COM_GEOI_SEARCH_EXPAND')).'" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'int\')">';
								echo "<br> ";
							}
							
							//echo '<br>';
						}
						?>
						</div>
						<label id="PolTitle" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_EXPAND'));?>'  class="SubTitleWindow" open="closed"><b><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_POL'))?></b></label><br><hr>
						<div id="contpol" class="conattr" >
						<?php foreach ($this->search_array as $search){
							if ($search[1]=="POL") {		
								//echo "<div>";
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$values="";
								foreach ($search[3] as $se){$values=$values.$se;if(end($search[3])!=$se){$values=$values.',';}}
								echo '<input type="image" title="'.utf8_encode(JTEXT::_('COM_GEOI_SEARCH_EXPAND')).'" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'cat\')">';
								echo '<br>';
						
							}					
						}
						?>
					<script>
						  var jsonsearch = jQuery.parseJSON('<?php echo json_encode($this->search_array)?>');
						  var arrayText = Array();
						  var userdataarray=Array();
						  userdataarray[0]='<?php echo $user->id; ?>';
						  userdataarray[1]='<?php echo $user->username; ?>';
						  userdataarray[2]='<?php echo $user->email; ?>';
						  var userarray=Array();
						  userarray[0]='USER';
						  userarray[1]='USER';
						  userarray[2]=userdataarray;
						  //console.log(userid, username, useremail);
						  
						 //arrayText[0] = '<?php echo utf8_encode(JTEXT::_(''));?>';
						 arrayText[0] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_RESULT_TITTLE'));?>';
						 arrayText[1] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_ERRORVAL'));?>';
						 arrayText[2] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_ERRORIM'));?>';
						 arrayText[3] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_EXPAND'));?>';
						 arrayText[4] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_GO'));?>';
						 arrayText[5] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_CLEAR'));?>';
						 arrayText[6] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_LOGIN'));?>';
						 arrayText[7] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_COLLAPSE'));?>';
						 arrayText[8] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_SAVEDATA'));?>';
						 arrayText[9] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_DELETEFEATURE'));?>';
						 arrayText[10] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_MODIFYFEATURE'));?>';
						 arrayText[11] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_MODIFIEDFEATURE'));?>';
						 arrayText[12] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_DELETEDFEATURE'));?>';
						 arrayText[13] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_INVALIDDATA'));?>';
						 arrayText[14] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_INSERTEDDATA'));?>';
						 arrayText[15] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_DRAWLAYERNAME'));?>';
						 arrayText[16] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_LAYERSEARCH'));?>';
						 arrayText[17] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_OPENPIC'));?>';
						 arrayText[18] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_UPLOADPIC'));?>';
						 arrayText[19] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_RESTRICTFILEUP'));?>';
						 arrayText[20] ='<?php echo JURI::root()."media/com_geoi/css/map.css" ?>';
						 arrayText[21] = "http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js";
						 arrayText[22] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_DELETEPIC'));?>';
						 arrayText[23] = '<?php echo utf8_encode(JTEXT::_('COM_GEOI_DELETEDPIC'));?>';
				
				</script>
						<div class="LabelWindow"><b><strong><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_DRAW')) ?>: </strong></b></div>
						<input type="image" src="media/com_geoi/images/pol_off.png" id="SearchPolygon" selected="false" style="width:22px;heigth:22px;" onclick="polButtonClick()" >
						</div>
						<br>
						<input type="image" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_GO'));?>' title="Buscar" src="media/com_geoi/images/earth_search.png" id="SearchButton" style="width:30px;heigth:30px;" onclick="SearchPoints(jsonsearch,'');">
						<input type="image" title='<?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_CLEAR'));?>' title="Limpiar Busqueda" src="media/com_geoi/images/window_close.png" id="ClearButton" style="width:30px;heigth:30px;" onclick="ClearPoints();"><br>
				</div>
	        	<div id="LoginWindow" class="BasicWindow" style="display: none;">
	        			<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
						<label id="SearchTitle" class="TitleWindow"><b>Login</b></label><br><hr>
						<!--
						<label class="LabelWindow"><b>USERNAME:</b>
						<input type="text" name="username" id="username" class="InputLogin"></label>
						<label class="LabelWindow"><b>PASSWORD:</b><br>
						<input type="password" name="pwd" id="pwd" class="InputLogin"></label>
						<input type="image" src="media/com_geoi/images/send.png" id="loginButton" value="Login" style="float:rigth;width:15px;heigth:15px;"><br>
						-->
						<?php jimport('joomla.application.module.helper');
						// this is where you want to load your module position
						$modules = JModuleHelper::getModule('login'); 
						//foreach($modules as $module)
						//{
						echo JModuleHelper::renderModule($modules);
						//}
						?>
	        	</div>
	        	<div id="MultiValuesWindow" class="BasicWindow" style="display: none;" data-content="">
	        		<img id="CloseMultiValuesWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
	        		<br>
	        		<div id="DataContainer" ></div>
	        	</div>
	        	<div id="LayerDisplayControl" class="BasicWindow" style="display: none;">
	        		 	<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
	        	       	<label id="SearchTitle" class="TitleWindow"><b><?php echo utf8_encode(JTEXT::_('COM_GEOI_LAYERSWITCHER'));?></b></label><br><hr>
			        	<div id="ContainerDisplayControl" ></div>
	        	</div>
	        	<div id="movecontrols" style="top: 1em; left: 90%; right: 0px; overflow: hidden; height: 20em;">
	        	<div id="panpanel" style="position: relative; z-index: 1009;right: 5em;top: 10em;float:left;"></div>
	        	<div id="zoompanel" style="position: relative; z-index: 1009;right: 5em;top: 15em;float:left;"></div>
	        	</div>
	        	<div id="Loading" class="loadingdiv" style="display:none;"><img id="LoadingImage" src="media/com_geoi/images/rotearth1.gif"></div>
	        	<iframe id="hiddenFrame" style="display:none;" onload="responseUploads()" src="index.php?option=com_geoi&task=UploadImages" name="hiddenFrame"></iframe>
        </div>
        <script src="<?php echo JURI::root()."media/com_geoi/js/geoi.js" ?>"></script>
	</body>
</html>



