

<?php
// No direct access to this file
header('Content-Type: text/html; charset=utf-8');
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.model');
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';


class GeoiModelGeoi extends JModel
{	
	var $pointOnVertex = true; // Check if the point sits exactly on one of the vertices? - POINT IN POLYGON
		private function WKT_to_GeoJson($wkt) {
			$wktr = new WKT();
			$geometry = $wktr ->read($wkt,true);
			//print_r( $geometry);
			$geojsonw = new GEOJSON();
			$geojson = $geojsonw->write($geometry);
			return $geojson;
		}
		
		private function GetColArray(){
			
			$selconf ="SELECT CONCAT( SUBSTRING( PARAM, 3 ) , ' \'', VAL, '\'' ) FROM `#__geoiconf` WHERE PARAM LIKE 'N\_%';";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			$namestr="";
			if (!$ex) {	echo $msg; echo "<br>";} 
			else{
			$cont=0;
			foreach ($results as $res){
				foreach($res as $r)
					{
					//$namestr=$namestr." ".$r.",";
					$namestr[$cont]=$r;
					}
					$cont++;
				}}
			//$namestr = substr($namestr, 0, -1);
			Return $namestr;
			
		}
			
        public function STtoGeoJson($tbl,$bbox,$colums,$type, $idlist) 
        {
        	///$where="oid IN ( ".$idlist.")";
			//echo JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';;
        	//$fecha = new DateTime();
        	//echo $fecha->format('c')."\n";
        	//$fecha1= $fecha->getTimestamp();
			//echo "1.".$fecha1."\n";
        	$where="";
        	$tbl="`#__".strtolower($tbl)."`";
			if ($type!=''){$colsi= array("AsText(geom)", "oid , LOWER(".$type.") 'type'");}
			else{$colsi= array("AsText(geom)", "oid ");}
			if($bbox!=FALSE){$where=$where. "Intersects(geom, GeomFromText('".$bbox."'))";}
			if($idlist!=FALSE){
				if($bbox!=FALSE){$where=$where." AND oid IN ( ".$idlist.")";}
				else{$where=$where." oid IN ( ".$idlist.")";}
			}
			//echo $where."\n";
			if ($colums!=TRUE){
				$colo=$this->GetColArray();
				$cols=array_merge($colsi,$colo);
				
			}
			else {
				$cols=$colsi;
			}
			//print_r($cols);
			//$st="SELECT AsText(geom),oid ,  TYPEP 'Tipo de Inmueble', TYPEO 'Tipo de Oferta', VALUE 'VALUE', AREA ''  FROM ".$tbl.";";
			//$st ="SELECT AsText(geom) geom, ".$cols." FROM ".$tbl.";";
			//$st ="SELECT AsText(geom) geom, idpol , idint FROM `#__geoipol1`";
			//echo $st;
			$db = JFactory::getDbo();

			$st= $db->getQuery(true);
			$st
				->select($cols)
				->from($tbl);
			if(is_string($bbox) || is_string($idlist)){ $st->where($where);}
			$db->setQuery($st);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			//$results=json_encode($results);
			//echo "montaxxxxxzzzzzxxxxxx";
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			//print_r($results );
			//$fecha2 = new DateTime();
			//echo $fecha2->format('c')."\n";
			$cont=0;	
			$coll = '{'."\n";
			$coll =$coll. "	".'"type":"FeatureCollection",'."\n";
			$coll =$coll. '	"features":['."\n";
			//print_r($results);
			//echo "\n\n\n\n";
			foreach($results  as $res){
					$cc=get_object_vars($res );
					$restk=Array();
					$restk=array_keys($cc);
					//print_r($restk);
					$cont2=0;
					foreach ($res as $r ) {
						//$rec = $table->nextRecord();
						//$cont ++;
						//echo "\nfila:".$cont."\n";
						//echo "col:".$cont2."\n";
						//echo "xxxxxxxxxxxxxxxAAAAxxxxxxxxxxxxxxx";
						if ($cont2 ==0){
								//$wktgeo = $geometria['geom']['wkt'];
								
								//echo "\n";
								//echo "geom:".$r;
								//echo "\n";
								//echo "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
								//echo $r."\n";

								//$geom = geoPHP::load($r,'wkt');
								//$geojson = $geom->out('json');;
								//print_r($geom);
								//echo $this->WKT_to_GeoJson($r);
								$geojson =  $this->WKT_to_GeoJson($r);
								
								$coll =$coll. "		{"."\n";
								$coll =$coll. '		"type":"Feature",'."\n";
								$coll =$coll. '		"geometry":'."\n"."			".$geojson.","."\n".'		"properties":{'."\n";
						}
						else{
								//$cc=get_object_vars($i );
								//$colname = $cc['name'];
								//print_r($restk);
								//echo $restk[$cont2].":".$r."\n\n";
								$coll =$coll. '			"'.$restk[$cont2].'": ';
								//$fs = $rec->getString($colname);
								$coll =$coll. '"'.$r.'"';
								if ($r != end($res)){
									$coll =$coll. ',';}
								$coll =$coll. "\n";
							}
						$cont2++;
						}
					
					$cont++;
					$coll =$coll. '			}'."\n";
					$coll =$coll. "		}";
					if ($res != end($results)){
							$coll =$coll. ',';}
					$coll =$coll. "\n";
					}
					//$fecha3 = new DateTime();
					//echo $fecha3->format('c')."\n";
					$coll =$coll. ']'."\n".'}';
					//$coll=json_encode($coll);
					return $coll;
					//echo "\n\n\n\n Array: \n\n\n";
					//echo $coll;
			
			
			//return $results;
        }
		
		public function GetParam($param) 
        {
			$selconf ="SELECT VAL FROM `#__geoiconf` WHERE PARAM ='".$param."' ";
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
			$selconf ="SELECT PARAM FROM `#__geoiconf` WHERE VAL ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			foreach ($results as $res){return $res;}
        }
        
        public function GetIDOwner($fid)
        {
        	$selconf ="SELECT USERNAME FROM `#__geoiofertas` WHERE oid =".$fid.";";
        	$db = JFactory::getDbo();
        	$db->setQuery($selconf);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>";}
        	foreach ($results as $res){
        		foreach ($res as $r) return $r;}
        }
        
        public function GetAttributesbyID($table,$idlist){
        	//$idlist=$idlist.", oid";
        	$where="oid IN ( ".$idlist.")";
        	if(strtolower($table)=='geoiofertas'){
        		$colo=$this->GetColArray();
        		array_push($colo, "oid");
        		$table=strtolower("`#__".$table."`");
        	}
        	else{$colo='*';}
        	//array_push($colo,'oid');
        	$db = JFactory::getDbo();
        	$st= $db->getQuery(true);
        	$st
        	->select($colo)
        	->from($table);
        	if($idlist!=FALSE){
        		$st->where($where);
        	}
        	$db->setQuery($st);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return $msg;}
        	return json_encode($results);
        }
        
        public function GetRestrictions(){
        	$qu="SELECT SUBSTRING( PARAM, 3 ) PARAM, VAL FROM `#__geoiconf` WHERE PARAM LIKE 'R_%' AND VAL !='' ORDER BY PARAM ASC;";
        	$db = JFactory::getDbo();
        	$db->setQuery($qu);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return $msg;}
        	print_r(json_encode($results));
        }
        
        public function GetMapParameters(){
        	$parameters = Array();
        	$parameters['EPSG_DATA']=$this->GetParam('EPSG_DATA');
        	$parameters['EPSG_DISP']=$this->GetParam('EPSG_DISP');
        	$parameters['BOUNDS']=$this->GetParam('BOUNDS');
        	$parameters['MAXRESOLUTION']=$this->GetParam('MAXRESOLUTION');
        	
        	$parameters['SYMBOLOGY_FIELD']=$this->GetParam('SYMBOLOGY_FIELD');
        	
        	$parameters['SYMBOLOGY_VALUES']=Array();
        	//SELECT DISTINCT LOWER(TYPEO) FROM `#__geoiofertas`;
        	$st="SELECT DISTINCT LOWER(".$parameters['SYMBOLOGY_FIELD']." ) SYMBOLOGY_VALUES FROM `#__geoiofertas` WHERE CHAR_LENGTH(TRIM(".$parameters['SYMBOLOGY_FIELD']."))>0 AND  ".$parameters['SYMBOLOGY_FIELD']." NOT REGEXP '^[0-9]+$' ORDER BY 1 ASC";
        	$db = JFactory::getDbo();
        	$db->setQuery($st);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return $msg;}
        	$cont=0;
        	foreach($results as $res){
        		foreach ($res as $r){
        		$parameters['SYMBOLOGY_VALUES'][$cont]=$r;
        		}
        		$cont++;
        	}
        	$parameters['ICON']=Array();
        	$ts="SELECT PATH, SYMVALUE FROM `#__geoisymbols` ORDER BY 2 ASC";
        	$db->setQuery($ts);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return $msg;}
        	$cont=0;
        	$parameters['ICON']=$results;
        	/*
        	foreach($results as $res){
        		//foreach ($res as $r){
        			$parameters['ICON'][$cont]=$r;
        		//}
        		$cont++;
        	}*/
        	
        	
        	
        	$parameters['LYR_NAME']=$this->GetParam('LYR_NAME');
        	$parameters['CLUSTER_DISTANCE']=$this->GetParam('CLUSTER_DISTANCE');
        	$parameters['CLUSTER_THRESHOLD']=$this->GetParam('CLUSTER_THRESHOLD');
        	$parameters['FIELDS_FORM']=$this->GetFields();
        	//$parameters['']=$this->GetParam('');
        	return json_encode($parameters);
        }
        
        public function GetSearchParameters(){
        	////SELECT VAL FROM geoi.`#__geoiconf` WHERE PARAM = 'SEARCH_FIELDS' 
        	//$query="SELECT SUBSTRING( PARAM, 4 ) NAME, VAL ALIAS FROM `#__geoiconf` WHERE PARAM LIKE 'N\_%';";
        	$query="SELECT group_concat( concat(SUBSTRING( PARAM, 4 ),':', VAL) SEPARATOR ',') vals FROM `#__geoiconf` WHERE PARAM LIKE 'SF\_%' AND VAL!=''";
        	$db = JFactory::getDbo();
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	foreach ($results[0] as $res){$search_fields=$res;break;}
        	//return $results ;
        	
        	//$search_fields=$this-> GetParam('SEARCH_FIELDS'); 	
        	$search_fields=explode(",",$search_fields);
        	///$cont=count($search_fields);
        	$i=0;
        	//print_r($search_fields);
        	foreach ($search_fields as $search){
        		$arrexploded=explode(":",$search_fields[$i]);
        		$search_fields[$i]=Array();
        		array_push($arrexploded,$this->GetColString($arrexploded[0]));
        		if($arrexploded[1]=="CAT"){
        			$category=$this->GetCategoryField($arrexploded[0],0,0);
        			array_push($arrexploded,$category);
        		}else if($arrexploded[1]=="INT"){
        			$interval=$this->GetIntervalField($arrexploded[0]);
        			array_push($arrexploded,$interval);
        		}
        		$search_fields[$i]=$arrexploded;
        		$i++;
        		
        	}
        	//$num_pol=$this-> GetParam('NUMPOL');
        	for ($j=1;$j<1000;$j++){
        		if($this-> GetParam('POL'.$j)!=""){
		        		$pol_parameters=Array();
		        		$pol_nom=$this-> GetParam('POL'.$j);
		        		array_push($pol_parameters,"POL".$j);
		        		array_push($pol_parameters,"POL");
		        		array_push($pol_parameters,$pol_nom);
		        		$category=$this->GetCategoryField("NAME",1,$j);
		        		array_push($pol_parameters,$category);
		        		$search_fields[$i]=$pol_parameters;
		        		$i++;
        		}
        	}
        	//print_r($search_fields);
        	return $search_fields;
        }
        
        private function GetCategoryField($field,$opt,$poltable){
        	//$selconf ="SELECT VAL FROM `#__geoiconf` WHERE PARAM ='".$param."' ";
        	switch ($opt){
        		case 0:
	        		$query="SELECT DISTINCT LOWER( ".$field .") CATEGORIES FROM `#__geoiofertas` WHERE CHAR_LENGTH(TRIM(".$field."))>0 AND  ".$field." NOT REGEXP '^[0-9]+$'  ORDER BY 1 ASC";
	        		break;
        		case 1:
        			$query="SELECT DISTINCT LOWER( ".$field .") CATEGORIES FROM `#__geoipol".$poltable."` WHERE CHAR_LENGTH(TRIM(".$field."))>0 AND ".$field." NOT REGEXP '^[0-9]+$' ORDER BY 1 ASC";
        			break;
        		default:
        			echo JText::_('COM_GEOI_OPT_ERR');
        			break;
        	}
        	
        	$db = JFactory::getDbo();
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	//return results;
        	//foreach ($results[0] as $res){return $res;}
        	$array_res=Array();
        	$cont=0;
        	foreach ($results as $res){
        		foreach ($res as $r){
        			$array_res[$cont]=$r;
        		}
        		$cont++;
        	}
        	return $array_res;
        }
        
        private function GetIntervalField($field){
        	//return Array();
        	$query="SELECT MIN(IFNULL( ".$field .",0)) FROM `#__geoiofertas` UNION  SELECT MAX(IFNULL(".$field .",1))  FROM `#__geoiofertas`;";
        	$db = JFactory::getDbo();
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	//return results;
        	//foreach ($results[0] as $res){return $res;}
        	$array_res=Array();
        	$cont=0;
        	foreach ($results as $res){
        		foreach ($res as $r){
        			$array_res[$cont]=$r;
        		}
        		$cont++;
        	}
        	return $array_res;
        }
        
        private function GetColString($field){
        	$query="SELECT VAL FROM `#__geoiconf` WHERE SUBSTRING( PARAM, 3 )='".$field."' AND VAL!='' AND PARAM LIKE 'N%';";
        	$db = JFactory::getDbo();
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	//return results;
        	//foreach ($results[0] as $res){return $res;}
        	//$array_res=Array();
        	//$cont=0;
        	foreach ($results as $res){
        		foreach ($res as $r){
        			return $r;
        		}
        		//$cont++;
        	}
        	///return $array_res;
        }
        
        private function GetFields(){
        	$query="SELECT SUBSTRING( PARAM, 3 ) NAME, VAL ALIAS FROM `#__geoiconf` WHERE PARAM LIKE 'N\_%' AND VAL!='';";
        	$db = JFactory::getDbo();
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	return $results ;
        }
        
        public function WKT2Array($geomtxt){
        	//$geomtxt="POLYGON((35 10,10 20,15 40,45 45,35 10),(20 30, 35 35, 30 20, 20 30))";
        	$inif=strripos($geomtxt,"(");
        	$strini= substr ( $geomtxt , 0, $inif);
        	$geomtxt = str_replace($strini, "", $geomtxt);
        	$geomtxt = str_replace("(", "", $geomtxt);
        	$geomtxt = str_replace(")", "", $geomtxt);
        	$geomarr=explode(",",$geomtxt);
        	return $geomarr;
        }
        
        public function WKT2String($geomtxt){
        	//$geomtxt="POLYGON((35 10,10 20,15 40,45 45,35 10),(20 30, 35 35, 30 20, 20 30))";
        	$inif=strripos($geomtxt,"(");
        	$strini= substr ( $geomtxt , 0, $inif);
        	$geomtxt = str_replace($strini, "", $geomtxt);
        	$geomtxt = str_replace("(", "", $geomtxt);
        	$geomtxt = str_replace(")", "", $geomtxt);
        	//$geomarr=explode(",",$geomtxt);
        	return $geomtxt;
        }
        
        public function testintersection(){
        	//$points = array("50 70","70 40","-20 30","100 10","-10 -10","40 -20","110 -20");
        	//$polygon = array("-50 30","50 70","100 50","80 10","110 -10","110 -30","-20 -50","-30 -40","10 -10","-10 10","-30 -20","-50 -30");
        	// The last point's coordinates must be the same as the first one's, to "close the loop"
        	$points = $this->WKT2Array('POINT(-8247036.2342 517670.8443)');
        	print_r( $points) ;
        	$polygon=$this->WKT2Array('POLYGON((-8258027.2157964 521912.02217256,-8258180.0898529 511516.58632722,-8248701.8983469 508306.23113969,-8238000.7143884 524052.25896424,-8258027.2157964 521912.02217256))');
        	print_r( $polygon) ;
        	foreach($points as $key => $point) {
        		echo "point " . ($key+1) . " ($point): " . $this->pointInPolygon($point, $polygon) . "\n";
        	}
        }
        
        public function DeletePointsO($deloids){
        	//$query="DELETE FROM `#__geoiofertas`  WHERE oid IN ( ".$deloids.");";
        	$db = JFactory::getDbo();
        	$query = $db->getQuery(true);
        	$conditions="oid IN ( ".$deloids.")";
        	$query->delete('#__geoiofertas');
			$query->where($conditions);
        	$db->setQuery($query);
        	//$ex=$db->execute();
        	$results = $db->query();
        	$msg=$db->getErrorMsg();
        	if($msg!="") echo json_encode($msg);
        	
	         	
        	$query2 = $db->getQuery(true);
        	$conditions2="FID IN ( ".$deloids.")";
        	$query2->delete('#__geoipictures');
			$query2->where($conditions2);
        	$db->setQuery($query2);
        	$results = $db->query();
        	$msg=$db->getErrorMsg();
        	echo json_encode($msg);
        	
	        foreach (glob(JPATH_ROOT.DS.'media'.DS.'com_geoi'.DS.'images'.DS."FID".$deloids."*") as $filename) {
		    			unlink($filename);
				}
        	
        	//if($results!=null) print_r(json_encode( $results)) ;
        }
        
        public function UpdatePointsO($updatedata,$user){
        	////muyyyy demorado
			$fields = array();
            foreach($updatedata as $updates){
            	//$fieldt= $this->GetFieldType($updates[0]);
            	//echo $fieldt;
            	if($updates[0]=="oid")
        				$oidupdate=$updates[1];
        		elseif($updates[0]=="geom") array_push($fields , $updates[0]."= GeomFromText('".$updates[1]."')") ;
        		//elseif($fieldt=='char') array_push($fields , $updates[0]."='".$updates[1]."'"); 
        		elseif ($updates[0]=="TYPEP") array_push($fields , $updates[0]."='".$updates[1]."'");
        		elseif ($updates[0]=="TYPEO") array_push($fields , $updates[0]."='".$updates[1]."'");
        		else{
        		if($updates[1]==""||$updates[1]<0) $updates[1]=0;
        		 array_push($fields , $updates[0]."=".$updates[1]); 
        		}
        	}/*
			$conditions = array(
			    "oid IN ( ".$oidupdate.")"			    
			);*/
        	$fieldss=implode(",",$fields);
			$db = JFactory::getDbo();
			$query ="UPDATE `#__geoiofertas` SET ".$fieldss;
			$query =$query." WHERE oid = ".$oidupdate;
			//$query = $db->getQuery(true);
			//$query->update($db->quoteName('#__geoiofertas'))->set($fields)->where($conditions);
			//echo $query->__toString();
			$db->setQuery($query);
			$result = $db->query();
			$msg=$db->getErrorMsg();
        	echo json_encode($msg);
        	//if($results!=null)	print_r(json_encode( $results)) ;///*/
        }
        
        public function InsertPointsO($insertdata,$user){
			$fields = array();
			$values = array();
            foreach($insertdata as $updates){
            	if($updates[0]=="oid") $oidupdate=$updates[1];
            	elseif($updates[0]=="geom"){ array_push($fields , $updates[0]) ;array_push($values,"GeomFromText('".$updates[1]."')");}
	       		elseif ($updates[0]=="TYPEP"){ array_push($fields , $updates[0]);if($updates[1]=="") array_push($values,"'0'");else array_push($values,"'".$updates[1]."'");}
        		elseif ($updates[0]=="TYPEO"){ array_push($fields , $updates[0]);if($updates[1]=="") array_push($values,"'0'");else array_push($values,"'".$updates[1]."'");}
        		else{ array_push($fields , $updates[0]);
        			if($updates[1]==""||$updates[1]<0) array_push($values,0);
        			else{ $updates[1]=$updates[1];array_push($values,"'".$updates[1]."'");};
        		 }
        	}
        	array_push($fields , "USERNAME");
        	array_push($values,"'".$user->username."'");
        	array_push($fields , "USERID");
        	array_push($values,$user->id);
        	array_push($fields , "EMAIL");
        	array_push($values,"'".$user->email."'");
        	$fieldss=implode(",",$fields);
        	$valuess =implode(",",$values);
			$db = JFactory::getDbo();
			$query ="INSERT INTO `#__geoiofertas` ( ".$fieldss.") VALUES ( ".$valuess .")";
			//echo $query ;
			$db->setQuery($query);
			$result = $db->query();
			$msg=$db->getErrorMsg();
        	if($msg!="")	echo json_encode($msg);
        	
        	$query2="SELECT MAX(oid) oid FROM `#__geoiofertas`";
        	$db2 = JFactory::getDbo();
        	$db2->setQuery($query2);
        	$ex=$db2->execute();
        	$results2 = $db2->loadObjectList();
        	print_r(json_encode($results2));
        	if($msg!="")	echo json_encode($msg);
        	//print_r($result);
        }
        
        public function GetPhotos($fidphoto){
        	$db = JFactory::getDbo();
        	$query = "SELECT pid picid, PATH picpath FROM `#__geoipictures` WHERE FID=".$fidphoto.";";
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if($msg!="") echo json_encode($msg);
        	print_r(json_encode($results));
        }
        
        public function DeletePictures($fidphoto){
        	$db = JFactory::getDbo();
        	//$query = "DELETE FROM `#__geoipictures` WHERE FID=".$fidphoto.";";
        	//$db->setQuery($query);
        	$query = $db->getQuery(true);
        	$conditions="FID =".$fidphoto;
        	$query->delete('#__geoipictures');
        	$query->where($conditions);
        	$db->setQuery($query);
        	$result = $db->query();
        	$msg=$db->getErrorMsg();
        	echo json_encode($msg);
        	foreach (glob(JPATH_ROOT.DS.'media'.DS.'com_geoi'.DS.'images'.DS."FID".$fidphoto."*") as $filename) {
        		unlink($filename);
        	}
        }
        
        public function GetFavicon(){
        	$db = JFactory::getDbo();
        	$query = "SELECT PATH FROM `#__geoisymbols` WHERE SYMVALUE='favicon';";
        	$db->setQuery($query);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if($msg!="") echo json_encode($msg);
        	foreach ($results[0] as $res){return $res;}
        }
        
        private function GetFieldType($fieldname){
        	//$query="SELECT DATA_TYPE FROM information_schema.columns WHERE TABLE_NAME='#__geoiofertas' AND COLUMN_NAME='".$fieldname."';";
        	$db = JFactory::getDbo();
        	$query = $db->getQuery(true);
        	$query->select('DATA_TYPE');
        	$query->from("information_schema.columns");
        	$conditions = array(
				//"TABLE_NAME=`#__geoiofertas`",
				"COLUMN_NAME='".$fieldname."'"        	
			);
        	$query->where($conditions );
        	$ex=$db->execute();
        	$db->setQuery($query);
        	//echo $query->__toString();
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	foreach ($results as $res){
        		foreach ($res as $r){
        			return $r;
        		}
        	}
        	//print_r($results);
        	//if($results!=null) return ( $results) ;
        }
        
        public function UploadImages($picarray){
        	$insert="INSERT INTO  `#__geoipictures` (PATH, FID) VALUES ";
        	foreach($picarray as $pics){
        		$insert=$insert."('".$pics['path']."',".$pics['fid'].")";
        		if($pics!=end($picarray)) $insert=$insert.",";
        	}
        	$db = JFactory::getDbo();
        	$db->setQuery($insert);
        	//$ex=$db->execute();
        	$results = $db->query();
        	$msg=$db->getErrorMsg();
        	if ($msg!="") {	echo json_encode($msg); echo "<br>"; return "ERROR:".$msg;}
        	print_r(json_encode($results));
        }
        public function SearchPoints($array_search,$pcol){
        	$db = JFactory::getDbo();
        	$where=Array();
        	$where_pol_draw=Array();
        	$where_pol_name=Array();
        	$filter=Array();
        	$colsi= array("AsText(geom) geom", "oid");
        	$colspol= array("NAME","AsText(geom) geom");
        	$colo=$this->GetColArray();
        	if ($pcol==true){
        		$cols=array_merge($colsi,$colo);
        	}else {$cols=$colsi;}
        	foreach($array_search as $arra){
        		if ($arra[1]=="CAT"){
        			$str_where="";
        			foreach(($arra[2]) as $arr2){
        				$str_where=$str_where.$arra[0]."='".$arr2."'";
        				if(end($arra[2])!=$arr2){
        					$str_where=$str_where." OR ";
        				}
        			}
        			array_push($where,$str_where);
        		}elseif ($arra[1]=="INT."){
        			///expr BETWEEN min AND max
        			$minmax = explode(",", $arra[2]);
        			array_push($where,$arra[0]." BETWEEN ".$minmax[0]." AND ".$minmax[1]);
        		}elseif ($arra[1]=="POL"){
        			//retornar la gemetria, no hacer busqueda
        			//echo $arra[0];
        			$pol_idx=$this->GetParamName($arra[0]);
        			$tbl="`#__geoi".$arra[0]."`";
        			$strimplode=implode(",",$arra[2]);
        			$strimplode=str_replace(",", "','", $strimplode);
        			$strimplode="'".$strimplode."'";
        			$st= $db->getQuery(true);
        			$st
        			->select( $colspol)
        			->from(strtolower($tbl))
        			->where(" LOWER(NAME) IN (".$strimplode.")");
        			$db->setQuery($st);
        			//var_dump($db);die;
        			$ex=$db->execute();
        			$results = $db->loadObjectList();
        			$msg=$db->getErrorMsg();
        			if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        			//print_r("XXXX=<br>");
        			//print_r($results);
        			array_push($where_pol_name,$results);
        		}elseif ($arra[1]=="POLDRAW"){
        			//array_push($where," ST_Intersects(geom, GeomFromText('".$arra[2]."'))");
        			$geompol =array();
        			$geompol['geom'] =$arra[2];
        			$geompol['NAME'] ="Drawn";
        			array_push($where_pol_draw,$geompol);
        			//retornar la gemetria, no hacer busqueda
        		}elseif ($arra[1]=="USER"){
        			$userwhere="";
        			$userwhere="USERNAME='".$arra[2][1]."'";
        			array_push($where,$userwhere);
        		}else{return FALSE;}
        	}
        
        	$tbl="`#__geoiofertas`";
        	$st= $db->getQuery(true);
        	$st
        	->select($cols)
        	->from($tbl)
        	->order('VALUE ASC');
        	if(count($where)>0){   $st->where($where);}
        	$db->setQuery($st);
        	$ex=$db->execute();
        	//echo $st->__toString();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	 
        	foreach($results as $res){
        		$res=(array)$res;
        		//POL SERARCH
        		$respol=0;
        		if(count($where_pol_draw)>0){   $respol=$where_pol_draw;}
        		if(count($where_pol_name)>0){   $respol=$where_pol_name[0];}
        		if($respol!=0){
        			$inters=0;
        			foreach ($respol as $pol){
        				$pol=(array)$pol;
        				$point=$this->WKT2String($res['geom']);
        				$polygon=$this->WKT2Array($pol['geom']);
        				//echo "OID: ".$res['oid']." NAME:".$pol['NAME']." Intersects?".$this->pointInPolygon($point, $polygon);
        				//echo "\n\n";
        				//echo "<br>----";
        				//print_r($point);
        				//print_r($polygon);
        				//print_r($this->pointInPolygon($point, $polygon));
        				//echo "----<br>";
        				if($this->pointInPolygon($point, $polygon)==1){	array_push($filter,$res);$inters++;}
        			}
        		}
        		else {array_push($filter,$res);}
        		//print_r($res);
        		//$res['geom']
        	}
        	 
        	//print_r(json_encode($results));
        	//$results2["SEARCH"]=$results;
        	//if(count($where_pol_draw)>0){   $results2["DRAWPOL"]=$where_pol_draw;}
        	//if(count($where_pol_name)>0){   $results2["NAMEPOL"]=$where_pol_name;}
        	//else{}
        	//echo $inters;
        	print_r(json_encode($filter));
        }
        
        ////POINT IN POLYGON ALGORITHM
        ////http://assemblysys.com/php-point-in-polygon-algorithm/
        function pointInPolygon($point, $polygon, $pointOnVertex = true) {
        	$this->pointOnVertex = $pointOnVertex;
        
        	// Transform string coordinates into arrays with x and y values
        	$point = $this->pointStringToCoordinates($point);
        	$vertices = array();
        	foreach ($polygon as $vertex) {
        		$vertices[] = $this->pointStringToCoordinates($vertex);
        	}
        
        	// Check if the point sits exactly on a vertex
        	if ($this->pointOnVertex == true and $this->pointOnVertex($point, $vertices) == true) {
        		return 1;
        	}
        
        	// Check if the point is inside the polygon or on the boundary
        	$intersections = 0;
        	$vertices_count = count($vertices);
        
        	for ($i=1; $i < $vertices_count; $i++) {
        		$vertex1 = $vertices[$i-1];
        		$vertex2 = $vertices[$i];
        		if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $point['y'] and $point['x'] > min($vertex1['x'], $vertex2['x']) and $point['x'] < max($vertex1['x'], $vertex2['x'])) { // Check if point is on an horizontal polygon boundary
        			return 1;
        		}
        		if ($point['y'] > min($vertex1['y'], $vertex2['y']) and $point['y'] <= max($vertex1['y'], $vertex2['y']) and $point['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
        			$xinters = ($point['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
        			if ($xinters == $point['x']) { // Check if point is on the polygon boundary (other than horizontal)
        				return 1;
        			}
        			if ($vertex1['x'] == $vertex2['x'] || $point['x'] <= $xinters) {
        				$intersections++;
        			}
        		}
        	}
        	// If the number of edges we passed through is odd, then it's in the polygon.
        	if ($intersections % 2 != 0) {
        		return 1;
        	} else {
        		return 0;
        	}
        }
        
        function pointOnVertex($point, $vertices) {
        	foreach($vertices as $vertex) {
        		if ($point == $vertex) {
        			return true;
        		}
        	}
        
        }
        
        function pointStringToCoordinates($pointString) {
        	$coordinates = explode(" ", $pointString);
        	return array("x" => $coordinates[0], "y" => $coordinates[1]);
        }
}