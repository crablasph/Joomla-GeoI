<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
//JHtml::_('behavior.tooltip');
?>
<!DOCTYPE html>
<html >
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<base href="<?php echo JURI::root()?>" >
	<title><?php echo JTEXT::_('COM_GEOI')?></title>
	<script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo JURI::root()."media/com_geoi/openlayers/OpenLayers.js" ?>"></script>
	<link rel="stylesheet" href="<?php echo JURI::root()."media/com_geoi/css/map.css" ?>" type="text/css">
    </head>
	<body onload="init()">
        <div id="map-id" >
	        	<div id="TaskBar">
		              <div class="TaskDiv"><img id="SearchTask" class="ImageTask"  src="media/com_geoi/images/chart_search.png"></img></div>
		              <div class="TaskDiv"><img id="AuthTask" class="ImageTask"  src="media/com_geoi/images/Lock.png"></img></div>
	        	</div>
	        	<div id="SearchWindow" class="BasicWindow">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
					<label class="TitleWindow"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_TITLE')?></b></label><hr>
					<label id="AttrTitle" class="SubTitleWindow" open="closed"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_CAR')?></b></label>
					<div id="contattr" style="display:none;">
					<br>
						<?php foreach ($this->search_array as $search){
							
							if ($search[1]=="CAT") {
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$values="";
								foreach ($search[3] as $se){$values=$values.$se;if(end($search[3])!=$se){$values=$values.',';}}
								echo '<input type="image" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'cat\')">';
								echo '<br>';
								
							}elseif($search[1]=="INT"){
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$min=$search[3][0];
								$max=$search[3][1];
								$values=$min.','.$max;
								echo '<input type="image" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'int\')">';
								echo "<br> ";
							}
							
							//echo '<br>';
						}
						?>
						</div>
						<br>
						<label id="PolTitle" class="SubTitleWindow" open="closed"><b><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_POL'))?></b></label>
						<div id="contpol" style="display:none;">
						<?php foreach ($this->search_array as $search){
							if ($search[1]=="POL") {		
								//echo "<div>";
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$values="";
								foreach ($search[3] as $se){$values=$values.$se;if(end($search[3])!=$se){$values=$values.',';}}
								echo '<input type="image" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'cat\')">';
								echo '<br>';
						
							}					
						}
						?>
						
						<br>
						<div class="LabelWindow"><b><strong><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_DRAW')) ?>: </strong></b></div>
						<input type="image" src="media/com_geoi/images/pol_off.png" id="SearchPolygon" selected="false" style="width:22px;heigth:22px;" onclick="polButtonClick()" >
						</div>
						<br>
						<input type="image" src="media/com_geoi/images/earth_search.png" id="SearchButton" style="width:30px;heigth:30px;"><br>
	        	</div>
	        	<div id="LoginWindow" class="BasicWindow" style="display: none;">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
						<label id="SearchTitle" class="TitleWindow"><b>Login</b></label><br><hr>
						<label class="LabelWindow"><b>USERNAME:</b>
						<input type="text" name="username" id="username" class="InputLogin"></label>
						<label class="LabelWindow"><b>PASSWORD:</b><br>
						<input type="password" name="pwd" id="pwd" class="InputLogin"></label>
						<input type="image" src="media/com_geoi/images/send.png" id="loginButton" value="Login" style="float:rigth;width:15px;heigth:15px;"><br>
	        	</div>
	        	<div id="MultiValuesWindow" class="BasicWindow" style="display: none;" data-content="">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
	        		<br>
	        		<div id="DataContainer" ></div>
	        	</div>
        </div>
        <script src="<?php echo JURI::root()."media/com_geoi/js/geoi.js" ?>"></script>
	</body>
</html>



