<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
//JHtml::_('behavior.tooltip');
?>
<!DOCTYPE html>
<html >
    <head>
	<base href="<?php echo JURI::root()?>" >
	<title><?php echo JTEXT::_('COM_GEOI')?></title>
	<script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="<?php echo JURI::root()."media/com_geoi/openlayers/OpenLayers.js" ?>"></script>
	<link rel="stylesheet" href="<?php echo JURI::root()."media/com_geoi/css/map.css" ?>" type="text/css">
    </head>
	<body onload="init()">
        <div id="map-id" >
	        	<div id="TaskButtons">
		              <img id="SearchTask" class="ImageTask" style="position: relative;" src="media/com_geoi/images/chart_search.png"></img>
		              <img id="AuthTask" class="ImageTask" style="position: relative;" src="media/com_geoi/images/Lock.png"></img>
	        	</div>
	        	<div id="SearchWindow" class="BasicWindow">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
						<label for="login" class="TitleWindow"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_TITLE')?></b></label><br>
						<label id="typep" class="LabelWindow"><b>ATRIBUTO:</b>
						<input type="text" name="atributo" id="username" class="InputLogin"></label>
						<input type="image" src="media/com_geoi/images/earth_search.png" id="SearchButton" style="width:30px;heigth:30px;"><br>
	        	</div>
	        	<div id="LoginWindow" class="BasicWindow" style="display: none;">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
						<label id="SearchTitle" class="TitleWindow"><b>Login</b></label><br>
						<label for="login" class="LabelWindow"><b>USERNAME:</b>
						<input type="text" name="username" id="username" class="InputLogin"></label>
						<label for="login" class="LabelWindow"><b>PASSWORD:</b><br>
						<input type="password" name="pwd" id="pwd" class="InputLogin"></label>
						<input type="image" src="media/com_geoi/images/send.png" id="loginButton" value="Login" style="float:rigth;width:15px;heigth:15px;"><br>
	        	</div>
        </div>
        <script src="<?php echo JURI::root()."media/com_geoi/js/geoi.js" ?>"></script>
	</body>
</html>



