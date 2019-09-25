<?php
class Util   /// \brief check user input
{
  var $SQL ;
  var $CFG ;
  var $conf;
  var $currentUser;
  var $currentCollection;

# ---------------------------------------------------------------------------------------------
function __construct ( $CFG, $SQL )
{
  $this -> SQL    = $SQL;
  $this -> CFG    = $CFG;
  $this -> conf   = $this -> CFG -> getConf();
}

# ---------------------------------------------------------------------------------------------
function getInput ( )
{

$operator          = new operator();
$currentCollection = new collection();
$currentUser       = new User();
$medium            = new medium();
$this -> HAWdb     = new HAW_DB();                                    # Aus der SQLite DB


if ( ! isset ( $_SESSION [ 'DEP_2_BIB' ] ) )  // Standardkonstanten werden nur beim ersten Aufruf eingelesen.
{
  $_SESSION[ 'DEP_2_BIB'    ] = $this -> HAWdb -> getDEP_2_BIB ();
  $_SESSION[ 'FAK'          ] = $this -> HAWdb -> getAllFak ();
  $_SESSION[ 'FACHBIB'      ] = $this -> HAWdb -> getAllFachBib ();
  $_SESSION[ 'DOC_TYPE'     ] = $this -> SQL -> getAllDocTypes();
  $_SESSION[ 'MEDIA_STATE'  ] = $this -> SQL -> getAllMedStates ();
  $_SESSION[ 'ACTION_INFO'  ] = $this -> CFG -> C -> CONST_ACTION_INFO ;
  $_SESSION[ 'CFG'          ] = $this -> CFG -> getConf();
  $_SESSION[ 'SEM'          ] = $this -> CFG -> C -> sem;
  $_SESSION[ 'CUR_SEM'      ] = $this -> getCurrentSem ();
}


if ( isset ( $_GET[ 'uid' ] ) )  ##  Initiale Parameterübergabe über  Moodle ## // Kurskurzname   /* Paramterübergabe von EMIL  */
{
  $O = $this -> getGET_BASE_Values () ;
  $currentCollection = $O[ 'currentCollection' ] ;
  $currentUser       = $O[ 'currentUser'       ] ;
  $medium            = $O[ 'medium'            ] ;

  $_SESSION[ 'currentUser'       ] = (array) $currentUser      ;
}
else
{
  $currentUser       -> array2obj( $_SESSION[ 'currentUser'       ] );
}



#  if ( $_SESSION['currentUser']['Userrole_id'] == ''   ) { die(  '<div style="  display: flex;  position: absolute;  top:45%; right:45%; font-size: 30px; "> TIME OUT <div>'); }

##
### ------------------------------- SET HISTORY  --------------------------------
##
if (isset(  $_SERVER [ 'HTTP_REFERER'      ] ))
{
  $_SESSION[ 'history' ][ 3 ] = $_SESSION[ 'history' ][ 2 ];
  $_SESSION[ 'history' ][ 2 ] = $_SESSION[ 'history' ][ 1 ];
  $_SESSION[ 'history' ][ 1 ] = $_SESSION[ 'history' ][ 0 ];
  $_SESSION[ 'history' ][ 0 ] = $_SERVER [ 'HTTP_REFERER' ];
  $operator->set_history ( $_SESSION[ 'history' ] );
}

##
### ------------------------------- OPERATOR  --------------------------------
##
## Action DEFAULTEEINSTELLUNGEN für die einzelnen Rollen
if (  $this->hasRole( $currentUser,'admin', 'staff') )       { $operator -> set_action           ( 'show_collection_list' );  }
else                                                                 { $operator -> set_action           ( 'show_collection'      );  }

if ( isset ( $_GET[ 'item'                                     ] ) ) { $operator -> set_item             ( $_GET[ 'item'               ] ) ; }
if ( isset ( $_GET[ 'action'                                   ] ) ) { $operator -> set_action           ( $_GET[ 'action'             ] ) ; }
if ( isset ( $_GET[ 'mode'                                     ] ) ) { $operator -> set_mode             ( $_GET[ 'mode'               ] ) ; }
if ( isset ( $_GET[ 'category'                                 ] ) ) { $operator -> set_category         ( $_GET[ 'category'           ] ) ; }

if ( isset ( $_GET[ 'lms-download'                             ] ) ) { $operator -> set_action           ( 'lms-download'           ) ;
                                                                       $operator -> set_item             ( 'collection'             ) ;
                                                                       $operator -> set_url              (  $_GET[ 'lms-download'      ] ) ;
}  ## Hook, für BELUGA IMPORT, wird als action= "Downloadlink", mode


#if ( isset ( $_GET[ 'ro'                                       ] ) ) { $operator->set_role              ( $_GET[ 'ro'                 ] ) ; }
#if ( isset ( $_GET[ 'redirect'                                 ] ) ) { $operator->set_redirect          ( $_SESSION['last_page'       ] ) ; }


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
                                                                        $medium -> set_in_SA               ( $_GET[ 'shelf_remain'      ] ) ; }

##
### ------------------------------- COLLECTION  --------------------------------
##
if ( isset ( $_GET[ 'sortorder'                                ] ) )  { $currentCollection -> set_sortorder            ( $_GET[ 'sortorder'            ] ) ; }
if ( isset ( $_GET[ 'expiry_date'                              ] ) )  { $currentCollection -> set_expiry_date          ( $_GET[ 'expiry_date'          ] ) ; }
if ( isset ( $_GET[ 'user_id'                                  ] ) )  { $currentCollection -> set_user_id              ( $_GET[ 'user_id'              ] ) ; }
if ( isset ( $_GET[ 'bib_id'                                   ] ) )  { $currentCollection -> set_bib_id               ( $_GET[ 'bib_id'               ] ) ; }
if ( isset ( $_GET[ 'department_id'                            ] ) )  { $currentCollection -> set_department_id        ( $_GET[ 'department_id'        ] ) ; }
if ( isset ( $_GET[ 'semester_id'                              ] ) )  { $currentCollection -> set_sem                  ( $_GET[ 'semester_id'          ] ) ; }
if ( isset ( $_GET[ 'notes_to_studies_col'                     ] ) )  { $currentCollection -> set_notes_to_studies_col ( $_GET[ 'notes_to_studies_col' ] ) ; }


if      ( isset ( $_GET[ 'imsid'                               ] ) )  { $currentCollection -> set_collection_id        ( $_GET[ 'imsid'                ] ) ; } ## IMSID sollte zu collection_id geändert werden

if      ( isset ( $_GET[ 'collection_id'                       ] ) )  { $currentCollection -> set_collection_id        ( $_GET[ 'collection_id'        ] ) ; $currentCollection -> set_dc_collection_id ( $this->b64en( $_GET[ 'collection_id'     ] ) ) ; }
else if ( isset ( $_GET[ 'dc_collection_id'                    ] ) )  { $currentCollection -> set_dc_collection_id     ( $_GET[ 'dc_collection_id'     ] ) ; $currentCollection -> set_collection_id    ( $this->b64de( $_GET[ 'dc_collection_id'  ] ) ) ; }


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
### ------------------------------- Rückgabewerte I  --------------------------------
##
$I[ 'operator'           ] = $operator;
$I[ 'currentCollection'  ] = $currentCollection;
$I[ 'currentUser'        ] = $currentUser;
$I[ 'medium'             ] = $medium;
$I[ 'filter'             ] = $this -> get_filter( $operator );

return $I ;
}


# ---------------------------------------------------------------------------------------------
function getGET_BASE_Values ( )
{
  $operator                =  new operator();
  $currentCollection       =  new collection();
  $currentUser             =  new user();
  $medium                  =  new medium();


  $_SESSION[ 'filter' ][ 'bib'   ] = '';
  $_SESSION[ 'filter' ][ 'sem'   ] = '';
  $_SESSION[ 'filter' ][ 'state' ] = '';
  $_SESSION[ 'filter' ][ 'type'  ] = '';
  $_SESSION[ 'filter' ][ 'user'  ] = '';

  #### Übergabe aus Moodle / IDM
#------------------------------------------------------------------------

  if ( isset ( $_GET[ 'cid'] ) )  { $currentCollection -> set_course_id    ( $this -> b64de( $_GET[ 'cid']  ) );  }
  if ( isset ( $_GET[ 'mid'] ) )  { $currentCollection -> set_modul_id     ( $this -> b64de( $_GET[ 'mid']  ) );  }
  if ( isset ( $_GET[ 'sn' ] ) )  { $currentCollection -> set_title_short  ( $this -> b64de( $_GET[ 'sn' ]  ) );  }
  if ( isset ( $_GET[ 'cn' ] ) )  { $currentCollection -> set_title        ( $this -> b64de( $_GET[ 'cn' ]  ) );  }

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

  $currentUser = $this -> getRole   ( $currentUser, $currentCollection );
  $currentUser = $this -> getFachBIB( $currentUser );
  $currentUser = $this -> getSex    ( $currentUser );

#-------------------------------------------------------------------------

  $operator->set_sem ( $_SESSION[ 'CUR_SEM' ] );
  $operator->set_item (  'collection' );

 # if (  $this->hasRole( $currentUser,'admin', 'staff') )  {  $operator->set_action (  'show_collection_list' );  }
 # else                                                            {  $operator->set_action (  'show_collection'      );  }

  #-------------------------------------------------------------------------

  $O[ 'operator'          ]  =  $operator;
  $O[ 'currentCollection' ]  =  $this -> updateCollection(  $currentCollection, $currentUser );
  $O[ 'currentUser'       ]  =  $this -> updateUser( $currentUser  );
  $O[ 'medium'            ]  =  $medium;

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
  if ( isset( $collection ) AND in_array( $collection->get_title_short() , $this -> conf[ 'adminzone' ]  ) ) ## Alle Nuztzer die ELSE in einem moodle Raum aufrufen, die in 'adminzone' aufgelistet sind, erhalten automatisch die Rolle 'staff'
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
  if ( isset( $_SESSION[ 'DEP_2_BIB' ][ $user->get_department()  ] ) )
              {  $bib = $_SESSION[ 'DEP_2_BIB' ][ $user->get_department() ];  }
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
    $IC[ 'category_id'    ] = $IDMuser[ 'department' ] ;
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

    /*
    $SESS[ 'user' ] = $ans[ 0 ] ;

    switch ( $IDMuser[ 'role' ] )
    {
      case 1:  $SESS[ 'work' ][ 'mode' ] = 'admin' ;  break ;
      case 2:  $SESS[ 'work' ][ 'mode' ] = 'staff' ;  break ;
      case 3:  $SESS[ 'work' ][ 'mode' ] = 'edit'  ;  break ;
      default: $SESS[ 'work' ][ 'mode' ] = 'guest' ;
    }
  */
  return $user;
  }
}


# ---------------------------------------------------------------------------------------------
function updateCollection ( $collection , $user )
{
  if ( $user -> get_role_id () == 1    OR   $user -> get_role_id () == 2    OR   $user -> get_role_id () == 3)
  {
    $old_collection = $this -> SQL -> getCollectionMetaData( $collection->get_title_short() ) ;

    if (  $old_collection->get_id() != '' )                                          #  echo "Semesterapparat existiert schon";
    {
      $ret = $this -> SQL -> updateColMetaData( $collection  );                       #  echo "- Bestehender Semesterapparat (UPDATE DB )-";
    }
    else                                                                        #  echo "Semesterapparat existiert NOCH NICHT ";
    {
      $collection->set_expiry_date ( $this -> get_new_expiry_date() );
      $ret = $this -> SQL -> initCollection( $collection , $user );                        #  echo "- NEUER Semesterapparat (INIT DB )-";
    }

    $upd_collection = $this -> SQL -> getCollectionMetaData( $collection->get_title_short() ) ;

    return $upd_collection;
  }
}


# ---------------------------------------------------------------------------------------------
function sendBIB_APmails()
{
  $BIB_Anrede  = $this -> conf[ 'BIB_Anrede' ]; #= "Liebe ELSE/HIBS Mitarbeiterin  \r\n\r\n";
  $BIB_Gruss   = $this -> conf[ 'BIB_Gruss'  ]; #= "\r\n\r\nIhr ELSE Server \r\n\r\n http://www.elearning.haw-hamburg.de/mod/else/view.php?id=443297  \r\n\r\n";

  $mailInfos =     $this -> SQL -> getAdminEmailInfos ( ) ;

  foreach ($mailInfos as $mi)
  {
    $message ="";
    $message .= $BIB_Anrede;
    if ( $mi[9] > 0 OR $mi[1] > 0 OR $mi[4] > 0  )
    {
      {                    $subject  = 'ELSE: Statusbericht -- '.$mi['bib_id'] . " -- [ N:".$mi[1]." ] [ K:".$mi[9]." ] [ E:".$mi[4]." ]";
                           $message .= "ELSE Statusbericht: \r\n\r\n";
        if(  $mi[1] > 0 ) {$message .= " Neu bestellt: "  .$mi[1]. "\r\n"; }
        if(  $mi[9] > 0 ) {$message .= " Kaufvorschlag: " .$mi[9]. "\r\n"; }
        if(  $mi[4] > 0 ) {$message .= " Entfernen: "     .$mi[4]. "\r\n"; }
      }

      $message .= $BIB_Gruss;

      $to =  $mi[ 'bib_ap_mail' ];
      $this -> sendAMail($to, $subject, $message);
    }
  }
}


# ---------------------------------------------------------------------------------------------
function sendAMail($to, $subject='ELSE:', $message)
{

    $BIB_BCC = $this -> conf['BIB_BCC'];
    $BIB_FROM = $this -> conf['BIB_FROM'];
    $BIB_RPTO = $this -> conf['BIB_RPTO'];

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
  foreach( $_SESSION[ 'SEM' ] as $SemKurz => $SemT )
  {
    $sA   = explode('_' , $SemT[0] );
    $semA = mktime( 0 , 0  , 0  , $sA[1] , $sA[2] , $sA[0] );

    if ( $semA <=  time( ) )   { $curSem = $SemKurz; }
  }

return $curSem;
}


function hasRole( $currentUser, $r1, $r2 = null, $r3 = null, $r4 = null, $r5 = null, $r6 = null, $r7 = null )
{
  $role_name = $currentUser->role_name;
  if
  ($role_name != '' AND
    (  $role_name == $r1
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

  $filter -> set_bib     ( $_SESSION[ 'filter' ]['bib'  ]  );
  $filter -> set_sem     ( $_SESSION[ 'filter' ]['sem'  ]  );
  $filter -> set_state   ( $_SESSION[ 'filter' ]['state']  );
  $filter -> set_type    ( $_SESSION[ 'filter' ]['type' ]  );
 # $filter -> set_user    ( $_SESSION[ 'filter' ]['user' ]  );

  if ( $operator -> get_mode() == 'filterBib'   ) { $filter -> set_bib   ( $operator -> get_category() );  $filter -> set_state ( 0 );  } # Filter State wird auf ALLE zurückgesetzt bei Filter auf Bib
  if ( $operator -> get_mode() == 'filterSem'   ) { $filter -> set_sem   ( $operator -> get_category() );  $filter -> set_state ( 0 );  } # Filter State wird auf ALLE zurückgesetzt bei Filter auf Sem
  if ( $operator -> get_mode() == 'filterState' ) { $filter -> set_state ( $operator -> get_category() );  }
  if ( $operator -> get_mode() == 'filterType'  ) { $filter -> set_type  ( $operator -> get_category() );  }
  if ( $operator -> get_mode() == 'filterUser'  ) { $filter -> set_user  ( $operator -> get_category() );  }

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





function checkER()
{
  $SA = array(
 'WS..WI.S18 KLR_BAPU'
,'WS..SA.S18AÄM_BASA'
,'LS..OT.W17 WPG'
,'WS..WI.S18 A_BSCAIM'
,'DMI.DS.S18 MOGE1'
,'DMI.DS.S18 DMDK'
,'WS..WI.S18 KLR_BAP_1'
,'WS..PM.S18 POM_BAPUMA'
,'WS.SA.S18 FPJM'
,'WS.SA.S18 SAPJM'
,'TI..IN.S18 FSV Pdb'
,'TI..IN.S18 ATUFS'
,'WS..SA.S18 GKA_BASA'
,'WS..SA.S18 VEF1_BASA'
,'WS..PF.S18 M/TEG'
,'WS..PM.S18 PFE_PUMA_'
,'LS..UT.S18 LALB'
,'DMI.DS.S18 PM1'
,'WS..PM.S18 PFE_PUMA'
,'DMI.IM.S18 IKM'
,'TI..IE.S18 GKT Shn'
,'DMI.IM.S18 BMK4'
,'LS..VT.S18 PHYPRAMT'
,'LS..HW.S18 XTI1'
,'LS..HW.S18 XTI2'
,'WS..SA.S18 SGU1_BASA'
,'WS..WI.S18 WBB_DW'
,'WS..SA.S18 THGR_BASA'
,'DMI.MD.S18 ET1.LAB_16'
,'WS..SA.S18 VWSM'
,'WS..SA.S18 EKSAM'
,'LS..MT.S18 MRTS'
,'LS..MT.S18 MPT'
,'LS..MT.S18 MEST'
,'WS..SA.S18 QRMH'
);

  $ERA = array(
  'DMI.IM.W17 MWIT'
  ,'DMI.IM.W18_MIO4'
  ,'WS..PM.W17 PAP'
  ,'WS..PM.W17 WKB'
  ,'ELSE-ADMIN'
  ,'LS..BT.W17 BIOG'
  ,'TI..FF.W17 DARG'
  ,'TI..IE.W17 CP2'
  ,'WS..SA.W17 ERZH'
  ,'WS..SA.W17 FPAS'
  ,'TI..FF.W17 DYK'
  ,'LS..MT.S18 GDC_'
  ,'WS..PM.W17 ORG_'
  ,'WS..PM.W17 GSP_'
  ,'TI..FF.S18 AFK'
  ,'GL_HAW'
  ,'LS..OT.S18 MTL'
  ,'LS..MT.S18 MPT'
  ,'LS..MT.S18 MEST'
  ,'LS..MT.W17 BP'
  ,'TI..IE.S18 EDS'
  ,'TI..FF.W17 BFT'
  ,'TI..FF.S18 DAVE'
  ,'TI..FF.W17 MA2'
  ,'TI..IE.S18 MIPR'
  ,'LS..MT.S18 ELEK'
  ,'WS..WI.S18 WPSL'
  ,'WS..WI.S18 SL_B'
  ,'WS..WI.S18 WIL_'
  ,'HAW.XX.IO Dual'
  ,'HAW.XX.IO BR Su'
  ,'ELK'
  ,'DMI.DS.S18 ICJS'
  ,'DMI.MD.S18 TPET'
  ,'DMI.MD.S18 TPET'
  ,'DMI.MD.S18 ET1.'
  ,'DMI.MD.S18 ET2.'
  ,'DMI.MD.S18 NAT.'
  ,'WS.WI.W16 TISMA'
  ,'TI..IE.S18 MAT2'
  ,'WS..WI.S18 WIBN'
  ,'LS..MT.S18 BWL'
  ,'LS..MT.S18 KR'
  ,'LS..BT.S18 ARVP'
  ,'LS..BT.W17 DDF'
  ,'LS..BT.S18 PPC'
  ,'LS..BT.W17 PT'
  ,'LS..OT.S18 MPEV'
  ,'LS..BT.S18 BCP'
  ,'LS..OT.S18 PUTV'
  ,'LS..OT.S18 GKR'
  ,'LS..BT.S18 BC2'
  ,'LS..VT.W17 CHE2'
  ,'LS..OT.S18 FPB2'
  ,'LS..OT.S18 LMS'
  ,'LS..BT.S18 SML'
  ,'LS..MT.S18 TDK'
  ,'LS..VT.S18 CVT1'
  ,'LS..VT.S18 CVT2'
  ,'LS..VT.S18 CVTP'
  ,'LS..VT.S18 KOMP'
  ,'LS..VT.S18 WSUB'
  ,'LS..OT.S18 PROE'
  ,'LS..OT.S18 SEWA'
  ,'LS..MT.S18 EAS'
  ,'LS..MT.S18 PSI'
  ,'LS..MT.S18 STAM'
  ,'LS..MT.S18 STIK'
  ,'LS..OT.S18 VDSU'
  ,'LS..OT.W17 FPB2'
  ,'WS..WI.S18 A_BS'
  ,'WS..WI.S18 IM3_'
  ,'WS..WI.S18 IBA_'
  ,'TI..IE.S18 GE2'
  ,'TI..IE.S18 GE1'
  ,'TI..IE.S18 GN'
  ,'TI..IE.W17 NSA'
  ,'WS.PM.W17 AIEB'
  ,'WS..Wi.S18 WI1T'
  ,'WS..WI.S18 WI1T'
  ,'WS..WI.S18 BAFB'
  ,'WS..WI.S18 CON_'
  ,'WS..WI.S18 RW11'
  ,'WS..WI.S18 CR1_'
  ,'WS..WI.S18 CTR2'
  ,'WS..WI.S18 RWA_'
  ,'WS..WI.S18 RIM_'
  ,'TI..FF.S18 TM3'
  ,'TI..FF.S18 TM1'
  ,'TI..FF.W17 BFV'
  ,'WS..PM.W17 MIA1'
  ,'TI..FF.S18 EICN'
  ,'WS..PF.S18 MBA1'
  ,'WS..PM.S18 MBA7'
  ,'WS..PM.S18 MBA9'
  ,'WS..PM.S18 MBA1'
  ,'WS.SA.S18 BASAM'
  ,'WS..SA.S18 BASA'
  ,'WS.SA.S18 BASAM'
  ,'WS..SA.S18 FOBW'
  ,'LS..HW.S18 PHYS'
  ,'LS..HW.W17 WIEN'
  ,'WS..WI.S18 PMZ3'
  ,'WS..PM.W17 VHIN'
  ,'WS.SA.W17 WAP'
  ,'WS..SA.S18 PPP_'
  ,'WS..SA.S18 KIDK'
  ,'WS..SA.W17 FQ_B'
  ,'WS..WI.S18 GT_B'
  ,'WS..WI.S18 KT_B'
  ,'WS..WI.S18 MKT_'
  ,'WS..WI.S18MLT_M'
  ,'WS..WI.S18 TL_B'
  ,'WS..WI.S18 TIP'
  ,'WS..WI.S18 TT_B'
  ,'WS..SA.W17 EPG_'
  ,'WS..SA.W17_A PS'
  ,'WS..SA.W17 TPSG'
  ,'LS..UT.S18 PHY-'
  ,'LS..UT.S18 ENU'
  ,'LS..XX.W17 PVS'
  ,'DMI.IM.S18 BWIM'
  ,'DMI.IM.W17 BM3_'
  ,'DMI.IM.W17 BM3_'
  ,'DMI.IM.W17 BM3_'
  ,'DMI.MD.S18 M1_M'
  ,'DMI.MD.S18 M2_M'
  ,'DMI.MD.S18 M3MS'
  ,'TI..IE.W17 SC1'
  ,'TI..IN.S18 WPC'
  ,'TI..IN.S18 NBD'
  ,'TI..FF.S18 M1SP'
  ,'TI..FF.W17 AERO'
  ,'TI..FF.W17 HUBA'
  ,'TI..IE.W17 GET1'
  ,'TI..IE.W17 CN'
  ,'TI..MP.W17 SPAF'
  ,'TI..MP.S18 MATH'
  ,'TI..MP.S18 MCL'
  ,'TI..FF.S18 DTD'
  ,'TI..FF.S18 WKA'
  ,'TI..FF.S18 WKB'
  ,'TI..FF.S18 FWB'
  ,'LS..GW.S18 STGS'
  ,'TI..FF.W17 NFP'
  ,'TI..FF.S18 FWG'
  ,'TI..FF.W17 SIF'
  ,'TI..FF.S18 SEF'
  ,'TI..FF.S18 TZ'
  ,'LS..BT.S18 MA1'
  ,'LS..BT.S18 MA2'
  ,'LS..BT.S18 XMII'
  ,'Personalrat'
  ,'LS..BT.W17 MOS'
  ,'LS..HW.W17 XM2'
  ,'TI..IN.W17 SE1'
  ,'TI..FF.S18 FST'
  ,'TI..FF.W17 EDS'
  ,'TI..FF.W17 TM2'
  ,'WS..SA.S18 TPSS'
  ,'TI..MP.W17 AN'
  ,'TI..MP.W17 LAN'
  ,'TI..MP.W17 EAT'
  ,'TI..MP.W17 EA'
  ,'TI..MP.W17 PAN'
  ,'TI..IE.W17 RE'
  ,'TI..IN.S18 BSY'
  ,'TI..IN.W17 RMP'
  ,'TI..MP.W17 MSR'
  ,'TI..MP.W17 SVA'
  ,'TI..MP.W17 UMVK'
  ,'TI..ME.S18 K2 S'
  ,'TI..MP.W17 KON3'
  ,'TI..FF.S18 MA1'
  ,'TI..IE.W17 EDS'
  ,'TI..IE.W17 MR'
  ,'TI..IE.S18 SAS'
  ,'TI..ME.W17 BUS'
  ,'TI..MP.S18 CFDB'
  ,'TI..MP.S18 SL1'
  ,'TI..MP.W17 CFDM'
  ,'HAW.XX.IO DigMe'
  ,'TI..IE.W17 MC'
  ,'TI..MP.S18 ESY'
  ,'TI..MP.W17 ESY'
  ,'TI..IE.W17 SS1'
  ,'TI..ME.S18 EL'
  ,'TI..IE.S18 LEK'
  ,'WS..PM.W17 STGP'
  ,'TI..IE.W17 P1'
  ,'LS..GW.S18 WPL'
  ,'TI..FF.S18 DAVE'
  ,'TI..FF.S18 MTL'
  ,'TI..FF.S18 RT'
  ,'TI..FF.S18 LBK'
  ,'TI..FF.S18 LBL'
  ,'TI..FF.W17 VTF'
  ,'TI..FF.W17 FVT'
  ,'TI..MP.S18 LEW'
  ,'TI..MP.S18 MAT'
  ,'TI..MP.S18 MA1'
  ,'TI..MP.W17 1200'
  ,'TI..IE.S18 DSP'
  ,'TI..IE.S18 DSAS'
  ,'TI..IE.S18 DUA'
  ,'TI..IE.S18 PA M'
  ,'TI..IE.S18 MS1'
  ,'TI..ME.S18 MS2'
  ,'TI..IE.S18 PMS'
  ,'TI..IE.W17 DSAS'
  ,'TI..IE.W17 DUA'
  ,'TI..ME.W17 MS2'
  ,'TI..IE.W17 ELEK'
  ,'TI..ME.W17 EDL'
  ,'TI..MP.S18 PRM'
  ,'TI..MP.W17 RPL'
  ,'TI..MP.W17 QZ'
  ,'WS..WI.S18 MF_B'
  ,'WS..WI.W17 RW2_'
  ,'TI..MP.S18 METK'
  ,'TI..MP.S18 ELAT'
  ,'TI..MP.S18 ETA'
  ,'TI..MP.S18 ETB'
  ,'TI..FF.S18 MA2'
  ,'TI..ME.S18_A AD'
  ,'TI..ME.S18 TMC'
  ,'TI..IE.W17 DT'
  ,'TI..FF.W17 LEAR'
  ,'TI..FF.S18 DV'
  ,'TI..FF.S18 FWM'
  ,'TI..FF.S18 RETE'
  ,'TI..FF.S18 TM4'
  ,'TI..FF.W17 SEM'
  ,'TI..FF.S18 KMT'
  ,'TI..FF.S18 SL'
  ,'TI..FF.S18 FMA'
  ,'TI..FF.S18 THDY'
  ,'TI..MP.S18 MSRL'
  ,'LS..BT.S18 BIP'
  ,'LS..MT.S18 AIA'
  ,'LS..UT.S18 LALB'
  ,'LS..UT.S18 PHYP'
  ,'LS..VT.W17 MATH'
  ,'HAW.XX.IO ENGLI'
  ,'TI..FF.S18 BHE'
  ,'TI..FF.S18 KMM'
  ,'TI..FF.S18 LKK'
  ,'TI..FF.S18 PPAW'
  ,'TI..FF.S18 EKS'
  ,'TI..FF.S18 RTL'
  ,'TI..FF.W17 VEK'
  ,'TI..IE.S18 MPT'
  ,'TI..IE.W17 BUM'
  ,'TI..FF.W17 MAT2'
  ,'TI..FF.W17 STKO'
  ,'TI..FF.S18 FT'
  ,'DMI.DS.S18 QM'
  ,'TI..MP.S18 FTSM'
  ,'TI..MP.S18 MFT'
  ,'TI..MP.S18 I40'
  ,'TI..MP.S18 GPM'
  ,'TI..MP.S18 KTR'
  ,'TI..MP.W17 ILK'
  ,'TI..MP.W17 OM'
  ,'TI..MP.W17 PPS'
  ,'TI..MP.S18 FCS'
  ,'TI..MP.S18 MEES'
  ,'TI..MP.S18 TT1'
  ,'TI..MP.S18 TTD1'
  ,'TI..MP.S18 TTD2'
  ,'TI..MP.S18 WSU'
  ,'TI..FF.W17 NK1'
  ,'TI..FF.S18 FETE'
  ,'TI..FF.W17 FETE'
  ,'TI..FF.S18 INPR'
  ,'WS..WI.S18 ARW_'
  ,'WS..WI.S18 TCAI'
  ,'WS..WI.W17 IAT_'
  ,'WS..WI.S18 IBL_'
  ,'WS..WI.S18 IW_M'
  ,'LS..BT.S18 PF'
  ,'WS..WI.S18 ICM_'
  ,'WS..WI.W17 CF_M'
  ,'WS..WI.S18 FZ_B'
  ,'WS..WI.S18 IF_B'
  ,'TI..IE.W17 PKM'
  ,'TI..ME.W17 SUE'
  ,'DMI.MD.S18 PHY1'
  ,'TI..MP.W17 SWL'
  ,'TI..MP.S18 WSM'
  ,'TI..MP.W17 TM1B'
  ,'TI..MP.S18 MDV'
  ,'TI..MP.S18 ETP'
  ,'TI..MP.S18 SYST'
  ,'TI..MP.S18 LASE'
  ,'LS..MT.S18 KPBK'
  ,'LS..BT.W17 INF3'
  ,'LS..MT.W17 MATH'
  ,'LS..MT.S18 EMtA'
  ,'TI..IE.W17 EF'
  ,'TI..IE.W17 EMV'
  ,'TI..IE.W17 HMT'
  ,'TI..IE.W17 HE'
  ,'TI..IE.S18 PBS'
  ,'TI..IE.S18 ST'
  ,'TI..IE.W17 DVW'
  ,'TI..IE.W17 EVPL'
  ,'TI.IE.REE3.IPJ1'
  ,'TI..IN.W17 PT'
  ,'LS..MT.S18 FASI'
  ,'DMI.MD.S18 Phy_'
  ,'DMI.MD.S18 Phy2'
  ,'LS..VT.S18 STRO'
  ,'LS..VT.S18 TVT1'
  ,'LS..VT.S18 TVT2'
  ,'LS..OT.S18 WPG'
  ,'LS..MT.S18 HYGI'
  ,'DMI.IM.W17 IUAI'
  ,'DMI.IM.W17 WO'
  ,'HAW.IO.KDA'
  ,'HAW.XX.IO WIG'
  ,'LS..HW.S18 XEMA'
  ,'LS..HW.S18 XET1'
  ,'WS..SA.W17 GSAR'
  ,'WS..SA.W17 M1GU'
  ,'WS..SA.W17 BRB_'
  ,'WS..SA.S18 SB_B'
  ,'TI..FF.S18 FHR'
  ,'TI..FF.W17 VMV'
  ,'LS..VT.S18 ANS'
  ,'TI..MP.W17 NM'
  ,'TI..MP.W17 MA1'
  ,'TI..MP.W17 MEES'
  ,'LS..VT.W17 CWI'
  ,'LS..VT.W17 CST'
  ,'LS..VT.S18 TM2_'
  ,'LS..VT.S18 TM2'
  ,'LS..VT.S18 SM'
  ,'WS..SA.S18 TKJF'
  ,'WS..SA.S18 PHEK'
  ,'WS..SA.W17 IFMF'
  ,'LS..MT.S18 KPBK'
  ,'LS..MT.S18 PEFÃ'
  ,'LS..MT.S18 PMAN'
  ,'WS.PM.S18 WKR'
  ,'WS..PF.W17 DIP_'
  ,'WS..PF.W17 GSA_'
  ,'TI..IE.S18 PSP'
  ,'TI..IE.W17 EC'
  ,'TI..ME.S18 AT2'
  ,'WS..SA.W17 FWAU'
  ,'WS..SA.S18 MH_M'
  ,'WS..SA.S18 LEAN'
  ,'WS..SA.W17 KJS_'
  ,'WS..SA.W17 QMS_'
  ,'LS..BT.S18 WSTO'
  ,'LS..VT.S18 WST'
  ,'LS..MT.S18 LOM'
  ,'TI..MP.S18 MSR'
  ,'TI..MP.W17 EE'
  ,'WS..SA.W17 FSAI'
  ,'LS..BT.W17 ARV'
  ,'LS..BT.W17 BME'
  ,'TI..FF.S18 FUS'
  ,'TI..FF.S18 FFL'
  ,'TI..FF.S18 FEIL'
  ,'TI..FF.S18 HFL'
  ,'TI..FF.S18 TEME'
  ,'TI..FF.S18 TECM'
  ,'LS..BT.S18 IA1P'
  ,'LS..BT.S18 SLMA'
  ,'LS..BT.S18 IA1'
  ,'LS..BT.S18 OCP'
  ,'WS..PM.W17 EP'
  ,'WS..PM.S18 FUS_'
  ,'WS..PM.W17 EAB'
  ,'WS.SA.W17 SFAMB'
  ,'WS.SA.W17 EPKJ'
  ,'LS..GW.S18 Ã–KO'
  ,'WS..WI.S18 WIBN'
  ,'WS..WI.S18 WIBN'
  ,'WS..SA.S18 WSAR'
  ,'Pro_HAW_WS'
  ,'WS..PF.S18 BWSS'
  ,'TSE_QUERST'
  ,'TI..MP.S18 ETA'
  ,'TI..MP.S18 ELEA'
  ,'HAW.XX.IO TSEQF'
  ,'DMI.IM.S18 KML1'
  ,'DMI.IM.S18 KML'
  ,'WS..SA.S18 QM4_'
  ,'TI..IN.W17 RNA'
  ,'TI..IN.W17 GSP'
  ,'TI..IN.W17 ESE'
  ,'WS..WI.W17 Ã–E'
  ,'WS..WI.S18 GVWL'
  ,'WS..WI.S18 VWLM'
  ,'WS..WI.S18 VWLL'
  ,'WS..WI.W17 NÃ–_'
  ,'TI..ME.S18 FTL'
  ,'TI..MP.S18 FTT'
  ,'TI..MP.S18 EIN'
  ,'TI..MP.S18 FTL'
  ,'TI..MP.S18 WML'
  ,'TI..MP.W17 UF'
  ,'TI..FF.S18 DG1A'
  ,'TI..FF.S18 DGB'
  ,'TI..FF.S18 EUD'
  ,'TI..FF.S18 SYE'
  ,'TI..FF.S18 IGP'
  ,'LS..UT.S18 UMT'
  ,'DMI.MD.S18 SE_1'
  ,'DMI.MD.S18 MMI'
  ,'DMI.MD.S18 RDB_'
  ,'WS..WI.S18 ERPP'
  ,'WS..WI.S18 WI1P'
  ,'TI..IE.W17 SAM'
  ,'TI..ME.W17 MS1'
  ,'TI..IE.W17 ICG'
  ,'LS..GW.S18 AWS'
  ,'TI..IE.W17 NMS'
  ,'TI..IE.W17 GE2'
  ,'DMI.MD.S18 NUA'
  ,'DMI.MD.S18 KRY_'
  ,'DMI.MD.S18 STOR'
  ,'DMI.MD.S18 InfB'
  ,'DMI.MD.S18 Game'
  ,'DMI.MD.S18 GA1_'
  ,'DMI.IM.S18 IKM'
  ,'WS..WI.S18 PRKT'
  ,'TI..MP.W17 AT'
  ,'TI..MP.S18 FT1'
  ,'TI..MP.W17 CE'
  ,'TI..MP.S18 PPSL'
  ,'TI..MP.W17 ILL'
  ,'TI..MP.W17 UFUP'
  ,'TI..MP.S18 POM'
  ,'TI..FF.W17 MANM'
  ,'WS..PM.W17 BWLÃ'
  ,'WS..WI.W17 PCV_'
  ,'WS..WI.W17 BWL1'
  ,'WS..WI.W17 BWL3'
  ,'WS..WI.W17 UE_B'
  ,'HAW.XX.IO GGD'
  ,'HAW.XX.IO GBD'
  ,'TI..MP.W17 WKCA'
  ,'WS..PF.S18 RUF'
  ,'WS..PF.W17 RF'
  ,'WS..PF.W17 WKB_'
  ,'TI..IN.S18 INF'
  ,'WS..SA.W17 MPF_'
  ,'WS..SA.S18 TPSK'
  ,'WS..WI.S18 STT_'
  ,'WS..WI.S18 QMMT'
  ,'WS..WI.W17 BIAA'
  ,'WS..SA.W17 GSPS'
  ,'WS..PM.S18 IT2_'
  ,'WS..SA.W17 Ã–E_'
  ,'WS..SA.S18 EKSA'
  ,'WS..SA.S18 PROF'
  ,'WS..SA.S18 VWSM'
  ,'LS..HW.S18 XPUV'
  ,'LS..HW.S18 XTST'
  ,'LS..HW.S18 XTSS'
  ,'LS..VT.W17 XENT'
  ,'WS..WI.S18 BM_M'
  ,'WS..WI.S18 DP_B'
  ,'WS..WI.S18 PRO_'
  ,'WS..WI.S18 PJM_'
  ,'WS..WI.S18 VM_B'
  ,'WS..WI.S18 VMM'
  ,'WS..WI.S18 WAZ_'
  ,'WS..WI.S18 ZF_B'
  ,'LS..BT.S18 REGT'
  ,'LS..MT.S18 ACS'
  ,'WS..WI.S18 WI1T'
  ,'WS..WI.S18 WI1T'
  ,'LS..VT.S18 TLV'
  ,'LS..VT.S18 VTPM'
  ,'LS..VT.S18 ANTE'
  ,'LS..VT.S18 AAP'
  ,'LS..VT.S18 KON'
  ,'TI..MP.S18 WKCB'
  ,'LS..UT.S18 REE'
  ,'LS..UT.S18 MST'
  ,'LS..OT.S18 BIOF'
  ,'LS..OT.S18 TLV'
  ,'LS..MT.S18 MAT3'
  ,'LS..MT.S18 MAT4'
  ,'TI..IE.S18 VPJ'
  ,'LS..GW.S18 GVS'
  ,'TI..MP.S18 RBT'
  ,'TI..IE.S18 DSP'
  ,'DMI.MD.S18 TONL'
  ,'DMI.MD.S18 ATP2'
  ,'DMI.MD.S18 BST_'
  ,'DMI.MD.S18 TON1'
  ,'DMI.MD.S18 TON_'
  ,'DMI.MD.S18 TT2'
  ,'LS..GW.S18 GEPH'
  ,'LS..GW.S18 EPI1'
  ,'LS..GW.S18 EPI2'
  ,'LS..GW.S18 SP'
  ,'LS..GW.S18 EIH1'
  ,'LS..GW.S18 EIH2'
  ,'LS..GW.S18 STA'
  ,'LS..GW.S18 GDE'
  ,'TI..FF.S18 DG2L'
  ,'TI..FF.S18 FZD'
  ,'TI..FF.S18 FHZ'
  ,'TI..FF.S18 STR'
  ,'TI..FF.S18 SUA'
  ,'TI..FF.S18 TM3'
  ,'LS..GW.S18 ENGH'
  ,'WS..SA.S18 KBBA'
  ,'WS..SA.S18 KBBB'
  ,'WS..SA.S18 WFLF'
  ,'WS..SA.S18 BK1_'
  ,'WS..SA.S18 PP2_'
  ,'WS..SA.S18 HKT_'
  ,'WS..SA.S18 KRIM'
  ,'WS..SA.S18 BQM_'
  ,'WS..SA.S18 Ã–SA'
  ,'WS..SA.S18 SOZM'
  ,'LS..UT.S18 TD_U'
  ,'LS..UT.S18 SWU'
  ,'LS..UT.S18 EWI'
  ,'TI..FF.S18 WK2'
  ,'TI..FF.S18 WPL'
  ,'LS..VT.S18 MVT2'
  ,'LS..VT.S18 MVT1'
  ,'LS..MT.S18 TM1'
  ,'WS..SA.S18 JUGH'
  ,'WS..SA.S18 JUGH'
  ,'WS..WI.S18 PM_B'
  ,'WS..WI.S18 IM_B'
  ,'LS..GW.S18 EVE_'
  ,'LS..OT.S18 PHN'
  ,'WS..PM.W17 PROP'
  ,'WS..PF.S18 M/TE'
  ,'WS..PF.S18 M15C'
  ,'WS..PM.S18 M7U2'
  ,'DMI.MD.S18 OE_1'
  ,'DMI.MD.S18 Elek'
  ,'DMI.MD.S18 IT-S'
  ,'DMI.MD.S18 TI'
  ,'LS..HW.S18 XMK'
  ,'LS..HW.S18 PHYP'
  ,'WS..SA.S18 TPSG'
  ,'WS..WI.S18 MBWL'
  ,'WS..WI.S18 BHR_'
  ,'WS..WI.S18 GWPR'
  ,'WS..WI.S18 HGR_'
  ,'WS..WI.S18 RIL_'
  ,'LS..BT.S18 MOBI'
  ,'WS.SA.S18-S20 S'
  ,'LS..GW.S18 HURE'
  ,'LS..OT.S18 MTLT'
  ,'LS..OT.S18 BC'
  ,'LS..OT.S18 LC'
  ,'WS.SA.S18 PKF'
  ,'DMI.MD.S18 MGD3'
  ,'LS..MT.S18 QM'
  ,'WS..PM.S18 DLGV'
  ,'WS..SA.S18-S20'
  ,'WS.SA.S18-S20 E'
  ,'WS..SA.S18 BW_B'
  ,'WS..SA.S18 PROF'
  ,'WS..SA.S18 TPH_'
  ,'LS..OT.S18 EKO'
  ,'LS..OT.S18 DIA'
  ,'LS..OT.S18 EPO'
  ,'LS..OT.S18 LELE'
  ,'LS..OT.S18 SOS'
  ,'TI..FF.S18 AML'
  ,'TI..FF.S18 FP'
  ,'TI..FF.S18 LFP'
  ,'TI..FF.S18 STLE'
  ,'WS..SA.S18 BAWV'
  ,'LS..UT.S18 PRSE'
  ,'LS..UT.S18 UVT'
  ,'LS..UT.S18 AWAL'
  ,'LS..UT.S18 UMAN'
  ,'LS..BT.S18 FEBR'
  ,'LS..BT.S18 RGB'
  ,'LS..BT.S18 SST'
  ,'LS..BT.S18 WSA'
  ,'TI..ME.S18 AT1'
  ,'LS..OT.S18 PUTP'
  ,'LS..OT.S18 MPEP'
  ,'WS..SA.S18 EVAL'
  ,'KOM_ASD'
  ,'DMI.MD.S18 VID'
  ,'LS..OT.S18 MGM'
  ,'LS..GW.S18 MPH'
  ,'LS..GW.S18 GFP'
  ,'PRZ_HAW'
  ,'LS..GW.S18 CDPS'
  ,'LS..GW.S18 PGE'
  ,'LS..GW.S18 FOM'
  ,'LS..GW.S18 GHP'
  ,'LS..OT.S18 PM'
  ,'WS..SA.S18 SCHS'
  ,'LS..OT.S18 KPS'
  ,'WS.SA.S18 TPSFE'
  ,'WS..WI.S18 GBWL'
  ,'TI..IE.S18 UEA'
  ,'WS..SA.S18 TPSK'
  ,'LS..OT.S18 QM'
  ,'DMI.MD.S18 DSIG'
  ,'DMI.MD.S18 ET1_'
  ,'DMI.MD.S18 NAT_'
  ,'DMI.MD.S18 NT1'
  ,'WS..WI.S18 QMH_'
  ,'TI..MP.S18 CAD'
  ,'TI..MP.S18 SPP'
  ,'DMI.MD.S18 MAN_'
  ,'LS..MT.S18 BAT'
  ,'WS..WI.S18 ONEC'
  ,'WS..PM.S18 POM_'
  ,'WS..WI.S18 KLR_'
  ,'WS..WI.S18 SAZ_'
  ,'WS..WI.S18 LUT_'
  ,'WS..WI.S18 GLM_'
  ,'WS..WI.S18 LTP_'
  ,'WS..WI.S18 ML_M'
  ,'WS..WI.S18 PO_M'
  ,'WS..WI.S18 MLO_'
  ,'WS..WI.S18 PSCM'
  ,'WS..WI.S18 OPM_'
  ,'WS..WI.S18 OPM_'
  ,'WS..WI.S18 WA_B'
  ,'WS..WI.S18 WA_B'
  ,'LS..GW.S18 PQM'
  ,'LS..UT.S18 IA1'
  ,'LS..UT.S18 IA1P'
  ,'LS..UT.S18 CHEM'
  ,'LS..UT.S18 LPRO'
  ,'LS..UT.S18  PRA'
  ,'TI..IN.S18 DTB'
  ,'TI..IN.S18 BW2'
  ,'WS..WI.S18 FDIT'
  ,'WS..WI.S18 IOC_'
  ,'WS..WI.S18 IHRM'
  ,'WS..WI.S18 IM15'
  ,'WS..WI.S18 GAW_'
  ,'WS..WI.S18 PE_B'
  ,'WS.WI.S18 MTEQ'
  ,'WS..WI.S18 GT1_'
  ,'WS..WI.S18 M_BS'
  ,'WS.WI.S18 TLLL'
  ,'WS..WI.S18 TT'
  ,'DMI.IM.S18 MJ4'
  ,'WS..WI.S18 PSL_'
  ,'WS..WI.S18 LGKB'
  ,'WS..WI.S18 ABWL'
  ,'WS..SA.S18 GKA_'
  ,'DMI.DS.S18 TEVE'
  ,'WS..PM.S18 MKK'
  ,'LS..MT.S18 MST'
  ,'DMI.DS.S18 TP2'
  ,'WS..WI.S18 MM_B'
  ,'WS..WI.S18 KP1_'
  ,'WS..WI.S18 KV1_'
  ,'WS..WI.S18 WBB_'
  ,'WS..WI.S18 SBTE'
  ,'LS..MT.S18 RGLT'
  ,'LS..MT.S18 EKT1'
  ,'LS..OT.S18 HRML'
  ,'LS..OT.S18 BWLA'
  ,'LS..HW.S18 TM1'
  ,'LS..HW.S18 TM2'
  ,'LS..HW.S18 KON2'
  ,'LS..HW.S18 KON1'
  ,'WS..WI.S18 IC'
  ,'WS..WI.S18 IF'
  ,'TI..FF.S18 BWL'
  ,'TI..IE.S18 BWL'
  ,'WS..SA.S18 QM1_'
  ,'LS..MT.S18 FEM'
  ,'LS..MT.S18 PHY1'
  ,'LS..MT.S18 BIOM'
  ,'LS..MT.S18 PH2'
  ,'LS..MT.S18 TECM'
  ,'LS..MT.S18 TECM'
  ,'DMI.MD.S18 MUT'
  ,'DMI.MD.S18 MR_0'
  ,'DMI.MD.S18 MR_0'
  ,'DMI.MD.S18 WA'
  ,'LS..OT.S18 MDB'
  ,'LS..MT.S18 ET1'
  ,'LS..BT.S18 OCB1'
  ,'LS..BT.S18 PHAT'
  ,'LS..BT.S18 SPEK'
  ,'WS..WI.S18 BRHR'
  ,'WS..WI.S18 GRWP'
  ,'WS..WI.S18 RADM'
  ,'WS..WI.S18 SUHR'
  ,'LS..OT.S18 LMBP'
  ,'LS..MT.S18 HBIO'
  ,'LS..OT.S18 HBIO'
  ,'LS..OT.S18 PATH'
  ,'DMI.MD.S18 ANPR'
  ,'DMI.MD.S18 AVPR'
  ,'TI..FF.S18 DV P'
  ,'TI..FF.S18 DG1'
  ,'TI..FF.S18 QM P'
  ,'HAW.XX.IO EMIL_'
  ,'LS..HW.S18 XWK'
  ,'LS..HW.S18 MAWI'
  ,'LS..HW.S18 XC1'
  ,'LS..HW.S18 MAWI'
  ,'LS..VT.S18 WTL'
  ,'DMI.IM.S18 BWIT'
  ,'LS..UT.S18 AWAL'
  ,'LS..VT.S18 VT1P'
  ,'LS..VT.S18 VT2P'
  ,'WS..WI.S18 GVWL'
  ,'WS..WI.S18 IVWL'
  ,'WS..WI.S18 IVWL'
  ,'LS..GW.S18 GOM'
  ,'LS..GW.S18 GSP'
  ,'LS..UT.S18 ZMB'
  ,'LS..GW.S18 BWLG'
  ,'DMI.MD.S18 GMGD'
  ,'LS..MT.S18 PMAN'
  ,'LS..MT.S18 GA-M'
  ,'DMI.MD.S18 LIT_'
  ,'DMI.MD.S18 LIT_'
  ,'LS..GW.S18 GQ'
  ,'LS..MT.S18 SPRO'
  ,'HAW.XX.IO HOOU-'
  ,'LS..UT.S18 ETI'
  ,'LS..OT.S18 EGO'
  ,'LS..UT.S18 BIO'
  ,'LS..UT.S18 BCU'
  ,'LS..UT.S18 URE'
  ,'LS..HW.S18 ST'
  ,'LS..VT.S18 MAT2'
  ,'LS..VT.S18 MAT3'
  ,'WS..SA.S18 EK_B'
  ,'WS.SA.S18 TPSHA'
  ,'WS..SA.S18 BGBT'
  ,'TI..IE.S18 ETK'
  ,'TI..IE.S18 EESW'
  ,'WS..SA.S18 HMIE'
  ,'WS..SA.S18  TPS'
  ,'WS..SA.S18EIM_B'
  ,'LS..HW.S18 XEVN'
  ,'LS..GW.S18 AGSB'
  ,'DMI.IM.S18 BM2'
  ,'LS..UT.S18 FCA'
  ,'DMI.IM.S18 MÃ–M'
  ,'WS..PM.S18 MKK4'
  ,'LS..OT.S18 VWRE'
  ,'WS..PF.S18 PFD_'
  ,'LS..VT.S18 Phy1'
  ,'LS..GW.S18 EGPH'
  ,'LS..MT.S18 TEME'
  ,'LS..XX.S18 VPAC'
  ,'LS..XX.S18 WSTO'
  ,'LS..BT.S18 RECH'
  ,'LS..MT.S18 RGW'
  ,'LS..OT.S18 COBE'
  ,'WS..SA.S18 P26_'
  ,'WS..SA.S18 M24F'
  ,'WS..SA.S18 TS_B'
  ,'WS..SA.S18 M19T'
  ,'WS..SA.S18 M27B'
  ,'WS..PM.S18 RFMM'
  ,'LS..OT.S18 LWV'
  ,'WS..PF.S18 PRSL'
  ,'LS..OT.S18 HBT'
  ,'WS..WI.S18 IAT_'
  ,'LS..GW.S18 FPGP'
  ,'WS..WI.S18 RWAT'
  ,'DMI.DS.S18 SGH'
  ,'LS..OT.S18 QRM'
  ,'LS..HW.S18 XTIP'
  ,'LS..HW.S18 XTI1'
  ,'LS..HW.S18 XTI2'
  ,'LS..HW.S18 XTI3'
  ,'LS..GW.S18 BUG'
  ,'DMI.MD.S18 PPM3'
  ,'TI..MP.S18 TM1'
  ,'TI..MP.S18 TM2'
  ,'TI..MP.S18 FT_V'
  ,'TI..MP.S18 TET1'
  ,'WS..SA.S18 BGFR'
  ,'AK LabVIEW'
  ,'HAW.XX.IO Aufsc'
  ,'HAW.DP.IO SKG'
  );


  foreach ($SA as $s)
  {
    if (in_array ($s,$ERA ))
    {
      echo "<br>" . $s;
    }
    else
    {
      echo "<br>Nope!";
    }
  }


}



  function xml2array ( $xmlObject, $out = array () )
  {
    foreach ( (array) $xmlObject as $index => $node )
      $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;

    return $out;
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

            if ( ! in_array( $host1[2], $this -> conf['ok_host'] ) )  {  die("<div style='text-align:center;'><h1>ACCESS ERROR<h1><h3>Unzul&auml;ssiger Zugriff!</h3><a href=\"javascript:window.back()\">Zur&uuml;ck</a></div>"); }
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
    function current_page_url()
    {
        $page_url   = 'http';
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            $page_url .= 's';
        }
        return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }



    /*
    function showSA( $I )
    {
        $docID = $I[ 'W' ] [ 'document_id' ] ;


        $tpl_vars[ 'errors_info'     ][] = '';
        $tpl_vars[ 'doc_type'        ] = $this -> SQL -> getAllDocTypes();
        $tpl_vars[ 'MEDIA_STATE'     ] = $this -> SQL -> getAllMedStates();
        $tpl_vars[ 'FACHBIB'         ] = $_SESSION[ 'FACHBIB'   ];
        $tpl_vars[ 'department'      ] = $_SESSION[ 'DEP_2_BIB'   ];
        $tpl_vars[ 'operator'          ] = $_SESSION[ 'operator'    ];

        $tpl_vars[ 'ACTION_INFO'    ] =  $this -> CFG -> C -> CONST_ACTION_INFO;

        $conf = $this -> CFG -> getConf();
        $tpl_vars[ 'work'            ] = $I[ 'W' ];
        $tpl_vars[ 'user'            ] = $I[ 'U' ];

        $_SESSION['operator']['off'] = true;
        $ci = $this -> SQL -> getCollection( $I[ 'W' ]['collection_id'] );
        $tpl_vars[ 'ci' ] = array_pop($ci );
        $_SESSION['operator']['off'] = false;

        $tpl_vars[ 'collection'      ] = $ci;
        $tpl_vars[ 'di'              ] = $this -> SQL -> getDocumentInfos ( $docID );
        $tpl_vars[ 'di'              ][ 'doc_type' ] = $tpl_vars[ 'doc_type'  ][$tpl_vars[ 'di' ]['doc_type_id']  ]['doc_type'];

        $book['doc_type_id'] =  $tpl_vars[ 'di' ][ 'doc_type_id'];

        $dt = $this -> CFG -> C -> getDocType( $book );

        $tpl_vars[ 'di'              ] = array_merge($tpl_vars[ 'di'              ] , $dt) ;

        $conf = $this -> CFG -> getConf();
        $tpl_vars[ 'work'    ]['catURLlnk']    = $conf['catURLlnk'];


        $this -> RENDERER -> do_template( 'SA.tpl', $tpl_vars, false );
    }

    */

}
?>