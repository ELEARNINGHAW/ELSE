<?php
class ConfigELSE
{  
var $C;
var $CFG;

function getConf()
{
  $iniFile = "../php/config/config.ini";
  $ini     = file_get_contents( $iniFile );
  $conf    = parse_ini_string( $ini, true );

  #$conf = parse_ini_file("../php/config/config.ini", true);

  foreach ( $conf[ 'SEM' ] as $sem => $startend ) { $cfg[ 'SEM' ][ $sem ] =  explode( ',',  str_replace( ' ' , '' , $startend ) ) ; }
  $conf[ 'SEM' ] = $cfg[ 'SEM' ];

  $pat = explode( '/', $_SERVER[ 'SCRIPT_FILENAME' ] );  ## Ermittelt in welchem Verzeichnis dieses Programm liegt (ELSE vs. ELSE-TEST)
  array_pop($pat); array_pop($pat);
  $conf[ 'CONF' ][ 'cwd' ] = array_pop($pat);

  if (!isset ($conf['SERVER']))
  {
     $conf[ 'SERVER' ] =  $conf[ $conf[ 'CONF' ][ 'cwd' ] ];
  }

  return $conf;
}



function __construct( $CONSTANT )
{
  $this->C = $CONSTANT;
  $this->CFG = $this->getConf();

  $_SESSION[ 'CFG'    ] =  $this -> CFG;
  $_SESSION[ 'CON'  ] =  $CONSTANT;
#  deb($_SESSION);
}

}
?>