<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import the Joomla modellist library
jimport('joomla.application.component.model');


require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'shpparser'.DS.'shpParser.php';
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'dbf'.DS.'Table.php';
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'dbf'.DS.'Column.php';
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'dbf'.DS.'Record.php';
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';

class GeoiModelGeoi extends JModel
{
		public $ZippedShapefile;
		public $ShapeFileSchemaArray;
		public $BaseShapefileName;
		public $SaveArray;
		public $ShapefileArray;
		public $Shapefilenumrec;
		public $PolArray;

		public function ExtractZipFile()
		{
			$zip = new ZipArchive;
			$res = $zip->open($this->ZippedShapefile);
			$vershape=FALSE;
			$contstr=0;
			if($res===TRUE){
					
						for ($i = 0; $i < $zip->numFiles; $i++) {
						$filenames = $zip->getNameIndex($i);
						//echo $filenames.'<br>' ;
						$exploded=explode('.', $filenames);
						$ext = end($exploded);
						$contstr=substr_count($filenames,"/");
						$contstr=$contstr+substr_count($filenames,"\\");
						if($contstr>0){ return FALSE;}
						if ($ext=='shp' or $ext=='SHP'){
								$vershape=TRUE;
								$shapefilename=$filenames;
								$extshapefile=$ext;
								}
							}
							//exit;
						if ($vershape===TRUE){
								$path_parts = pathinfo($this->ZippedShapefile);
								$basepath = $path_parts['dirname'];
								$verextract=$zip->extractTo($basepath);
								$zip->close();
								if($verextract===TRUE){
								$baseshapefile=basename($shapefilename, $extshapefile);
								$this->BaseShapefileName=$basepath.DS.$baseshapefile;
								return TRUE;
									}
								else{return FALSE;}
								}
						else{$zip->close();return FALSE;}
			}
			else{return FALSE;}
			
		}
		 
        public function getShapeFileSchemaArray() 
        {
				$table = new Table($this->BaseShapefileName.'dbf');
				$colu =$table-> getColumns();
				//print_r($colu);
				$this->ShapeFileSchemaArray=Array();
				$cont=0;
				foreach($colu as $col){
					$name = $col->getName();
					$type = $col->getType();
					$length = $col->getLength();
					$colarray=Array();
					$colarray['index']=$cont;
					//$name=$this->normalizestring ($name);
					$colarray['name']= $name;
					switch($type)
					{
						case 'N':
							$colarray['type']= 'INTEGER';
							break;
						case 'C':
							$colarray['type']= 'CHAR';
							break;
						case 'F':
							$colarray['type']= 'REAL';
							break;
						default:
							$colarray['type']= 'UKNOWN';
							break;
					}
					$colarray['length']= $length;
					$this->ShapeFileSchemaArray[$cont]=$colarray;
					$cont++;
					}
					//print_r($this->ShapeFileSchemaArray);
					return $this->ShapeFileSchemaArray;
					
        }
		
		protected function normalizestring ($cadena){
				$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
				$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
				$cadena = utf8_decode($cadena);
				$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
				$cadena = strtolower($cadena);
				return utf8_encode($cadena);
		}

        public function getShapefileGeom() 
        {
				$shp = new shpParser();
				$shpl = $shp -> load($this->BaseShapefileName."shp");
				$header = $shp -> headerInfo();
				$keys = array_keys($header);
				$ShapefileGeom = $header[$keys[1]] ;
				return $ShapefileGeom;
        }
        
        private function getShapefileArray() 
        {
				$shp = new shpParser();
				$shpl = $shp -> load($this->BaseShapefileName."shp");
				$table = new Table($this->BaseShapefileName.'dbf');
				$colu =$table-> getColumns();
				$record = $table->getRecord();
				$shpdata = $shp -> getShapeData();
				$this->ShapefileArray=Array();
				$cont2 =0;
				
				foreach($shpdata  as $geometria){
				//Proximo registro del DBF
				$rec = $table->nextRecord();
				$wktgeo = $geometria['geom']['wkt'];
				$geom = geoPHP::load($wktgeo,'wkt');
				$wkbgeo =  $geom->out('wkb');
				$FeatureArray=Array();
				$FeatureArray['index']=$cont2;
				$FeatureArray['geom']=$wktgeo;
				$cont1 =0;
				foreach ($colu as $i ) {
					$cc=get_object_vars($i );
					$colname = $cc['name'];
					$fs = $rec->getString($colname);
					//$coll =$coll. '"'.$fs.'"';
					if($this->SaveArray['TYPEP']==$cont1){$FeatureArray['TYPEP']=$fs;}
					if($this->SaveArray['TYPEO']==$cont1){$FeatureArray['TYPEO']=$fs;}
					if($this->SaveArray['VALUE']==$cont1){$FeatureArray['VALUE']=$fs;}
					if($this->SaveArray['area']==$cont1){$FeatureArray['area']=$fs;}
					if($this->SaveArray['ROOMS']==$cont1){$FeatureArray['ROOMS']=$fs;}
					if($this->SaveArray['toilet']==$cont1){$FeatureArray['toilet']=$fs;}
					if($this->SaveArray['AGE']==$cont1){$FeatureArray['AGE']=$fs;}
					if($this->SaveArray['tel1']==$cont1){$FeatureArray['tel1']=$fs;}
					if($this->SaveArray['tel2']==$cont1){$FeatureArray['tel2']=$fs;}
					$cont1 ++;
				}
				$FeatureArray['username']=$this->SaveArray['username'];
				$FeatureArray['userid']=$this->SaveArray['userid'];
				$FeatureArray['email']=$this->SaveArray['email'];
				$this->ShapefileArray[$cont2]=$FeatureArray;
				$cont2 ++;
				}
				$this->Shapefilenumrec=$cont2 +1;
				//echo '<br><br>';
				//print_r($this->ShapefileArray);
	        }
        
        private function getShapefileArrayPol() 
        {
				$shp = new shpParser();
				$shpl = $shp -> load($this->BaseShapefileName."shp");
				$table = new Table($this->BaseShapefileName.'dbf');
				$colu =$table-> getColumns();
				$record = $table->getRecord();
				$shpdata = $shp -> getShapeData();
				$this->ShapefileArray=Array();
				$cont2 =0;
				
				foreach($shpdata  as $geometria){
				//Proximo registro del DBF
				$rec = $table->nextRecord();
				$wktgeo = $geometria['geom']['wkt'];
				$geom = geoPHP::load($wktgeo,'wkt');
				$wkbgeo =  $geom->out('wkb');
				$FeatureArray=Array();
				$FeatureArray['index']=$cont2;
				$FeatureArray['geom']=$wktgeo;
				$cont1 =0;
				foreach ($colu as $i ) {
					$cc=get_object_vars($i );
					$colname = $cc['name'];
					$fs = $rec->getString($colname);
					//$coll =$coll. '"'.$fs.'"';
					if($this->PolArray['idpol']==$cont1){$FeatureArray['idpol']=$fs;}
					if($this->PolArray['nompolis']==$cont1){$FeatureArray['nompolis']=$fs;}
					$cont1 ++;
				}
				$this->ShapefileArray[$cont2]=$FeatureArray;
				$cont2 ++;
				}
				$this->Shapefilenumrec=$cont2 +1;
				//echo '<br><br>';
				//print_r($this->ShapefileArray);
	        }
        
        public function SaveData() 
        {
			$this->DropIndexes();
			$this->getShapefileArray();
			$values=" VALUES ";
			foreach($this->ShapefileArray as $shparr){
					//print_r($shparr);echo '<br>';
					$values=$values." (";
					$insert="INSERT INTO GeoIOfertas ( geom , TYPEP , TYPEO , VALUE , EMAIL , USERID , USERNAME ";
					$values=$values." PointFromText('".$shparr['geom']."') , ";
					$values=$values."'".$shparr['TYPEP']."' , ";
					$values=$values."'".$shparr['TYPEO']."' , ";
					$values=$values.$shparr['VALUE']." , ";
					$values=$values."'".$shparr['email']."' , ";
					$values=$values.$shparr['userid']." , ";
					$values=$values."'".$shparr['username']."' ";
					if(is_numeric($this->SaveArray['ROOMS'])){$insert=$insert.", ROOMS ";if(is_numeric($shparr['ROOMS'])){$values=$values.", ".$shparr['ROOMS'];}else{$values=$values.", ''";}}
					if(is_numeric($this->SaveArray['AGE'])){$insert=$insert.", AGE ";if(is_numeric($shparr['AGE'])){$values=$values.", ".$shparr['AGE'];}else{$values=$values.", ''";}}
					if(is_numeric($this->SaveArray['toilet'])){$insert=$insert.", TOILET ";if(is_numeric($shparr['toilet'])){$values=$values.", ".$shparr['toilet'];}else{$values=$values.", ''";}}
					if(is_numeric($this->SaveArray['area'])){$insert=$insert.", AREA ";if(is_numeric($shparr['area'])){$values=$values.", ".$shparr['area'];}else{$values=$values.", ''";}}
					if(is_numeric($this->SaveArray['tel1'])){$insert=$insert.", TEL1 ";if(is_numeric($shparr['tel1'])){$values=$values.", ".$shparr['tel1'];}else{$values=$values.", ''";}}
					if(is_numeric($this->SaveArray['tel2'])){$insert=$insert.", TEL2 ";if(is_numeric($shparr['tel2'])){$values=$values.", ".$shparr['tel2'];}else{$values=$values.", ''";}}
					$values = $values.")" ;
					if($shparr != end($this->ShapefileArray)){$values = $values." , " ;}
					$insert=$insert.")".$values;
			}
			$insert=$insert.";";
			//echo $insert."<br><br><br><br>";
			//try {
					$db = JFactory::getDbo();
					$db->setQuery($insert);
					$ex=$db->execute();
					$msg=$db->getErrorMsg();
			//	} catch (Exception $e) {echo 'ERROR: ',  $e->getMessage(), "\n";}
			//print_r($msg);
			//if (!$ex) {	throw new Exception($msg); } 
			if (!$ex) {	echo $msg; } 
			else {echo '<br><b>'.Jtext::_('COM_GEOI_OLOADED').count($this->ShapefileArray).' <b>';}
			$this->CreateIndexes();
        }
		
		public function SaveDataPol() 
        {
			//$this->DropIndexes();
			$this->getShapefileArrayPol();
			$tbln=$this->GetParamName($this->PolArray['nompol']);
			$cont=0;
			$fmsg="";
			foreach($this->ShapefileArray as $shparr){
					$cont++;
					$values=" VALUES ";
					$insert="";
					$values=$values." (";
					$insert="INSERT INTO GeoI".$tbln." ( geom , idpol , NAME )";
					$geomf = (string)$shparr['geom'];
					$npar=substr_count($geomf, '(');
					if($npar==1){
						$geomf = str_replace("(", "((", $geomf);
						$geomf = str_replace(")", "))", $geomf);
					}
					else{
						$geomf = str_replace("POLYGON(", "POLYGON((", $geomf);
						$geomf = str_replace("))", ")))", $geomf);					
					}
					$values=$values." GeomFromText('".$geomf."') , ";
					$values=$values."'".$shparr['idpol']."' , ";
					$values=$values."'".$shparr['nompolis']."' ";
					$values = $values.")" ;
					//if($shparr != end($this->ShapefileArray)){$values = $values." , " ;}
					$insert=$insert." ".$values;
					$insert=$insert.";";
					$db = JFactory::getDbo();
					$db->setQuery($insert);
					$ex=$db->execute();
					$msg=$db->getErrorMsg();
					if (!$ex) {	$fmsg = $fmsg.'<b>'.$msg.'</b><br>'; } 
					//else {echo '<b>'.Jtext::_('COM_GEOI_PLOADED').$cont."/".count($this->ShapefileArray).'</b><br>';}
			}
			echo $fmsg;
			echo '<b>'.Jtext::_('COM_GEOI_PLOADED').$cont."/".count($this->ShapefileArray).'</b><br>';
			//$this->CreateIndexes();
        }
		
		protected function DropIndexes() 
        {
			$db = JFactory::getDbo();
			$drop ="ALTER TABLE GeoIOfertas DROP INDEX oid ;";
			$db->setQuery($drop);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			
			$drop ="ALTER TABLE GeoIOfertas DROP INDEX USERID;";
			$db->setQuery($drop);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			
			$drop ="ALTER TABLE GeoIOfertas DROP INDEX geom ;";
			$db->setQuery($drop);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>"; } 
        }
		
		protected function CreateIndexes() 
        {
			$create ="ALTER TABLE GeoIOfertas ADD INDEX ( oid );";
			$db = JFactory::getDbo();
			$db->setQuery($create);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			
			$create ="ALTER TABLE GeoIOfertas ADD INDEX ( USERID );";
			$db = JFactory::getDbo();
			$db->setQuery($create);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			
			$create ="ALTER TABLE GeoIOfertas ADD SPATIAL INDEX ( geom );";
			$db = JFactory::getDbo();
			$db->setQuery($create);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 			
			
        }
		
        public function GetParam($param) 
        {
			$selconf ="SELECT VAL FROM GeoIConf WHERE PARAM ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			if(empty($results)){return "";}
			foreach ($results[0] as $res){return $res;}
        }
        
         public function GetParamName($param) 
        {
			$selconf ="SELECT PARAM FROM GeoIConf WHERE VAL ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			if(empty($results)){return "";}
			foreach ($results[0] as $res){return $res;}
        }
        
        public function CreatePol($nom) 
        {
			$numpol=$this->GetParam('NUMPOL');
			$numpola=$numpol+1;
			
			$selconf ="INSERT INTO GeoIConf (PARAM, VAL) VALUES ( 'POL".$numpola."' , '".$nom."' );";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			

			$updatepol ="UPDATE GeoIConf SET VAL ='".$numpola."' WHERE PARAM = 'NUMPOL' ;";
			$db = JFactory::getDbo();
			$db->setQuery($updatepol);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			
			$crea ="CREATE TABLE GeoIPOL".$numpola."( oid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, geom GEOMETRY NOT NULL ,idpol CHAR(11) NOT NULL, NAME CHAR(15) NOT NULL, SPATIAL INDEX ( geom ) ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";;
			$db = JFactory::getDbo();
			$db->setQuery($crea);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			
			//$addcol ="ALTER TABLE GeoIOfertas ADD IDPOL".$numpola." int(11);";
			$addcol="CREATE TABLE GEOIOPOL".$numpola." (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY, idpol int(11) NOT NULL, idofe int(11) NOT NULL)ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";
			$db = JFactory::getDbo();
			$db->setQuery($addcol);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 	
        }
		
		protected function getPolArray($idpol){
			$pol="SELECT AsText(geom) geom, oid FROM GeoIPOL".$idpol.";";
			//$ofe="SELECT AsText(geom), oid FROM GeoIOfertas";
			$db = JFactory::getDbo();
			$db->setQuery($pol);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			$results = $db->loadObjectList();
			if (!$ex) {	echo $msg; echo "<br>";} 
			return $results;
		}
		
		public function Intersects($pol){
			$ofe="SELECT oid FROM GeoIOfertas WHERE Intersects(geom, GeomFromText(";
			$ofe=$ofe."));";
			$db = JFactory::getDbo();
			$db->setQuery($ofe);
			$ex=$db->execute();
			$msg=$db->getErrorMsg();
			$points = $db->loadObjectList();
			if (!$ex) {	echo $msg; echo "<br>";}
			//$numpol=$this->GetParam('NUMPOL');
			$nampol=strrev ($this->GetParamName($pol));
			$polArray=$this->getPolArray($nampol{0});
			print_r($polArray);
			//for ($i=1;i<$npol+1;$i++){
				//print_r($this->getPolArray(1));
				//print_r($this->getPolArray(2));
			//}
			
		}
        
        public function getMsg() 
        {
                if (!isset($this->msg)) 
                {
                        $this->msg = 'Hello World!';
                }
                return $this->msg;
        }
        
}
