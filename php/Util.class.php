<?php
class Util   /// \brief check user input
{   
  var $SQL ;
  var $currentUser;
  var $currentCollection;
  private $conf;
  private $CONS;
# ---------------------------------------------------------------------------------------------
function __construct ( $SQL )
{
  $this -> SQL    = $SQL;
  $this -> conf   = $_SESSION[ 'CFG' ];
  $this -> CONS   = $_SESSION[ 'CON' ];
}

# ---------------------------------------------------------------------------------------------
function getInput ( )
{
# deb($_POST);
# deb($_GET,1);
$operator          = new operator();
$currentCollection = new collection();
$currentUser       = new User();
$medium            = new medium();
$this -> HAWdb     = new HAW_DB();                                    # Aus der SQLite DB


#if ( ! isset ( $_SESSION [ 'DEP_2_BIB' ] ) )  // Standardkonstanten werden nur beim ersten Aufruf eingelesen.
{
  $_SESSION[ 'DEP_2_BIB'    ] = $this -> HAWdb -> getDEP_2_BIB ();
  $_SESSION[ 'FAK'          ] = $this -> HAWdb -> getAllFak ();
  $_SESSION[ 'FACHBIB'      ] = $this -> HAWdb -> getAllFachBib ();
  $_SESSION[ 'DOC_TYPE'     ] = $this -> SQL -> getAllDocTypes();
  $_SESSION[ 'MEDIA_STATE'  ] = $this -> SQL -> getAllMedStates ();
  $_SESSION[ 'ACTION_INFO'  ] = $this -> CONS -> CONST_ACTION_INFO;
  $_SESSION[ 'CUR_SEM'      ] = $this -> getCurrentSem ();
}


if ( isset ( $_GET[ 'uid' ] ) )  ##  Initiale Parameterübergabe über  Moodle ## // Kurskurzname   /* Paramterübergabe von EMIL  */
{ #deb( $_GET[ 'uid' ] ,1);
  $O = $this -> getGET_BASE_Values () ;
  $currentCollection = $O[ 'currentCollection' ] ;
  $currentUser       = $O[ 'currentUser'       ] ;
  $medium            = $O[ 'medium'            ] ;
  $_SESSION[ 'currentUser'       ] = (array) $currentUser      ;
}

else
{
  if  ( ( isset (  $_SESSION[ 'currentCollection'       ]   ) ) )
  {
    $currentCollection -> array2obj( $_SESSION[ 'currentCollection'       ] );
  }
  if  ( ( isset (  $_SESSION[ 'currentUser'       ]   ) ) )
  {
    $currentUser->array2obj($_SESSION['currentUser']);
  }
  else
  {
    die("<div style='text-align:center;'><h1>ACCESS ERROR<h1><h3>Netzwerkfehler!</h3>Bitte ELSE neu starten</div>");
  }
}





### ------------------------------- SET HISTORY  --------------------------------
##
if ( (isset( $_SERVER [ 'HTTP_REFERER'      ] ) AND   $_SERVER [ 'HTTP_REFERER'      ] != $_SESSION[ 'history' ][ 0 ] ))
{
  if (isset( $_SESSION[ 'history' ][ 2 ]  )) { $_SESSION[ 'history' ][ 3 ] = $_SESSION[ 'history' ][ 2 ]; }
  if (isset( $_SESSION[ 'history' ][ 1 ]  )) { $_SESSION[ 'history' ][ 2 ] = $_SESSION[ 'history' ][ 1 ]; }
  if (isset( $_SESSION[ 'history' ][ 0 ]  )) { $_SESSION[ 'history' ][ 1 ] = $_SESSION[ 'history' ][ 0 ]; }
  if (isset( $_SERVER [ 'HTTP_REFERER' ]  )) { $_SESSION[ 'history' ][ 0 ] = $_SERVER [ 'HTTP_REFERER' ]; }
  $operator->set_history ( $_SESSION[ 'history' ] );
}

##
## ------------------------------- OPERATOR  --------------------------------
##

## Übler Workaround für veraltetes ELSE Plugin in EMIL
if ( isset( $_GET[ 'action' ] ) AND  $_GET[ 'action' ] == 'b_coll_edit' ) {  unset($_GET[ 'action' ]) ; }
## -----------------------------------------------------------------------------

## Action DEFAULTEEINSTELLUNGEN für die einzelnen Rollen
if (  $this -> hasRole( $currentUser,'admin', 'staff') )      { $operator -> set_action           ( 'show_collection_list'    ); }
else                                                                 { $operator -> set_action           ( 'show_collection'         ); }

if ( isset ( $_GET[ 'item'                                     ] ) ) { $operator -> set_item             ( $_GET[ 'item'               ] ) ; }
if ( isset ( $_GET[ 'loc'                                      ] ) ) { $operator -> set_loc              ( $_GET[ 'loc'                ] ) ; }
if ( isset ( $_GET[ 'shelf_remain'                             ] ) ) { $operator -> set_shelf_remain     ( $_GET[ 'shelf_remain'       ] ) ; }
if ( isset ( $_GET[ 'action'                                   ] ) ) { $operator -> set_action           ( $_GET[ 'action'             ] ) ; }
if ( isset ( $_GET[ 'mode'                                     ] ) ) { $operator -> set_mode             ( $_GET[ 'mode'               ] ) ; }
if ( isset ( $_GET[ 'category'                                 ] ) ) { $operator -> set_category         ( $_GET[ 'category'           ] ) ; }

if ( isset ( $_GET[ 'msg'                                      ] ) ) { $operator -> set_msg              ( $_GET[ 'msg'                ] ) ; }

if ( isset ( $_GET[ 'lms-download'                             ] ) ) { $operator -> set_action           ( 'lms-download'           ) ;
                                                                       $operator -> set_item             ( 'collection'             ) ;
                                                                       $operator -> set_url              (  $_GET[ 'lms-download'      ] ) ;
}  ## Hook, für BELUGA IMPORT, wird als action= "Downloadlink", mode

if ( isset ( $_GET[ 'mediaListID'                              ] ) )
{
  if ( strlen ('' . $_GET[ 'mediaListID' ]  ) > 5 )   ##  https://dev.haw.beluga-core.de/vufind/MyResearch/MyList/28
  {
    $idTMP = explode('/', $_GET[ 'mediaListID' ] );
    $_GET[ 'mediaListID' ] = array_pop($idTMP);
  }
  #if ( is_int( $_GET[ 'mediaListID' ]  ) )
  {
  $operator->set_mediaListID($_GET['mediaListID']) ;
 
  }
}

##
### ------------------------------- MEDIUM  --------------------------------
##
if ( isset ( $_GET[ 'document_id'                              ] ) )  { $medium -> set_id                  ( $_GET[ 'document_id'       ] ) ; }
if ( isset ( $_GET[ 'doc_type'                                 ] ) )  { $medium -> set_doc_type            ( $_GET[ 'doc_type'          ] ) ; }
if ( isset ( $_GET[ 'doc_type_id'                              ] ) )  { $medium -> set_doc_type_id         ( $_GET[ 'doc_type_id'       ] ) ; }
if ( isset ( $_GET[ 'ppn'                                      ] ) )  { $medium -> set_ppn                 ( $_GET[ 'ppn'               ] ) ; }
if ( isset ( $_GET[ 'physicaldesc'                             ] ) )  { $medium -> set_physicaldesc        ( $_GET[ 'physicaldesc'      ] ) ; }
if ( isset ( $_GET[ 'title'                                    ] ) )  { $medium -> set_title               ( $_GET[ 'title'             ] ) ; }
if ( isset ( $_GET[ 'author'                                   ] ) )  { $medium -> set_author              ( $_GET[ 'author'            ] ) ; }
if ( isset ( $_GET[ 'publisher'                                ] ) )  { $medium -> set_publisher           ( $_GET[ 'publisher'         ] ) ; }
if ( isset ( $_GET[ 'ISBN'                                     ] ) )  { $medium -> set_ISBN                ( $_GET[ 'ISBN'              ] ) ; }
if ( isset ( $_GET[ 'year'                                     ] ) )  { $medium -> set_year                ( $_GET[ 'year'              ] ) ; }
if ( isset ( $_GET[ 'volume'                                   ] ) )  { $medium -> set_volume              ( $_GET[ 'volume'            ] ) ; }
if ( isset ( $_GET[ 'edition'                                  ] ) )  { $medium -> set_edition             ( $_GET[ 'edition'           ] ) ; }
if ( isset ( $_GET[ 'signature'                                ] ) )  { $medium -> set_signature           ( $_GET[ 'signature'         ] ) ; }
if ( isset ( $_GET[ 'notes_to_staff'                           ] ) )  { $medium -> set_notes_to_staff      ( $_GET[ 'notes_to_staff'    ] ) ; }
if ( isset ( $_GET[ 'notes_to_studies'                         ] ) )  { $medium -> set_notes_to_studies    ( $_GET[ 'notes_to_studies'  ] ) ; }
if ( isset ( $_GET[ 'shelf_remain'                             ] ) )  { $medium -> set_shelf_remain        ( $_GET[ 'shelf_remain'      ] ) ;
                                                                        $medium -> set_location_id         ( $_GET[ 'shelf_remain'      ] ) ; }
if ( isset ( $_GET[ 'location_id'                              ] ) )  { $medium -> set_location_id         ( $_GET[ 'location_id'       ] ) ; }
if ( isset ( $_GET[ 'lmsid'                                    ] ) )  { $medium -> set_collection_id       ( $this -> splitCourseName_user( $_GET[ 'lmsid'            ] )  ); }
if ( isset ( $_GET[ 'dc_collection_id'                         ] ) )  { $medium -> set_collection_id       ( $this -> b64de(                $_GET[ 'dc_collection_id' ] )  ); }

##
### ------------------------------- COLLECTION  --------------------------------
##

if      ( isset ( $_GET[ 'collection_id'                       ] ) )  { $currentCollection =   $this ->   SQL-> getCollection ( $_GET[ 'collection_id'  ]   );$currentCollection -> set_collection_id    ( $_GET[ 'collection_id'        ] ) ; $currentCollection -> set_dc_collection_id ( $this->b64en( $_GET[ 'collection_id'     ] ) ) ; }
else if ( isset ( $_GET[ 'dc_collection_id'                    ] ) )  {
  $cid = $this->b64de( $_GET[ 'dc_collection_id'  ] );

  $cc = $this ->   SQL-> getCollection (  $cid );
  $currentCollection =   $cc[ $cid ] ;
  $currentCollection -> set_dc_collection_id     ( $_GET[ 'dc_collection_id'     ] ) ; $currentCollection -> set_collection_id    ( $cid ) ;
}
else if ( isset ( $_GET[ 'lmsid'                               ] ) )  { $currentCollection -> set_dc_collection_id     ( $this-> b64en( $this-> splitCourseName_user( $_GET[ 'lmsid' ] ) ) )  ; $currentCollection -> set_collection_id  (  $this-> splitCourseName_user( $_GET[ 'lmsid' ] )  ); }
if ( isset ( $_GET[ 'sortorder'                                ] ) )  { $currentCollection -> set_sortorder            ( $_GET[ 'sortorder'            ] ) ; }
if ( isset ( $_GET[ 'expiry_date'                              ] ) )  { $currentCollection -> set_expiry_date          ( $_GET[ 'expiry_date'          ] ) ; }
if ( isset ( $_GET[ 'user_id'                                  ] ) )  { $currentCollection -> set_user_id              ( $_GET[ 'user_id'              ] ) ; }
if ( isset ( $_GET[ 'bib_id'                                   ] ) )  { $currentCollection -> set_bib_id               ( $_GET[ 'bib_id'               ] ) ; }
if ( isset ( $_GET[ 'department_id'                            ] ) )  { $currentCollection -> set_department_id        ( $_GET[ 'department_id'        ] ) ; }
if ( isset ( $_GET[ 'semester_id'                              ] ) )  { $currentCollection -> set_sem                  ( $_GET[ 'semester_id'          ] ) ; }
if ( isset ( $_GET[ 'notes_to_studies_col'                     ] ) )  { $currentCollection -> set_notes_to_studies_col ( $_GET[ 'notes_to_studies_col' ] ) ; }
if ( isset ( $_GET[ 'notes_to_staff_col'                       ] ) )  { $currentCollection -> set_notes_to_staff_col   ( $_GET[ 'notes_to_staff_col'   ] ) ; }
if ( isset ( $_GET[ 'imsid'                                    ] ) )  { $currentCollection -> set_collection_id        ( $_GET[ 'imsid'                ] ) ; } ## IMSID sollte zu collection_id geändert werden
if ( isset ( $_GET[ 'lms-download'                             ] ) )  { $cc  =  $this -> SQL -> getCollection( $_SESSION[ 'currentCollection' ][ 'collection_id' ] );
                                                                        $currentCollection =  array_pop   ( $cc  );  }
 

##
### ------------------------------- EMAIL  --------------------------------
##
if      ( isset ( $_GET[ 'to'                                  ] ) )
{
 $email = new Email();
 $email -> set_to    ( $_GET[ 'to'     ] );
 if ( isset ( $_GET[ 'to'                                ] ) )  { $email -> set_to              ( $_GET[ 'to'               ] ) ; }
 if ( isset ( $_GET[ 'cc'                                ] ) )  { $email -> set_cc              ( $_GET[ 'cc'               ] ) ; }
 if ( isset ( $_GET[ 'bcc'                               ] ) )  { $email -> set_bcc             ( $_GET[ 'bcc'              ] ) ; }
 if ( isset ( $_GET[ 'from'                              ] ) )  { $email -> set_from            ( $_GET[ 'from'             ] ) ; }
 if ( isset ( $_GET[ 'mailtext'                          ] ) )  { $email -> set_mailtext        ( $_GET[ 'mailtext'         ] ) ; }
 $I[ 'email'  ] = $email;
}

##
## ------------------------------- Rückgabewerte I  --------------------------------
##
$I[ 'operator'           ] = $operator;
$I[ 'currentCollection'  ] = $currentCollection;
$I[ 'currentUser'        ] = $currentUser;
$I[ 'medium'             ] = $medium;
$I[ 'filter'             ] = $this -> get_filter( $operator );

return $I ;
}

function splitCourseName_user($cnu, $selector = false )
{
  $ret =  explode ( '###', $lms = $this -> b64de( $cnu ) );          # deb( $lms );
  if ($selector)   {  $ret = $ret[ 1 ];  }
  else             {  $ret = $ret[ 0 ];  }

  return $ret;
}

# ---------------------------------------------------------------------------------------------
function getGET_BASE_Values ( )
{
  $operator                =  new operator();
  $currentCollection       =  new collection();
  $currentUser             =  new user();
  $medium                  =  new medium();
  
  
  $_SESSION[ 'filter' ][ 'bib'   ] = '';
  $_SESSION[ 'filter' ][ 'sem'   ] = $_SESSION[ 'CUR_SEM'      ] ;
  $_SESSION[ 'filter' ][ 'state' ] = '';
  $_SESSION[ 'filter' ][ 'type'  ] = '';
  $_SESSION[ 'filter' ][ 'user'  ] = '';

  $_SESSION[ 'history' ][ 3 ] = '';
  $_SESSION[ 'history' ][ 2 ] = '';
  $_SESSION[ 'history' ][ 1 ] = '';
  $_SESSION[ 'history' ][ 0 ] = '';

  #### Übergabe aus Moodle / IDM
#------------------------------------------------------------------------

  if ( isset ( $_GET[ 'cid'] ) )  { $currentCollection -> set_course_id    ( $this -> b64de( $_GET[ 'cid']  ) );  }
  if ( isset ( $_GET[ 'mid'] ) )  { $currentCollection -> set_modul_id     ( $this -> b64de( $_GET[ 'mid']  ) );  }
  if ( isset ( $_GET[ 'sn' ] ) )  { $currentCollection -> set_title_short  ( $this -> b64de( $_GET[ 'sn' ]  ) );  }
  if ( isset ( $_GET[ 'cn' ] ) )  { $currentCollection -> set_title        ( $this -> b64de( $_GET[ 'cn' ]  ) );  }

 #  deb($currentCollection,1);
#------------------------------------------------------------------------

  if ( isset ( $_GET[ 'uid'] ) )  { $currentUser -> set_id                 ( $this -> b64de( $_GET[ 'uid']  ) ) ; }
  if ( isset ( $_GET[ 'm'  ] ) )  { $currentUser -> set_email              ( $this -> b64de( $_GET[ 'm'  ]  ) ) ; }
  if ( isset ( $_GET[ 'fn' ] ) )  { $currentUser -> set_forename           ( $this -> b64de( $_GET[ 'fn' ]  ) ) ; }
  if ( isset ( $_GET[ 'ln' ] ) )  { $currentUser -> set_surname            ( $this -> b64de( $_GET[ 'ln' ]  ) ) ; }
  if ( isset ( $_GET[ 'u'  ] ) )  { $currentUser -> set_hawaccount         ( $this -> b64de( $_GET[ 'u'  ]  ) ) ; }
  if ( isset ( $_GET[ 'dp' ] ) )  { $currentUser -> set_department         ( $this -> b64de( $_GET[ 'dp' ]  ) ) ; }
  if ( isset ( $_GET[ 'sx' ] ) )  { $currentUser -> set_sex                ( $this -> b64de( $_GET[ 'sx' ]  ) ) ; }
  if ( isset ( $_GET[ 'ro' ] ) )  { $currentUser -> set_role_id            ( $this -> b64de( $_GET[ 'ro' ]  ) ) ; }
  if ( isset ( $_GET[ 'ro' ] ) )  { $currentUser -> set_role_encode        (                 $_GET[ 'ro' ]    ) ; }

  $currentUser  =  $this -> getRole    ( $currentUser  , $currentCollection  );
  $currentUser  =  $this -> getFachBIB ( $currentUser );
  $currentUser  =  $this -> getSex     ( $currentUser );
 
#-------------------------------------------------------------------------


 
#-------------------------------------------------------------------------

 
  $O[ 'operator'          ]  =  $operator;
  $O[ 'currentCollection' ]  =  $this -> updateCollection(  $currentCollection, $currentUser );
  $O[ 'currentUser'       ]  =  $this -> updateUser( $currentUser  );
  $O[ 'medium'            ]  =  $medium;
   # deb($O[ 'currentCollection' ] ,1);
  return $O;
}


function controller( $operator )
{
  if ( $operator -> mode == 'filterBib' OR $operator -> mode == 'filterSem' )
  { $operator -> action = 'show_collection_list';
    $operator -> mode   = '';
  }

  return $operator;
}

# ---------------------------------------------------------------------------------------------
function getRole( $user, $collection = null  )
{
  $az =  explode("," ,  str_replace( " ", "", $this -> conf['CONF'][ 'adminzone' ]   ) );      #  --- Optimierung??  nicht jedes Mal in ein Array umwandeln?

  if ( isset( $collection ) AND in_array( $collection->get_title_short() ,  $az ) ) ## Alle Nuztzer die ELSE in einem moodle Raum aufrufen, die in 'adminzone' aufgelistet sind, erhalten automatisch die Rolle 'staff'
  { $role[ 'role'   ] = 2;
    $role[ 'tmpcat' ] = 1;
  }
  else
  { $role[ 'role'   ] =  $user->get_role_id();
    $role[ 'tmpcat' ] = '';
  }
  $role[ 'role_name' ] = $this -> SQL -> getRoleName ( $role[ 'role' ] ) ;  ## echo "--Transformiert EMIL-Rechte zu ELSE-Rechte/Rollennamen --";
  
  $user -> set_tmpcat      ( $role[ 'tmpcat'                ]   ) ; 
  $user -> set_role_name   ( $role[ 'role_name'             ]   ) ;
  $user -> set_role_id     ( $role[ 'role'                  ]   ) ;
  $user -> set_role_encode ( $this -> b64en ( $role[ 'role' ] ) ) ;
 
  return  $user;
}


# ---------------------------------------------------------------------------------------------
function getSex( $user )
{
  if   ( $user -> get_sex ( 2 ) ) { $user -> set_sex ( 'w' ); }
  else                            { $user -> set_sex ( 'm' ); }
  return  $user;
}


# ---------------------------------------------------------------------------------------------
function getFachBIB( $user )
{
  if ( isset( $_SESSION[ 'DEP_2_BIB' ][ $user -> get_department()  ] ) )
              {  $bib = $_SESSION[ 'DEP_2_BIB' ][ $user -> get_department() ];  }
  else        {  $bib = $_SESSION[ 'DEP_2_BIB' ][ 101 ];                }  ## Preset auf FachBib = HAW, falls dem User kein Department zugeordnet wurde.

  $user -> set_dep_id       (  $bib[ 'dep_id'      ] ) ;
  $user -> set_dep_name     (  $bib[ 'dep_name'    ] ) ;
  $user -> set_dep_2_fak    (  $bib[ 'dep_2_fak'   ] ) ;
  $user -> set_DEP_2_BIB    (  $bib[ 'DEP_2_BIB'   ] ) ;
  $user -> set_fak_id       (  $bib[ 'fak_id'      ] ) ;
  $user -> set_fak_name     (  $bib[ 'fak_name'    ] ) ;
  $user -> set_fak_abk      (  $bib[ 'fak_abk'     ] ) ;
  $user -> set_bib_id       (  $bib[ 'bib_id'      ] ) ;
  $user -> set_bib_name     (  $bib[ 'bib_name'    ] ) ;
  $user -> set_bib_ap_name  (  $bib[ 'bib_ap_name' ] ) ;
  $user -> set_bib_ap_mail  (  $bib[ 'bib_ap_mail' ] ) ;

  return  $user;
}


# ---------------------------------------------------------------------------------------------
function getCollectionMetaData ( $Course, $IDMuser )
{
  if ( $this -> SQL -> getCollectionMetaData ( $Course[ 'shortname' ] ) )
  {
    $IC[ 'category_id'      ] = $IDMuser[ 'department' ] ;
    $IC[ 'title'            ] = $Course[  'fullname'   ] ;
    $IC[ 'title_short'      ] = $Course[  'shortname'  ] ;
    $IC[ 'collection_id'    ] = $Course[  'shortname'  ] ;
    $IC[ 'dc_collection_id' ] = $this -> b64de( $Course[  'shortname'  ]) ;
    $IC[ 'bib_id'           ] = $_SESSION[ 'DEP_2_BIB'   ][ $IDMuser[ 'department' ] ] [ 'bib_id' ];

    return $IC;
  }
}



# ---------------------------------------------------------------------------------------------
function updateUser ( $user )
{
  if ( $user -> get_role_id () == 1    OR   $user -> get_role_id () == 2    OR   $user -> get_role_id () == 3)
  {
    if    ( $this -> SQL -> checkUserExistence ( $user -> get_hawaccount ()  ) ) { $this -> SQL -> updateUser (  $user  ) ; }  # echo "- Bestehender USER (UPDATE DB )-";
    else                                                                         { $this -> SQL -> initUser   (  $user  ) ; }  # echo "- NEUER USER (INIT DB )-";

    $user = $this -> SQL -> getUserMetaData ( $user -> get_hawaccount () ) ;

    $user = $this -> getRole   ( $user );
    $user = $this -> getFachBIB( $user );
    $user = $this -> getSex    ( $user );
  }
  return $user;
}


# ---------------------------------------------------------------------------------------------
function updateCollection ( $collection , $user )
{
  if ( $collection->get_id() != '' AND ( $user -> get_role_id () == 1    OR   $user -> get_role_id () == 2    OR   $user -> get_role_id () == 3) )
  {
    $old_collection = $this -> SQL -> getCollectionMetaData( $collection -> get_title_short() ) ;

    if (  $old_collection -> get_id() != '' )                                            #  echo "Semesterapparat existiert schon";
    {
      $ret = $this -> SQL -> updateColMetaData( $collection  );                          #  echo "- Bestehender Semesterapparat (UPDATE DB )-";
    }
  }
  else                                                                                    #  echo "Semesterapparat existiert NOCH NICHT ";
  {
    $collection -> set_expiry_date ( $this -> get_new_expiry_date() );
    $ret = $this -> SQL -> initCollection( $collection , $user );                         #  echo "- NEUER Semesterapparat (INIT DB )-";
  }
  $collection = $this -> SQL -> getCollectionMetaData( $collection -> get_title_short() ) ;
  return $collection;
}


# ---------------------------------------------------------------------------------------------
  function sendBIB_APmails()
  {
    
    $BIB_Anrede  = $this -> conf[ 'BIBMAIL' ][ 'Anrede' ]; #= "Liebe ELSE/HIBS Mitarbeiterin  \r\n\r\n";
    $BIB_Gruss   = $this -> conf[ 'BIBMAIL' ][ 'Gruss'  ]; #= "\r\n\r\nIhr ELSE Server \r\n\r\n http://www.elearning.haw-hamburg.de/mod/else/view.php?id=443297  \r\n\r\n";
    
    $mailInfos =     $this -> SQL -> getAdminEmailInfos ( ) ;
    deb($mailInfos );
    foreach ($mailInfos as $mi)
    { $trenner  ="---------------------------------------";
      $message  ="";
      $subject  = '';
      $message .= $BIB_Anrede. "\r\n\r\n";
      if ( $mi[9] > 0 OR $mi[1] > 0 OR $mi[4] > 0  )
      {
        {
          $message .= "ELSE Statusbericht: \r\n\r\n";
          $message .= $trenner. "\r\n";
          if( conf[ 'ADMINEMAILINFO' ][ 'new'      ] AND  $mi[ 1  ] > 0 ) {$message .= " Neu bestellt: "  . $mi[ 1  ] . "\r\n"; $subject .= " [ N:".$mi[1]."  ] "; }
          if( conf[ 'ADMINEMAILINFO' ][ 'suggest'  ] AND  $mi[ 9  ] > 0 ) {$message .= " Kaufvorschlag: " . $mi[ 9  ] . "\r\n"; $subject .= " [ K:".$mi[9]."  ] "; }
          if( conf[ 'ADMINEMAILINFO' ][ 'obsolete' ] AND  $mi[ 4  ] > 0 ) {$message .= " Entfernen: "     . $mi[ 4  ] . "\r\n"; $subject .= " [ E:".$mi[4]."  ] "; }
          if( conf[ 'ADMINEMAILINFO' ][ 'continue' ] AND  $mi[ 10 ] > 0 ) {$message .= " Verlängert: "    . $mi[ 10 ] . "\r\n"; $subject .= " [ V:".$mi[10]." ] "; }
          $message .= $trenner. "\r\n";
  
          $subject  = "ELSE: Statusbericht -- ". $mi['bib_id'] . " -- " . $subject;
        }
     
        $message .= "\r\n\r\n" . $BIB_Gruss;
        
        $to =  $mi[ 'bib_ap_mail' ];
        deb($message);
        $this -> sendAMail($to, $subject, $message);
      }
    }
  }


# ---------------------------------------------------------------------------------------------
function sendAMail($to, $subject='ELSE:', $message)
{

    $BIB_BCC  = $this -> conf[ 'BIBMAIL' ]['BCC'];
    $BIB_FROM = $this -> conf[ 'BIBMAIL' ]['FROM'];
    $BIB_RPTO = $this -> conf[ 'BIBMAIL' ]['RPTO'];

    $bcc = $BIB_BCC;  # 'daniela.mayer@haw-hamburg.de, werner.welte@haw-hamburg.de' ;
    $from = $BIB_FROM;  # 'ELSE-noreply@haw-hamburg.de' ;
    $rpto = $BIB_RPTO;  # 'werner.welte@haw-hamburg.de';

    $header = 'From: ' . $from . "\r\n";
    $header .= 'Reply-To: ' . $rpto . "\r\n";
    $header .= 'Bcc: ' . $bcc . "\r\n";
    $header .= "Mime-Version: 1.0\r\n";
    $header .= "Content-type: text/plain; charset=iso-8859-1";
    $header .= 'EMIL-Mailer: PHP/' . phpversion();

    ## $to = 'werner.welte@haw-hamburg.de';  ## ++++++++++++ TESTING ONLY !!! ++++++++++++++++++++++++

    $sendok = mail($to, $subject, $message, $header);
}



# ---------------------------------------------------------------------------------------------
function get_new_expiry_date ()
{
  $t = getdate () ;

  $t[ 'mday' ] = 1 ;

  if      ( $t[ 'mon' ] <= 2 )  { $t[ 'mon' ] = 3 ;  }
  else if ( $t[ 'mon' ] <= 7 )  { $t[ 'mon' ] = 9 ;  }
  else                          { $t[ 'mon' ] = 3 ;  $t[ 'year' ] ++ ;  }

  $ans = sprintf ( "%04d%02d%02d" , $t[ 'year' ] , $t[ 'mon' ] , $t[ 'mday' ] ) ;
  return $ans ;
}



# ---------------------------------------------------------------------------------------------
function getCurrentSem()
{
  date_default_timezone_set('UTC');
  foreach( $_SESSION[ 'CFG' ][ 'SEM' ] as $SemKurz => $SemT )
  {
    $sA   = explode('_' , $SemT[0] );
    $semA = mktime( 0 , 0  , 0  , $sA[1] , $sA[2] , $sA[0] );

    if ( $semA <=  time( ) )   { $curSem = $SemKurz; }
  }
#deb($curSem,1);
return $curSem;
}


function hasRole( $currentUser, $r1, $r2 = null, $r3 = null, $r4 = null, $r5 = null, $r6 = null, $r7 = null )
{
  $role_name = $currentUser->role_name;
  if
  ($role_name != '' AND
    (    $role_name == $r1
      OR $role_name == $r2
      OR $role_name == $r3
      OR $role_name == $r4
      OR $role_name == $r5
      OR $role_name == $r6
      OR $role_name == $r7
    )
  )    { return true;  }
else { return false;    }
}



# ---------------------------------------------------------------------------------------------
  function b64de( $str )  # BASE64 decode: GET input
  {
    return  trim( rawurldecode ( base64_decode( $str ) ) );
  }

# ---------------------------------------------------------------------------------------------
  function b64en( $str )  # BASE64 decode: GET input
  {
    return  base64_encode ( rawurlencode ( $str ) );
  }

# ---------------------------------------------------------------------------------------------
  function deb($obj, $kill=false) {   echo "<pre>";  print_r ($obj);  echo "</pre>";  if($kill){die();} }


function get_filter( $operator )
{
  
  $filter = new Filter();

  $filter -> set_bib     ( $_SESSION[ 'filter' ][ 'bib'   ]  );
  $filter -> set_sem     ( $_SESSION[ 'filter' ][ 'sem'   ]  );
  $filter -> set_state   ( $_SESSION[ 'filter' ][ 'state' ]  );
  $filter -> set_type    ( $_SESSION[ 'filter' ][ 'type'  ]  );

  if ( $operator -> get_mode() == 'filterBib'   )                 # Filter State u. Type  wird auf ALLE zurückgesetzt bei Filter auf Bib
  { $filter -> set_bib   ( $operator -> get_category( ) );
    $filter -> set_state ( 0 );
    $filter -> set_type  ( 0 );
  }
  
  if ( $operator -> get_mode() == 'filterSem'   )                  # Filter State u. Type  wird auf ALLE zurückgesetzt bei Filter auf Sem
  { $filter -> set_sem   ( $operator -> get_category( ) );
    $filter -> set_state ( 0 );
    $filter -> set_type  ( 0 );
  }
  
  if ( $operator -> get_mode() == 'filterState' )
  { $filter -> set_state ( $operator -> get_category( ) );
    # if (  $operator -> get_category( ) == 0 )
    {  $filter -> set_type ( 'X' );
    }
  }
  
  if ( $operator -> get_mode() == 'filterType'  )
  { $filter -> set_type  ( $operator -> get_category( ) );
    $filter -> set_state ( 0 );
  }
  
  if ( $operator -> get_mode() == 'filterUser'  )
  { $filter -> set_user  ( $operator -> get_category( ) );
  }

  $_SESSION[ 'filter' ] = $filter -> obj2array ();
  
  return $filter;
}




# -DEPRECATED ??? --------------------------------------------------------------------------------------------
    function get_item_owner ( $item , $id )
    {
        $user = NULL ;

        if ( $id )
        {
            switch ( $item ) {
                case "user":
                    $p   = array ( 'tables' => "user,degree" , 'columns' => "user.*,degree.description AS degree_description" , 'cond' => "user.id = $id AND degree.id = user.degree_id" ) ;
                    $ans = $this -> SQL -> SQL_query ( 'select' , $p ) ;
                    if ( empty ( $ans ) )
                    {
                        user_error ( "database query failed" , E_USER_ERROR ) ;
                    }
                    $user = $ans[ 0 ] ;
                    break ;

                case "collection":
                    $p   = array ( 'tables' => "collection" , 'cond' => "id = $id" ) ;
                    $ans = $this -> SQL -> SQL_query ( 'select' , $p ) ;
                    if ( empty ( $ans ) )
                    {
                        user_error ( "database query failed" , E_USER_ERROR ) ;
                    }
                    $user = $this -> get_item_owner ( "user" , $ans[ 0 ][ 'user_id' ] ) ;
                    break ;

                case "email":
                    $p    = array ( 'tables' => "email" , 'cond' => "id = $id" ) ;
                    $ans  = $this -> SQL -> SQL_query ( 'select' , $p ) ;
                    $user = get_item_owner ( "document" , $ans[ 0 ][ 'document_id' ] ) ;
                    break ;

                default:
                    $p   = array ( 'tables' => "document" , 'cond' => "id = $id" ) ;
                    $ans = $this -> SQL -> SQL_query ( 'select' , $p ) ;
                    if ( empty ( $ans ) )
                    {
                        user_error ( "database query failed" , E_USER_ERROR ) ;
                    }
                    $user = $this -> get_item_owner ( "collection" , $ans[ 0 ][ 'collection_id' ] ) ;
                    break ;
            }
        }
        return $user ;
    }




# ---------------------------------------------------------------------------------------------
function check_acl ( $acl_list , $item , $id )
{
    if ( isset ( $acl_list[ 'any' ] ) )
    {
        $acl = $acl_list[ 'any' ] ;
    }
    else if ( isset ( $acl_list[ $item ] ) )
    {
        $acl = $acl_list[ $item ] ;
    }
    else
    {
        return FALSE ;
    }

    $u = $_SESSION[ 'user' ] ;

    $ok_ower = $ok_role = $ok_any  = false ;

    if ( isset ( $acl ) )
    {
        foreach ( explode ( ',' , $acl ) as $a ) {      # $a = 'role=admin' OR 'owner=true'
            list( $k , $v ) = explode ( '=' , $a ) ;  # $k = 'role'/'owner' --  $v = 'admin' / 'edit'
            if ( $k == 'owner' )
            {
                $o       = $this -> get_item_owner ( $item , $id ) ;
                $ok_ower = ( $u[ 'id' ] == $o[ 'id' ]) ;
                if ( $ok_ower )
                {
                    break ;
                }
            }

            if ( $k == 'role' )
            {
                $ok_role = ( $u[ 'role_name' ] == $v ) ;
                if ( $ok_role )
                {
                    break ;
                }
            }

            if ( $k == 'any' )
            {
                $ok_any = true ;
                break ;
            }
        }
    }
    return ( $ok_ower || $ok_role || $ok_any ) ;
}

  function xml2array ( $xmlObject, $out = array () )
  {
    foreach ( (array) $xmlObject as $index => $node )
      $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

    return $out;
  }
  
  
  ####################### --- PRE DEPRECATED TOOLS --- #######################
  
  function getOPACDocType($book)
  {
    
    if (isset ($book['physicaldesc']) AND !isset ($book['state_id'] )  )
    {                                                                                                      $book['state_id'   ]  =  1 ;
      if(      stristr(  $book['physicaldesc']  , 'Online') == TRUE ) { $book['doc_type_id']  = 4;  $book['state_id'   ]  =  3;}
      else if( stristr(  $book['physicaldesc']  , 'CD-ROM') == TRUE ) { $book['doc_type_id']  = 3;  $book['state_id'   ]  =  1;}
    }
    
    if (!isset ($book['doc_type_id']))
    {  $book['doc_type_id'] = 1;
    }
    
    if( $book['doc_type_id']  == 4 )
    {
      $book['doc_type'   ]  = "electronic";   #  E-BOOK
      $book['item'       ]  = 'online';
    }
    else if( $book['doc_type_id']  == 3 )     # CD-ROM
    {
      $book['doc_type'   ]  = "cd-rom";
      $book['item'       ]  = 'book';
      /* Status: NEU BESTELLT  */
    }
    else if( $book['doc_type_id']  == 2 )      # BUCH als Literaturhinweis
    {
      $book['doc_type'   ]  = "print";
      $book['item'       ]  = 'lh_book';
    }
    else                                       # BUCH im Semesterapparat
    {
      $book['doc_type'   ]  = "print";
      $book['item'       ]  = 'book';
      $book['doc_type_id']  =  1;
    }
  
    $book['sigel'   ]  = "HAW-Hamburg";
    return $book;
  }

  
  

####################### --- DEPRECATED TOOLS --- #######################
# ---------------------------------------------------------------------------------------------
    function check_permission ( $INPUT )
    {
        #--------------------------------------------------------------------------------------------------------------------
        if ( !        $this -> check_acl (  $_SESSION[ 'ACTION_INFO'  ][ $INPUT[ 'work' ][ 'action' ] ][ 'acl' ] , $INPUT[ 'work' ][ 'item' ] , $INPUT[ 'work' ][ 'id' ] ) )
        {
            user_error ( "Permission denied: action " . $INPUT[ 'work' ][ 'action' ] . " on item type " . $INPUT[ 'work' ][ 'item' ] . " for: " . $_SESSION[ 'user' ][ 'role_name' ] . " / " . $_SESSION[ 'user' ][ 'surname' ] . " " , E_USER_ERROR ) ;
        }
    }


# ---------------------------------------------------------------------------------------------
    function check_host()
    {
        /* (Assuming session already started) */
        if(     isset( $_SESSION[ 'referrer'     ] ) ) {  $referrer   = $_SESSION[ 'referrer'     ];  }  // Get existing referrer
        elseif( isset( $_SERVER[  'HTTP_REFERER' ] ) ) {  $referrer   = $_SERVER[  'HTTP_REFERER' ];  }  // Use given referrer
        else         {}    // No referrer

        // Save current page as next page's referrer
        $_SESSION['referrer']   = $this -> current_page_url();

        if  ( isset ($_SERVER['HTTP_REFERER' ] ) )
        {   $host1 = explode('/', $_SERVER['HTTP_REFERER']);

            if ( ! in_array( $host1[2],$this -> conf[ 'CONF' ]['ok_host'] ) )  {  die("<div style='text-align:center;'><h1>ACCESS ERROR<h1><h3>Unzul&auml;ssiger Zugriff!</h3><a href=\"javascript:window.back()\">Zur&uuml;ck</a></div>"); }
        }
        else
        {  if( $_SERVER['SERVER_NAME' ] != 'localhost' )
        {
            header("Location:index.html");
            die("<div style='text-align:center;'><h1>ACCESS ERROR<h1><h3>Unzul&auml;ssiger Zugriff!</h3><a href=\"javascript:window.back()\">Zur&uuml;ck</a></div>");
        }
        }
    }

    // Get the full URL of the current page
# ---------------------------------------------------------------------------------------------
   /*
    function current_page_url()
    {
        $page_url   = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            $page_url .= 's';
        }
        return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }
   */
}

function deb($obj, $kill=false) {   echo "<pre>";  print_r ($obj);  echo "</pre>";  if($kill){die();} }
?>