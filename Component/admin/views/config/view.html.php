<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */
class geoiViewConfig extends JView
{
        //public $epsgdisp;
       // public $epsgdata;
        function display($tpl = null) 
        {
        	//$epsgdisp=$this->epsgdisp;
        	//$epsgdata=$this->epsgdata;
                if (count($errors = $this->get('Errors'))) 
                {
                        JError::raiseError(500, implode('<br />', $errors));
                        return false;
                }
                parent::display($tpl);
        }
}
