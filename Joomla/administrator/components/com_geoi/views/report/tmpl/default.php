<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
 
// load tooltip behavior
JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_epia'); ?>" method="post" name="adminForm">
        <table class="adminlist">
                <thead><?php echo "X"; 
                echo $this->msg2;?></thead>
                <tfoot><?php echo "Y";?></tfoot>
                <tbody><?php echo "Z"?></tbody>
        </table>
</form>
