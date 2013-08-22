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
			$view->epsg=$model->GetParam('EPSG');
            $view->numpol=$model->GetParam('NUMPOL');
            $view->polnom=Array();
            if($view->numpol > 0) {
					for ($i = 1; $i <= $view->numpol ; $i++) {
						$pol='POL'.$i;
						$view->polnom[$i]=$model->GetParam($pol);
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
			JToolBarHelper::Title(Jtext::_('COM_GEOI_MCONFIG'));
			echo "CONFIGURACION";
			GeoiHelper::addSubmenu('task');
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
				if ((($_FILES["file"]["type"] == "application/zip")
				|| ($_FILES["file"]["type"] == "application/x-zip-compressed")
				|| ($_FILES["file"]["type"] == "multipart/x-zip")
				|| ($_FILES["file"]["type"] == "application/x-compressed")
				|| ($_FILES["file"]["type"] == "application/octet-stream"))
				&& ($_FILES["file"]["size"] < 4000000000)
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
							  $model=$this->getModel();
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
												if($pnom!=""){echo JText::_('COM_GEOI_ERRPOLN');break;}
												if($nompol==""){echo JText::_('COM_GEOI_ERRPOLN');break;}
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
					$model->SaveArray['TYPEP']=$post_array['TYPEP'];
					$model->SaveArray['TYPEO']=$post_array['TYPEO'];
					$model->SaveArray['VALUE']=$post_array['VALUE'];
					$model->SaveArray['area']=$post_array['area'];
					$model->SaveArray['ROOMS']=$post_array['ROOMS'];
					$model->SaveArray['toilet']=$post_array['toilet'];
					$model->SaveArray['AGE']=$post_array['AGE'];
					$model->SaveArray['tel1']=$post_array['tel1'];
					$model->SaveArray['tel2']=$post_array['tel2'];
					//$arrsave['Shapeloc']=$post_array['Shapeloc'];
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
		
		function testPolIntersectsArray(){
			$model=$this->getModel();
			echo $model->PolIntersectsArray('upz');
			
			}
		
			
}
