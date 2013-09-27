

<?php
// No direct access to this file
header('Content-Type: text/html; charset=utf-8');
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.model');
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';


class GeoiModelGeoi extends JModel
{
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
		
		private function GetParam($param) 
        {
			$selconf ="SELECT VAL FROM `#__geoiconf` WHERE PARAM ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			foreach ($results[0] as $res){return $res;}
        }
        
         private function GetParamName($param) 
        {
			$selconf ="SELECT PARAM FROM `#__geoiconf` WHERE VAL ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			foreach ($results[0] as $res){return $res;}
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
        
        public function GetMapParameters(){
        	$parameters = Array();
        	$parameters['EPSG_DATA']=$this->GetParam('EPSG_DATA');
        	$parameters['EPSG_DISP']=$this->GetParam('EPSG_DISP');
        	$parameters['BOUNDS']=$this->GetParam('BOUNDS');
        	$parameters['MINSCALE']=$this->GetParam('MINSCALE');
        	
        	$parameters['SYMBOLOGY_FIELD']=$this->GetParam('SYMBOLOGY_FIELD');
        	
        	$parameters['SYMBOLOGY_VALUES']=Array();
        	//SELECT DISTINCT LOWER(TYPEO) FROM `#__geoiofertas`;
        	$st="SELECT DISTINCT LOWER(".$parameters['SYMBOLOGY_FIELD']." ) SYMBOLOGY_VALUES FROM `#__geoiofertas` WHERE CHAR_LENGTH(TRIM(".$parameters['SYMBOLOGY_FIELD']."))>0 ORDER BY 1 ASC";
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
        	$ts="SELECT VAL FROM `#__geoiconf` WHERE PARAM LIKE 'ICON_%' ORDER BY PARAM ASC";
        	$db->setQuery($ts);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return $msg;}
        	$cont=0;
        	
        	foreach($results as $res){
        		foreach ($res as $r){
        			$parameters['ICON'][$cont]=$r;
        		}
        		$cont++;
        	}
        	
        	
        	
        	$parameters['LYR_NAME']=$this->GetParam('LYR_NAME');
        	$parameters['CLUSTER_DISTANCE']=$this->GetParam('CLUSTER_DISTANCE');
        	$parameters['CLUSTER_THRESHOLD']=$this->GetParam('CLUSTER_THRESHOLD');
        	//$parameters['']=$this->GetParam('');
        	return json_encode($parameters);
        }
        
        public function GetSearchParameters(){
        	////SELECT VAL FROM geoi.`#__geoiconf` WHERE PARAM = 'SEARCH_FIELDS' 
        	$search_fields=$this-> GetParam('SEARCH_FIELDS'); 	
        	$search_fields=explode(",",$search_fields);
        	///$cont=count($search_fields);
        	$i=0;
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
        	$num_pol=$this-> GetParam('NUMPOL');
        	for ($j=1;$j<$num_pol+1;$j++){
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
        	return $search_fields;
        }
        
        private function GetCategoryField($field,$opt,$poltable){
        	//$selconf ="SELECT VAL FROM `#__geoiconf` WHERE PARAM ='".$param."' ";
        	switch ($opt){
        		case 0:
	        		$query="SELECT DISTINCT LOWER( ".$field .") CATEGORIES FROM `#__geoiofertas` WHERE CHAR_LENGTH(TRIM(".$field."))>0 ORDER BY 1 ASC";
	        		break;
        		case 1:
        			$query="SELECT DISTINCT LOWER( ".$field .") CATEGORIES FROM `#__geoipol".$poltable."` WHERE CHAR_LENGTH(TRIM(".$field."))>0 ORDER BY 1 ASC";
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
        	$query="SELECT MIN( ".$field .") FROM `#__geoiofertas` UNION  SELECT MAX(".$field .")  FROM `#__geoiofertas`;";
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
        	$query="SELECT VAL FROM `#__geoiconf` WHERE SUBSTRING( PARAM, 3 )='".$field."';";
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
        
        public function SearchPoints($array_search){
        	$where=Array();
        	$where_cat=Array();
        	$where_int=Array();
        	$where_pol=Array();
        	$colsi= array("AsText(geom) geom", "oid ");
        	$colo=$this->GetColArray();
        	$cols=array_merge($colsi,$colo);
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
        			//$pol_idx=$this->GetParamName($arra[0]);
        			//array_push($where_pol,", `#__geoi".$arra[0]."` b WHERE Intersects(a.geom, b.geom) and b.NAME IN ");
        		}elseif ($arra[1]=="POLDRAW"){
        			//array_push($where," Intersects(geom, GeomFromText('".$arra[2]."'))");
        			//retornar la gemetria, no hacer busqueda
        		}else{return FALSE;}
        	}
        	//print_r(json_encode($where_cat));
        	//print_r(json_encode($where_int));
        	///print_r(json_encode($array_search));
        	$res_array=Array();
        	if(count($where_cat)>0){array_push($res_array,$where_cat);}
        	if(count($where_int)>0){array_push($res_array,$where_int);}
        	if(count($where_pol)>0){array_push($res_array,$where_pol);}
        	
        	$tbl="`#__geoiofertas`";
        	$db = JFactory::getDbo();
        	$st= $db->getQuery(true);
        	$st
        	->select($cols)
        	->from($tbl)
        	->where($where);
        	$db->setQuery($st);
        	$ex=$db->execute();
        	$results = $db->loadObjectList();
        	$msg=$db->getErrorMsg();
        	if (!$ex) {	echo $msg; echo "<br>"; return "ERROR:".$msg;}
        	
        	print_r(json_encode($results));
        }
}
