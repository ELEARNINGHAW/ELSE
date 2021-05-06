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

  foreach ( $conf[ 'SEM' ] as $sem => $startend )
  {
    
    $s =  explode( ',',  str_replace( ' ' , '' , $startend ) ) ;
    $sem = str_replace( ' ' , '' , $sem )  ;
    
    if( strstr ( $sem, 'A' ) ){ $sem = str_replace( 'A' , '' , $sem )  ;  $cfg[ 'ASEM' ][ $sem ] = $s; }
    else               {                                                                      $cfg[  'SEM' ][ $sem ] = $s; }
  }
  $conf[ 'SEM' ] = $cfg[ 'SEM' ];
  $conf[ 'ASEM' ] = $cfg[ 'ASEM' ];

  $pat = explode( '/', $_SERVER[ 'SCRIPT_FILENAME' ] );  ## Ermittelt in welchem Verzeichnis dieses Programm liegt (ELSE vs. ELSE-TEST)
  array_pop($pat); array_pop($pat);
  $conf[ 'CONF' ][ 'cwd' ] = array_pop($pat);
  
  if($conf[ 'CONF' ][ 'cwd' ] == 'www') { $conf[ 'CONF' ][ 'cwd' ] = 'ELSE'; }
  
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
  
  $conf_cwd =$this->CFG[ 'SERVER' ];
 
  error_reporting( $conf_cwd[ 'error_reporting' ] );
  ini_set("display_errors", $conf_cwd[ 'display_errors' ]);
}

}
?>