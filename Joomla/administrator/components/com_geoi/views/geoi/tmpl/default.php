<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_geoi'); ?>" method="post" name="adminForm">
<?php echo Jtext::_('COM_GEOI_WELCOME'); echo "<TR>";?>
</form>
