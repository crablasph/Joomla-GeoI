<html>
<body>
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
$savedataurl= JURI::current(); 
//$savedataurl= $savedataurl."?option=com_geoi&task=load&layout=upload";
$savedataurl= $savedataurl."?option=com_geoi&task=savedatapol";
?>


<?php echo JText::_('COM_GEOI_FRMSAVE_MSG')?>
<br>
<br>

<form action=<?php echo $savedataurl; ?> method="post"enctype="multipart/form-data">



<table border="1">

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_IDPOL')?></textarea>
</td>
<td>
	<select name="idpol">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

<tr>
<td>
	<?php echo JText::_('COM_GEOI_FRMSAVE_NOMPOL')?></textarea>
</td>
<td>
	<select name="nompolis">
		<?php foreach($this->Schema as $sch){echo '<option value="'.$sch['index'].'">'.$sch['name'] .' ('.$sch['type'].')</option>';}?>
	</select>
</td>
</tr>

</table> 
<input type="hidden" name="Shapeloc" value="<?php echo $this->ShapeLoc;?>">
<input type="hidden" name="nompol" value="<?php echo $this->nompol;?>">
<input type="hidden" name="cpol" value="<?php echo $this->cpol;?>">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>




</body>
</html> 





