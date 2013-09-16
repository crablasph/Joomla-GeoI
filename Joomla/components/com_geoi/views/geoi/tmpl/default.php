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
					
					<label class="SubTitleWindow"><b><?php echo JTEXT::_('COM_GEOI_SEARCH_CAR')?></b></label>
					<br>
						<?php foreach ($this->search_array as $search){
							
							if ($search[1]=="CAT") {
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$values="";
								foreach ($search[3] as $se){$values=$values.$se;if(end($search[3])!=$se){$values=$values.',';}}
								//$values_json=json_encode($values);
								echo '<input type="image" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'cat\')">';
								//echo '<div class="SelectDiv">';
								//echo ' <select class="SelectList" id="'.$search[0].'" multiple="multiple"> ';
								//foreach ($search[3] as $se){echo '<option value="'.$se.'" selected>'.$se .'</option> ';}
								//echo "</select>";
								//echo '</div>';
								echo '<br>';
								
							}elseif($search[1]=="INT"){
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								//echo "<br> ";
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
								
								///DESDE ACA NUEVO
								$min=$search[3][0];
								$max=$search[3][1];
								$values=$min.','.$max;
								echo '<input type="image" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'int\')">';
								echo "<br> ";
							}
							
							//echo '<br>';
						}
						?>
						<label id="PolTitle" class="SubTitleWindow"><b><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_POL'))?></b></label><br>
						<?php foreach ($this->search_array as $search){
							if ($search[1]=="POL") {		
								//echo "<div>";
								echo '<div class="LabelWindow"> <b><strong>'.$search[2].':</strong></b>';
								echo '</div>';
								$values="";
								foreach ($search[3] as $se){$values=$values.$se;if(end($search[3])!=$se){$values=$values.',';}}
								//$values_json=json_encode($values);
								echo '<input type="image" src="media/com_geoi/images/rightblue.png" id="ShowValues'.$search[0].'" class="ShowValuesButton" open="closed" onclick="showValues(\''.$search[0].'\', \''.$values.'\',\'cat\')">';
								//echo ' <select class="SelectListPOL" id="'.$search[0].'"';
								//foreach ($search[3] as $se){echo '<option value="'.$se.'" selected>'.$se.'</option> ';}
								//echo "</select>";
								//echo "</div>";
								echo '<br>';
						
							}					
						}
						?>
						<div class="LabelWindow"><b><strong><?php echo utf8_encode(JTEXT::_('COM_GEOI_SEARCH_DRAW')) ?>: </strong></b></div>
						<input type="image" src="media/com_geoi/images/pol_off.png" id="SearchPolygon" selected="false" style="width:22px;heigth:22px;" onclick="polButtonClick()" >
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



