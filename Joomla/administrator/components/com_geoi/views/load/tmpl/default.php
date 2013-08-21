<html>
<body>
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
$uploadurl= JURI::current(); 
//$uploadurl= $uploadurl."?option=com_geoi&task=load&layout=upload";
$uploadurl= $uploadurl."?option=com_geoi&task=uploadfile";
echo "<b>".Jtext::_('COM_GEOI_WELCOME')."</b><br><br>";
echo Jtext::_('COM_GEOI_LOADMESSAGE').$this->epsg."<br>";
echo Jtext::_('COM_GEOI_LOAD_INFO')."<br>";
?>

<br>
<br>
<b><?php echo Jtext::_('COM_GEOI_UPLOADFORM_CO');?></b><br>
<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
<label for="file"><?php echo Jtext::_('COM_GEOI_UPLOADFORM_NAME');?></label>
<input type="file" name="file" id="file"><br>
<input type="hidden" name="opt" value="ofertas">
<input type="hidden" name="nompol" value="ofertas">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>

<br>
<br>

<b><?php echo Jtext::_('COM_GEOI_UPLOADFORM_CP');?></b><br>
<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
<label for="file"><?php echo Jtext::_('COM_GEOI_UPLOADFORM_POLCAR');?></label>
<input type="file" name="file" id="file"><br>
<?php echo Jtext::_('COM_GEOI_UPLOADFORM_NOMPOL');?>
<select name="nompol">
	<option value=""></option>
	<?php foreach($this->polnom as $sch){echo '<option value="'.$sch.'">'.$sch .'</option>';}?>
</select><br>
<input type="hidden" name="opt" value="policar">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>

<br>
<br>

<b><?php echo Jtext::_('COM_GEOI_UPLOADFORM_CRP');?></b><br>
<form action=<?php echo $uploadurl; ?> method="post"enctype="multipart/form-data">
<label for="file"><?php echo Jtext::_('COM_GEOI_UPLOADFORM_POLCRE');?></label>
<input type="file" name="file" id="file"><br>
<?php echo Jtext::_('COM_GEOI_UPLOADFORM_NOMPOL');?> <input type="text" name="nompol"><br>
<input type="hidden" name="opt" value="policre">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>


</body>
</html> 





