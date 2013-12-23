<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * HelloWorld component helper.
 */
abstract class GeoiHelper
{
        /**
         * Configure the Linkbar.
         */
    public static function addSubmenu($selected=null)
    {
        //$option = 'com_geoi';
        
        JSubMenuHelper::addEntry(
            Jtext::_('COM_GEOI_MHOME'),
            'index.php?option=com_geoi',
            $selected=='load'
        );
        JSubMenuHelper::addEntry(
            Jtext::_('COM_GEOI_MLOAD'),
            'index.php?option=com_geoi&task=load',
            $selected=='report'
        );
        JSubMenuHelper::addEntry(
            utf8_encode ( Jtext::_('COM_GEOI_MCONFIG')),
            'index.php?option=com_geoi&task=config',
            $selected=='config'
        );
    }
}
