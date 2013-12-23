<?php
// No direct access to this file
header('Content-Type: text/html; charset=utf-8');
defined('_JEXEC') or die('Restricted access');
 

class com_GeoiInstallerScript
{
        /**
         * method to install the component
         *
         * @return void
         */
        function install($parent) 
        {
        			$query ="INSERT INTO `#__geoiconf` (PARAM , VAL) VALUES";
        			
        			$query = $query ."
        			('TITLE','GEOI INMOBILIARIO'),
        			('EPSG_DATA','3857') ,
        			('EPSG_DISP','3857') ,
        			('BOUNDS','-8279888.2058829,483769.94506356,-8203451.1776083,560206.9733381'),
        			('MAXRESOLUTION','30'),
        			('SYMBOLOGY_FIELD','TYPEP'),
        			('LYR_NAME','Ofertas'),
        			('CLUSTER_DISTANCE','50'),
        			('CLUSTER_THRESHOLD','2'),
        			('NUMPOL','0'),
        			('SF_TYPEP','CAT'),
        			('SF_TYPEO','CAT'),
        			('SF_VALUE','INT'),
        			('SF_ROOMS','INT'),
        			('SF_TOILET','INT'),
        			('ULIMIT_IMAGES','3145728'),
        			('ULIMIT_SHAPE','10485760'),
        			('N_TYPEP','Tipo de Inmueble'),
        			('N_TYPEO','Tipo de Oferta'),
        			('N_VALUE','Precio'),
        			('N_AREA','".utf8_encode('Área')."'),
        			('N_ROOMS','".utf8_encode('Número de Habitaciones')."'),
        			('N_AGE','Edad del Inmueble'),
        			('N_TOILET','".utf8_encode('Número de Baños')."'),
        			('N_TEL1','Telefono 1'),
        			('N_TEL2','Telefono 2'),
        			('N_EMAIL','E-mail'),
        			('R_TYPEP','lote,apartamento,casa,local,habitacion,apartaestudio,loft,casa lote'),
        			('R_TYPEO','venta,arriendo,permuta'),
        			('R_VALUE','0-999999999999999'),
        			('R_AREA','0-1000000'),
        			('R_ROOMS','0-10000'),
        			('R_AGE','0-1000'),
        			('R_TOILET','0-100000000000'),
        			('R_TEL1','1111111-999999999999'),
        			('R_TEL2','1111111-999999999999');";
					$db = JFactory::getDbo();
					$db->setQuery($query);
					$ex=$db->execute();
					$msg=$db->getErrorMsg();
					if (!$ex) {	echo $msg; echo "<br>";} 
					
					$query2 ="INSERT INTO `#__geoisymbols` (PATH , SYMVALUE) VALUES";
					$query2 = $query2 ."
					('media/com_geoi/images/building.png','apartamento'),
        			('media/com_geoi/images/home.png','casa'),
        			('media/com_geoi/images/shop.png','local'),
        			('media/com_geoi/images/land.png','lote'),
        			('media/com_geoi/images/home2.png','apartaestudio'),
        			('media/com_geoi/images/home3.png','habitacion'),
        			('media/com_geoi/images/modify_edit.png','modify'),
        			('media/com_geoi/images/delete_edit.png','delete'),
        			('media/com_geoi/images/save.png','save'),
					('media/com_geoi/images/favicon.png','favicon'),
        			('media/com_geoi/images/green_home.png','editsymbol'),
        			('media/com_geoi/images/search_home.png','search'),
        			('media/com_geoi/images/lgarrow.png','prevpic'),
        			('media/com_geoi/images/rgarrow.png','nextpic'),
        			('media/com_geoi/images/unknown.png','unknown');";
					$db->setQuery($query2);
					$ex=$db->execute();
					$msg=$db->getErrorMsg();
					if (!$ex) {	echo $msg; echo "<br>";}
					
					$parent->getParent()->setRedirectURL('index.php?option=com_geoi');
        }
 
        /**
         * method to uninstall the component
         *
         * @return void
         */
		 /*
        function uninstall($parent) 
        {
                // $parent is the class calling this method
                echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
        }
		*/
        /**
         * method to update the component
         *
         * @return void
         */
        function update($parent) 
        {
                // $parent is the class calling this method
                echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
        }
 
        /**
         * method to run before an install/update/uninstall method
         *
         * @return void
         */
        function preflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
        }
 
        /**
         * method to run after an install/update/uninstall method
         *
         * @return void
         */
        function postflight($type, $parent) 
        {
                // $parent is the class calling this method
                // $type is the type of change (install, update or discover_install)
                echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        }
}
