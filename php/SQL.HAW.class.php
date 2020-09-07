<?php
# ---------------------------------------------------------------------------------------------
# ---------------------------------------------------------------------------------------------
class HAW_DB
{
  var $db;
  var $log;
  var $status;


# ---------------------------------------------------------------------------------------------
  function __construct( )
  {
    {	#$this -> db = new SQLite3( '../DB/HAW-Fak-Dep-SG.s3db' );
      $this -> db = new PDO( 'sqlite:../DB/HAW-Fak-Dep-SG.s3db' );
      if( $this -> db )
      {  $this -> log = fopen("../log/HAW-FAK-DEP.log", "a");
      }
      else
      {	die( "<b>KEINE Verbindung zur HAW FAK-DB Datenbank möglich</b>" );
      }
    }
  }

# ---------------------------------------------------------------------------------------------
  function getDEP_2_BIB()
  {
    $r = NULL;
    $SQL =  "SELECT 
DepID      as dep_id      ,
DepName    as dep_name    ,
Dep2Fak    as dep_2_fak   , 
Dep2BIB    as DEP_2_BIB   , 
FakID      as fak_id      ,
FakName    as fak_name    ,
FakAbk     as fak_abk     ,
BibID      as bib_id      ,
BibName    as bib_name    ,
BibAPName  as bib_ap_name ,
BibAPMail  as bib_ap_mail 
FROM 
Department as D,
Fakultaet  as F,
HIBS       as H
WHERE D.Dep2Fak = F.FakID 
AND   D.Dep2BIB = H.BibID;";

    $result =  $this -> db -> query( $SQL );

    foreach ( $result as $tmp )										// Daten zeilenweise in Array speichern
    {
      $r[$tmp['dep_id']][ 'dep_id'      ] = $tmp[ 'dep_id'      ];
      $r[$tmp['dep_id']][ 'dep_name'    ] = $tmp[ 'dep_name'    ];
      $r[$tmp['dep_id']][ 'dep_2_fak'   ] = $tmp[ 'dep_2_fak'   ];
      $r[$tmp['dep_id']][ 'DEP_2_BIB'   ] = $tmp[ 'DEP_2_BIB'   ];
      $r[$tmp['dep_id']][ 'fak_id'      ] = $tmp[ 'fak_id'      ];
      $r[$tmp['dep_id']][ 'fak_name'    ] = $tmp[ 'fak_name'    ];
      $r[$tmp['dep_id']][ 'fak_abk'     ] = $tmp[ 'fak_abk'     ];
      $r[$tmp['dep_id']][ 'bib_id'      ] = $tmp[ 'bib_id'      ];
      $r[$tmp['dep_id']][ 'bib_name'    ] = $tmp[ 'bib_name'    ];
      $r[$tmp['dep_id']][ 'bib_ap_name' ] = $tmp[ 'bib_ap_name' ];
      $r[$tmp['dep_id']][ 'bib_ap_mail' ] = $tmp[ 'bib_ap_mail' ];
    }
    return $r;
  }


# ---------------------------------------------------------------------------------------------
  function getAllFak()
  {
    $r = NULL;
    $SQL =  "
SELECT
FakID      as fak_id      ,
FakName    as fak_name    ,
FakAbk     as fak_abk     
FROM  Fakultaet;";

    $result =  $this -> db -> query( $SQL );

    foreach ( $result as $tmp )											// Daten zeilenweise in Array speichern
    {
      $r[$tmp['fak_id']][ 'fak_id'    ] = $tmp[ 'fak_id'    ];
      $r[$tmp['fak_id']][ 'fak_name'  ] = $tmp[ 'fak_name'  ];
      $r[$tmp['fak_id']][ 'fak_abk'   ] = $tmp[ 'fak_abk'   ];
    }
    return $r;
  }

# ---------------------------------------------------------------------------------------------
  function getAllFachBib()
  {
    $r = NULL;
    $SQL =  "SELECT 
  BibID      as bib_id      ,
  BibName    as bib_name    ,
  BibAPName  as bib_ap_name ,
  BibAPMail  as bib_ap_mail 
  FROM  HIBS;";

    $result =  $this -> db -> query( $SQL );

    foreach ( $result as $tmp )											// Daten zeilenweise in Array speichern
    {
      $r[$tmp['bib_id']][ 'bib_id'      ] = $tmp[ 'bib_id'     ];
      $r[$tmp['bib_id']][ 'bib_name'    ] = $tmp[ 'bib_name'   ];
      $r[$tmp['bib_id']][ 'bib_ap_name' ] = $tmp[ 'bib_ap_name' ];
      $r[$tmp['bib_id']][ 'bib_ap_mail' ] = $tmp[ 'bib_ap_mail' ];
    }
    return $r;
  }

  # ---------------------------------------------------------------------------------------------
  function deleteMassMedia( $type) # type: orpaned, toDelete
  {
    $ret = NULL;

    $SQL = "SELECT collection_id , document.id as document_id,  document.state_id as document_state_id FROM document  ";
    $res =  mysqli_query ( $this -> db, $SQL);
    $this -> DB -> set_charset('utf8');
    if ($res)
    {
      while ($row = mysqli_fetch_assoc($res))
      { $ret[ $row['document_id'] ] = $row;    # Liste ALLER Dokumente/Medien
      }
    }

    $IU[ 'role_name' ]   = 'admin';                ### ACHTUNG TEMPORÄRER HACK!! ###

    foreach ($ret as $r)
    {
      $SQL = "SELECT id , COUNT(*) as cnt FROM collection WHERE id = '".$r['collection_id']."'";
      $res  = mysqli_query ( $this -> DB, $SQL );
      $row  = mysqli_fetch_assoc($res);

      if ( ( $type == 'orpaned'  AND $row[ 'cnt'             ] == 0 )          # Dieses Medium ist ein KEINEM Semesterapparat enthalten -> ist verweist
        OR ( $type == 'toDelete' AND $r[ 'document_state_id' ] == 6 ) )          # Oder zum Löschen freigegeben
      {
        $r[ 'cnt' ]          = $row[ 'cnt' ];
        $IW[ 'document_id' ] = $r[ 'document_id' ];
        $this -> deleteMedia( $IW, $IU );                # Verweistes Medium wird gelöscht.
        $ret2[] = $r;
      }
    }
    return $ret;
  }


  function deleteEmptyCollections()
  {
    $IU[ 'role_name' ]   = 'admin';                ### ACHTUNG TEMPORÄRER HACK!! ###

    $SQL = "SELECT id FROM `collection`";
    $res =  mysqli_query ( $this -> DB, $SQL);
    if ($res)
    {
      while ( $row = mysqli_fetch_assoc( $res ) )
      {
        $SQL2 = " SELECT id, COUNT(*) as cnt FROM `document` WHERE `collection_id` = '".$row['id']."' ";
        $res2 =  mysqli_query ( $this -> DB, $SQL2 );
        if ( $res2 )
        {  $row2 = mysqli_fetch_assoc( $res2 );
          if ( $row2[ 'cnt' ] == 0 )
          {

            $IW['collection_id'] = $row['id'];
            # $this -> deleteCollection($IW, $IU);
          }
        }
      }
    }
  }



# ---------------------------------------------------------------------------------------------
  function getAdminEmailInfos()
  {
    foreach ( $_SESSION[ 'FACHBIB' ] as $HIBS_loc ) {
      $ret[ $HIBS_loc[ 'bib_id' ] ] = $HIBS_loc;

      $SQL1 = "
    SELECT COUNT( * )
    FROM document
    INNER JOIN collection ON document.collection_id = collection.id
    WHERE document.state_id = '1' AND collection.bib_id = '" . $this -> es ( $HIBS_loc[ 'bib_id' ] ) . "'";    /* Status 1 = Neu Angefordert */
      $res = mysqli_query ( $this -> db, $SQL1 );
      $tmp = mysqli_fetch_assoc ( $res );
      $ret[ $HIBS_loc[ 'bib_id' ] ][ 1 ] = $tmp[ 'COUNT( * )' ];

      $SQL2 = "
    SELECT COUNT( * )
    FROM document
    INNER JOIN collection ON document.collection_id = collection.id
    WHERE document.state_id = '9' AND collection.bib_id = '" . $this->es ( $HIBS_loc[ 'bib_id' ] ) . "'";   /* Status 9 = Kaufvorschlag  */
      $res = mysqli_query ( $this->DB , $SQL2 );
      $tmp = mysqli_fetch_assoc ( $res );
      $ret[ $HIBS_loc[ 'bib_id' ] ][ 9 ] = $tmp[ 'COUNT( * )' ];

      $SQL3 = "
    SELECT COUNT( * )
    FROM document
    INNER JOIN collection ON document.collection_id = collection.id
    WHERE document.state_id = '4' AND collection.bib_id = '" . $this->es ( $HIBS_loc[ 'bib_id' ] ) . "'";     /* Status 4 = Wird Entfernt  */

      $res = mysqli_query ( $this->DB , $SQL3 );
      $tmp = mysqli_fetch_assoc ( $res );
      $ret[ $HIBS_loc[ 'bib_id' ] ][ 4 ] = $tmp[ 'COUNT( * )' ];
    }
    return $ret;
  }



# ---------------------------------------------------------------------------------------------
  function es( $str )   # ESCAPEd Daten
  {
    #return  $this->DB->real_escape_string ( $str );
    return   $str ;
  }




  /*

  # ---------------------------------------------------------------------------------------------
    function getCollection2( $colID = null , $filter = false , $doc_type_id = null , $doc_state_id = null , $short = null )
    {

      $semesterFilter  = '';
      $bibFilter       = '';
      $docStateFilter  = '';
      $docTypeFilter   = '';

      $collection = '';
      $ret = false;

      if ( $colID ) {
        $collection .= " AND collection.id = \"" . $this->es ( $colID ) . "\" ";
      }

      if ( $filter )                            # Wenn Filter AKTIV
      {
        if ( $filter[ 'sem' ] != ''  AND  $filter[ 'sem' ] != 'X' )                 # SEMESTER filter
        {
          $semesterFilter = " AND c.sem = '" .  $filter[ 'sem' ] . "' ";
        }

        if ( $filter[ 'bib' ] != ''   )                                             # Bibliotheks filter
        {
          $bibFilter = "AND (" . $this->getCAT () . ")";
        }
      }


      if ( isset( $doc_state_id ) AND ( $doc_state_id != 6 AND $doc_state_id != 0 ) ) {
        $docStateFilter = " AND `state_id`    = " . $this->es ( $doc_state_id );
      }
      if ( isset( $doc_type_id ) AND $doc_type_id != 0 ) {
        $docTypeFilter = " AND `doc_type_id` = " . $this->es ( $doc_type_id );
      }


      ## ---------------------------------------------------------------------------------------------------------------------------------------------------
      ## EIN oder
      ## ALLE Semesterapparate. Gefiltert nach BIB und/oder SEMESTER
      $SQL = "SELECT *,  u.bib_id AS user_bib_id , c.bib_id AS coll_bib_id,   c.id  AS collID, c.sem AS collSem , u.id AS uID";
      $SQL .= " FROM `collection` AS c , `user` AS u, `document` AS d ";
      $SQL .= " WHERE  u.hawaccount = c.user_id AND d.collection_id = c.id " .  $collection  . " " . $bibFilter . " " . $semesterFilter. " " . $docStateFilter. " " . $docTypeFilter;       # ."  ".$user;
      $SQL .= " ORDER BY c.id ";



      $res = mysqli_query ( $this->DB , $SQL );
      ## ---------------------------------------------------------------------------------------------------------------------------------------------------

      ## ---------------------------------------------------------------------------------------------------------------------------------------------------
      ##  ALLE Medieninfo zu dem entsprechenden SA werden ermittelt
      ## ---------------------------------------------------------------------------------------------------------------------------------------------------
      if ( $res )
        while ( $row = mysqli_fetch_assoc ( $res ) ) {
          $userInfo = $this->getUserMetaData ( $row[ 'user_id' ] );
          $sortorder = explode ( ',' , $row[ 'sortorder' ] );

          $ret[ $row[ 'collID' ] ] = $row;
          $ret[ $row[ 'collID' ] ][ 'dc_collID' ] = base64_encode ( $ret[ $row[ 'collID' ] ][ 'collID' ] );
          $ret[ $row[ 'collID' ] ][ 'user_info' ] = $userInfo[ 0 ];

          $dl = $this->getDokumentList ( $row[ 'collID' ] , $doc_type_id , $doc_state_id );  ## Liste mit Informationen aller Medien des SA ( $doc_ID, $doc_type_id = null , $doc_state_id = null  )


  #   $di = $this->getDocumentInfos('3304');


          if ( $dl ) {
            $withoutSortOrder = array();
            $withSortOrder = array();

            foreach ( $dl as $d ) {
              $withoutSortOrder[ $d[ 'id' ] ] = array_merge ( $d , $this->CFG->C->getDocType ( $d ) );
            }   ## --- Attribute hinzufügen 'doc_type', 'item', 'doc_type_id', 'state_id'
            if ( $sortorder[ 0 ] != '' AND $doc_state_id == '0' ) {
              foreach ( $sortorder as $ppn ) {
                foreach ( $withoutSortOrder as $wso ) {
                  if ( $wso[ 'ppn' ] == $ppn ) {
                    $withSortOrder[] = $wso;
                    unset( $withoutSortOrder[ $wso[ 'id' ] ] );
                  }
                }
              }
              $withSortOrder = array_merge ( $withSortOrder , $withoutSortOrder );
            } else {
              $withSortOrder = $withoutSortOrder;
            }

            $ret[ $row[ 'collID' ] ][ 'document_info' ] = $withSortOrder;
          } elseif ( $short )   # Wenn SA keine Medien beinhaltet, wird er wieder entfernt
          {
            unset ( $ret[ $row[ 'collID' ] ] );
          } elseif ( $doc_state_id != null ) {
            unset ( $ret[ $row[ 'collID' ] ] );
          }
        }

      return $ret;
    }
  */


  /*
  # ---------------------------------------------------------------------------------------------
    function getDokumentList( $colID , $doc_type_id = null , $state_id = null )
    {
      $ret = NULL;

      $SQL = "
    SELECT *
    FROM `document`
    WHERE `collection_id` = \"" . $colID . "\"";

      if ( isset( $state_id ) AND ( $state_id != 6 AND $state_id != 0 ) ) {
        $SQL .= " AND `state_id`    = " . $this->es ( $state_id );
      }
      if ( isset( $doc_type_id ) AND $doc_type_id != 0 ) {
        $SQL .= " AND `doc_type_id` = " . $this->es ( $doc_type_id );
      }

      $res = mysqli_query ( $this->DB , $SQL );


      if ( $res )
        while ( $row = mysqli_fetch_assoc ( $res ) ) {
          $ret[ $row[ 'id' ] ] = $row;
          $ret[ $row[ 'id' ] ][ 'document_id' ] = $row[ 'id' ];
        }
      return $ret;
    }
  */





  /*
  # ---------------------------------------------------------------------------------------------
    function getUserMetaDataXX( $operator )
    {
      $user = '';
      # if ( $operator->get_user () != '' ) {    $user = "AND user.hawaccount ='" . $operator->get_user () . "'";   }

      $param = array
      (
        "tables" => "user,role,state" ,
        "cond" => "
           user.state_id  =  state.id
       AND user.role_id   =  role.id
       AND (role.name    != 'edit' OR role.name != 'staff' )" ,

        #  "columns" => " user.* " ,
        "columns" => "role_id    as  u_role_id   ,
                      surname    as  u_surname   ,
                      forename   as  u_forename  ,
                      sex        as  u_sex       ,
                      email      as  u_email     ,
                      state_id   as  u_state_id  ,
                      bib_id     as  u_bib_id    ,
                      department as  u_department,
                      hawaccount as  u_hawaccount " ,

        "order" => "surname, forename, sex"
      );

      if ( $operator->get_mode () == "edit" and $operator->get_user () != '' ) {  $param[ 'cond' ] .= " AND user.id     =" . $this->es ( $operator->get_user () );  }
      if ( $operator->get_mode () == "view"                                  ) {  $param[ 'cond' ] .= " AND state.name  =  'active'";                               }
      else                                                                     {  $param[ 'cond' ] .= " AND state.name  != 'new'";                                  }

      $SQL = "SELECT " . $param[ 'columns' ] . " FROM " . $param[ 'tables' ] . "  WHERE  " . $param[ 'cond' ] . " " . $user . " ORDER BY user.surname";

      $res = mysqli_query ( $this->DB , $SQL );

      if ( isset ( $res ) )
        while ( $row = mysqli_fetch_assoc ( $res ) )
        {
          $u = new user();
          $u->set_role_id    ( $row[ 'u_role_id'    ] ); #=> 3
          $u->set_surname    ( $row[ 'u_surname'    ] ); #=> Alpers
          $u->set_forename   ( $row[ 'u_forename'   ] ); #=> Markus
          $u->set_sex        ( $row[ 'u_sex'        ] ); #=> m
          $u->set_email      ( $row[ 'u_email'      ] ); #=> markus.alpers@haw-hamburg.de
          $u->set_state_id   ( $row[ 'u_state_id'   ] ); #=> 3
          $u->set_bib_id     ( $row[ 'u_bib_id'     ] ); #=> DMI
          $u->set_department ( $row[ 'u_department' ] ); #=> 23
          $u->set_hawaccount ( $row[ 'u_hawaccount' ] ); # => aca274

          $ret[] = $u;
        }

      return $ret;
    }
  */
}