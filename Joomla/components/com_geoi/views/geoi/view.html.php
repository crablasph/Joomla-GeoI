<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 

class GeoiViewGeoi extends JView
{
		public $search_array;
        function display($tpl = null) 
        {
        	$search_array=$this->search_array;
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }

 
                // Display the template
                parent::display($tpl);
        }
}
