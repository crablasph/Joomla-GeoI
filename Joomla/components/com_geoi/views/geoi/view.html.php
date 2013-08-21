<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class GeoiViewGeoi extends JView
{
        /**
         * HelloWorlds view display method
         * @return void
         */
        function display($tpl = null) 
        {

                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }

 
                // Display the template
                parent::display($tpl);
        }
}
