<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class GeoiViewGeojson extends JView
{
        /**
         * HelloWorlds view display method
         * @return void
         */
        function display($tpl = null) 
        {
                // Set up the data to be sent in the response.
				//$data = array('some data');
				//echo json_encode($data);

                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                // Assign data to the view
                //$this->items = $items;
                //$this->pagination = $pagination;
 
                // Display the template
                parent::display($tpl);
        }
}
