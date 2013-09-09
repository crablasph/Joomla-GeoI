

<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.model');
require JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';
/**
 * HelloWorld Model
 */
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
			
			$selconf ="SELECT CONCAT( SUBSTRING( PARAM, 3 ) , ' \'', VAL, '\'' ) FROM GeoIConf WHERE PARAM LIKE 'N\_%';";
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
			
        public function STtoGeoJson($tbl,$bbox,$colums,$type) 
        {
			//echo JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';;
        	//$fecha = new DateTime();
        	//echo $fecha->format('c')."\n";
        	//$fecha1= $fecha->getTimestamp();
			//echo "1.".$fecha1."\n";
			if ($type!=''){$colsi= array("AsText(geom)", "oid , LOWER(".$type.") 'type'");}
			else{$colsi= array("AsText(geom)", "oid ");}
			$where= "Intersects(geom, GeomFromText('".$bbox."'))";
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
			//$st ="SELECT AsText(geom) geom, idpol , idint FROM GeoIPOL1";
			//echo $st;
			$db = JFactory::getDbo();

			$st= $db->getQuery(true);
			$st
				->select($cols)
				->from($tbl);
			if(is_string($bbox)){ $st->where($where);}
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
			$selconf ="SELECT VAL FROM GeoIConf WHERE PARAM ='".$param."' ";
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
			$selconf ="SELECT PARAM FROM GeoIConf WHERE VAL ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
			foreach ($results[0] as $res){return $res;}
        }
        
        public function GetAttributesbyID($table,$idlist){
        	$where="oid IN ( ".$idlist.")";
        	if(strtolower($table)=='geoiofertas'){$colo=$this->GetColArray();}
        	else{$colo='*';}
        	array_push($colo,'oid');
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
        	//SELECT DISTINCT LOWER(TYPEO) FROM GeoIOfertas;
        	$st='SELECT DISTINCT LOWER('.$parameters['SYMBOLOGY_FIELD'].' ) SYMBOLOGY_VALUES FROM GeoIOfertas ORDER BY 1 ASC';
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
        	$ts="SELECT VAL FROM GeoIConf WHERE PARAM LIKE 'ICON_%' ORDER BY PARAM ASC";
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
}
