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
                //$input = $app->input;
                //$input->set('view', $input->getCmd('view', 'Geoi'));
				$view = $this->getView('Geoi','html');
				$doc = JFactory::getDocument();
				$model=$this->getModel();
				$view->search_array=$model->GetSearchParameters();
				$view->display();
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
            //echo "XXXXXXXXXXXXXX".$get_array['bbox']."XXXXXXXXXXXXXXXXXXXXXX";
            $properties=TRUE;
            if( isset ($get_array['bbox'])){$bbox=$get_array['bbox'];}else {$bbox=FALSE;}
            if( isset ($get_array['layer'])){$layer=$get_array['layer'];}else {$layer='GeoIOfertas';}
            if( isset ($get_array['type'])){$type=$get_array['type'];}else {$type='';}
            if( isset ($get_array['idlist'])){$idlist=$get_array['idlist'];}else {$idlist=FALSE;}
            if( isset ($get_array['properties'])){
            	if(strtolower($get_array['properties'])=='true'){$properties=FALSE;}
            }
            
            //echo $bbox."\n\n";
            echo $model->STtoGeoJson($layer, $bbox, $properties,$type, $idlist) ;
            
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
			if( isset ($get_array['layer'])){$layer=$get_array['layer'];}else {$layer='GeoIOfertas';}
			if( isset ($get_array['idlist'])){$idlist=$get_array['idlist'];}else {$idlist=FALSE;}
			//$idlist=$get_array['idlist'];
			echo $model->GetAttributesbyID($layer,$idlist);
			
			parent::display($cachable = false);
			$app->close();
		}
		
		function GetMapParameters(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			echo $model->GetMapParameters();
			parent::display($cachable = false);
			$app->close();
		}
		
		function SearchPoints(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			if(isset($get_array['searchdata'])){
				$model->SearchPoints($get_array['searchdata']);
				//print_r($json_arr);
			}
			
			//print_r($model->GetSearchParameters());
			parent::display($cachable = false);
			$app->close();
		}
		
		
			
}
