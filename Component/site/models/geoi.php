

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
		public function wkt_to_json($wkt) {
			$wktr = new WKT();
			$geometry = $wktr ->read($wkt,true);
			//print_r( $geometry);
			$geojsonw = new GEOJSON();
			$geojson = $geojsonw->write($geometry);
			return $geojson;
		}
		
		public function getColArray(){
			
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
			
        public function STtoGeoJson($tbl,$bbox,$colums) 
        {
			//echo JPATH_ADMINISTRATOR.DS.'components'.DS.'com_geoi'.DS.'src'.DS.'geophp'.DS.'geoPHP.inc';;
        	//$fecha = new DateTime();
        	//echo $fecha->format('c')."\n";
        	//$fecha1= $fecha->getTimestamp();
			//echo "1.".$fecha1."\n";
			
			$colsi= array("AsText(geom)", "oid ");
			$where= "Intersects(geom, GeomFromText('".$bbox."'))";
			if ($colums!=TRUE){
				$colo=$this->getColArray();
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
								//echo $this->wkt_to_json($r);
								$geojson =  $this->wkt_to_json($r);
								
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
			$selconf ="SELECT VAL FROM GeoIConf WHERE PARAM ='".$param."' ";
			$db = JFactory::getDbo();
			$db->setQuery($selconf);
			$ex=$db->execute();
			$results = $db->loadObjectList();
			$msg=$db->getErrorMsg();
			if (!$ex) {	echo $msg; echo "<br>";} 
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
			foreach ($results[0] as $res){return $res;}
        }
        
        public function GetAttributesbyID($table,$idlist){
        	$where="oid IN ( ".$idlist.")";
        	if(strtolower($table)=='geoiofertas'){$colo=$this->getColArray();}
        	else{$colo='*';}
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
}
