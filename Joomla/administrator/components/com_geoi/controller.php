<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');
JLoader::register('GeoiHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/geoi.php');
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
                JToolBarHelper::Title(Jtext::_('COM_GEOI_DEFAULT'));
                $input = JFactory::getApplication()->input;
                $input->set('view', $input->getCmd('view', 'Geoi'));
 
                // call parent behavior
                parent::display($cachable);
                GeoiHelper::addSubmenu('task');
                
        }
        
        function load()
        {
			
			//echo "INSERTAR";
			//$model = $this->getModel('geoi');
			JToolBarHelper::Title(Jtext::_('COM_GEOI_MLOAD'));
			GeoiHelper::addSubmenu('task');
			$model=$this->getModel();
			//$input = JFactory::getApplication()->input;
			$view = $this->getView('Load','html');
			$view->epsg=$model->GetParam('EPSG_DATA');
            $view->numpol=$model->GetParam('NUMPOL');
            $view->polnom=Array();
            if($view->numpol > 0) {
					for ($i = 1; $i <= 1000 ; $i++) {
						$pol='POL'.$i;
						if($model->GetParam($pol)!="")
							array_push($view->polnom,$model->GetParam($pol));
						//$view->polnom[$i]=$model->GetParam($pol);
									}
					}
			$view->display();
            
            //$input->set('view', $input->getCmd('view', 'Load'));
             // call parent behavior
            //parent::display($cachable = false);
            
			
		}
		
		function report()
		        {
			JToolBarHelper::Title(Jtext::_('COM_GEOI_MREPORT'));
			echo "INFORME";
			GeoiHelper::addSubmenu('task');
		}
		
		function config()
		        {
			JToolBarHelper::Title(utf8_encode(Jtext::_('COM_GEOI_MCONFIG')));
			//echo "CONFIGURACION";
			GeoiHelper::addSubmenu('task');
			$view = $this->getView('Config','html');
			$model=$this->getModel();
			$view->epsgdata=$model->GetParam('EPSG_DATA');
			$view->epsgdisp=$model->GetParam('EPSG_DISP');
			$view->maxresolution=$model->GetParam('MAXRESOLUTION');
			$view->lname=$model->GetParam('LYR_NAME');
			$view->clusterd=$model->GetParam('CLUSTER_DISTANCE');
			$view->clustert=$model->GetParam('CLUSTER_THRESHOLD');
			$view->ulimage=$model->GetParam('ULIMIT_IMAGES');
			$view->ulshape=$model->GetParam('ULIMIT_SHAPE');
			$view->title=$model->GetParam('TITLE');
			$view->symbolfield=$model->GetParam('SYMBOLOGY_FIELD');
			$view->symbols=$model->GetSymbols();
			$view->fields=$model->GetFieldsO();
			//print_r($view->fields);
			$fieldsatt=array();
			//echo $model->GetParam('SF_prueba2');
			foreach ($view->fields as $fields){
				$arrfields=array();
				$arrfields['name']=$fields;
				$arrfields['alias']=$model->GetParam('N_'.$fields);
				$arrfields['type']=$model->GetParam('SF_'.$fields);
				$arrfields['restriction']=$model->GetParam('R_'.$fields);
				array_push($fieldsatt,$arrfields);
				//print_r($arrfields);
				//print_r("<br>");
				
			}
			$view->fieldsatt=$fieldsatt;
			$view->bounds=$model->GetParam('BOUNDS');
			$doc = JFactory::getDocument();
			$doc->addStyleSheet(JURI::root()."media/com_geoi/css/admin.css");
			//$doc->addScript("http://maps.google.com/maps/api/js?v=3&amp;sensor=false");
			$doc->addScript(JURI::root()."media/com_geoi/openlayers/OpenLayers.js");
			//$doc->addScript(JURI::root()."media/com_geoi/js/geoi_admin.js");
			$doc->addScript("http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js");
			
			
			//$view->xx=$model->GetParam('xx');
			
			//
			//
			//
			$view->display();
		}
		
		function uploadfile(){
				JToolBarHelper::Title(Jtext::_('COM_GEOI_MSAVE'));
				GeoiHelper::addSubmenu('task');
				$input = JFactory::getApplication()->input;
				$post_array = $input->getArray($_POST);
				$uploadpath=JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'uploads';
				$allowedExts = array("zip");
				$temp = explode(".", $_FILES["file"]["name"]);
				$extension = end($temp);
				$model=$this->getModel();
				$uplimit=$model->GetParam('ULIMIT_SHAPE');
				if ((($_FILES["file"]["type"] == "application/zip")
				|| ($_FILES["file"]["type"] == "application/x-zip-compressed")
				|| ($_FILES["file"]["type"] == "multipart/x-zip")
				|| ($_FILES["file"]["type"] == "application/x-compressed")
				|| ($_FILES["file"]["type"] == "application/octet-stream"))
				&& ($_FILES["file"]["size"] < $uplimit)
				&& in_array($extension, $allowedExts)
				)
				  {
				if ($_FILES["file"]["error"] > 0)
				  {
				  echo "Error: " . $_FILES["file"]["error"] . "<br>";
				  }
				else
				  {
				  //echo "Upload: " . $_FILES["file"]["name"] . "<br>";
				  //echo "Type: " . $_FILES["file"]["type"] . "<br>";
				  //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
				  //echo "Stored in: " . $_FILES["file"]["tmp_name"];
				  if (file_exists($uploadpath . $_FILES["file"]["name"]))
					  {
					  echo $_FILES["file"]["name"] . JText::_('COM_GEOI_MSG_EXIST');
					  }
					else
					  {
						  $storedfile= $uploadpath . DS . $_FILES["file"]["name"];
						  $tmpfile=$_FILES["file"]["tmp_name"];
						  $move=move_uploaded_file($tmpfile, $storedfile);
						  clearstatcache() ;
						  if (file_exists($storedfile)) {
									$file2upload=$storedfile;
									//echo "<b>El fichero $storedfile existe<b>";
								} else {
									$file2upload=$tmpfile;
									//echo "El fichero $storedfile no existe";
								}
							  
							  $model->ZippedShapefile=$file2upload;
							  $checkz=$model->ExtractZipFile();
							  if($checkz===TRUE){
								  //echo 'READ---------<br>---<br><br>';
								  $input = JFactory::getApplication()->input;
								  $post_array = $input->getArray($_POST);
								  //print_r($post_array );
								  $opt=$post_array['opt'];
								  //echo $opt;
								  $nompol=$post_array['nompol'];
								  $t_geom=$model->getShapefileGeom();
								  switch($opt){
									  case 'ofertas':
										  if($t_geom=="Point"){
												//$model->getShapeFileSchemaArray();
												$view = $this->getView('createtable','html');
												$view->Schema = $model->getShapeFileSchemaArray();
												$view->ShapeLoc=$model->BaseShapefileName;
												$view->FieldsArray=$model->GetFieldsNType();
												$view->display();
												}
										  else {echo JText::_('COM_GEOI_ERRPOINT');}
										  break;
									  case 'policar':
									  		if($t_geom=="Polygon"){
												//$model->getShapeFileSchemaArray();
												$view = $this->getView('createtable','html');
												$view->Schema = $model->getShapeFileSchemaArray();
												$view->ShapeLoc=$model->BaseShapefileName;
												$nompol = str_replace(' ', '', $nompol);
												if($nompol==""){echo JText::_('COM_GEOI_ERRPOLN');break;}
												$view->nompol=$nompol;
												$view->cpol=0;
												$tpl='default:pol';
												$view->setLayout( $tpl );
												$view->display($tpl);
												}
										  else {echo JText::_('COM_GEOI_ERRPOL');}
										  break;
									  case 'policre':
									  		if($t_geom=="Polygon"){
												//$model->getShapeFileSchemaArray();
												$view = $this->getView('createtable','html');
												$view->Schema = $model->getShapeFileSchemaArray();
												$view->ShapeLoc=$model->BaseShapefileName;
												$pnom=$model->GetParamName($nompol);
												//$pnom=strtolower($pnom);
												if($pnom!=""){echo JText::_('COM_GEOI_ERRPOLN');break;}
												if($nompol==""){echo JText::_('COM_GEOI_ERRPOLN');break;}
												$nompol = str_replace(' ', '', $nompol);
												$view->nompol=$nompol;
												$view->cpol=1;
												//$model->CreatePol($nompol);
												$tpl='default:pol';
												$view->setLayout( $tpl );
												$view->display($tpl);
												}
										  else {echo JText::_('COM_GEOI_ERRPOL');}
										  break;
									  default:
										  echo JText::_('COM_GEOI_ERRPOPC');
										  break;
										}
							  }
							  else{echo JText::_('COM_GEOI_MSG_ERREXTRACT'). $uploadpath;}

					  }
				  }
				 
				   }
				else
				  {
				  echo JText::_('COM_GEOI_MSG_ERRFILE');
				  }
		}
		
		function savedata()
		{
				  JToolBarHelper::Title(Jtext::_('COM_GEOI_MSAVE'));
				  GeoiHelper::addSubmenu('task');
				  $model=$this->getModel();
				  $input = JFactory::getApplication()->input;
				  $user = JFactory::getUser();
				  $username=$user->username;
				  $email=$user->email;
				  $id=$user->id;
				  //echo $username.$email; 
				  $arrsave=Array();
				  
				  //echo "<br>********<br>";
					$post_array = $input->getArray($_POST);
					//print_r($post_array);
					/*
					$model->SaveArray['TYPEP']=$post_array['TYPEP'];
					$model->SaveArray['TYPEO']=$post_array['TYPEO'];
					$model->SaveArray['VALUE']=$post_array['VALUE'];
					$model->SaveArray['AREA']=$post_array['AREA'];
					$model->SaveArray['ROOMS']=$post_array['ROOMS'];
					$model->SaveArray['TOILET']=$post_array['TOILET'];
					$model->SaveArray['AGE']=$post_array['AGE'];
					$model->SaveArray['TEL1']=$post_array['TEL1'];
					//$model->SaveArray['tel2']=$post_array['tel2'];
					*/
					//$arrsave['Shapeloc']=$post_array['Shapeloc'];
					
					foreach($post_array as $keys=>$posta){
						//$keys=key($posta);
						if($keys!="Shapeloc"
							&&$keys!="submit"
							&&$keys!="option")
							$model->SaveArray[$keys]=$post_array[$keys];
					}
					
					$model->SaveArray['username']=$username;
					$model->SaveArray['email']=$email;
					$model->SaveArray['userid']=$id;
					//print_r($model->SaveArray);
					$model->BaseShapefileName=$post_array['Shapeloc'];
					//$model->getShapefileArray();
					$model->SaveData();
					//echo "<br>********<br>";
		}
		
				function savedatapol()
		{
				  JToolBarHelper::Title(Jtext::_('COM_GEOI_MSAVE'));
				  GeoiHelper::addSubmenu('task');
				  $model=$this->getModel();
				  $input = JFactory::getApplication()->input;
				  
					$post_array = $input->getArray($_POST);
					if($post_array['cpol']==1){$model->CreatePol($post_array['nompol']);}
					$model->PolArray['nompol']=$post_array['nompol'];
					$model->PolArray['idpol']=$post_array['idpol'];
					$model->PolArray['nompolis']=$post_array['nompolis'];
					$model->BaseShapefileName=$post_array['Shapeloc'];
					//$model->getShapefileArray();
					$model->SaveDataPol();
					//echo "<br>********<br>";
		}
		
		function intersect(){
				  JToolBarHelper::Title(Jtext::_('COM_GEOI_INTERSECT'));
				  GeoiHelper::addSubmenu('task');
				  $model=$this->getModel();
				  $input = JFactory::getApplication()->input;
				  $post_array = $input->getArray($_POST);
				  $model->Intersects($post_array['nompol']);
			
			}
			
		function deletepolygon(){
			
			JToolBarHelper::Title(Jtext::_('COM_GEOI_DELETETEXT'));
			GeoiHelper::addSubmenu('task');
			$model=$this->getModel();
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model->DeletePolygon($post_array['nompol']);
			
		}
		
		function deleteO(){
				
			JToolBarHelper::Title(Jtext::_('COM_GEOI_DELETETEXT'));
			GeoiHelper::addSubmenu('task');
			$model=$this->getModel();
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model->DeleteO($post_array['fid']);
				
		}
		
		function truncateO(){
			JToolBarHelper::Title(Jtext::_('COM_GEOI_DELETETEXT'));
			GeoiHelper::addSubmenu('task');
			$model=$this->getModel();
			$model->TruncateO();
		}
		
		function SetParameter(){
			JToolBarHelper::Title(Jtext::_('COM_GEOI_SETPARAMETER_TEXT'));
			GeoiHelper::addSubmenu('task');
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model=$this->getModel();
			foreach ($post_array as $postk=>$postv){
				if($postk!="options") {$param=$postk; $value=$postv;break;}
			}
			//echo $param."--xxxx--". $value;
			$model->SetParameter($param, $value);
			
		}
		
		function SetSymbol(){
				JToolBarHelper::Title(Jtext::_('COM_GEOI_SETPARAMETER_TEXT'));
				GeoiHelper::addSubmenu('task');
				$input = JFactory::getApplication()->input;
				$post_array = $input->getArray($_POST);
				$model=$this->getModel();
				$uplimit=$model->GetParam('ULIMIT_IMAGES');
				$allowedExts = array("gif", "jpeg", "jpg", "png");
				if($_FILES['file']['tmp_name']!=""){
					$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
					$tmpFilePath = $_FILES['file']['tmp_name'];
					if((   ($_FILES["file"]["type"] == "image/gif")
								|| ($_FILES["file"]["type"] == "image/jpeg")
								|| ($_FILES["file"]["type"] == "image/jpg")
								|| ($_FILES["file"]["type"] == "image/pjpeg")
								|| ($_FILES["file"]["type"] == "image/x-png")
								|| ($_FILES["file"]["type"] == "image/png"))
								&& ($_FILES["file"]["size"] < $uplimit)
								&& in_array($ext, $allowedExts)){
						
					
						$newFname="ICON_".uniqid().".".$ext;
						$newFilePath = $uploadpath=JPATH_ROOT.DS.'media'.DS.'com_geoi'.DS.'images'.DS.$newFname;
						$newRPath='media/com_geoi/images/'.$newFname;
						if(move_uploaded_file($tmpFilePath, $newFilePath)) {
							$model->SetSymbol($post_array['id'],$post_array['SYMVALUE'],$newRPath);
						}else echo Jtext::_('COM_GEOI_ERR_MOVE');
						
					}else echo Jtext::_('COM_GEOI_ERR_ICO');
				}
				else {$model->SetSymbol($post_array['id'],$post_array['SYMVALUE'],"");}
					
					//print_r($post_array);
				
			
		}
		
		function AddSymbol(){
				JToolBarHelper::Title(Jtext::_('COM_GEOI_SETPARAMETER_TEXT'));
				GeoiHelper::addSubmenu('task');
				$input = JFactory::getApplication()->input;
				$post_array = $input->getArray($_POST);
				$model=$this->getModel();
				$uplimit=$model->GetParam('ULIMIT_IMAGES');
				$allowedExts = array("gif", "jpeg", "jpg", "png");
				if($_FILES['file']['tmp_name']!=""){
					$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
					$tmpFilePath = $_FILES['file']['tmp_name'];
					if((   ($_FILES["file"]["type"] == "image/gif")
								|| ($_FILES["file"]["type"] == "image/jpeg")
								|| ($_FILES["file"]["type"] == "image/jpg")
								|| ($_FILES["file"]["type"] == "image/pjpeg")
								|| ($_FILES["file"]["type"] == "image/x-png")
								|| ($_FILES["file"]["type"] == "image/png"))
								&& ($_FILES["file"]["size"] < $uplimit)
								&& in_array($ext, $allowedExts)){
						$newFname="ICON_".uniqid().".".$ext;
						$newFilePath = $uploadpath=JPATH_ROOT.DS.'media'.DS.'com_geoi'.DS.'images'.DS.$newFname;
						$newRPath='media/com_geoi/images/'.$newFname;
						if(move_uploaded_file($tmpFilePath, $newFilePath)) {
							$model->AddSymbol($post_array['SYMVALUE'],$newRPath);
						}else echo Jtext::_('COM_GEOI_ERR_MOVE');
						
					}else echo Jtext::_('COM_GEOI_ERR_ICO');
				}
				else {$model->AddSymbol($post_array['SYMVALUE'],"");}
		}
		
		function DelSymbol(){
			JToolBarHelper::Title(Jtext::_('COM_GEOI_SETPARAMETER_TEXT'));
			GeoiHelper::addSubmenu('task');
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model=$this->getModel();
			if(isset($post_array['id'])){
				$model->DelSymbol($post_array['id']);
			}
			
		}
		
		function UpdateField(){
			JToolBarHelper::Title(Jtext::_('COM_GEOI_SETFIELD_TEXT'));
			GeoiHelper::addSubmenu('task');
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model=$this->getModel();
			if(isset($post_array['alias'])
				&&isset($post_array['namefield'])
				&&isset($post_array['type'])
				&&isset($post_array['restriction'])){
				//echo $post_array['type']."<br><br>";
				switch ($post_array['type']){
					case "": 
						$model->UpdateField($post_array['namefield'],$post_array['alias'],"",$post_array['restriction']);
						break;
					case "CAT": if(strrpos($post_array['restriction'],",")==false&&$post_array['restriction']!="")
									echo Jtext::_('COM_GEOI_SETFIELD_ERR1');
								else
									$model->UpdateField($post_array['namefield'],$post_array['alias'],$post_array['type'],$post_array['restriction']);
						break;
					case "INTE": if(strrpos($post_array['restriction'],"-")==false&&$post_array['restriction']!="")
									echo Jtext::_('COM_GEOI_SETFIELD_ERR1');
								else
									$model->UpdateField($post_array['namefield'],$post_array['alias'],"INT",$post_array['restriction']);
						break;
				}
				
			}
				
		}
		
		function DeleteField(){
			JToolBarHelper::Title(Jtext::_('COM_GEOI_SETFIELD_TEXT'));
			GeoiHelper::addSubmenu('task');
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model=$this->getModel();
			if(isset($post_array['namefield'])){
				$model->DeleteField($post_array['namefield']);
			}
			
		}
		
		function AddField(){
			JToolBarHelper::Title(Jtext::_('COM_GEOI_ADDFIELD_TEXT'));
			GeoiHelper::addSubmenu('task');
			$input = JFactory::getApplication()->input;
			$post_array = $input->getArray($_POST);
			$model=$this->getModel();
			//print_r($post_array);
			if(isset($post_array['fieldname'])
				&&isset($post_array['alias'])
				&&isset($post_array['type'])
				&&isset($post_array['restrictions'])
				&&isset($post_array['length'])
				&&is_numeric($post_array['length'])){
				//echo $post_array['fieldname']."<br>".$post_array['alias']."<br>".$post_array['type']."<br>".$post_array['restrictions']."<br>";
				//$model->AddField($post_array['fieldname'],$post_array['alias'],$post_array['type'],$post_array['restrictions']);
				switch ($post_array['type']){
					case "":
						$model->AddField($post_array['fieldname'],$post_array['alias'],"",$post_array['restrictions'],$post_array['length']);
						break;
					case "CAT": if(strrpos($post_array['restrictions'],",")==false&&$post_array['restrictions']!="")
						echo Jtext::_('COM_GEOI_SETFIELD_ERR1');
					else
						$model->AddField($post_array['fieldname'],$post_array['alias'],$post_array['type'],$post_array['restrictions'],$post_array['length']);
					break;
					case "INTE": if(strrpos($post_array['restrictions'],"-")==false&&$post_array['restrictions']!="")
						echo Jtext::_('COM_GEOI_SETFIELD_ERR1');
					else
						$model->AddField($post_array['fieldname'],$post_array['alias'],"INT",$post_array['restrictions'],$post_array['length']);
					break;
				}
				
			}else echo Jtext::_('COM_GEOI_ADDFIELD_VER');
		}
			
}
