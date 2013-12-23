<html>
<body>
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
$uploadurl= JURI::current(); 
//$uploadurl= $uploadurl."?option=com_geoi&task=load&layout=upload";
$intersecturl=$uploadurl."?option=com_geoi&task=intersect";
$uploadurl= $uploadurl."?option=com_geoi&task=uploadfile";
$delpolurl= $uploadurl."?option=com_geoi&task=deletepolygon";
$deleteo= $uploadurl."?option=com_geoi&task=deleteO";
$truncateo= $uploadurl."?option=com_geoi&task=truncateO";
echo "<h1>".Jtext::_('COM_GEOI_WELCOME')."</h1><br><br>";
echo Jtext::_('COM_GEOI_LOADMESSAGE').$this->epsg."<br>";
echo Jtext::_('COM_GEOI_LOAD_INFO')."<br>";
?>

<br>
<br>
<h2><?php echo Jtext::_('COM_GEOI_LOADTEXT');?></h2>
<br>
<hr>
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

<br>
<br>
<h2><?php echo Jtext::_('COM_GEOI_DELETETEXT');?></h2>
<br>
<hr>

<b><?php echo Jtext::_('COM_GEOI_DELPOL');?></b><br>
<form action=<?php echo $delpolurl; ?> method="post"enctype="multipart/form-data">
<?php echo Jtext::_('COM_GEOI_UPLOADFORM_NOMPOL');?>
<select name="nompol">
	<?php foreach($this->polnom as $sch){echo '<option value="'.$sch.'">'.$sch .'</option>';}?>
</select><br>
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>

<br>
<br>

<b><?php echo Jtext::_('COM_GEOI_DELO');?></b><br>
<form action=<?php echo $deleteo; ?> method="post"enctype="multipart/form-data">
<?php echo Jtext::_('COM_GEOI_DELOID');?> <input type="number" name="fid"><br>
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>

<br>
<br>

<b><?php echo Jtext::_('COM_GEOI_TRUNCATEO');?></b><br>
<form action=<?php echo $truncateo; ?> method="post"enctype="multipart/form-data">
<input type="submit" name="submit" value="<?php echo Jtext::_('COM_GEOI_UPLOADFORM_SEND');?>">
</form>

</body>
</html> 





