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

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_TIPOI')?></textarea>
</td>
<td>
	<select name="tipoi">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_TIPOO')?></textarea>
</td>
<td>
	<select name="tipoo">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_PRECIO')?></textarea>
</td>
<td>
	<select name="precio">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_AREA')?></textarea>
</td>
<td>
	<select name="area">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_HAB')?></textarea>
</td>
<td>
	<select name="hab">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>


<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_TOILET')?></textarea>
</td>
<td>
	<select name="toilet">
		<option value="0"></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>


<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_EDAD')?></textarea>
</td>
<td>
	<select name="edad">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_TEL1')?></textarea>
</td>
<td>
	<select name="tel1">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_TEL2')?></textarea>
</td>
<td>
	<select name="tel2">
		<option value=""></option>
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .'</option>';}?>
	</select>
</td>
</tr>


</table> 
<input type="hidden" name="Shapeloc" value="<?php echo $this->ShapeLoc;?>">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>




</body>
</html> 





