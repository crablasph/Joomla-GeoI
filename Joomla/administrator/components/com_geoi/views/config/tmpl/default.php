<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
$uploadurl= JURI::current();
$updatefield=$uploadurl."?option=com_geoi&task=UpdateField";
$deletefield=$uploadurl."?option=com_geoi&task=DeleteField";
$addfield=$uploadurl."?option=com_geoi&task=AddField";
$uploadsymbol=$uploadurl."?option=com_geoi&task=SetSymbol";
$addsymbol=$uploadurl."?option=com_geoi&task=AddSymbol";
$deletesymbol=$uploadurl."?option=com_geoi&task=DelSymbol";
$uploadurl= $uploadurl."?option=com_geoi&task=SetParameter";
?>



<h2><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_GENERAL'));?></h2>
<hr>
<table>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_TITLE'));?></b></td>
			<td><input type="text" name="TITLE" value="<?php echo $this->title;?>"></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>

<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_LYRN'));?></b></td>
			<td><input type="text" name="LYR_NAME" value="<?php echo $this->lname;?>"></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_CLUSTERD'));?></b></td>
			<td><input type="number" name="CLUSTER_DISTANCE" value="<?php echo (int)$this->clusterd;?>"></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_CLUSTERT'));?></b></td>
			<td><input type="number" name="CLUSTER_THRESHOLD" value="<?php echo (int)$this->clustert;?>"></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_ULIMITIMAGE'));?></b></td>
			<td><input type="number" name="ULIMIT_IMAGES" value="<?php echo (int)$this->ulimage;?>"></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_ULIMITSHAPE'));?></b></td>
			<td><input type="number" name="ULIMIT_SHAPE" value="<?php echo (int)$this->ulshape;?>"></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
</table>

<div id="map" class="smallmap"></div>
<table>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo Jtext::_('COM_GEOI_PARAMETER_SRSDATA');?></b></td>
		<td> <select name="EPSG_DATA">
				<option <?php if($this->epsgdata=="4326") echo "selected" ;?>>4326</option>
				<option <?php if($this->epsgdata=="3857") echo "selected" ;?>>3857</option>
			</select></td>
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo Jtext::_('COM_GEOI_PARAMETER_SRSDISP');?></b></td>
			<td> <select name="EPSG_DISP">
				<option <?php if($this->epsgdisp=="4326") echo "selected" ;?>>4326</option>
				<option <?php if($this->epsgdisp=="3857") echo "selected" ;?>>3857</option>
			</select></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_MAXRESOLUTION')." (".$this->maxresolution.")");?></b></td>
			<td><input type="number" name="MAXRESOLUTION" id="MAXRESOLUTION" value="<?php echo (int)$this->maxresolution ?>">
			</input></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>

<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_BOUNDS'));?></b></td>
			<td><input type="text" name="BOUNDS" id="BOUNDS" value="<?php echo $this->bounds ?>">
			</input></td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
</table>


<br><br>
<h2><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_ICONS'));?></h2>
<hr>
<table>
<tr>
	<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_SYMBOLFIELD'));?></b></td>
			<td>
				<select name="SYMBOLOGY_FIELD">
				<?php 
					foreach ($this->fields as $f)
					{
						//echo "<option";
						if($this->symbolfield==$f)	echo "<option selected>";
						else  echo "<option>";
						//echo ">";
						echo $f;
						echo "</option>";
					}
				?>
				</select>
			</td>		
		<td><input type="submit"   value="<?php echo Jtext::_('COM_GEOI_CHANGE_PARAMETER');?>"></td>
	</form>
</tr>
</table>

<br>
<table>
	<thead>
		<tr>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_VALUE'));?></b></td>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_PATH'));?></b></td>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_IMAGE'));?></b></td>
			<td><b></b></td>
		</tr>
	<thead>
	<tbody>
		<?php
		
			foreach ($this->symbols as $sym){
				$symarr = get_object_vars($sym);
				echo '<form method="post"enctype="multipart/form-data" action='.$uploadsymbol.'>';
				echo '<tr>';
				echo '<td>';
				if($symarr["SYMVALUE"]=='editsymbol'||$symarr["SYMVALUE"]=='favicon')
					echo '<label>'.$symarr["SYMVALUE"].'</label>';
				else
					echo '<input type="text" name="SYMVALUE" value="'.$symarr["SYMVALUE"].'" ></input>';
				
				echo '</td>';
				echo '<td>';
				echo '<input type="file" name="file" ></input>';
				echo '</td>';
				echo '<td>';
				echo '<image src="'.JURI::base().'../'.$symarr["PATH"].'"  height="42px" width="42px"></image>';
				echo '</td>';
				echo '<td>';
				echo '<input type="submit" name="submit" value="'.utf8_encode(Jtext::_('COM_GEOI_SYMBOL_MODIFY')).'"></input>';
				echo '</td>';
				echo '<input type="hidden" value="'.$symarr["id"].'" name="id" value="'.$symarr["id"].'">';
				echo '</form>';
				
				echo '<form method="post"enctype="multipart/form-data" action='.$deletesymbol.'>';
				echo '<td>';
				if($symarr["SYMVALUE"]!='editsymbol'&&$symarr["SYMVALUE"]!='favicon')
						echo '<input type="submit" value="'.utf8_encode(Jtext::_('COM_GEOI_SYMBOL_DELETE')).'" ></input>';
				echo '<input type="hidden" value="'.$symarr["id"].'" name="id" value="'.$symarr["id"].'">';
				echo '</td>';
				echo '</form>';
				
				echo '</tr>';
				
			}
		?>
	</tbody>
</table>
<br>
<b><?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_NEW')); ?></b><br>
<form method="post"enctype="multipart/form-data" action=<?php echo $addsymbol ?>>
<table>
	<tr>
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_VALUE'));?></b></td>
		<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_PATH'));?></b></td>
		</td>
	</tr>
	<tr>
		<td><input type="text" name="SYMVALUE" value="" ></input></td>
		<td><input type="file" name="file" value="" ></input></td>		
	</tr>
	
</table>
<input type="submit" value="<?php echo utf8_encode(Jtext::_('COM_GEOI_SYMBOL_ADDTEXT')) ;?>"></input>
</form>


<br><br>
<h2><?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_ATTRIBUTES'));?></h2>
<hr>
<br>
<?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_NOTEFIELD0'));?>
<br>
<?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_NOTEFIELD1'));?>
<br>
<?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_NOTEFIELD2'));?>
<br>
<?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_NOTEFIELD3'));?>
<br>
<?php echo utf8_encode(Jtext::_('COM_GEOI_PARAMETER_NOTEFIELD4'));?>
<br><br>
<table>
	<thead>
		<tr>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_NAME'));?></b></td>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_ALIAS'));?></b></td>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_TYPE'));?></b></td>
			<td><b><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_REST'));?></b></td>
			<td></td>
			<td></td>
		</tr>
	</thead>
	<tbody>
		<?php 
			foreach ($this->fieldsatt as $field){
				echo '<form method="post"enctype="multipart/form-data" action='.$updatefield.'>';
				echo '<tr>';
				echo '<td>';
				echo '<label>'.$field['name'].'</label>';
				echo '<input type="hidden" name="namefield" value="'.$field['name'].'"></input>';
				echo '</td>';
				echo '<td>';
				echo '<input type="text" name="alias" value="'.$field['alias'].'"></input>';
				echo '</td>';
				echo '<td>';
				echo '<select name="type" onchange=>';
					if($field['type']=='CAT'){
						echo '<option value=""></option>';
						echo '<option value="CAT" selected="selected">'.utf8_encode(Jtext::_('COM_GEOI_FIELD_CAT')).'</option>';
						echo '<option value="INTE">'.utf8_encode(Jtext::_('COM_GEOI_FIELD_INT')).'</option>';
						}
					else if($field['type']=='INT'){
						echo '<option value=""></option>';
						echo '<option value="CAT">'.utf8_encode(Jtext::_('COM_GEOI_FIELD_CAT')).'</option>';
						echo '<option value="INTE" selected="selected">'.utf8_encode(Jtext::_('COM_GEOI_FIELD_INT')).'</option>';
					}else {
						echo '<option value="" selected="selected"></option>';
						echo '<option value="CAT">'.utf8_encode(Jtext::_('COM_GEOI_FIELD_CAT')).'</option>';
						echo '<option value="INTE">'.utf8_encode(Jtext::_('COM_GEOI_FIELD_INT')).'</option>';
					}

				//$field['type']
				echo '</select>';
				echo '</td>';
				echo '<td>';
				echo '<input type="text" name="restriction" value="'.utf8_encode($field['restriction']).'"></input>';
				echo '</td>';
				echo '<td>';
				echo '<input type="submit" value="'.utf8_encode(Jtext::_('COM_GEOI_SYMBOL_MODIFY')).'"></input>';
				echo '</td>';
				echo '<input type="hidden" name="name" value="'.utf8_encode($field['name']).'"></label>';
				//echo '</tr>';
				echo '</form>';
				//echo '<tr>';
				echo '<form method="post"enctype="multipart/form-data" action='.$deletefield.'>';
				echo '<input type="hidden" name="namefield" value="'.$field['name'].'"></input>';
				echo '<td>';
				if($field['name']!='TYPEP'&&$field['name']!='TYPEO'&&$field['name']!='VALUE')
					echo '<input type="submit" value="'.utf8_encode(Jtext::_('COM_GEOI_SYMBOL_DELETE')).'"></input>';
				echo '</td>';
				echo '</tr>';
				echo '</form>';
				
				
				

				}

				//print_r($this->fieldsatt);
		?>
	</tbody>
</table>
<br><br>
<b><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_NEW'));?></b>
<form action=<?php echo $addfield; ?> method="post"enctype="multipart/form-data">
<table>
		<tr><td><?php echo  utf8_encode(Jtext::_('COM_GEOI_FIELD_NAME'));?></td><td><input type="text" name="fieldname" ></input></td></tr>	
		<tr><td><?php echo  utf8_encode(Jtext::_('COM_GEOI_FIELD_ALIAS'));?></td><td><input type="text" name="alias" ></input></td></tr>		
		<tr><td><?php echo  utf8_encode(Jtext::_('COM_GEOI_FIELD_TYPE'));?></td>
			<td><select name="type">
			<option></option>
			<option VALUE="CAT"><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_CAT'));?></option>
			<option VALUE="INTE"><?php echo utf8_encode(Jtext::_('COM_GEOI_FIELD_INT'));?></option>
			</select></td></tr>
		<tr><td><?php echo  utf8_encode(Jtext::_('COM_GEOI_FIELD_LENGTH'));?></td><td><input type="number" name="length" ></td></tr>
		<tr><td><?php echo  utf8_encode(Jtext::_('COM_GEOI_FIELD_REST'));?></td><td><input type="text" name="restrictions" ></input></td></tr>
			
</table>			
		<input type="submit"   value="<?php echo Jtext::_('COM_GEOI_FIELD_NEWSEND');?>">
</form>




<script type="text/javascript">
			//var body = document.getElementsByTagName("body")[0];
			//console.log(body);
			//body.addEventListener("load", init, false);
			//body.onload = init;
			
			if (document.addEventListener) {
				  document.addEventListener("DOMContentLoaded", init, false);
				}
			else if (/WebKit/i.test(navigator.userAgent)) { // sniff
					  var _timer = setInterval(function() {
					    if (/loaded|complete/.test(document.readyState)) {
					      init(); // call the onload handler
					    }
					  }, 10);
					}
			else window.onload = init;
			var map;

			
			
            function init(){
                map = new OpenLayers.Map('map', {
                    controls: [
                        new OpenLayers.Control.Navigation(),
                        new OpenLayers.Control.ZoomPanel(),
                        new OpenLayers.Control.PanPanel(), 
                        //new OpenLayers.Control.LayerSwitcher({'ascending':false}),
                        new OpenLayers.Control.MousePosition(),
                        new OpenLayers.Control.KeyboardDefaults()
                    ],
                    numZoomLevels: 50,
                    projection: new OpenLayers.Projection("EPSG:"+<?php echo $this->epsgdata; ?>),
					displayProjection: new OpenLayers.Projection("EPSG:"+<?php echo $this->epsgdisp; ?>),
                    eventListeners: {
                    	"moveend":ModParameters
                    	
                   }
                    
                });
                var osm = new OpenLayers.Layer.OSM();
                map.addLayers([osm]);
                var str = "<?php echo $this->bounds ?>";
        		str=str.split(",");
        		var bounds = new OpenLayers.Bounds(str[0],str[1],str[2],str[3]);
                map.zoomToExtent(bounds);
                
            }

            function ModParameters(){
                //alert("Extent:"+map.getExtent());
                //alert("Resolution: "+map.getResolution());
            	document.getElementById("MAXRESOLUTION").value=map.getResolution();
            	document.getElementById("BOUNDS").value=map.getExtent();
            }
 </script>
		
<hr>
