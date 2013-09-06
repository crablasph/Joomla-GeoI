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
    <script src="<?php echo JURI::root()."media/com_geoi/js/geoi_map.js" ?>"></script>
	<link rel="stylesheet" href="<?php echo JURI::root()."media/com_geoi/css/map.css" ?>" type="text/css">
    </head>
	<body onload="init()">
        <div id="map-id" >
	        	<div id="TaskButtons">
		              <img id="SearchTask" class="ImageTask" style="position: relative;" src="media/com_geoi/images/chart_search.png"></img>
		              <img id="AuthTask" class="ImageTask" style="position: relative;" src="media/com_geoi/images/Lock.png"></img>
	        	</div>
	        	<div id="BasicWindow">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
	        	</div>
        </div>
        <script src="<?php echo JURI::root()."media/com_geoi/js/geoi_windows.js" ?>"></script>
	</body>
</html>



