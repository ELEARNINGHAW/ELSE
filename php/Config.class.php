<?php
class ConfigELSE
{

var $C;
var $CFG;

function __construct( $CONSTANT )
{
  $this->C = $CONSTANT;
  $this->CFG = $this->getConf();
}

function getConf()
{

$conf[ 'ajaxON'              ] = false;               ## AJAX active
$conf[ 'adminzone'           ] = array ( 'ELSE-ADMIN', 'WWW.W171' , 'WWW S17' );    # Diese EMIL-Räume sind ELSE-Admin Räume
$conf[ 'doNotShow'           ] = array ( 'ELSE-ADMIN' , 'HIBS ELSE' ); ## Semesterapparate dieser EMIL-Räume werden in keiner Liste angezeigt

$pat = explode( '/', $_SERVER[ 'SCRIPT_FILENAME' ] );  ## Ermittelt in welchem Verzeichnis dieses Programm liegt (ELSE vs. ELSE-TEST)
array_pop($pat); array_pop($pat);
$conf[ 'dir' ] = array_pop($pat);

#  $conf[ 'URL'                   ] = 'https://141.22.117.12/ELSE/htdocs';               ## SRU Retrivel Service

if ($conf[ 'dir' ] == 'ELSE')
{
  $conf['db'][ 'db_host'               ] = "localhost";		                     ## MySQL Database parametes
  $conf['db'][ 'db_name'               ] = "ELSEDB";
  $conf['db'][ 'db_user'               ] = "ELSE";
  $conf['db'][ 'db_pass'               ] = "ELSEDB";
  $conf[ 'templates_compile_dir'       ] = "/home/ELSE/template/";             ## Directory for storing compiled HTML teplates
  $conf[ 'URL'                         ] = 'https://lernserver.el.haw-hamburg.de/ELSE/';    ## SRU Retrivel Service
}

elseif ($conf[ 'dir' ] == 'ELSE-TEST')
{
  $conf['db'][ 'db_host' ] = "localhost";                                      ## MySQL Database parametes
  $conf['db'][ 'db_name' ] = "ELSETEST";
  $conf['db'][ 'db_user' ] = "ELSETEST";
  $conf['db'][ 'db_pass' ] = "ELSETESTDB";
  $conf[ 'templates_compile_dir' ] = "/home/ELSE-TEST/template/";                          ## Directory for storing compiled HTML teplates
  $conf[ 'URL'                   ] = 'https://lernserver.el.haw-hamburg.de/ELSE-TEST/';    ## SRU Retrivel Service

#  error_reporting(E_ALL);
#  ini_set("display_errors", 1);
  error_reporting(0);
  ini_set("display_errors", 0);

}


elseif ($conf[ 'dir' ] == 'ELSE-DEV')
  {
    $conf['db'][ 'db_host'               ] = "localhost";		                     ## MySQL Database parametes
    $conf['db'][ 'db_name'               ] = "ELSEDB";
    $conf['db'][ 'db_user'               ] = "ELSE";
    $conf['db'][ 'db_pass'               ] = "ELSEDB";
    $conf[ 'templates_compile_dir' ] = "/home/ELSE/template/";             ## Directory for storing compiled HTML teplates
#  $conf[ 'URL'                   ] = 'https://lernserver.el.haw-hamburg.de/ELSE/';    ## SRU Retrivel Service
  $conf[ 'URL'                   ] = 'https://141.22.117.12/ELSE-DEV/';    ## SRU Retrivel Service
  }


#  $conf[ 'URL'                   ] = 'https://lernserver.el.haw-hamburg.de/ELSE-TEST/';    ## SRU Retrivel Service


$conf[ 'catURL'                ] = 'http://sru.gbv.de/';               ## SRU Retrivel Service
$conf[ 'cat'                   ] = 'opac-de-18-302';  # HIBS 
$conf[ 'recordSchema'          ] = 'marc21';       # turbomarc /marc21 (marcxml) / mods
$conf[ 'recordSchema2'         ] = 'turbomarc';       # turbomarc /marc21 (marcxml) / mods
$conf[ 'maxRecords'            ] =  50;

#$conf[ 'catURLlnk'             ] = 'https://kataloge.uni-hamburg.de/CHARSET=ISO-8859-1/DB=2/LNG=DU/CMD?ACT=SRCHA&amp;IKT=12&amp;SRT=YOP&amp;TRM=';  ## Link zum Mediuim im Bib-Server
#$conf[ 'catURLlnk'             ] = 'https://haw.beluga-core.de/vufind/Record/';  ## Link zum Medium im Bib-Server

$conf[ 'catURLlnk' ][1]  =  "https://haw.beluga-core.de/vufind/Record/";
$conf[ 'catURLlnk' ][2]  =  "https://haw.beluga-core.de/vufind/Search2Record/";


$conf['defUser' ][ 'u_bib_id'          ] = 'HAW'      ;
$conf['defUser' ][ 'u_department'      ] = '0'        ;
$conf['defUser' ][ 'u_forename'        ] = 'default'  ;
$conf['defUser' ][ 'u_surname'         ] = 'user'     ;
$conf['defUser' ][ 'u_departmentName'  ] = 'unknown'  ;

$conf[ 'expoimp' ][ 'doc_type_id'       ]  =  'doc_type_id'      ;
$conf[ 'expoimp' ][ 'physicaldesc'      ]  =  'physicaldesc'     ;
$conf[ 'expoimp' ][ 'state_id'          ]  =  'state_id'         ;
$conf[ 'expoimp' ][ 'location_id'       ]  =  'location_id'      ;
$conf[ 'expoimp' ][ 'title'             ]  =  'title'            ;
$conf[ 'expoimp' ][ 'author'            ]  =  'author'           ;
$conf[ 'expoimp' ][ 'edition'           ]  =  'edition'          ;
$conf[ 'expoimp' ][ 'ISBN'              ]  =  'ISBN'             ;
$conf[ 'expoimp' ][ 'publisher'         ]  =  'publisher'        ;
$conf[ 'expoimp' ][ 'signature'         ]  =  'signature'        ;
$conf[ 'expoimp' ][ 'ppn'               ]  =  'ppn'              ;
$conf[ 'expoimp' ][ 'notes_to_studies'  ]  =  'notes_to_studies' ;
$conf[ 'expoimp' ][ 'notes_to_staff'    ]  =  'notes_to_staff'   ;
$conf[ 'expoimp' ][ 'created'           ]  =  'created'          ;
$conf[ 'expoimp' ][ 'last_state_change' ]  =  'last_state_change';
#$conf[ 'expoimp' ][ 'shelf_remain'      ]  =  'shelf_remain'     ;


## --- Mail an die HIBS/ELSE - Ansprechpartner  ---
$conf[ 'BIB_BCC'               ] =  'daniela.mayer@haw-hamburg.de, werner.welte@haw-hamburg.de' ;
$conf[ 'BIB_FROM'              ] =  'ELSE-noreply@haw-hamburg.de' ;

$conf[ 'BIB_RPTO'              ] =  'werner.welte@haw-hamburg.de';

$conf[ 'BIB_Anrede'            ] = "Liebe ELSE/HIBS Mitarbeiterin  \r\n\r\n";
$conf[ 'BIB_Gruss'             ] = "\r\n\r\nIhr ELSE Server \r\n\r\n http://www.elearning.haw-hamburg.de/mod/else/view.php?id=443297  \r\n\r\n";
## --------------------------------------------------

$conf[ 'debug_level'           ] = 0;  ## Debugging level (0 .. 99)
$conf[ 'default_role_id'       ] = 3;  ## Default Role id for new users. 
$conf[ 'default_bib_id'        ] = 'HAW';  ## Default Location id for new document collections
$conf[ 'canYAZ'                ] = true;

$conf['ok_host'] =  array( "www.elearning.haw-hamburg.de", "lernserver.el.haw-hamburg.de", "www.emil2-test.ls.haw-hamburg.de", "www.emil2-archiv.haw-hamburg.de", "localhost"  );

$conf[ 'salt'                ] = 'dbc32357199120e8d994ddaf3d3f2acccb47bac2077934bd5f58e6429e9cea77';

return $conf;
}
# cronjob: http://lernserver.el.haw-hamburg.de/ELSE/htdocs/index.php?item=email&action=HIBSAPmail&ro=NQ%3D%3D&uid=MDA%
}

function deb($obj, $kill=false) {   echo "<pre>";  print_r ($obj);  echo "</pre>";  if($kill){die();} }
?>