<?php
// No direct access to this file
header('Content-Type: text/html; charset=utf-8');
defined('_JEXEC') or die('Restricted access');
 
/**
 * Script file of HelloWorld component
 */
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
        			('EPSG_DATA','3857') ,
        			('EPSG_DISP','3857') ,
        			('BOUNDS','-8279888.2058829,483769.94506356,-8203451.1776083,560206.9733381'),
        			('MINSCALE','50000'),
        			('ICON_1','media/com_geoi/images/building.png'),
        			('ICON_2','media/com_geoi/images/home.png'),
        			('ICON_3','media/com_geoi/images/shop.png'),
        			('ICON_4','media/com_geoi/images/land.png'),
        			('ICON_5','media/com_geoi/images/home2.png'),
        			('ICON_6','media/com_geoi/images/home3.png'),
        			('ICON_97','media/com_geoi/images/green_home.png'),
        			('ICON_98','media/com_geoi/images/search_home.png'),
        			('ICON_99','media/com_geoi/images/unknown.png'),
        			('SEARCH_FIELDS','TYPEP:CAT,TYPEO:CAT,VALUE:INT,ROOMS:INT,TOILET:INT'),
        			('SYMBOLOGY_FIELD','TYPEP'),
        			('LYR_NAME','Ofertas'),
        			('CLUSTER_DISTANCE','50'),
        			('CLUSTER_THRESHOLD','2'),
        			('NUMPOL','0'),
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
        			('N_USERNAME','Nombre de Usuario');";
					$db = JFactory::getDbo();
					$db->setQuery($query);
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
        function uninstall($parent) 
        {
                // $parent is the class calling this method
                echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
        }
 
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