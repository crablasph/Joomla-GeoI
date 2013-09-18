<?xml version="1.0" encoding="utf-8"?>
<extension type="component" version="2.5.0" method="upgrade">
 
        <name>com_geoi</name>
        <!-- The following elements are optional and free of formatting constraints -->
        <creationDate>2013</creationDate>
        <author>Carlos Romero, Jeisson Rueda</author>
        <authorEmail>karrlyttos@hotmail.com, jeirueda@hotmail.com</authorEmail>
        
        <!--  The version string is recorded in the components table -->
        <version>0.0.1</version>
        <!-- The description is optional and defaults to the name -->
        <!--<description>Gestor de Ofertas Inmobiliarias</description> -->
		<description>COM_GEOI_DESC</description>
        <scriptfile>script.php</scriptfile>
        <install> <!-- Runs on install -->
                <sql>
                        <file driver="mysql" charset="utf8">sql/install.mysql.utf8.sql</file>
                </sql>
        </install>
        <uninstall> <!-- Runs on uninstall -->
                <sql>
                        <file driver="mysql" charset="utf8">sql/uninstall.mysql.utf8.sql</file>
                </sql>
        </uninstall>
        <update> <!-- Runs on update; New in 2.5 -->
                <schemas>
                        <schemapath type="mysql">sql/updates/mysql</schemapath>
                </schemas>
        </update>
        
        <media destination="com_geoi" folder="media">
              <folder>css</folder>
              <folder>js</folder>
              <folder>images</folder>
              <folder>openlayers</folder>
        </media>
 
        <!-- Site Main File Copy Section -->
        <!-- Note the folder attribute: This attribute describes the folder
                to copy FROM in the package to install therefore files copied
                in this section are copied from /site/ in the package -->
        <files folder="site">
                <filename>index.html</filename>
                <filename>geoi.php</filename>
                <filename>controller.php</filename>
				<folder>views</folder>
                <folder>models</folder>
        </files>
        <languages folder="language">
					<language tag="en-GB">en-GB.com_geoi.ini</language>
					<language tag="es-ES">es-ES.com_geoi.ini</language>
		</languages>
				
	
		<administration>
                <menu>COM_GEOI_MENU</menu>
                <submenu>
						<menu link="option=com_geoi&amp;task=load">COM_GEOI_SUBMENU_LOAD</menu>
						<menu link="option=com_geoi&amp;task=report">COM_GEOI_SUBMENU_REPORT</menu>
						<menu link="option=com_geoi&amp;task=config">COM_GEOI_SUBMENU_CONFIG</menu>
                
				
				</submenu>
                <files folder="admin">
                        <filename>index.html</filename>
                        <filename>geoi.php</filename>
                        <filename>controller.php</filename>
                        <folder>sql</folder>
						<folder>src</folder>
                        <folder>models</folder>
                        <folder>uploads</folder>
                        <folder>views</folder>
                        <folder>helpers</folder>
                </files>
				<languages folder="admin/language">
					<language tag="en-GB">en-GB.com_geoi.ini</language>
					<language tag="en-GB">en-GB.com_geoi.sys.ini</language>
					<language tag="es-ES">es-ES.com_geoi.ini</language>
					<language tag="es-ES">es-ES.com_geoi.sys.ini</language>
		    	</languages>
        </administration>
</extension>
