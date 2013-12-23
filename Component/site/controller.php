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
				$view->title=$model->GetParam('TITLE');
				$view->favicon=$model->GetFavicon();
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
			$get_array = $input->getArray($_POST);
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
				$model->SearchPoints($get_array['searchdata'],false);
				//print_r($json_arr);
			}
			
			//print_r($model->GetSearchParameters());
			parent::display($cachable = false);
			$app->close();
		}
		
		function DeletePoints(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			if($user->id!=0){
				if(isset($get_array['deletedata'])){
					$userowner=$model->GetIDOwner($get_array['deletedata']);
					if($userowner==$user->username)
						$model->DeletePointsO($get_array['deletedata']);
					//print_r($json_arr);
				}
			}
			
			//print_r($model->GetSearchParameters());
			parent::display($cachable = false);
			$app->close();
		}
		
		function UpdatePoints(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			if($user->id!=0){
				if(isset($get_array['updatedata'])){
					$userowner=$model->GetIDOwner($get_array['updatedata'][9][1]);
					if($userowner==$user->username)
						$model->UpdatePointsO($get_array['updatedata'],$user);
				}
			}
			parent::display($cachable = false);
			$app->close();
		}

		function InsertPoints(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			if($user->id!=0){
				if(isset($get_array['insertdata'])){
					$model->InsertPointsO($get_array['insertdata'],$user);
				}
			}
			parent::display($cachable = false);
			$app->close();
		}
		
		function DeletePictures(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			if($user->id!=0){
				if(isset($get_array['fid'])){
					$userowner=$model->GetIDOwner($get_array['fid']);
					if($userowner==$user->username)
						$model->DeletePictures($get_array['fid']);
				}
			}
			parent::display($cachable = false);
			$app->close();
		}
		
		function UploadImages(){
			$app = JFactory::getApplication();
			$input=$app->input;
			//$input->set('view', $input->getCmd('view', 'Geojson'));
			//$document = JFactory::getDocument();
			//$document->setMimeEncoding('application/json; charset=UTF8');
			//$document->setType('raw');
			//$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$post_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			$valerr=0;
			//if($userowner==$user->username&&isset($post_array['fid'])){
			if($user->id!=0&&isset($post_array['fid'])){
				$userowner=$model->GetIDOwner($post_array['fid']);
				if($userowner==$user->username){
					$sarr=array();
					$allowedExts = array("gif", "jpeg", "jpg", "png");
					for($i=0; $i<count($_FILES['imageuploads']['name']); $i++) {
						$tmpFilePath = $_FILES['imageuploads']['tmp_name'][$i];
						$type=$_FILES['imageuploads']['type'][$i];
						$size=($_FILES["imageuploads"]["size"][$i] / 1024) ;
						$ext = pathinfo($_FILES['imageuploads']['name'][$i], PATHINFO_EXTENSION);
						$uplimit=$model->GetParam('ULIMIT_IMAGES');
						//print_r(json_encode("type:".$_FILES["imageuploads"]["type"][$i]."\n"));
						//print_r(json_encode("ext:".$ext."\n"));
						//print_r(json_encode("size:".$size."\n"));
						//print_r(json_encode("tmpfilepath".$tmpFilePath."\n"));
						//print_r(json_encode("___________\n\n"));
						if((   ($_FILES["imageuploads"]["type"][$i] == "image/gif")
							|| ($_FILES["imageuploads"]["type"][$i] == "image/jpeg")
							|| ($_FILES["imageuploads"]["type"][$i] == "image/jpg")
							|| ($_FILES["imageuploads"]["type"][$i] == "image/pjpeg")
							|| ($_FILES["imageuploads"]["type"][$i] == "image/x-png")
							|| ($_FILES["imageuploads"]["type"][$i] == "image/png"))
							&& ($_FILES["imageuploads"]["size"][$i] < $uplimit)
							&& in_array($ext, $allowedExts)
							&&$tmpFilePath != ""){
								
							$newFname="FID".$post_array['fid']."_".uniqid().".".$ext;
							$newFilePath = $uploadpath=JPATH_ROOT.DS.'media'.DS.'com_geoi'.DS.'images'.DS.$newFname;
							$newRPath='media/com_geoi/images/'.$newFname;
							if(move_uploaded_file($tmpFilePath, $newFilePath)) {
								$farr=array();
								$farr['path']=$newRPath;
								$farr['fid']=$post_array['fid'];
								array_push ($sarr,$farr);
								}else {$valerr++;echo json_encode(JText::_('COM_GEOI_MSG_ERRMOVE'). $uploadpath);}
							}else {$valerr++;echo json_encode(JText::_('COM_GEOI_MSG_ERRVALPIC'));}
						}
						if($valerr==0) $model->UploadImages($sarr);
						//print_r(json_encode($sarr));
					}}
					//parent::display($cachable = false);
					$app->close();
		}
		
		function GetPhotos(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			//if($user->id!=0){
				if(isset($get_array['fid'])){
					$model->GetPhotos($get_array['fid']);
				}
			//}
			parent::display($cachable = false);
			$app->close();
		}
		
		function GetRestrictions(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$get_array = $input->getArray($_POST);
			$user = JFactory::getUser();
			if($user->id!=0){
				//echo json_encode("monda");
				$model->GetRestrictions();
			}
			parent::display($cachable = false);
			$app->close();
		}
		
		
		
		function test(){
			$app = JFactory::getApplication();
			$input=$app->input;
			$input->set('view', $input->getCmd('view', 'Geojson'));
			$document = JFactory::getDocument();
			$document->setMimeEncoding('application/json; charset=UTF8');
			$document->setType('raw');
			$view = $this->getView( 'Geojson', 'raw' );
			$model=$this->getModel();
			$user = JFactory::getUser();
			echo "------------\n";
			echo "USERNAME:".$user->name."\n";
			echo "USERID:".$user->id."\n";
			echo "------------";
			print_r( $model->GetIDOwner("20"));
			//$model->testintersection();
				
			//print_r($model->GetSearchParameters());
			parent::display($cachable = false);
			$app->close();
		}
		
		
			
}
