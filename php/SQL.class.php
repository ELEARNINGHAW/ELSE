<?php
class SQL
{  
var $DB;
var $conf;
var $CFG;
var $user;
var $collection;

function __construct( $CFG )
{
  $this -> CFG  = $CFG;
  $this -> conf = $CFG -> getConf ();

  $this -> DB = new mysqli( $this -> conf[ 'db' ][ 'db_host' ] ,
  $this -> conf[ 'db' ][ 'db_user' ] ,
  $this -> conf[ 'db' ][ 'db_pass' ] ,
  $this -> conf[ 'db' ][ 'db_name' ] );

  if ( mysqli_connect_errno () )
  {
    printf ( "Verbindung fehlgeschlagen: %s\n" , mysqli_connect_error () );
    exit();
  }
}

# ---------------------------------------------------------------------------------------------
function initMediaMetaData( $book )
{
#  deb($book);
$SQL = '
INSERT INTO `document`
SET
doc_type_id        = "' . $this -> es ( $book -> get_doc_type_id       ( ) ) . '",
collection_id      = "' . $this -> es ( $book -> get_collection_id     ( ) ) . '",
title              = "' . $this -> es ( $book -> get_title             ( ) ) . '",
author             = "' . $this -> es ( $book -> get_author            ( ) ) . '",
edition            = "' . $this -> es ( $book -> get_edition           ( ) ) . '",
publisher          = "' . $this -> es ( $book -> get_publisher         ( ) ) . '",
ISBN               = "' . $this -> es ( $book -> get_ISBN              ( ) ) . '",
signature          = "' . $this -> es ( $book -> get_signature         ( ) ) . '",
ppn                = "' . $this -> es ( $book -> get_ppn               ( ) ) . '",
physicaldesc       = "' . $this -> es ( $book -> get_physicaldesc      ( ) ) . '",
state_id           = "' . $this -> es ( $book -> get_state_id          ( ) ) . '",
notes_to_staff     = "' . $this -> es ( $book -> get_notes_to_staff    ( ) ) . '",
notes_to_studies   = "' . $this -> es ( $book -> get_notes_to_studies  ( ) ) . '",
shelf_remain       = "' . $this -> es ( $book -> get_shelf_remain      ( ) ) . '",

created            = NOW() ,
last_modified      = NOW() ,
last_state_change  = NOW()';

#deb($SQL,1);

$res = mysqli_query ( $this -> DB , $SQL );

$ret = $this->getDocumentID ( $book );

return $ret;
}


# ---------------------------------------------------------------------------------------------
  function updateMediaMetaData( $medium )
  {

    $SQL = " UPDATE document SET ";
    if ( $medium -> get_title              ( ) != '' )  {  $SQL .= " title            = \"" . $this -> es (  $medium -> get_title              ( ) ) . "\" ,";  }
    if ( $medium -> get_signature          ( ) != '' )  {  $SQL .= " signature        = \"" . $this -> es (  $medium -> get_signature          ( ) ) . "\" ,";  }
    if ( $medium -> get_author             ( ) != '' )  {  $SQL .= " author           = \"" . $this -> es (  $medium -> get_author             ( ) ) . "\" ,";  }
    if ( $medium -> get_ISBN               ( ) != '' )  {  $SQL .= " ISBN             = \"" . $this -> es (  $medium -> get_ISBN               ( ) ) . "\" ,";  }
    if ( $medium -> get_notes_to_studies   ( ) != '' )  {  $SQL .= " notes_to_studies = \"" . $this -> es (  $medium -> get_notes_to_studies   ( ) ) . "\" ,";  }
    if ( $medium -> get_notes_to_staff     ( ) != '' )  {  $SQL .= " notes_to_staff   = \"" . $this -> es (  $medium -> get_notes_to_staff     ( ) ) . "\" ,";  }
                                                           $SQL .= " last_modified    = NOW()  ";
                                                           $SQL .= " WHERE id         = \"" . $this -> es (  $medium -> get_id                 ( ) ) . "\"  ";


    $res = mysqli_query ( $this -> DB , $SQL );
    return $res;
  }

  
  
# ---------------------------------------------------------------------------------------------
function getDokumentList( $colID , $filter = null  )
{
$ret = NULL;

  $filterSem   = '';
  $filterBib   = '';
  $filterState = '';
  $filterType  = '';

#deb($filter,1);

if( $filter )
{
  $filterSem = $filter->get_sem();
  $filterBib = $filter->get_bib();
  $filterState = $filter->get_state();
  $filterType = $filter->get_type();
}

$SQL =
"SELECT * FROM `document`
WHERE `collection_id` = \"" . $colID . "\"";
if (  $filterState != ''  AND  $filterState != 0   ) { $SQL .= " AND `state_id`     = "  . $this -> es ( $filterState  ); }
#if (  $filter_type  != ''                            ) { $SQL .= " AND `doc_type_id` = "  . $this -> es ( $filter_type   ); }
if (  $filterType   != ''  AND  $filterType != 'X'   ) { $SQL .= " AND `doc_type_id`  = "  . $this -> es ( $filterType   ); }

#deb($SQL);
$res = mysqli_query ( $this -> DB , $SQL );


if ( $res )
{ while ( $row = mysqli_fetch_assoc ( $res ) )
  {
    #  deb($row );
    if( $row[ 'state_id' ] != 6 OR  $filter_state = 6 )  ## Gelöschte Medien werden NICHT angezeigt, außer in der Liste der gelöschten Medien
    {
      $m = new Medium();
      $m -> set_id                   ( $row[ 'id'                  ] );
      $m -> set_state_id             ( $row[ 'state_id'            ] );
      $m -> set_title                ( $row[ 'title'               ] );
      $m -> set_author               ( $row[ 'author'              ] );
      $m -> set_edition              ( $row[ 'edition'             ] );
      $m -> set_publisher            ( $row[ 'publisher'           ] );
      $m -> set_ISBN                 ( $row[ 'ISBN'                ] );
      $m -> set_signature            ( $row[ 'signature'           ] );
      $m -> set_ppn                  ( $row[ 'ppn'                 ] );
      $m -> set_doc_type_id          ( $row[ 'doc_type_id'         ] );
      $m -> set_shelf_remain         ( $row[ 'shelf_remain'        ] );
      $m -> set_physicaldesc         ( $row[ 'physicaldesc'        ] );
      $m -> set_collection_id        ( $row[ 'collection_id'       ] );
      $m -> set_notes_to_studies     ( $row[ 'notes_to_studies'    ] );
      $m -> set_notes_to_staff       ( $row[ 'notes_to_staff'      ] );
      $m -> set_created              ( $row[ 'created'             ] );
      $m -> set_last_modified        ( $row[ 'last_modified'       ] );
      $m -> set_last_state_change    ( $row[ 'last_state_change'   ] );
    }

    $ret[ $row[ 'id' ] ] = $m;
  }
}
return $ret;
}




# ---------------------------------------------------------------------------------------------
  function getCollectionMetaData( $collection_id )
  {
    $collection = new collection();

    if ( $collection_id != '' ) {
      $SQL = "
  SELECT *
  FROM collection
  WHERE `title_short` = \"" . $this->es ( $collection_id ) . "\"";

      $res = mysqli_query ( $this->DB , $SQL );

      $col = mysqli_fetch_assoc ( $res );
      $col[ 'collection_id' ] = $collection_id;

        if ( isset ( $col[ 'id' ] ) )
      {
        $collection->set_id                    ( $col[ 'id'                    ] ); # (> ELSE-ADMIN
        $collection->set_user_id               ( $col[ 'user_id'               ] ); # (> aaa527
        $collection->set_state_id              ( $col[ 'state_id'              ] ); # (> 3
        $collection->set_title                 ( $col[ 'title'                 ] ); # (> ELSE-Admin
        $collection->set_title_short           ( $col[ 'title_short'           ] ); # (> ELSE-ADMIN
        $collection->set_sem                   ( $col[ 'sem'                   ] ); # (> W15
        $collection->set_created               ( $col[ 'created'               ] ); # (> 2015-09-15 17:03:57
        $collection->set_last_modified         ( $col[ 'last_modified'         ] ); # (> 2018-09-13 09:17:26
        $collection->set_last_state_change     ( $col[ 'last_state_change'     ] ); # (> 2015-09-15 17:03:57
        $collection->set_expiry_date           ( $col[ 'expiry_date'           ] ); # (> 2016-03-01
        $collection->set_notes_to_studies_col  ( $col[ 'notes_to_studies_col'  ] ); # (>
        $collection->set_course_id             ( $col[ 'course_id'             ] ); # (> 13230
        $collection->set_modul_id              ( $col[ 'modul_id'              ] ); # (> 16
        $collection->set_sortorder             ( $col[ 'sortorder'             ] ); # (>
        $collection->set_collection_id         ( $col[ 'collection_id'         ] ); # (> ELSE-ADMIN
        $collection->set_dc_collection_id      (base64_encode ( rawurlencode ( $col[ 'collection_id'    ] ) ) ); # (> ELSE-ADMIN
        $collection->set_bib_id                ( $col[ 'bib_id'                ] ); # (> LS
        $collection->set_Bib                   ( $this -> get_bib_info        ( $col[ 'bib_id'      ] ) );
        $collection->set_MedState              ( $this -> get_med_state        ( $col[ 'id'          ] ) );
        $collection->set_Owner                 ( $this -> getUserMetaData     ( $col[ 'user_id'     ] ) );
      }
    } else {
      $collection = null;
    }

    return $collection;
  }



# ---------------------------------------------------------------------------------------------

function getCollection( $colID = null , $filter = false ,  $short = null )
{
  $bibFilter       = '';
  $semesterFilter  = '';
  $collection      = '';
  $ret             = false;

  ## ---------------------------------------------------------------------------------------------------------------------------------------------------
  ## EIN oder  ALLE Semesterapparate. Gefiltert nach BIB und/oder SEMESTER

  $filterSem   =  '';
  $filterBib   =  '';
  $filterState =  '';
  $filterType  =  '';

  if($filter)
  {
    $filterSem   =  $filter -> get_sem();
    $filterBib   =  $filter -> get_bib();
    $filterState =  $filter -> get_state();
    #$filterType  =  $filter -> get_type();

    if ( $filterSem  != ''  AND   $filterSem  != 'X'                           )  {  $semesterFilter  = " AND c.sem           = '" .   $filterSem  . "' "; }  # SEMESTER filter
    if ( $filterBib  != ''  AND   $filterBib  != 'X' AND   $filterBib != '0'   )  {  $bibFilter       = " AND c.bib_id        = '" .   $filterBib  . "' "; }  # Bibliotheks filter
    else                                                                          {  $bibFilter       = " AND c.bib_id       != '' "                     ; }
  }

  if ( $colID )   { $collection .= " AND c.id = \"" . $this->es ( $colID ) . "\" ";  }

  $SQL  = "SELECT  c.id  as  c_id,    c.sortorder as c_sortorder ";
  $SQL .= " FROM `collection` c , `user` u";
  $SQL .= " WHERE  u.hawaccount = c.user_id " .  $collection  . " " . $bibFilter . " " . $semesterFilter;  # ."  ".$user;
  $SQL .= " ORDER BY c.id ";

 # deb($SQL,1);

  $res = mysqli_query ( $this->DB , $SQL );

  ## ---------------------------------------------------------------------------------------------------------------------------------------------------
  ##  ALLE Medieninfo zu dem entsprechenden SA werden ermittelt
  ## ---------------------------------------------------------------------------------------------------------------------------------------------------

  if ( $res )
    while ( $row = mysqli_fetch_assoc ( $res ) )
    {
      $ret[ $row[ 'c_id' ] ] =  $this -> getCollectionMetaData( $row[ 'c_id' ] );                                       ## Metadaten des Semesterapparats

      $dl                    =  $this -> getDokumentList ( $row[ 'c_id' ] , $filter );             ## Alle/gefilterte Medien des SA ( $doc_ID, $doc_type_id = null , $doc_state_id = null  )
      if ( $dl )
      { # Medien nach 'sortorder' neu anordnen
        $withoutSortOrder = array();
        $withSortOrder    = array();

        foreach ( $dl as $d )
        {
          $d -> calcDocType ( );  ## TODO check ob überhaupt notwendig?
          $withoutSortOrder[ $d -> get_id()  ] = $d ;   ## --- Attribute hinzufügen 'doc_type', 'item', 'doc_type_id', 'state_id'
        }

        $sortorder = explode ( ',' , $row[ 'c_sortorder' ] ); # Array von PPN als Identifikatoren der Medien

        if ( $sortorder[ 0 ] != '' AND $filterState == '' )     ##  Falls es eine Sortorder gibt, wird nach dieser Sortiert
        { foreach ( $sortorder as $ppn )
          { foreach ( $withoutSortOrder as $wso )
            {
              if ( $wso -> get_ppn() == $ppn )
              {
                $withSortOrder[] = $wso;
                unset( $withoutSortOrder[ $wso -> get_id() ] );
              }
            }
          }

          foreach($withoutSortOrder as $wso)    ## Falls in der Sortoder-Liste weniger Medien stehen, als Medien im SA sind.
          {
            $withSortOrder[] = $wso;
          }
        } else
        { $withSortOrder = $withoutSortOrder;
        }


        $ret[ $row[ 'c_id' ] ]->set_media( $withSortOrder );
      }
      elseif ( $filterState != '' )   #Wenn SA keine Medien beinhaltet, wird er wieder entfernt
      { unset ( $ret[ $row[ 'c_id' ] ] );
      }
    }

  return $ret;
}

function getUserList(  )
{
  { $SQL = 'SELECT `hawaccount` FROM `user` WHERE 1';

    $res = mysqli_query ( $this -> DB , $SQL );
    if ( isset ( $res ) )
    {  while ( $row = mysqli_fetch_assoc ( $res ) )
      {
        $ret[ $row[ 'hawaccount' ] ] = $row[ 'hawaccount' ] ;
      }
    }
  }

  return $ret;
}



# ---------------------------------------------------------------------------------------------
  function getUserMetaData( $hawAccount )
  {
    $user = new user();

    $SQL = "
  SELECT
  user.id            as u_id,
  user.role_id       as u_role,
  user.forename      as u_forename,
  user.surname       as u_surname,
  user.sex           as u_sex,
  user.email         as u_mail,
  user.bib_id        as u_bib_id,
  user.department    as u_department_id,
  user.hawaccount    as u_hawaccount

  FROM user,state,role
  WHERE state.name    = 'active'
  AND user.state_id   = state.id
  AND user.hawaccount = \"" . $this -> es ( $hawAccount ) . "\"
  AND user.role_id    = role.id LIMIT 1";


    $res    = mysqli_query       ( $this->DB , $SQL );
    $userdb = mysqli_fetch_assoc ( $res             );

    $user->set_id           ( $userdb[ 'u_id'            ] );
    $user->set_forename     ( $userdb[ 'u_forename'      ] );
    $user->set_surname      ( $userdb[ 'u_surname'       ] );
    $user->set_hawaccount   ( $userdb[ 'u_hawaccount'    ] );
    $user->set_sex          ( $userdb[ 'u_sex'           ] );
    $user->set_email        ( $userdb[ 'u_mail'          ] );
    $user->set_role_id      ( $userdb[ 'u_role'          ] );
    $user->set_department   ( $userdb[ 'u_department_id' ] );
    $user->set_bib_id       ( $userdb[ 'u_bib_id'        ] );

    if( isset( $_SESSION[ 'DEP_2_BIB' ][ $userdb[ 'u_department_id' ] ][ 'dep_name' ] ) )
    {
      $user -> set_dep_name( $_SESSION[ 'DEP_2_BIB' ][ $userdb[ 'u_department_id' ] ][ 'dep_name' ] );
    }
    else
    {
      $user -> set_dep_name( 'nicht erkannt' );
      $user -> set_dep_id( 0 );
    }

    return $user;

  }


# ---------------------------------------------------------------------------------------------
#
  function checkUserExistence( $hawacc )
  {
    $SQL = "
  SELECT *
  FROM user
  WHERE `hawaccount` = \"" . $this->es ( $hawacc ) . "\"";
    $res = mysqli_query ( $this->DB , $SQL );
    $ret = mysqli_fetch_assoc ( $res );
    return $ret;
  }


function get_bib_info( $bib_id )
{
  $bib = $_SESSION[ 'FACHBIB' ][ $bib_id ];

  $bib_info = new Bib();

  $bib_info -> set_bib_ID     ( $bib[ 'bib_id'      ] ); # (> DMI
  $bib_info -> set_bib_Name   ( $bib[ 'bib_name'    ] ); # (> Fachbibliothek Design Medien Information
  $bib_info -> set_bib_APName ( $bib[ 'bib_ap_name' ] ); # (> Jonas Engeland, Bianca Schwarzer
  $bib_info -> set_bib_APMail ( $bib[ 'bib_ap_mail' ] ); # (> jonas.engeland@haw-hamburg.de,bianca.schwarzer@haw-hamburg.de

  return $bib_info;

}


function get_med_state( $collection_id )
{
  $med_state = new MedState();

  $SQL0 = "SELECT COUNT( `collection_id` ) AS anzahl FROM `document` WHERE state_id    != 6 AND `collection_id` = '" . $collection_id . "'"; # ALLE
  $SQL1 = "SELECT COUNT( `collection_id` ) AS anzahl FROM `document` WHERE doc_type_id  = 1 AND `collection_id` = '" . $collection_id . "'"; # BUCH als SA
  $SQL2 = "SELECT COUNT( `collection_id` ) AS anzahl FROM `document` WHERE doc_type_id  = 2 AND `collection_id` = '" . $collection_id . "'"; # BUCH als LL
  $SQL3 = "SELECT COUNT( `collection_id` ) AS anzahl FROM `document` WHERE doc_type_id  = 4 AND `collection_id` = '" . $collection_id . "'"; # E-Book

  $result0 = mysqli_query ( $this->DB , $SQL0 )->fetch_assoc ();
  $result1 = mysqli_query ( $this->DB , $SQL1 )->fetch_assoc ();
  $result2 = mysqli_query ( $this->DB , $SQL2 )->fetch_assoc ();
  $result3 = mysqli_query ( $this->DB , $SQL3 )->fetch_assoc ();

  $med_state->set_med_state_GE ( $result0[ 'anzahl' ] );
  $med_state->set_med_state_SA ( $result1[ 'anzahl' ] );
  $med_state->set_med_state_LH ( $result2[ 'anzahl' ] );
  $med_state->set_med_state_EB ( $result3[ 'anzahl' ] );

 return $med_state;
}

# ---------------------------------------------------------------------------------------------
  function getAllMedStates()
  {

    $SQL = "
  SELECT *
  FROM `state`
  ORDER BY `id` ASC";

    $res = mysqli_query ( $this->DB , $SQL );
    while ( $row = mysqli_fetch_assoc ( $res ) ) {
      $row[ 'description' ] = str_replace ( '##' , '<br />' , $row[ 'description' ] );                   ## Parst nach '##' und ersetzt durch '<br>'
      $ret[ $row[ 'id' ] ] = $row;
    }
    return $ret;
  }


# ---------------------------------------------------------------------------------------------
  function getRoleInfos( $style = NULL )
  {

    $SQL = "
  SELECT *
  FROM `role`
  ORDER BY `id` ASC";

    $res = mysqli_query ( $this->DB , $SQL );
    while ( $row = mysqli_fetch_assoc ( $res ) ) {
      if ( $style == 'name' ) {
        $ret[ $row[ 'id' ] ] = $row[ 'description' ];
      } else {
        $ret[ $row[ 'id' ] ] = $row;
      }
    }
    return $ret;
  }


# ---------------------------------------------------------------------------------------------
  function deleteCollection( $IW , $IU )
  {
    trigger_error ( "Deprecated function called: deleteCollection()" , E_USER_NOTICE );
    $ret = '';
    if ( $IU[ 'role_name' ] == 'staff' || $IU[ 'role_name' ] == 'admin' ) {
      $SQL = "
    DELETE
    FROM  `collection`
    WHERE `collection`.`id` = '" . $this->es ( $IW[ 'collection_id' ] ) . "'";
      $res = mysqli_query ( $this->DB , $SQL );
      $ret = mysqli_fetch_assoc ( $res );
    }
    return $ret;
    return $ret;
  }


# ---------------------------------------------------------------------------------------------
  function deleteMedia( $IW ,  $IU  )
  { #trigger_error("Deprecated function called: deleteMedia()", E_USER_NOTICE);
    $ret = '';

    if (  $IU -> get_role_name () == 'staff' ||  $IU -> get_role_name == 'admin' ) {
    $SQL = "
    DELETE
    FROM `document`
    WHERE `document`.`id` = " . $this->es ( $IW->get_id() );

     $res = mysqli_query ( $this->DB , $SQL );
     #  return  mysqli_fetch_assoc( $res );
    }
    return $ret;
  }


  /* Gibt alle Medien Daten zurück:
  $colID:       0 = ALLE,
  $state_id:    1 = neu bestellt, 2 wird bearbeitet, 3 aktiv, 4 wird entfernt, 5 inaktiv, 6 gelöscht, 9 Erwerbvorschlag
  $doc_type_id: 1 = Buch, 2 = E-Book
  */
# ---------------------------------------------------------------------------------------------
  function getMediaMetaData( $mediaID ) /* Gibt alle Medien Daten zurück */
  {  #  trigger_error("Deprecated function called: getMediaMetaData()", E_USER_NOTICE);

    $SQL = "
  SELECT *
  FROM  `document`
  WHERE `id` = " . $this->es ( $mediaID ) . "
  ORDER BY `doc_type_id`
  ASC
  ";

    $res = mysqli_query ( $this->DB , $SQL );

    if ( $res )
    { while ( $row = mysqli_fetch_assoc ( $res ) )
    {
      $m = new Medium();
      $m->set_id	             ( $row[ 'id'                ] );
      $m->set_state_id         ( $row[ 'state_id'          ] );
      $m->set_title            ( $row[ 'title'             ] );
      $m->set_author           ( $row[ 'author'            ] );
      $m->set_edition          ( $row[ 'edition'           ] );
      $m->set_publisher        ( $row[ 'publisher'         ] );
      $m->set_ISBN             ( $row[ 'ISBN'              ] );
      $m->set_signature        ( $row[ 'signature'         ] );
      $m->set_ppn              ( $row[ 'ppn'               ] );
      $m->set_doc_type_id      ( $row[ 'doc_type_id'       ] );
      $m->set_physicaldesc     ( $row[ 'physicaldesc'      ] );
      $m->set_collection_id    ( $row[ 'collection_id'     ] );
      $m->set_notes_to_studies ( $row[ 'notes_to_studies'  ] );
      $m->set_notes_to_staff   ( $row[ 'notes_to_staff'    ] );
      $m->set_created          ( $row[ 'created'           ] );
      $m->set_last_modified    ( $row[ 'last_modified'     ] );
      $m->set_last_state_change( $row[ 'last_state_change' ] );
      $m->set_location_id      ( $row[ 'location_id'       ] );
      $m->set_shelf_remain     ( $row[ 'shelf_remain'      ] );
    }
    }


    return $m;
  }


# ---------------------------------------------------------------------------------------------
  function setCollectionState( $colID , $state )
  {
    trigger_error ( "Deprecated function called: setCollectionState()" , E_USER_NOTICE );
    $SQL = "
  UPDATE collection
  SET `state_id` = '" . $this->es ( $state ) . "' WHERE `collection`.`id` = " . $this->es ( $colID );

    $res = mysqli_query ( $this->DB , $SQL );
    return $res;
  }


# ---------------------------------------------------------------------------------------------
function setMediaState( $mediaID , $state )
{
   $SQL = "
UPDATE document
SET `state_id` = '" . $this->es ( $state ) . "' WHERE `document`.`id` = " . $this->es ( $mediaID );

  $res = mysqli_query ( $this->DB , $SQL );

  return $res;
}


# ---------------------------------------------------------------------------------------------
  function setMediaType( $mediaID , $type )
  {
    $SQL = "
  UPDATE document
  SET `document`.`doc_type_id` = '" . $this->es ( $type ) . "' WHERE `document`.`id` = " . $this->es ( $mediaID );


    $res = mysqli_query ( $this->DB , $SQL );
    return $res;
  }


# ---------------------------------------------------------------------------------------------
  function initUser( $user )
  {
    $SQL = "
  INSERT INTO user SET
  id                = \"" . $this->es ( $user->id ) . "\"  ,
  role_id           = \"" . $this->es ( $user->role ) . "\"  ,
  surname           = \"" . $this->es ( $user->surname ) . "\"  ,
  forename          = \"" . $this->es ( $user->forename ) . "\"  ,
  sex               = \"" . $this->es ( $user->sex ) . "\"  ,
  email             = \"" . $this->es ( $user->mail ) . "\"  ,
  bib_id            = \"" . $this->es ( $user->bib_id ) . "\"  ,
  department        = \"" . $this->es ( $user->department ) . "\"  ,
  hawaccount        = \"" . $this->es ( $user->hawaccount ) . "\"  ,
  state_id          = 3                                                   ,
  created           = NOW()                                               ,
  last_modified     = NOW()                                               ,
  last_state_change = NOW()                                               ";

    $res = mysqli_query ( $this->DB , $SQL );
    return $res;
  }


# ---------------------------------------------------------------------------------------------
  function renewCollection( $IC )
  {
    $tmp = $this->getCollectionMetaData ( $IC[ 'newCollection_id' ] );

    if ( isset( $tmp[ 'title_short' ] ) )  # Anzulegender SA exisitiert schon
    {
      echo "DOUBLE";
    } else {
      $SQL = " UPDATE `collection` SET  state_id  = '7' WHERE `collection`.`id` = '" . $IC[ 'collection_id' ] . "' "; # Status des alten SA wird auf 'verlängert' gesetzt
      $res = mysqli_query ( $this->DB , $SQL );

      $SQL = " CREATE TEMPORARY TABLE tmp SELECT * FROM collection WHERE title_short = '" . $IC[ 'collection_id' ] . "';";
      if ( isset( $IC[ 'newCollection_id' ] ) ) {
        $SQL .= " UPDATE tmp SET id                 =  '" . $IC[ 'newCollection_id' ] . "';";
      }
      if ( isset( $IC[ 'newCollection_id' ] ) ) {
        $SQL .= " UPDATE tmp SET  title_short       =  '" . $IC[ 'newCollection_id' ] . "';";
      }
      if ( isset( $IC[ 'newTitle' ] ) ) {
        $SQL .= " UPDATE tmp SET  title             =  '" . $IC[ 'newTitle' ] . "';";
      }
      if ( isset( $IC[ 'newExpire_date' ] ) ) {
        $SQL .= " UPDATE tmp SET  expiry_date       =   " . $IC[ 'newExpire_date' ] . " ;";
      }
      if ( isset( $IC[ 'sortorder' ] ) ) {
        $SQL .= " UPDATE tmp SET  sortorder         =  '" . $IC[ 'sortorder' ] . "';";
      }
      if ( isset( $IC[ 'currentSemester' ] ) ) {
        $SQL .= " UPDATE tmp SET  sem               =  '" . $IC[ 'currentSemester' ] . "';";
      }

      $SQL .= " UPDATE tmp SET  last_state_change = NOW();";
      $SQL .= " UPDATE tmp SET  last_modified     = NOW();";
      $SQL .= " UPDATE tmp SET  course_id         = 1;";
      $SQL .= " UPDATE tmp SET  state_id          = 3;";
      $SQL .= " INSERT INTO collection SELECT * FROM tmp;";

      $res = mysqli_multi_query ( $this->DB , $SQL );

      while ( mysqli_next_result ( $this->DB ) ) {
        ;
      }    ## bugfix - flush multi_queries

      $this->renewDocument ( $IC );  ## Alle Dokumente des SA in den neuen SA kopieren. Dokumente im Status 'löschen' werden von DB gelöscht. Alle Dokumente in Bearbeitung-Status werden zurückgesetzt
    }

    return;
  }


# ---------------------------------------------------------------------------------------------
  function renewDocument( $IC )
  {
    # Document
    $SQL = " DELETE FROM `document` WHERE  `collection_id` = '" . $IC[ 'collection_id' ] . "' AND `state_id` = 6 ";   # Alle Dokumente des aktuellen SA im Status 'gelöscht' werden von der DB gelöscht
    $res0 = mysqli_query ( $this->DB , $SQL );

    $SQL = " SELECT * FROM `document` WHERE `collection_id` = '" . $IC[ 'collection_id' ] . "' ";   # Alle Documente des SA
    $res1 = mysqli_query ( $this->DB , $SQL );
    if ( $res1 ) {
      while ( $row = mysqli_fetch_assoc ( $res1 ) ) {
        $SQL = " INSERT INTO document (doc_type_id ,physicaldesc ,collection_id ,state_id,location_id ,title ,author  ,edition,year ,journal ,volume ,pages ,publisher ,signature ,ppn ,url ,url_type_id ,relevance ,notes_to_studies ,notes_to_staff  ,protected ,created ,last_modified,last_state_change) ";
        $SQL .= " VALUES ( '" .
          $row[ 'doc_type_id' ] . "' , '" .
          $row[ 'physicaldesc' ] . "' , '" .
          $IC[ 'newCollection_id' ] . "' , '" .
          $row[ 'state_id' ] . "' , '" .
          $row[ 'location_id' ] . "' , '" .
          $row[ 'title' ] . "' , '" .
          $row[ 'author' ] . "' , '" .
          $row[ 'edition' ] . "' , '" .
          $row[ 'year' ] . "' , '" .
          $row[ 'journal' ] . "' , '" .
          $row[ 'volume' ] . "' , '" .
          $row[ 'pages' ] . "' , '" .
          $row[ 'publisher' ] . "' , '" .
          $row[ 'signature' ] . "' , '" .
          $row[ 'ppn' ] . "' , '" .
          $row[ 'url' ] . "' , '" .
          $row[ 'url_type_id' ] . "' , '" .
          $row[ 'relevance' ] . "' , '" .
          $row[ 'notes_to_studies' ] . "' , '" .
          $row[ 'notes_to_staff' ] . "' , '" .
          $row[ 'protected' ] . "' , '" .
          $row[ 'created' ] . "' , '" .
          $row[ 'last_modified' ] . "' , '" .
          $row[ 'last_state_change' ] . "' , '" .


        $res2 = mysqli_query ( $this->DB , $SQL );                                # Alle Dokumente des bisherigen SA werden in den neunen SA kopiert

        $row[ 'new_state_id' ] = null;
        if ( $row[ 'state_id' ] == '1' OR $row[ 'state_id' ] == '2' OR $row[ 'state_id' ] == '3' ) {
          $row[ 'new_state_id' ] = 3;
        } # state 1, 2 wird 3 -- bestellt oder bearbeitet wird aktiv
        elseif ( $row[ 'state_id' ] == '4' OR $row[ 'state_id' ] == '9' OR $row[ 'state_id' ] == '5' ) {
          $row[ 'new_state_id' ] = 5;
        } # state 4, 9 wird 5 -- vorschlang oder entfernt wird inaktiv

        if ( isset( $row[ 'new_state_id' ] ) AND $row[ 'doc_type_id' ] == 1 ) # Nur Bücher im SA bekommen für das Archiv ein neuen, jeweiligen Status
        {
          $SQL = " UPDATE document SET ";
          $SQL .= " state_id              = \"" . $row[ 'new_state_id' ] . "\"";
          $SQL .= " , shelf_remain        =  1 ";
          $SQL .= " , doc_type_id         =  2 ";
          $SQL .= " WHERE `collection_id` = \"" . $IC[ 'collection_id' ] . "\"  AND  id = " . $row[ 'id' ];
          $res3 = mysqli_query ( $this->DB , $SQL );
        }

      }
    }
    return;
  }


# ---------------------------------------------------------------------------------------------
  function updateUser( $user )
  {
    $SQL = "UPDATE `user` SET";
    if ( $user->get_role_id  () != '' ) {  $SQL .= " role_id               = \"" . $this->es ( $user->get_role_id  () ) . "\" ," ; }
    if ( $user->get_surname  () != '' ) {  $SQL .= " surname               = \"" . $this->es ( $user->get_surname  () ) . "\" ," ; }
    if ( $user->get_forename () != '' ) {  $SQL .= " forename              = \"" . $this->es ( $user->get_forename () ) . "\" ," ; }
    if ( $user->get_sex      () != '' ) {  $SQL .= " sex                   = \"" . $this->es ( $user->get_sex      () ) . "\" ," ; }
    if ( $user->get_email    () != '' ) {  $SQL .= " email                 = \"" . $this->es ( $user->get_email    () ) . "\" ," ; }
    if ( $user->get_bib_id   () != '' ) {  $SQL .= " bib_id                = \"" . $this->es ( $user->get_bib_id   () ) . "\" ," ; }
    if ( $user->get_dep_id   () != '' ) {  $SQL .= " department            = \"" . $this->es ( $user->get_dep_id   () ) . "\" ," ; }
                                           $SQL .= " last_modified         =      NOW()                                      ";
                                           $SQL .= " WHERE `hawaccount`    = \"" . $this->es ( $user->get_hawaccount () ) . "\"";

    $res = mysqli_query ( $this->DB , $SQL );

    return $res;
  }


# ---------------------------------------------------------------------------------------------
  function setCollection( $Course )
  {
    $SQL = "
  INSERT INTO collection SET
  state_id         =      3                                                  ,
  last_modified    =      NOW()                                              ,
  last_state_change=      NOW()                                              ,
  created          =  \"" . $this->es ( $Course[ 'created' ] ) . "\" ,
  notes_to_studies =  \"" . $this->es ( $Course[ 'notes_to_studies_col' ] ) . "\" ,
  expiry_date      =  \"" . $this->es ( $Course[ 'expiry_date' ] ) . "\" ,
  id               =  \"" . $this->es ( $Course[ 'shortname' ] ) . "\" ,
  title            =  \"" . $this->es ( $Course[ 'title' ] ) . "\" ,
  title_short      =  \"" . $this->es ( $Course[ 'title_short' ] ) . "\" ,
  course_id        =  \"" . $this->es ( $Course[ 'id' ] ) . "\" ,
  bib_id           =  \"" . $this->es ( $Course[ 'bib_id' ] ) . "\" ,
  sem              =  \"" . $this->es ( $Course[ 'sem' ] ) . "\", ";

    if ( isset ( $Course[ 'sortorder' ] ) ) {
      $SQL .= " sortorder           =  \"" . $this->es ( $Course[ 'sortorder' ] ) . "\" ,";
    }

    $SQL .= " user_id             =  \"" . $this->es ( $Course[ 'user_id' ] ) . "\"";

    $res = mysqli_query ( $this->DB , $SQL );

    return $res;
  }


# ---------------------------------------------------------------------------------------------
  function setSAToLH( $collectionData )  # Alle SA Bücher werden zu aktiven Literaturhinweisen (LH)
  {
    $SQL = " UPDATE document SET ";
    $SQL .= " state_id              =  3 ";
    #$SQL .= " , shelf_remain        =  1 ";
    $SQL .= " , doc_type_id         =  2 ";
    $SQL .= " WHERE `collection_id` = \"" . $collectionData[ 'collection_id' ] . "\"  AND  doc_type_id =  1";
    $res3 = mysqli_query ( $this->DB , $SQL );
  }


# ---------------------------------------------------------------------------------------------
  function initCollection( $course , $user )
  {
    $SQL = "
  INSERT INTO collection SET
  state_id             =      3                                                          ,
  created              =      NOW()                                                      ,
  last_modified        =      NOW()                                                      ,
  last_state_change    =      NOW()                                                      ,
  notes_to_studies_col =   ''                                                            ,
  expiry_date          =  \"" . $this->es ( $course->get_expiry_date () ) . "\" ,
  id                   =  \"" . $this->es ( $course->get_title_short () ) . "\" ,
  title                =  \"" . $this->es ( $course->get_title () ) . "\" ,
  title_short          =  \"" . $this->es ( $course->get_title_short () ) . "\" ,
  course_id            =  \"" . $this->es ( $course->get_course_id () ) . "\" ,
  bib_id               =  \"" . $this->es ( $user->get_bib_id () ) . "\" ,
  user_id              =  \"" . $this->es ( $user->get_hawaccount () ) . "\" ,
  sem                  =  \"" . $this->es ( $_SESSION[ 'CUR_SEM' ] ) . "\"";

    $ret = mysqli_query ( $this->DB , $SQL );

    return $ret;
  }


# ---------------------------------------------------------------------------------------------
  function updateColMetaData( $collection )
  {
    $SQL = "UPDATE `collection`  SET";
    if   ( $collection -> get_modul_id             () != '' ) {  $SQL .= " modul_id             = \"" . $this->es ( $collection -> get_modul_id             () ) . "\" ,";  }
    if   ( $collection -> get_bib_id               () != '' ) {  $SQL .= " bib_id               = \"" . $this->es ( $collection -> get_bib_id               () ) . "\" ,";  }
    if   ( $collection -> get_sem                  () != '' ) {  $SQL .= " sem                  = \"" . $this->es ( $collection -> get_sem                  () ) . "\" ,";  }
    if   ( $collection -> get_title                () != '' ) {  $SQL .= " title                = \"" . $this->es ( $collection -> get_title                () ) . "\" ,";  }
 #  if   ( $collection -> get_notes_to_studies_col () != '' ) {  $SQL .= " notes_to_studies_col = \"" . $this->es ( $collection -> get_notes_to_studies_col () ) . "\" ,";  }
                                                              {  $SQL .= " notes_to_studies_col = \"" . $this->es ( $collection -> get_notes_to_studies_col () ) . "\" ,";  }
                                                                 $SQL .= " last_modified        = NOW() ";
    if   ( $collection -> get_title_short          () != '' ) {  $SQL .= " WHERE title_short    = \"" . $this->es ( $collection -> get_title_short          () ) . "\"  ";  }
    else {                                                       $SQL .= " WHERE id             = \"" . $this->es ( $collection -> get_collection_id        () ) . "\"  ";  }


    $res = mysqli_query ( $this->DB , $SQL );
    return $res;
  }



# ---------------------------------------------------------------------------------------------
  function
  updateCollectionSortOrder( $collection )
  {
    $SQL = " UPDATE collection SET ";
    $SQL .= " sortorder        = \"" . $this->es ( $collection -> get_sortorder() ) . "\"";
    $SQL .= " WHERE id         = \"" . $this->es ( $collection -> get_collection_id() ) . "\"";

    $res = mysqli_query ( $this->DB , $SQL );
    return $res;
  }



# ---------------------------------------------------------------------------------------------
  function exportCollection( $collection_id )
  {
    $csv_export = '';
    # $csv_filename = 'ELSE_'. urlencode( $collection_id ). '.' . date("YmdHis") . '.exp';
    $SQL = " SELECT * FROM `document`  WHERE collection_id = '" . $collection_id . "' AND state_id != 6";

    $res = mysqli_query ( $this -> DB , $SQL );

    if ( $res )
    { while ( $row = mysqli_fetch_assoc ( $res ) )
    { foreach ( $this->conf[ 'expoimp' ] as $exim )
    {  $csv_export .=  preg_replace("(\r\n|\n|\r)" , "<br/>",  $row[ $exim  ]  ) . ";;";
    }
      $csv_export .= "\r\n";
    }
    }
    return $csv_export;
  }


# ---------------------------------------------------------------------------------------------
function importCollection( $collection_id , $medium )
{
  #if ( substr_count ( $medium , ';;' ) >= 17 ) # Plausibilitätscheck des Inhalts. ';;' ist der Delimiter
  {
    $i = 0;
    $SQL = "INSERT INTO document SET ";
    $med = explode ( ";;" , $medium );

    foreach ( $this -> conf[ 'expoimp' ] as $exim )
    {
      if ( isset( $med[ 4 ] ) ) ## Zu importierender Datensatz hat zumindest ein Titel
      {
        $SQL .= " $exim  = \"" . $med[ $i++ ] . "\"  , ";
      }
    }

    $SQL .= " collection_id     = \"" . $collection_id . "\"  , ";
    $SQL .= " last_modified     = NOW()                         ";

   $fp       = fopen('dataIMP.txt', 'w');
   fwrite($fp, $SQL  ) ;

    $res = mysqli_query ( $this->DB , $SQL );
    return $res;
  }
}



  function getSAid( $SEM )
{
  $SQL = " SELECT id, title,  bib_id  FROM `collection` wHERE sem = '" . $SEM . "' ORDER BY bib_id DESC;";
  $res = mysqli_query ( $this->DB , $SQL );
  while ( $row = mysqli_fetch_assoc ( $res ) ) {
  $ret[ $row[ 'id' ] ] = $row;
  }
   return $ret;
}



# ---------------------------------------------------------------------------------------------
  function getDocumentInfos( $docID )  ## Kartesisches Produkt aller Dokumenten mit allen dazugehörigen Infos
  {
    $SQL = "SELECT * FROM `document` WHERE `id`  = " . $this->es ( $docID );
    $res = mysqli_query ( $this->DB , $SQL );
    $ans = mysqli_fetch_assoc ( $res );
    return $ans;
  }


# ---------------------------------------------------------------------------------------------
  function getDocumentID( $medium ) /* Kartesisches Produkt aller Dokumenten mit allen dazugehörigen Infos */
  {
    $SQL = " SELECT id FROM `document` WHERE" ;
    if ( $medium->get_collection_id   ( ) != '' ) { $SQL .= "     `collection_id`      = \"" . $this->es ( $medium->get_collection_id     ( ) ) . "\""; }
    if ( $medium->get_title           ( ) != '' ) { $SQL .= " AND `title`              = \"" . $this->es ( $medium->get_title             ( ) ) . "\""; }
    if ( $medium->get_author          ( ) != '' ) { $SQL .= " AND `author`             = \"" . $this->es ( $medium->get_author            ( ) ) . "\""; }
    if ( $medium->get_edition         ( ) != '' ) { $SQL .= " AND `edition`            = \"" . $this->es ( $medium->get_edition           ( ) ) . "\""; }
    if ( $medium->get_publisher       ( ) != '' ) { $SQL .= " AND `publisher`          = \"" . $this->es ( $medium->get_publisher         ( ) ) . "\""; }
    if ( $medium->get_signature       ( ) != '' ) { $SQL .= " AND `signature`          = \"" . $this->es ( $medium->get_signature         ( ) ) . "\""; }
    if ( $medium->get_ppn             ( ) != '' ) { $SQL .= " AND `ppn`                = \"" . $this->es ( $medium->get_ppn               ( ) ) . "\""; }
    if ( $medium->get_doc_type_id     ( ) != '' ) { $SQL .= " AND `doc_type_id`        = \"" . $this->es ( $medium->get_doc_type_id       ( ) ) . "\""; }
    if ( $medium->get_physicaldesc    ( ) != '' ) { $SQL .= " AND `physicaldesc`       = \"" . $this->es ( $medium->get_physicaldesc      ( ) ) . "\""; }
    if ( $medium->get_state_id        ( ) != '' ) { $SQL .= " AND `state_id`           = \"" . $this->es ( $medium->get_state_id          ( ) ) . "\""; }
    if ( $medium->get_notes_to_staff  ( ) != '' ) { $SQL .= " AND `notes_to_staff`     = \"" . $this->es ( $medium->get_notes_to_staff    ( ) ) . "\""; }
   #if ( $medium->get_shelf_remain    ( ) != '' ) { $SQL .= " AND `shelf_remain`       = \"" . $this->es ( $medium->get_shelf_remain      ( ) ) . "\""; }
    if ( $medium->get_notes_to_studies( ) != '' ) { $SQL .= " AND `notes_to_studies`   = \"" . $this->es ( $medium->get_notes_to_studies  ( ) ) . "\""; }
    $SQL .= " ORDER BY `id` DESC";

    # deb($medium,1);
    # deb($SQL,1);
    $res = mysqli_query ( $this -> DB , $SQL );
    while ( $ret = $res -> fetch_array ( MYSQLI_ASSOC ) )
    {  $ans = $ret[ 'id' ];
    }

    return $ans;
  }


# ---------------------------------------------------------------------------------------------
  function getAllDocTypes()
  {
    $SQL = "SELECT * FROM `doc_type` ORDER BY id asc";

    $res = mysqli_query ( $this->DB , $SQL );
    while ( $row = mysqli_fetch_assoc ( $res ) ) {
      $ret[ $row[ 'id' ] ] = $row;
    }

    return $ret;
  }


# ---------------------------------------------------------------------------------------------
  function getRoleName( $roleNr )
  {
    $ret = "";
    $SQL = "SELECT name FROM `role` WHERE id = " . $this->es ( $roleNr );

    $res = mysqli_query ( $this->DB , $SQL );
    while ( $row = mysqli_fetch_assoc ( $res ) ) {
      $ret = $row[ 'name' ];
    }

    return $ret;
  }



# ---------------------------------------------------------------------------------------------
  function getSAlist( $user , $I, $asObject=true )
  {
    $SQL  = " SELECT c.id                    as c_id,
                     c.user_id               as c_user_id,
                     c.state_id              as c_state_id,
                     c.title                 as c_title,
                     c.title_short           as c_title_short,
                     c.bib_id                as c_bib_id,
                     c.sem                   as c_sem,
                     c.created               as c_created,
                     c.last_modified         as c_last_modified,
                     c.last_state_change     as c_last_state_change,
                     c.expiry_date           as c_expiry_date,
                     c.notes_to_studies_col  as c_notes_to_studies_col,
                     c.course_id             as c_course_id,
                     c.sortorder             as c_sortorder,
                     u.department            as u_department,  
                     u.surname               as u_surname,
                     u.forename              as u_forename, 
                     u.bib_id                as u_bib_id,
                     s.name                 as state_name,
                     s.description          as state_description";
    $SQL .= " FROM collection c ";
    $SQL .= " LEFT JOIN  user u";
    $SQL .= " ON u.hawaccount = c.user_id ";
    $SQL .= " LEFT JOIN  state s";
    $SQL .= " ON s.id = c.state_id";
    $SQL .= " WHERE user_id = \"" . $this->es ( $user ) . "\"";
    if ( $I[ 'operator' ]->get_mode () == "view" )
  { $SQL .= " AND c.state_id = 3";                                                                }     # Zustand 3 = aktiv
    if ( $I[ 'filter' ] -> get_bib( ) != 'X' AND $I[ 'filter' ] -> get_bib( )  != ''          )         # sem: Filter auf das aktuelle Semester (oder alle Semester)
  { $SQL .= " AND (  c.bib_id = '" . $I[ 'filter' ] -> get_bib( )  . "'  )";                      }     # operator for category/department
    if ( $I[ 'filter' ] -> get_sem( )  != 'X' AND $I[ 'filter' ] -> get_sem( )  != ''         )         # sem: operator auf das aktuelle Semester (oder alle Semester)
  { $SQL .= " AND ( c.sem = '" . $I[ 'filter' ] -> get_sem( )  . "')";                            }
    $SQL .= " ORDER BY `c_id` ";
    $SQL .= " DESC ";

    #deb($SQL,1);
    $SAList = NULL;


    $res = mysqli_query ( $this->DB , $SQL );

    if ( $res )
    while ( $row = mysqli_fetch_assoc ( $res ) )
    {
        $collection = new collection();
        $collection -> set_bib_id                ( $row [ 'c_bib_id'            ]  );
        $collection -> set_id                    ( $row [ 'c_id'                ]  );
        $collection -> set_state_id              ( $row [ 'c_state_id'          ]  );
        $collection -> set_title                 ( $row [ 'c_title'             ]  );
        $collection -> set_title_short           ( $row [ 'c_title_short'       ]  );
        $collection -> set_sem                   ( $row [ 'c_sem'               ]  );
        $collection -> set_created               ( $row [ 'c_created'           ]  );
        $collection -> set_last_modified         ( $row [ 'c_last_modified'     ]  );
        $collection -> set_last_state_change     ( $row [ 'c_last_state_change' ]  );
        $collection -> set_expiry_date           ( $row [ 'c_expiry_date'       ]  );
        $collection -> set_notes_to_studies_col  ( $row [ 'c_notes_to_studies_col'  ]  );
        $collection -> set_user_id               ( $row [ 'c_user_id'           ]  );
        $collection -> set_course_id             ( $row [ 'c_course_id'         ]  );
        $collection -> set_sortorder             ( $row [ 'c_sortorder'         ]  );
        $collection -> set_collection_id         ( $row [ 'c_id'                ]  );
        $collection -> set_dc_collection_id      ( base64_encode ( rawurlencode ( $row[ 'c_id'    ] ) ) ); # (> ELSE-ADMIN


        $collection->set_Bib       ( $this -> get_bib_info     ( $row[ 'c_bib_id'      ] ) );
        $collection->set_Medstate  ( $this -> get_med_state    ( $row[ 'c_title_short' ] ) );
        $collection->set_Owner     ( $this -> getUserMetaData  ( $row[ 'c_user_id'     ] ) );

        if( !$asObject ) { $collection = $collection->obj2array (); }

        $SAList[ $row [ 'c_id' ]] =      $collection;
        unset( $collection );
    }

    return $SAList;
  }



# ---------------------------------------------------------------------------------------------
  function es( $str )   # ESCAPEd Daten
  {
    return $this->DB->real_escape_string ( $str );
  }


  function trimValue($currTable, $currTableRow)  // entfernt überzählige Leerzeichen aus den Dantenbankeinträgen
  {
#    $currTableRow = 'title_short';  # Spalte
#    $currTable    = 'collection';   # Tabelle

    $SQL = "SELECT `". $currTableRow."` FROM `".$currTable."`";

    $res = mysqli_query ( $this->DB , $SQL );

    if ( $res )
    {
      while ( $row = mysqli_fetch_assoc ( $res ) )
      {
        $newValue = trim($row[$currTableRow ]);
        $SQL2 = "UPDATE $currTable SET `$currTableRow` =  \"$newValue\"   WHERE `$currTableRow` LIKE \"" . $row[$currTableRow] . "\"";
        mysqli_query ( $this->DB , $SQL2 );
      }
    }
  }
}
