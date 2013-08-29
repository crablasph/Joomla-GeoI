<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>

<?php echo Jtext::_('COM_GEOI_WELCOME'); echo "<br>";?>
<br><br>
<a href="<?php echo JURI::root()."index.php?option=com_geoi"?>"><?php echo JTEXT::_('COM_GEOI_SHOW_MAP_FRONTEND');?></a>
<br>
<?php echo JTEXT::_('COM_GEOI_EMBEBED');?>
<br>
<textarea width="700" height="20">
<iframe src="<?php echo JURI::root()."index.php?option=com_geoi"?>" width="700" height="500">
</textarea>
<br><br>
<iframe src="<?php echo JURI::root()."index.php?option=com_geoi"?>" width="700" height="500"></iframe>

