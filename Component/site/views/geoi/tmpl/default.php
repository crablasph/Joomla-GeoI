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
	<link rel="stylesheet" href="<?php echo JURI::root()."media/com_geoi/js/noUiSlider/nouislider.space.css" ?>" type="text/css">
    </head>
	<body onload="init()">
        <div id="map-id" >
	        	<div id="TaskBar">
		              <img id="SearchTask" class="ImageTask" style="position: relative;" src="media/com_geoi/images/chart_search.png"></img>
		              <img id="AuthTask" class="ImageTask" style="position: relative;" src="media/com_geoi/images/Lock.png"></img>
	        	</div>
	        	<div id="SearchWindow" class="BasicWindow">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
						<label for="loginTitle" class="TitleWindow"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_TITLE')?></b></label><br><hr>
						<?php foreach ($this->search_array as $search){
							echo '<span class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
							if ($search[1]=="CAT") {
								echo ' <select class="SelectList" id="'.$search[0].'" multiple="multiple"> ';
								//echo '<option value=""> </option>';
								foreach ($search[3] as $se){echo '<option value="'.$se.'" selected>'.$se .'</option> ';}
								echo "</select>";
								echo '<br>';
							}elseif($search[1]=="INT"){
								echo '<div class="SliderContainer" id="'.$search[0].'"> ';
								//echo "<br> ";
								$valerror=JTEXT::_('COM_GEOI_SEARCH_VAL_ERROR');
								echo '<span class="RangeText">min:</span><input type="number" id="minbox'.$search[0].'" class="MinBox" value="'.$search[3][0].'" min="'.$search[3][0].'" max="'.$search[3][1].'" onclick="$(\'#max'.$search[0].'\').hide();$(\'#min'.$search[0].'\').show();" onchange="minr=document.getElementById(\'min'.$search[0].'\').value;minall=document.getElementById(\'minbox'.$search[0].'\').getAttribute(\'min\');min=document.getElementById(\'minbox'.$search[0].'\').value;max=document.getElementById(\'maxbox'.$search[0].'\').value;if(Number(min)>Number(max)||Number(min)<Number(minall)){alert(\''.$valerror.'\');document.getElementById(\'minbox'.$search[0].'\').value=minr;}else{document.getElementById(\'min'.$search[0].'\').value=min;}">';
								echo '<span class="RangeText">max:</span><input type="number" id="maxbox'.$search[0].'" class="MaxBox" value="'.$search[3][1].'" min="'.$search[3][0].'" max="'.$search[3][1].'" onclick="$(\'#min'.$search[0].'\').hide();$(\'#max'.$search[0].'\').show();" onchange="maxr=document.getElementById(\'max'.$search[0].'\').value;maxall=document.getElementById(\'maxbox'.$search[0].'\').getAttribute(\'max\');min=document.getElementById(\'minbox'.$search[0].'\').value;max=document.getElementById(\'maxbox'.$search[0].'\').value;if(Number(max)<Number(min)||Number(max)>Number(maxall)){alert(\''.$valerror.'\');document.getElementById(\'maxbox'.$search[0].'\').value=maxr;}else{document.getElementById(\'max'.$search[0].'\').value=max;}">';
								echo '<br>';
								
								echo '<input type="range" class="MinSlider" id="min'.$search[0].'" min="'.$search[3][0].'" max="'.$search[3][1].'" value="'.$search[3][0].'" onchange="$(\'#minbox'.$search[0].'\').val($(\'#min'.$search[0].'\').val()); $(\'#max'.$search[0].'\').attr(\'min\',$(\'#min'.$search[0].'\').val());"> ';
								echo '<input type="range" class="MaxSlider" id="max'.$search[0].'" min="'.$search[3][0].'" max="'.$search[3][1].'" value="'.$search[3][1].'" onchange="$(\'#maxbox'.$search[0].'\').val($(\'#max'.$search[0].'\').val()); $(\'#min'.$search[0].'\').attr(\'max\',$(\'#max'.$search[0].'\').val());"> ';
								echo '</div>';
							}
							echo '</span>';
							//echo '<br>';
						}
						?>
						<label for="PolTitle" class="TitleWindow"><b><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_POL'))?></b></label><br>
						<input type="image" src="media/com_geoi/images/earth_search.png" id="SearchButton" style="width:30px;heigth:30px;"><br>
	        	</div>
	        	<div id="LoginWindow" class="BasicWindow" style="display: none;">
	        		<img id="CloseWindow" class="CloseWindow" style="position: relative;" src="media/com_geoi/images/close.png"></img>
						<label id="SearchTitle" class="TitleWindow"><b>Login</b></label><br><hr>
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



