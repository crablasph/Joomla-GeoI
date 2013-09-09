DROP TABLE IF EXISTS GeoIOfertas;
DROP TABLE IF EXISTS GeoIConf;
DROP TABLE IF EXISTS GeoIPOL1;
DROP TABLE IF EXISTS GeoIPOL2;
DROP TABLE IF EXISTS GeoIPOL3;
DROP TABLE IF EXISTS GeoIPOL4;
DROP TABLE IF EXISTS GeoIPOL5;
DROP TABLE IF EXISTS GeoIPOL6;
DROP TABLE IF EXISTS GeoIPOL7;
DROP TABLE IF EXISTS GeoIPOL8;
DROP TABLE IF EXISTS GeoIPOL9;
DROP TABLE IF EXISTS GeoIPOL10;
DROP TABLE IF EXISTS GeoIPOL11;
 
CREATE TABLE GeoIOfertas (
  oid int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  geom GEOMETRY NOT NULL,
  TYPEP CHAR(15) NOT NULL,
  TYPEO CHAR(15) NOT NULL,
  VALUE int(15) NOT NULL,
  AREA REAL(10,6),
  ROOMS int(3),
  AGE int(3),
  TOILET int(11),
  TEL1 int(12),
  TEL2 int(12),
  EMAIL CHAR(70) NOT NULL,
  USERNAME CHAR(15) NOT NULL,
  USERID int(9) NOT NULL 
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
ALTER TABLE GeoIOfertas ADD FOREIGN KEY (USERID) REFERENCES #__users(id);
ALTER TABLE GeoIOfertas ADD INDEX ( oid ); 
ALTER TABLE GeoIOfertas ADD INDEX ( USERID ); 
ALTER TABLE GeoIOfertas ADD SPATIAL INDEX ( geom );

CREATE TABLE GeoIConf (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  PARAM CHAR(20) NOT NULL,
  VAL CHAR(80) NOT NULL 
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

INSERT INTO GeoIConf (PARAM , VAL) 
	VALUES 	('EPSG_DATA','3857') , 
			('EPSG_DISP','3857') , 
			('BOUNDS','-8279888.2058829,483769.94506356,-8203451.1776083,560206.9733381'),
			('MINSCALE','50000'),
			('ICON_1','media/com_geoi/images/home.png'),
			('ICON_2','media/com_geoi/images/building.png'),
			('ICON_3','media/com_geoi/images/shop.png'),
			('ICON_4','media/com_geoi/images/land.png'),
			('ICON_5','media/com_geoi/images/home2.png'),
			('ICON_6','media/com_geoi/images/home3.png'),
			('ICON_99','media/com_geoi/images/unknown.png'),
			('SYMBOLOGY_FIELD','TYPEP'),
			('LYR_NAME','Ofertas'),
			('CLUSTER_DISTANCE','50'),
			('CLUSTER_THRESHOLD','2'),
			('NUMPOL','0'),
			('N_TYPEP','Tipo de Inmueble'),
			('N_TYPEO','Tipo de Oferta'),
			('N_VALUE','Precio'),
			('N_AREA','�rea'),
			('N_ROOMS','N�mero de Habitaciones'),
			('N_AGE','Edad del Inmueble'),
			('N_TOILET','N�mero de ba�os'),
			('N_TEL1','Telefono 1'),
			('N_TEL2','Telefono 2'),
			('N_EMAIL','E-mail'),
			('N_USERNAME','Nombre de Usuario');
 
