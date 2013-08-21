<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
//echo JText::_('COM_GEOI_WELCOME');
 
// import joomla controller library
jimport('joomla.application.component.controller');
 
// Get an instance of the controller prefixed by HelloWorld
$controller = JController::getInstance('Geoi');
 
// Get the task
$jinput = JFactory::getApplication()->input;
$task = $jinput->get('task', "", 'STR' );
 
// Perform the Request task
//$controller->execute(Jrequest::getCmd('task'));
$controller->execute($task);
 
// Redirect if set by the controller
$controller->redirect();
