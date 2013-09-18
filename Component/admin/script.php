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
                // $parent is the class calling this method
                
                $db = JFactory::getDbo();
                $query ="INSERT INTO `#__geoiconf` (PARAM , VAL) VALUES ";
                $query = $query." ('EPSG_DATA','3857') , ";
                $query = $query." ('EPSG_DISP','3857') ,";
                $query = $query." ('BOUNDS','-8279888.2058829,483769.94506356,-8203451.1776083,560206.9733381'), ";
                $query = $query." ('MINSCALE','50000'), ";
                $query = $query." ('ICON_1','media/com_geoi/images/building.png'), ";
                $query = $query." ('ICON_2','media/com_geoi/images/home.png'), ";
                $query = $query." ('ICON_3','media/com_geoi/images/shop.png'), ";
                $query = $query." ('ICON_4','media/com_geoi/images/land.png'), ";
                $query = $query." ('ICON_5','media/com_geoi/images/home2.png'), ";
                $query = $query." ('ICON_6','media/com_geoi/images/home3.png'), ";
                $query = $query." ('ICON_99','media/com_geoi/images/unknown.png'),";
                $query = $query." ('SEARCH_FIELDS','TYPEP:CAT,TYPEO:CAT,VALUE:INT,ROOMS:INT,TOILET:INT'), ";
                $query = $query." ('SYMBOLOGY_FIELD','TYPEP'), ";
                $query = $query." ('LYR_NAME','Ofertas'), ";
                $query = $query." ('CLUSTER_DISTANCE','50'), ";
                $query = $query." ('CLUSTER_THRESHOLD','2'), ";
                $query = $query." ('NUMPOL','0'), ";
                $query = $query." ('N_TYPEP','Tipo de Inmueble'), ";
                $query = $query." ('N_TYPEO','Tipo de Oferta'), ";
                $query = $query." ('N_VALUE','Precio'), ";
                $query = $query." ('N_AREA','".utf8_encode('Área')."'); ";
                
                $query2 ="INSERT INTO `#__geoiconf` (PARAM , VAL) VALUES ";
                $query2 = $query2." ('N_ROOMS','".utf8_encode('Número de Habitaciones')."'), ";
                $query2 = $query2." ('N_AGE','Edad del Inmueble'), ";
                $query2 = $query2." ('N_TOILET','".utf8_encode('Número de baños')."'), ";
                $query2 = $query2." ('N_TEL1','Telefono 1'), ";
                $query2 = $query2." ('N_TEL2','Telefono 2'), ";
                $query2 = $query2." ('N_EMAIL','E-mail'), ";
                $query2 = $query2." ('N_USERNAME','Nombre de Usuario'); ";
                
                
                $db->setQuery("SET NAMES 'utf8'");
                $ex=$db->execute();
                $db->setQuery($query);
                $ex=$db->execute();
                $msg=$db->getErrorMsg();
                if (!$ex) {	echo $msg; echo "<br>";}
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
        function uninstall($parent) 
        {
                // $parent is the class calling this method
               // echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
        }
 
        /**
         * method to update the component
         *
         * @return void
         */
        function update($parent) 
        {
                // $parent is the class calling this method
               // echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
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
               // echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
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
                //echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        }
}