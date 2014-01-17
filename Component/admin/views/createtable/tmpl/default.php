<html>
<body>
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
$savedataurl= JURI::current(); 
//$savedataurl= $savedataurl."?option=com_geoi&task=load&layout=upload";
$savedataurl= $savedataurl."?option=com_geoi&task=savedata";
?>


<?php echo JText::_('COM_GEOI_FRMSAVE_MSG')?>
<br>
<br>

<form action=<?php echo $savedataurl; ?> method="post"enctype="multipart/form-data">
	<table border="1">
		<?php 
			foreach($this->FieldsArray as $fields){
			
				echo "<tr><td>";
				echo $fields[0]." ".$fields[1];
				echo "</td><td>";
				echo '<select name="'.$fields[0].'">';
				if($fields[0]!="TYPEP"&&$fields[0]!="TYPEO"&&$fields[0]!="VALUE"&&$fields[0]!="TEL1") 
					echo "<option></option>";
					foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}
				echo "</select></td></tr>";

			}	
		?>
	</table>
<input type="hidden" name="Shapeloc" value="<?php echo $this->ShapeLoc;?>">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>
<br>
<!-- 
<form action=<?php echo $savedataurl; ?> method="post"enctype="multipart/form-data">



<table border="1">

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_TYPEP'))?></textarea>
</td>
<td>
	<select name="TYPEP">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_TYPEO'))?></textarea>
</td>
<td>
	<select name="TYPEO">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_VALUE'))?></textarea>
</td>
<td>
	<select name="VALUE">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_AREA'))?></textarea>
</td>
<td>
	<select name="area">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_ROOMS'))?></textarea>
</td>
<td>
	<select name="ROOMS">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>


<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_TOILET'))?></textarea>
</td>
<td>
	<select name="toilet">
		<option value="0"></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>


<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_AGE'))?></textarea>
</td>
<td>
	<select name="AGE">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_TEL1'))?></textarea>
</td>
<td>
	<select name="tel1">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo utf8_encode(JText::_('COM_GEOI_FRMSAVE_TEL2'))?></textarea>
</td>
<td>
	<select name="tel2">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.utf8_encode($sch['name']) .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>


</table> 
<input type="hidden" name="Shapeloc" value="<?php echo $this->ShapeLoc;?>">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>
esto es un comentario-->



</body>
</html> 





