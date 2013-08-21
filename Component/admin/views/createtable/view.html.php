<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * HelloWorlds View
 */

 
class geoiViewcreatetable extends JView
{
		public $Schema;
		public $ShapeLoc;
		public $nompol;
		public $cpol;
        function display($tpl = null) 
        {
                // Get data from the model
                //$items = $this->get('Items');
                //$pagination = $this->get('Pagination');
                //$this->msg = $this->get('Msg');
				///$this->creartabla();
				//$this->model   = $this->getModel('load','Geoi');
				//print_r($this->model  );
				//$this->model = JModel::getInstance('geoi', 'Geoi'); 
				//print_r($this->model);
				//$model->creartabla();
				//$this->msg = $this->get('Msg');
				//$this->msg2 = $this->get('Msg2');
				//echo $model->msg2;
				//var_dump($abc);
                // Check for errors.
                
                //get layout
                $Schema = $this->Schema;
                $ShapeLoc=$this->ShapeLoc;
                $nompol=$this->nompol;
				$cpol=$this->cpol;
                $tpl=JRequest::getCmd('layout',null);
                           
                
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
