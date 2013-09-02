<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of HelloWorld component
 */
class GeoiController extends JController
{
        /**
         * display task
         *
         * @return void
         */
        
        function display($cachable = false, $urlparams = false) 
        {
                // set default view if not set
                //$model = $this->getModel('geoi');
                //////
				$app= JFactory::getApplication();
                $input = $app->input;
                $input->set('view', $input->getCmd('view', 'Geoi'));
				$doc = JFactory::getDocument();
				//$doc->addScript(JURI::root()."media/com_geoi/openlayers/OpenLayers.js");
				//$doc->addScript(JURI::root()."media/com_geoi/js/geoi.js");
				//$doc->addScript('http://maps.google.com/maps/api/js?v=3&amp;sensor=false',"text/javascript");
				//$doc->addStyleSheet(JURI::root()."media/com_geoi/css/style.css");
				//$doc->addStyleSheet(JURI::root()."media/com_geoi/css/map.css");
                // call parent behavior
                parent::display($cachable);
				$app->close();
                
        }
        
        function testgetColNameString()
        {
			$model=$this->getModel();
			echo $model->getColNameString();
			

			
		}
		
		function geojson()
		        {
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
            $document = JFactory::getDocument();
            $document->setMimeEncoding('application/json; charset=UTF8');
            $document->setType('raw');
            $view = $this->getView( 'Geojson', 'raw' );
            $model=$this->getModel();
            //echo "montaxxxxxxxxxxxxxx";
            //print_r( $input);
            $get_array = $input->getArray($_GET);
            $bbox=$get_array['bbox'];
            //echo $bbox."\n\n";
            echo $model->STtoGeoJson('GeoIOfertas', $bbox, TRUE) ;
            
            //echo $geojson;
            parent::display($cachable = false);
            $app->close();
		}
		
		function GetAttributes(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_GET);
			//Lista de IDS separadas por comas
			$idlist=$get_array['idlist'];
			echo $model->GetAttributesbyID($idlist);
			
			parent::display($cachable = false);
			$app->close();
		}
		
		function config()
		        {
			echo "CONFIGURACION";
		}
		
		
			
}
