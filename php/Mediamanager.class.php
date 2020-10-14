<?php
class MEDIAMANAGER
{  
var $RENDERER;
var $SQL;
var $UTIL;
var $MED;
var $CFG;
var $COLLMGR;

# ---------------------------------------------------------------------------------------------
function __construct( $CFG , $SQL, $RENDERER, $UTIL, $COLLMGR )
{
  $this -> CFG        = $CFG;
  $this -> SQL        = $SQL;
  $this -> UTIL       = $UTIL;
  $this -> RENDERER   = $RENDERER;
  $this -> COLLMGR     = $COLLMGR;
}
####################### --- MEDIA  --- #######################

function showHitList( $I , $books, $hits, $maxhits )
{
  $collection_id = $I[ 'currentCollection'    ] -> get_collection_id();
  $collection    = $this -> SQL -> getCollection ( $collection_id );

  $tpl_vars[ 'collection'      ]  = $collection[ $collection_id           ] -> obj2array ( );
  $tpl_vars[ 'user'            ]  = $I[ 'currentUser'                     ] -> obj2array ( );
  $tpl_vars[ 'operator'        ]  = $I[ 'operator'                        ] -> obj2array ( );
  $tpl_vars[ 'filter'          ]  = $I[ 'filter'                          ] -> obj2array ( ) ;

  $tpl_vars[ 'SEMESTER'        ]  = array_keys( $_SESSION[ 'CFG' ][ 'SEM' ] );                                      # $conf[ 'SEMESTER' ] ;
  $tpl_vars[ 'page'            ]  = 2;                                                                  /* Seite 2 = Anzeige der Trefferliste nach der Suche */

  $tpl_vars[ 'searchHits'      ]  = $hits;
  $tpl_vars[ 'maxHits'         ]  = $maxhits;

  $tpl_vars[ 'books_info'      ]  = $books;
  $tpl_vars[ 'back_URL'          ]                    = "index.php?item=collection&action=add_media&dc_collection_id=".$collection[ $collection_id           ]->get_dc_collection_id()."&r=".$I[ 'currentUser'  ]->get_role_id();
 
 
  $this -> RENDERER -> do_template ( 'new_book.tpl' , $tpl_vars ) ;

  exit(0);
}

###############################################################################################
function editMediaMetaData( $I )
{
  $collection_id                                          =  $I[ 'currentCollection' ] -> get_collection_id();
  $collection                                             =  $this -> SQL->getCollection ( $collection_id );
  $tpl_vars[ 'collection'      ]                          =  $collection[ $collection_id ] -> obj2array( );
  $tpl_vars[ 'medium'          ]                          =  $this -> SQL -> getMediaMetaData( $I[ 'medium' ] -> get_id() ) -> obj2array();
  $tpl_vars[ 'user'            ]                          =  $I[ 'currentUser' ] -> obj2array();
  $tpl_vars[ 'operator'        ]                          =  $I[ 'operator'    ] -> obj2array();
  $tpl_vars[ 'operator'        ][ 'mode'         ]        =  "save";
  $tpl_vars[ 'SEMESTER'        ]                          =  array_keys( $_SESSION[ 'CFG' ][ 'SEM' ] );  #$conf[ 'SEMESTER' ] ;
  $tpl_vars[ 'CFG'             ]                          =  $this -> CFG -> getConf();
  $tpl_vars[ 'filter'          ]                          =  $I[ 'filter'                          ] -> obj2array ( ) ;
  $tpl_vars[ 'DOC_TYPE'        ]                          =  $_SESSION[ 'DOC_TYPE'                 ];
  $tpl_vars[ 'CONF'            ]                          =  $_SESSION[ 'CFG'      ]['CONF'];
  $tpl_vars[ 'currentElement'  ]                          =  0 ;
  $tpl_vars[ 'maxElement'      ]                          =  1 ;
  $tpl_vars[ 'back_URL'        ]                          = $_SESSION[ 'history' ][ 0 ];
  # deb( $tpl_vars[ 'medium'          ] ,1);
  $this -> RENDERER -> do_template ( 'edit_book.tpl' , $tpl_vars ) ;
  exit(0);
}

###############################################################################################
function annoteNewMedia_showForm( $I )
{
  if ( isset( $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ]))                                #deb('--- 1 ---');
  {
    if ( $I[ 'medium' ] -> get_doc_type_id() != 16)                                                     ##  Erwerbungsvorschlag
    {
      $tmpBook = $_SESSION[ 'books' ][ 'booksHitList' ][ $_SESSION[ 'books' ][ 'currentElement' ] ];
      $I[ 'medium' ] -> array2obj( $tmpBook );
    }
    else if ( $I[ 'medium' ] -> get_title( ) == '' AND $I[ 'medium' ] -> get_doc_type_id( ) != 16 )     ## Kein Titel UND kein Erwerbungsvorschlag
    {
      $tmpBook = $_SESSION[ 'books' ][ 'booksHitList' ][ $I[ 'medium' ] -> get_ppn( ) ];                 ## Metadaten des aus der Trefferliste ausgeählte Mediums
      $I[ 'medium' ] -> array2obj( $tmpBook );
    }
    else
    {
      $_SESSION[ 'books' ][ 'currentElement' ] = 1;
      $_SESSION[ 'books' ][ 'maxElement'     ] = 1;
    }
  }
  
  elseif ( $I[ 'medium' ] -> get_ppn() != '' )                                              # deb('--- 2 ---');
  {
    $tmpBook = $_SESSION[ 'books' ][ 'booksHitList' ][ $I[ 'medium' ] -> get_ppn() ];       # Hitliste kommt aus der OPAC Trefferliste
    $tmpBook = $this -> UTIL -> getOPACDocType( $tmpBook );
    
    $I[ 'medium' ] -> array2obj( $tmpBook );
    unset($_SESSION[ 'books' ][ 'booksHitList' ]);
    $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ] = $tmpBook ;
    $_SESSION[ 'books' ][ 'currentElement' ] = 0;
    $_SESSION[ 'books' ][ 'maxElement'     ] = 1;
    
  }
  else
  {                                                                                         #deb('--- 3 ---');
    $_SESSION[ 'books' ][ 'currentElement' ] = 0;
    $_SESSION[ 'books' ][ 'maxElement'     ] = 0;
  }
    $collection_id = $I[ 'currentCollection' ] -> get_collection_id();
  
    $collection = $this -> SQL -> getCollection( $collection_id );
  
    $tpl_vars[ 'collection'     ] = $collection[ $collection_id] -> obj2array();
    $tpl_vars[ 'medium'         ] = $I[ 'medium'       ] -> obj2array();
    $tpl_vars[ 'user'           ] = $I[ 'currentUser'  ] -> obj2array();
    $tpl_vars[ 'operator'       ] = $I[ 'operator'     ] -> obj2array();
    $tpl_vars[ 'filter'         ] = $I[ 'filter'       ] -> obj2array();
    $tpl_vars[ 'SEMESTER'       ] = array_keys( $_SESSION[ 'CFG' ][ 'SEM' ]);
    $tpl_vars[ 'CONF'           ] = $_SESSION[ 'CFG'      ]['CONF'];
    $tpl_vars[ 'DOC_TYPE'       ] = $_SESSION[ 'DOC_TYPE' ];
    $tpl_vars[ 'currentElement' ] = $_SESSION[ 'books'    ][ 'currentElement' ];
    $tpl_vars[ 'maxElement'     ] = $_SESSION[ 'books'    ][ 'maxElement'     ];
 
    #deb($tpl_vars );
    $this -> RENDERER -> do_template( 'edit_book.tpl' , $tpl_vars );
    exit( 0 );
}



function purchaseSuggestion( $I )
{
  $arr = str_split(sha1 ( rand() ), 20 );
  $KVppn = 'KV' . $arr[ 0 ];

  $_SESSION[ 'books' ][ 'currentCollection'                     ]  =  dc_collection_id;
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'id'             ]  =  0;
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'title'          ]  = 'Kaufvorschlag';
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'ppn'            ]  =  $KVppn;
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'format'         ]  = 'Purchase suggestion';
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'item'           ]  = 'physical';
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'doc_type_id'    ]  =  16;
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'status_id'      ]  =  9;
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'doc_type'       ]  = 'Purchase suggestion';
  $_SESSION[ 'books' ][ 'booksHitList' ][ 0 ][ 'notes_to_staff' ]  = '........';
  $_SESSION[ 'books' ][ 'currentElement'                        ]  =  0;
  $_SESSION[ 'books' ][ 'maxElement'                            ]  =  1;
  $_SESSION[ 'books' ][ 'url'                                   ]  = 'index.php?ppn=1028111126&item=media&loc=1&action=annoteNewMedia&dc_collection_id=VEkuLk1QLlcxOSUyMEVQMUIlMjBCbW4=&mode=new&r=Mg==';

  # deb (  $KVppn  );
  # deb ($_SESSION[ 'books' ]);

  annoteNewMedia_showForm( $I );
}

###############################################################################################
function saveMediaMetaData( $I )
{
#deb($I[ 'medium' ] ,1);

  if (  $I[ 'operator' ] -> item == 'media'   AND $I[ 'medium' ] -> get_shelf_remain() == '0' )   #  Bei physischem Medium Keine Auswahl getroffen, ob Literaturhinweis oder Handapparat
  {
    $I[ 'operator' ] -> set_msg            ( 'shelf_remain' );
    $I[ 'operator' ] -> set_mode           ( 'new' );
    $this -> annoteNewMedia_showForm( $I );
  }

  if ( $I[ 'medium' ] -> get_doc_type_id () == '16' )                                                      ## Kaufvorschlag
  {
    $_SESSION ['books']['booksHitList'][0] = $I[ 'medium' ]->obj2array() ;
  }

 




  if ( $I[ 'medium' ] -> get_id() == 0 )                            ##  NEUES MEDIUM
  {
    $ppn = $I[ 'medium' ]->get_ppn() ;
    foreach($_SESSION ['books']['booksHitList'] as $m)
    {
      if ($m['ppn'] == $ppn)
      { $I[ 'medium' ] -> set_leader       ( $m[ 'leader'        ]  ); ## Datensatz aus dem Bibkatlog wird übernommen (zu den manuellen Metadaten-Einträgen)
        $I[ 'medium' ] -> set_item         ( $m[ 'item'          ]  );
        $I[ 'medium' ] -> set_doc_type     ( $m[ 'doc_type'      ]  );
        $I[ 'medium' ] -> set_physicaldesc ( $m[ 'physicaldesc'  ]  );
        $I[ 'medium' ] -> set_collection_id( $m[ 'collection_id' ]  );
        $I[ 'medium' ] -> set_state_id     ( $m[ 'state_id'      ]  );
        $I[ 'medium' ] -> set_format       ( $m[ 'format'        ]  );

        if ( $I[ 'medium' ] -> get_item () == 'online' )                                                         ## Bei ONLINE Medien
        { $I[ 'medium' ] -> set_shelf_remain ( 3 );                                                              ## ist der Medienort: 'Online' ;) , Status 3
          $I[ 'medium' ] -> set_state_id     ( 3 );                                                              ## und der Status wird 'aktiv'         , Status 3
        }
              
        #-----------------------------------------------------------------------------------------------------
       # if ( $I[ 'medium' ] -> get_shelf_remain () == '0'  OR $I[ 'medium' ] -> get_shelf_remain () == '' )      ##  Wenn kein Medien'ORT' angeklickt wurde, dann bleibt dass Medium im Regal (status: 2)
       # { $I[ 'medium' ] -> set_shelf_remain ( 2 );                                                              ##  ist der Medienort: 'verbleibt im Regal' ;) , Status 2
       #   $I[ 'medium' ] -> set_state_id     ( 3 );                                                              ##  und der Status wird 'aktiv'         , Status 3
       # }

        if ( $I[ 'medium' ] -> get_shelf_remain () == '1'  )                                                ##  Wenn Medien'ORT' 'Semesterapparat' (status: 1)
        {                                                                                                        ##  ist der Medienort: 'verbleibt im Regal' ;) , Status 1
          $I[ 'medium' ] -> set_state_id     ( 1 );                                                              ##  und der Status wird 'aktiv'         , Status 3
        }

        else if ( $I[ 'medium' ] -> get_shelf_remain () == '2'  )                                                ##  Wenn Medien'ORT' 'verbleibt im Regal' (status: 2)
        {                                                                                                        ##  ist der Medienort: 'verbleibt im Regal' ;) , Status 2
          $I[ 'medium' ] -> set_state_id     ( 3 );                                                              ##  und der Status wird 'aktiv'         , Status 3
        }

        else if ( $I[ 'medium' ] -> get_shelf_remain () == '4'  )                                                ##  Wenn Medien'ORT' 'Scanservice' (status: 2)
        {                                                                                                        ##  ist der Medienort: 'verbleibt im Regal' ;) , Status 2
          $I[ 'medium' ] -> set_state_id     ( 1 );                                                              ##  und der Status wird 'aktiv'         , Status 3
          $I[ 'medium' ] -> set_notes_to_staff  ( "[SCANSERVICE]\n " .   $I[ 'medium' ] -> get_notes_to_staff() ) ;
        }

        else if  ( $I[ 'medium' ] -> get_shelf_remain () == '5'  )                                               ##  Wenn kein Medium aus externer Bibliothek ist
        {                                                                                                        ##  ist der Medienort: 'verbleibt im Regal' ;) , Status 2
          $I[ 'medium' ] -> set_state_id     ( 3 );                                                              ##  und der Status wird 'aktiv'         , Status 3
        }
  
        if ( $I[ 'medium' ] -> get_doc_type_id () == '6' )                                                      ## Artikel
        {
          $I[ 'medium' ] -> set_shelf_remain ( 2 );                                                             ## ist der Medienort: Bibliothek
          $I[ 'medium' ] -> set_state_id     ( 3 );                                                              ## und der Status wird 'aktiv'         , Status 3
        }
        
        if ( $I[ 'medium' ] -> get_doc_type_id () == '16' )                                                      ## Kaufvorschlag
        {
          $I[ 'medium' ] -> set_doc_type ( $_SESSION[ 'DOC_TYPE' ][ 16 ][ 'description' ] );
          $I[ 'medium' ] -> set_in_SA    ( $_SESSION[ 'DOC_TYPE' ][ 16 ][ 'SA-ready'    ] );
          $I[ 'medium' ] -> set_item     ( $_SESSION[ 'DOC_TYPE' ][ 16 ][ 'item'        ] );
          # $I[ 'medium' ] -> set_shelf_remain ( 2 );                                                            ## ist der Medienort:
          $I[ 'medium' ] -> set_state_id     ( 9 );                                                              ## und der
          $_SESSION ['books']['booksHitList'][0] = $I[ 'medium' ]->obj2array() ;
        }
 
        $I[ 'medium' ] -> set_id            ( '' );
        $I[ 'medium' ] -> set_collection_id ( $I[ 'currentCollection' ] -> get_collection_id () );

        break;
        }
      }
      $this->SQL->initMediaMetaData ( $I[ 'medium' ] );
  }
  else
  {
    $this -> SQL-> updateMediaMetaData( $I[ 'medium' ]);                                                                   /* Metadaten des neuen Mediums speichern */
  }

  if( $_SESSION['books'][ 'currentElement'  ] <   $_SESSION['books'][ 'maxElement'  ] -1  )
  {
    $_SESSION['books'][ 'currentElement'  ] ++ ;
    $url = $_SESSION[ 'books' ][ 'url' ];
  }
  else
  {
    $url = "index.php?item=collection&action=show_collection&dc_collection_id=".$I[ 'currentCollection' ] -> get_dc_collection_id()."&r=".$I[ 'currentUser' ] -> get_role_id();
  }
  $this -> RENDERER -> doRedirect( $url );
  exit(0);
}


###############################################################################################
function saveNewMediaSuggest ( $I )
{
  $book[ 'edition'         ] = "";
  $book[ 'year'            ] = "";
  $book[ 'journal'         ] = "";
  $book[ 'volume'          ] = "";
  $book[ 'pages'           ] = "";
  $book[ 'publisher '      ] = "";
  $book[ 'ppn'             ] = "";
  $book[ 'url'             ] = "";
  $book[ 'relevance'       ] = "";
  $book[ 'doc_type_id'     ] = 1;  # BUCH
  $book[ 'state_id'        ] = 9;  # Bestellvorschlag
  $book[ 'notes_to_staff'  ] = "Erwerbungsvorschlag\n".$I[ 'W' ][ 'notes_to_staff' ];

  if (isset ( $I[ 'W' ][ 'collection_id'    ] )) { $book[ 'collection_id'    ]   = $I[ 'W' ][ 'collection_id' ];   }
  if (isset ( $I[ 'W' ][ 'title'            ] )) { $book[ 'title'            ]   = $I[ 'W' ][ 'title' ];           }
  if (isset ( $I[ 'W' ][ 'author'           ] )) { $book[ 'author'           ]   = $I[ 'W' ][ 'author' ];          }
  if (isset ( $I[ 'W' ][ 'ISBN'             ] )) { $book[ 'ISBN'             ]   = $I[ 'W' ][ 'ISBN' ];            }
  if (isset ( $I[ 'W' ][ 'signature'        ] )) { $book[ 'signature'        ]   = $I[ 'W' ][ 'signature' ];       }
  if (isset ( $I[ 'W' ][ 'notes_to_studies' ] )) { $book[ 'notes_to_studies' ]   = $I[ 'W' ][ 'notes_to_studies' ];}

  $_SESSION[ 'work' ][ 'document_id' ] =   $this -> SQL -> initMediaMetaData( $book );                                                                   /* Metadaten des neuen Mediums speichern */

  $url = "index.php?item=collection&action=show_collection&dc_collection_id=".$I[ 'currentCollection' ]->get_dc_collection_id()."&r=".$I[ 'currentUser' ]->get_role_id();

  $this -> RENDERER -> doRedirect( $url );
}




###############################################################################################
# ---------------------------------------------------------------------------------------------
function purchase_suggestion( $I )
{
 
  $collection_id = $I[ 'currentCollection' ] -> get_collection_id();
  $collection    = $this -> SQL -> getCollection ( $collection_id );
 

  $arr = str_split(sha1 ( rand() ), 20 );
  $KVppn = 'KV' . $arr[ 0 ];

  $I[ 'medium' ] -> set_title ( 'Kaufvorschlag' );
  $I[ 'medium' ] -> set_notes_to_staff ( $I[ 'operator' ] -> get_msg() );
  $I[ 'medium' ] -> set_doc_type_id ( 16 );
  $I[ 'medium' ] -> set_doc_type ( $_SESSION[ 'DOC_TYPE' ][ 16 ][ 'description' ] );
  $I[ 'medium' ] -> set_in_SA    ( $_SESSION[ 'DOC_TYPE' ][ 16 ][ 'SA-ready'    ] );
  $I[ 'medium' ] -> set_item     ( $_SESSION[ 'DOC_TYPE' ][ 16 ][ 'item'        ] );
  $I[ 'medium' ] -> set_ppn      ( $KVppn );
  $I[ 'medium' ] -> set_state_id ( 9 );

 
  $tpl_vars[ 'collection'      ]            =  $collection[  $collection_id          ] -> obj2array ( );
  $tpl_vars[ 'medium'          ]            =  $I[ 'medium'                          ] -> obj2array ( );
  $tpl_vars[ 'user'            ]            =  $I[ 'currentUser'                     ] -> obj2array ( );
  $tpl_vars[ 'book'            ]            =  $I[ 'medium'                          ] -> obj2array ( );
  $tpl_vars[ 'operator'        ]            =  $I[ 'operator'                        ] -> obj2array ( );
  $tpl_vars[ 'operator'        ][ 'mode' ]  =  "suggest";
  $tpl_vars[ 'filter'          ]            =  $I[ 'filter'                          ] -> obj2array ( ) ;
  $tpl_vars[ 'SEMESTER'        ]            =  array_keys( $_SESSION[ 'CFG' ][ 'SEM' ] );                                      # $conf[ 'SEMESTER' ] ;
  $tpl_vars[ 'CFG'             ]            =  $this -> CFG -> getConf();
  $tpl_vars[ 'DOC_TYPE'        ]            =  $_SESSION[ 'DOC_TYPE' ];
  $tpl_vars[ 'currentElement'  ]            =  0 ;
  $tpl_vars[ 'maxElement'      ]            =  1 ;
  $tpl_vars[ 'CONF'            ]            = $_SESSION[ 'CFG'      ]['CONF'];
  $tpl_vars[ 'back_URL'        ]            = $_SESSION[ 'history'  ][ 0 ];
  $this -> RENDERER -> do_template ( 'edit_book.tpl' , $tpl_vars ) ;

  exit(0);
}

/*
     Anzeige Forumulare:
 * - Suche im Katalog
 * - Anzeige der Trefferliste
 * - Anzeige des ausgwählten Mediums + eingabe der Metadaten
 * - Speichern des Mediums in den SA
 */




###############################################################################################
  function searchMediaOnLibraryServer( $I )  ## -- OPAC --
  {
    $bk = array();
  
    $toSearch[ 'title'     ] = $I[ 'medium' ] -> get_title();
    $toSearch[ 'author'    ] = $I[ 'medium' ] -> get_author();
    $toSearch[ 'signature' ] = $I[ 'medium' ] -> get_signature();
  
    $books = $this -> getHitList( $toSearch );
 
    $maxhits = $books[ 'maxRecords' ];
    $hits    = $books[ 'hits'       ];
    
    if ( $hits > 0 )
    {
      foreach ( $books[ 'hitlist' ] as $key => $b )
      {
        $b -> calcDocTypeID();
        $b -> calcDocType();
        $bk[ $key ] = $b -> obj2array();
      }
      
      unset( $_SESSION[ 'books' ] );
      $_SESSION[ 'books' ][ 'booksHitList'   ] = $this -> UTIL -> xml2array( $bk );
      $_SESSION[ 'books' ][ 'currentElement' ] = 0;
      $_SESSION[ 'books' ][ 'maxElement'     ] = 1;
      $this -> showHitList( $I, $bk, $hits, $maxhits );
    } else
      {
        $this -> COLLMGR -> showNewMediaForm( $I );
 
    }   ## -- Suche ergab keinen Treffer
  }
  
  
  
  # ---------------------------------------------------------------------------------------------
function getHitList( $searchQuery )
{
  $error = false;

#--------------------------------
  $conf = $this -> CFG -> getConf ();

  $cat          = $conf[ 'SRU' ][ 'cat'            ]; #'opac-de-18-302';  # HIBS
  $recordSchema = $conf[ 'SRU' ][ 'recordSchema2'  ]; #'turbomarc';       # turbomarc / mods / marcxml
  $maxRecords   = $conf[ 'SRU' ][ 'maxRecords'     ]; # 50;
  $catURL       = $conf[ 'SRU' ][ 'catURL'         ]; #'http://sru.gbv.de/';
#--------------------------------

  $query = $this->build_sru_query ( $searchQuery );

  $datasourceURL = $catURL . $cat . '?version=1.2&operation=searchRetrieve&query=' . $query . '+sortby+year%2Fdescending&maximumRecords=' . $maxRecords . '&recordSchema=' . $recordSchema;

  try                      { $page  = file_get_contents ( $datasourceURL );   }
  catch ( Exception $e )   { $error = true;                                   }

  if ( $error )
  {
  }

  else
  {
    $sxm = simplexml_load_string ( str_replace ( array( 'diag:' , 'zs:' ) , '' , $page ) );

    $hits = $sxm -> numberOfRecords;  # Anzahl Treffer
   
    if ( isset ( $sxm -> records -> record ) )
    foreach ( $sxm -> records -> record as $rec )
    {
      $m = new Medium();

      ## ------------------ TURBOMARC ----------------------------------------------------------------------------------
      if ( $recordSchema == 'turbomarc' )
      {
        $r = $rec -> recordData -> r;
 
        $ISBN = '';
        foreach ( $r -> d020 as $i )
        {
          $ISBN .= $i -> s9 . "<br/>";
        }
        $ISBN = substr ( $ISBN , 0 , -5 );  # letztes '<br/>' wieder entfernen

        $m -> set_ISBN           ( trim ( $ISBN ) );
        $m -> set_ppn            ( trim ( $r -> c001 ) );
        $m -> set_title          ( trim ( $r -> d245 -> sa ) );
        $m -> set_author         ( trim ( $r -> d100 -> sa ) );
        $m -> set_signature      ( trim ( $r -> d954 -> sd ) );
        $m -> set_physicaldesc   ( trim ( $r -> d300 -> sa ) );
        $m -> set_publisher      ( trim ($r->d264->sb . ' ' . $r->d264->sa . ' ' . $r->d264->sc ) );

        if ( $m -> get_publisher () == '' ) { $m -> set_publisher ( trim ( $r -> d260 -> sb . ' ' . $r -> d260 -> sa . ' ' . $r -> d260 -> sc . '' ) );  }
        if ( $m -> get_author    () == '' ) { $m -> set_author    ( trim ( $this -> getPersons ( $r -> d700 ) ) );      }
  
        if( strstr  (trim ( $r -> d300 -> sa ), 'Online'))
        {
          $m -> set_doc_type_id( '4') ;
          $m -> set_doc_type( $_SESSION['DOC_TYPE'][4]['doc_type']); ;
        }
        else {
          $m -> set_doc_type_id( '1') ;
          $m -> set_doc_type( $_SESSION['DOC_TYPE'][1]['doc_type']); ;
        }
 
        $ret['hitlist'][ $m->get_ppn () ] = $m;
      }


      ## ------------------ MARC21 -------------------------------------------------------------------------------------
      elseif ( $recordSchema == 'marcxml' )
      {
        $r = $rec->recordData->record;
        foreach ( $r->controlfield as $a => $b ) {
          if ( $b[ 'tag' ] == '001' ) {
            $PPN = (string) $b;
          }
        }

        $ISBN = '';
        foreach ( $r->d020 as $i ) {
          $ISBN .= $i->s9 . "<br/>";
        }
        $ISBN = substr ( $ISBN , 0 , -5 );  # letztes '<br/>' wieder entfernen


        foreach ( $r->datafield as $a => $b ) {

          if ( $b->attributes () == '100' ) {$author = $b;}
          if ( $b->attributes () == '245' ) {$title = $b;}
          if ( $b->attributes () == '300' ) {$physicaldesc = $b;}
          if ( $b->attributes () == '264' ) {$publisher = $b;}
          if ( $b->attributes () == '020' ) {
            foreach ( $b->subfield as $sub => $val )
            { if ( $val[ 'code' ][ 0 ] == 9 ) { $ISBN[] = (string) $val[ 0 ]; }
            }
          }
       }


      $medium[ $PPN ][ 'PPN' ] = $PPN;

      $medium[ $PPN ][ 'title' ] = $title->subfield;
      $medium[ $PPN ][ 'author' ] = $author->subfield;
      $medium[ $PPN ][ 'PPN' ] = (string) $PPN;
      $medium[ $PPN ][ 'physicaldesc' ] = $physicaldesc->subfield;
      #  $medium[(string)$PPN][ 'SIG'          ] = $signatur;
      $medium[ $PPN ][ 'publisher' ] = $publisher->subfield;
      $medium[ $PPN ][ 'ISBN' ] = $ISBN;


      $m->set_title ( trim ( $medium[ $PPN ][ 'title' ] ) );
      $m->set_author ( trim ( $medium[ $PPN ][ 'author' ] ) );
      # $m->set_signature   ( trim ( $r -> d954 -> sd   ) );
      $m->set_ppn ( trim ( $medium[ $PPN ][ 'PPN' ] ) );
      $m->set_physicaldesc ( trim ( $medium[ $PPN ][ 'physicaldesc' ] ) );
      # $m->set_ISBN        ( trim ( $ISBN              ) );
      $m->set_publisher ( trim ( $r->d264->sb . ' ' . $r->d264->sa . ' ' . $r->d264->sc ) );

        if ( $m->get_publisher () == '' ) {
          $m->set_publisher ( trim ( $r->d260->sb . ' ' . $r->d260->sa . ' ' . $r->d260->sc . '' ) );
        }
        if ( $m->get_author () == '' ) {
          $m->set_author ( trim ( $this->getPersons ( $r->d700 ) ) );
        } # Wenn im Datensatz kein Autor vorhanden ist, wird dafür 'Peronen' genommen
      }
      ## --------------------------------------------------------------------------------------------------------------

      ## ------------------ MODS --------------------------------------------------------------------------------------
      elseif ( $recordSchema == 'mods' )
      {
        $r = $rec->recordData->mods;
        $authors = '';
        foreach ( $r->name as $names => $name )
        {
          $link = false;
          $n = $name->namePart[ 0 ] . ", " . $name->namePart[ 1 ];                                                                                    # author
          foreach ( $name->attributes () as $a => $b )
          {
            if ( $a == 'valueURI' )
            {
              $link = true;
              break;
            }
          }                                          # autor has descr.link?      if ($link == true ) { $authors .= '<a target="_blank" class="author" href="'.$b.'">'.$n.'</a>, '; }  else {$authors .= $n.", "; }     # list of autors with links
          $authors .= $n . '; ';
        }
        if ( isset( $r->relatedItem[ 0 ]->location->url ) )
        {
          $directory = ( ( string ) $r->relatedItem[ 0 ]->location->url );
        }
        else
        {
          $directory = '';
        }# Inhaltsverzeichnis als PDF / oder Coverpic als jpg

        $book[ 'titleNonSortPart' ] = ( ( string ) $r -> titleInfo -> nonSort              );  # Buchtitel (unsortierter Teil)
        $book[ 'title'            ] = ( ( string ) $r -> titleInfo -> title                );  # Buchtitel
        $book[ 'subTitle'         ] = ( ( string ) $r -> titleInfo -> subTitle             );  # Subtitel
        $book[ 'publisher'        ] = ( ( string ) $r -> originInfo -> publisher           );  # Verlag
        $book[ 'edition'          ] = ( ( string ) $r -> originInfo -> edition             );  # Edition
        $book[ 'dateissued'       ] = ( ( string ) $r -> originInfo -> dateIssued          );  # Jahr
        $book[ 'author'           ] = $authors;                                                # list of autors with links
        $book[ 'signature'        ] = ( string ) ' ';                                          # Signature
        $book[ 'ppn'              ] = ( ( string ) $r -> recordInfo -> recordIdentifier    );  # PPN
        $book[ 'physicaldesc'     ] = ( ( string ) $r -> physicalDescription -> form[ 0 ]  );  # Form: electronic /  print
        $book[ 'extend'           ] = ( ( string ) $r -> physicalDescription -> extent     );  # Anzahl Seiten, Speicherplatz (Höhe in cm / kB)
        $book[ 'directory'        ] = $directory;                                              # Inhaltsverzeichnis als PDF

        $ret[ $book[ 'ppn' ] ] =  $book;

      }
      ## ---------------------------------------------------------------------------------------------------------------
     }
     if   ( ( string ) $hits == "" ) { $ret[ 'hits' ] = 0;                }
     else                            { $ret[ 'hits' ] = ( string ) $hits; } ## Erster Datensatz enthalt: Die Anzahl der gefundenen Medien
     $ret[ 'maxRecords' ] = $maxRecords;                                     #  Anzahl der gespeicherten Datensätze
    }


  return $ret;

  }
/*
    function getIMS_pack()
    {

     $imsid = 16;

      $error = false;

#--------------------------------
      $conf = $this->CFG->getConf ();
      $cat = $conf[ 'cat' ]; #'opac-de-18-302';  # HIBS
      $recordSchema = $conf[ 'recordSchema' ]; #'turbomarc';       # turbomarc / mods
      $maxRecords = $conf[ 'maxRecords' ]; # 50;
      $catURL = $conf[ 'catURL' ]; #'http://sru.gbv.de/';
#--------------------------------

      $datasourceURL =  $this -> conf ['VUFIND'][ 'vuFindURL'    ] . "Cart/imsdownload?imsid=$imsid";
      
      try {
        $page = file_get_contents ( $datasourceURL );
      } catch ( Exception $e ) {
        $error = true;
      }

      if ( !$error )
      {
        $sxm = simplexml_load_string ( $page );

        foreach ( $sxm as $r )
        {
          ## ------------- TURBOMARC ---------------
          if ( $recordSchema == 'turbomarc' )
          {
            $m = new Medium();

            $ISBN = '';
            foreach ( $r->d020 as $i )
            {
              $ISBN .= $i->s9 . "<br/>";
            }
            $ISBN = substr ( $ISBN , 0 , -5 );  # letztes '<br/>' wieder entfernen

            #turbomarc

            $m->set_title ( trim ( $r->d245->sa ) );
            $m->set_author ( trim ( $r->d100->sa ) );
            $m->set_signature ( trim ( $r->d954->sd ) );
            $m->set_ppn ( trim ( $r->c001 ) );
            $m->set_physicaldesc ( trim ( $r->d300->sa ) );
            $m->set_ISBN ( trim ( $ISBN ) );
            $m->set_publisher ( trim ( $r->d264->sb . ' ' . $r->d264->sa . ' ' . $r->d264->sc ) );
            $m->set_ISBN ( trim ( $ISBN ) );
            # $m->set_subTitle     ( trim ( $r -> d245 -> sb   ) );
            # $m->set_edition      ( trim ( $r -> d250 -> sa   ) );
            # $m->set_directory    ( trim ( $r -> d856 -> su   ) );


            
         #    $book[ 'title'            ]  =  $r -> d245 -> sa   .'';
         #    $book[ 'subTitle'         ]  =  $r -> d245 -> sb   .'';
         #    $book[ 'author'           ]  =  $r -> d100 -> sa   .'';   if ($book[ 'author' ]  == '' ) { $book[ 'author'  ]  = $this -> getPersons( $r->d700);} # Wenn im Datensatz kein Autor vorhanden ist, wird dafür 'Peronen' genommen
         #    $book[ 'edition'          ]  =  $r -> d250 -> sa   .'';
         #    $book[ 'signature'        ]  =  $r -> d954 -> sd   .'';
         #    $book[ 'ppn'              ]  =  $r -> c001         .'';
         #    $book[ 'directory'        ]  =  $r -> d856 -> su   .'';
         #    $book[ 'physicaldesc'     ]  =  $r -> d300 -> sa   .'';
         #    $book[ 'ISBN'             ]  =  '';
         #   $book[ 'publisher'        ]  =  $r -> d264 -> sb   .' '.  $r -> d264 -> sa .' '. $r -> d264 -> sc.'';
       
            if ( $m->get_publisher () == '' )
            {
              $m->set_publisher ( trim ( $r->d260->sb . ' ' . $r->d260->sa . ' ' . $r->d260->sc . '' ) );
            }
            if ( $m->get_author () == '' )
            {
              $m->set_author ( trim ( $this->getPersons ( $r->d700 ) ) );
            } # Wenn im Datensatz kein Autor vorhanden ist, wird dafür 'Peronen' genommen



            $ret[ $m->get_ppn () ] = $m;
          }

          ## ------------- MODS ---------------
          else if ( $recordSchema == 'mods' )
          {
            $r = $rec->recordData->mods;
            $authors = '';
            foreach ( $r->name as $names => $name ) {
              $link = false;
              $n = $name->namePart[ 0 ] . ", " . $name->namePart[ 1 ];                                                                                    # author
              foreach ( $name->attributes () as $a => $b ) {
                if ( $a == 'valueURI' ) {
                  $link = true;
                  break;
                }
              }                                          # autor has descr.link?      if ($link == true ) { $authors .= '<a target="_blank" class="author" href="'.$b.'">'.$n.'</a>, '; }  else {$authors .= $n.", "; }     # list of autors with links
              $authors .= $n . '; ';
            }
            if ( isset( $r->relatedItem[ 0 ]->location->url ) ) {
              $directory = ( ( string ) $r->relatedItem[ 0 ]->location->url );
            } else {
              $directory = '';
            }# Inhaltsverzeichnis als PDF / oder Coverpic als jpg

            $book[ 'titleNonSortPart' ] = ( ( string ) $r->titleInfo->nonSort );            # Buchtitel (unsortierter Teil)
            $book[ 'title' ] = ( ( string ) $r->titleInfo->title );                         # Buchtitel
            $book[ 'subTitle' ] = ( ( string ) $r->titleInfo->subTitle );                   # Subtitel
            $book[ 'publisher' ] = ( ( string ) $r->originInfo->publisher );                # Verlag
            $book[ 'edition' ] = ( ( string ) $r->originInfo->edition );                    # Edition
            $book[ 'dateissued' ] = ( ( string ) $r->originInfo->dateIssued );              # Jahr
            $book[ 'author' ] = $authors;                                                   # list of autors with links
            $book[ 'signature' ] = ( string ) ' ';                                          # Signature
            $book[ 'ppn' ] = ( ( string ) $r->recordInfo->recordIdentifier );               # PPN
            $book[ 'physicaldesc' ] = ( ( string ) $r->physicalDescription->form[ 0 ] );    # Form: electronic /  print
            $book[ 'extend' ] = ( ( string ) $r->physicalDescription->extent );             # Anzahl Seiten, Speicherplatz (Höhe in cm / kB)
            $book[ 'directory' ] = $directory;                                              # Inhaltsverzeichnis als PDF

            $ret[ $book[ 'ppn' ] ] = $book;
          }

        }

        $ret[ 'hits' ] = 3;
        $ret[ 'maxRecords' ] = 50;


        ###$ret[ 'hits'       ]  = ( string ) $hits;  ## Erster Datensatz enthalt: Die Anzahl der gefundenen Medien
        ###$ret[ 'maxRecords' ]  = $maxRecords;      #  Anzahl der gespeicherten Datensätze
      }

      $url = "https://localhost/moodle/mod/else/view.php?id=$imsid";
      $this->RENDERER->doRedirect ( $url );

      return $ret;
    }
*/
# ---------------------------------------------------------------------------------------------
    function getSignature( $ppn = NULL )
    {
      #--------------------------------
      $conf = $this->CFG->getConf ();;
      $cat = $conf[ 'cat' ]; #'opac-de-18-302';  # HIBS
      $catURL = $conf[ 'catURL' ]; #'http://sru.gbv.de/';
      #--------------------------------

      $datasource = $catURL . $cat . '?version=1.1&operation=searchRetrieve&query=pica.ppn=' . $ppn . '&maximumRecords=1&recordSchema=turbomarc';
      $page = file_get_contents ( $datasource );
      $sxm = simplexml_load_string ( str_replace ( 'zs:' , '' , $page ) );
      $book = $sxm->records->record->recordData->r;
      return $book->d954->sd . ""; /* Signatur */
    }

# ---------------------------------------------------------------------------------------------
    function getPPN( $signature )
    {
      #--------------------------------
      $conf = $this->CFG->getConf ();
      $cat = $conf[ 'cat' ]; #'opac-de-18-302';  # HIBS
      $catURL = $conf[ 'catURL' ]; #'http://sru.gbv.de/';
      #--------------------------------

      $datasource = $catURL . $cat . '?version=1.1&operation=searchRetrieve&query=pica.sgn=' . $signature . '&maximumRecords=1&recordSchema=turbomarc';
      $page = file_get_contents ( $datasource );
      $sxm = simplexml_load_string ( str_replace ( 'zs:' , '' , $page ) );
      $book = $sxm->records->record->recordData->r;
      return $book->c001 . "";  /* PPN  */
    }

# ---------------------------------------------------------------------------------------------
    function build_sru_query( $search )
    {
      $query = array();
      if ( ( isset( $search[ 'signature' ] ) AND ( $search[ 'signature' ] != '' ) ) ) {
        $search[ 'signature' ] = str_replace ( '.' , '?' , $search[ 'signature' ] );
        $query[] = 'pica.sgb="' . $search[ 'signature' ] . '"';
      }
      if ( ( isset( $search[ 'author' ] ) AND ( $search[ 'author' ] != '' ) ) ) {
        $query[] = 'pica.per="' . $search[ 'author' ] . '"';
      }
      if ( ( isset( $search[ 'title' ] ) AND ( $search[ 'title' ] != '' ) ) ) {
        $query[] = 'pica.tit="' . $search[ 'title' ] . '"';
      }

      $listSize = sizeof ( $query );
      if ( $listSize == 0 ) {
        $ret = '';
      } else if ( $listSize >= 1 ) {
        $ret = $query[ 0 ];
      }
      if ( $listSize >= 2 ) {
        for ( $i = 1 ; $i < $listSize ; $i++ ) {
          $ret .= ' AND ' . $query[ $i ];
        }
      }

      return urlencode ( $ret );
    }

    function showSA( $I )
    {
      $docID = $I[ 'W' ] [ 'document_id' ];

      $tpl_vars[ 'errors_info' ][] = '';
      $tpl_vars[ 'doc_type'    ] = $_SESSION[ 'DOC_TYPE' ];
      $tpl_vars[ 'MEDIA_STATE' ] = $_SESSION[ 'MEDIA_STATE' ];
      $tpl_vars[ 'FACHBIB'     ] = $_SESSION[ 'FACHBIB' ];
      $tpl_vars[ 'department'  ] = $_SESSION[ 'DEP_2_BIB' ];
      $tpl_vars[ 'operator'    ] = $_SESSION[ 'operator' ];

      $tpl_vars[ 'ACTION_INFO' ] = $_SESSION[ 'ACTION_INFO' ];

      $tpl_vars[ 'CFG'         ] = $this->CFG->getConf ();
      $tpl_vars[ 'work'        ] = $I[ 'W' ];
      $tpl_vars[ 'user'        ] = $I[ 'U' ];

      #  $_SESSION[ 'operator' ][ 'off' ] = true;
      $ci = $this->SQL->getCollection ( $I[ 'W' ][ 'collection_id' ] );
      $tpl_vars[ 'ci' ] = array_pop ( $ci );
      #  $_SESSION[ 'operator' ][ 'off' ] = false;

      $tpl_vars[ 'collection' ] = $ci;
      $tpl_vars[ 'di' ] = $this->SQL->getDocumentInfos ( $docID );
      $tpl_vars[ 'di' ][ 'doc_type' ] = $tpl_vars[ 'doc_type' ][ $tpl_vars[ 'di' ][ 'doc_type_id' ] ][ 'doc_type' ];

      $book[ 'doc_type_id' ] = $tpl_vars[ 'di' ][ 'doc_type_id' ];

      $dt = $this->CFG->C->getDocType ( $book );

      $tpl_vars[ 'di' ] = array_merge ( $tpl_vars[ 'di' ] , $dt );

      $conf = $this->CFG->getConf ();
      $tpl_vars[ 'work' ][ 'catURLlnk' ] = $conf[ 'catURLlnk' ];


      $this->RENDERER->do_template ( 'SA.tpl' , $tpl_vars , false );
    }

# ---------------------------------------------------------------------------------------------
    function getFullUserName( $u )
    {
      return $u[ 'forename' ] . " " . $u[ 'surname' ]; /*  forename, surname */
    }

    /*
    # ---------------------------------------------------------------------------------------------
    function cmp_coll ( $a , $b )                          # callback function for usort()
    {
      $key_a = $a[ 'title' ] . $a[ 'collection_no' ] ;
      $key_b = $b[ 'title' ] . $b[ 'collection_no' ] ;
      return strcmp ( $key_a , $key_b ) ;
    }
    */

###############################################################################################
    function showMailForm( $I )
    {
      # $doc_info = $this -> SQL -> getDocumentInfos ( $I[ 'W' ][ 'document_id' ] ) ;
      # $CI       = $this -> SQL -> getCollection ( $doc_info[ 'collection_id' ] ) ;
      # $col_info  = $CI[ $doc_info[ 'collection_id' ] ] ;
      # $user_info = $col_info [ 'user_info' ] ;

      $collection_id = $I[ 'currentCollection' ] -> get_collection_id ();
      $collection    = $this -> SQL -> getCollection ( $collection_id );

      $tpl_vars[ 'collection' ] = $collection[ $collection_id ]->obj2array ();
      $tpl_vars[ 'medium'     ] = $I[ 'medium'                ]->obj2array ();
      $tpl_vars[ 'user'       ] = $I[ 'currentUser'           ]->obj2array ();
      $tpl_vars[ 'operator'   ] = $I[ 'operator'              ]->obj2array ();
      $tpl_vars[ 'filter'     ] = $I[ 'filter'                ]->obj2array ();
      $tpl_vars[ 'SEMESTER'   ] = array_keys ( $_SESSION[ 'CFG' ][ 'SEM' ] );

      if    ( $tpl_vars[ 'collection' ][ 'Owner' ][ 'sex' ] == 'w' ) { $salutaton = 'Sehr geehrte/r' . $tpl_vars[ 'collection' ][ 'Owner' ][ 'forename' ] . ' ' . $tpl_vars[ 'collection' ][ 'Owner' ][ 'surname' ]; }
      else                                                           { $salutaton = 'Sehr geehrte/r' . $tpl_vars[ 'collection' ][ 'Owner' ][ 'forename' ] . ' ' . $tpl_vars[ 'collection' ][ 'Owner' ][ 'surname' ]; }

      $tpl_vars[ 'salutaton'    ] = $salutaton;
      $tpl_vars[ 'back_URL'     ]                = $_SESSION[ 'history'  ][ 0 ];
      $this -> RENDERER -> do_template ( 'email.tpl' , $tpl_vars );
    }


    # ---------------------------------------------------------------------------------------------


    function send_email( $I )
    {
      $subject = $I[ 'email' ]->set_subject ( 'Ihr ELSE Semesterapparat' );

      $to = $I[ 'email' ]->get_to ();
      $subject = $I[ 'email' ]->get_subject ();
      $message = $I[ 'email' ]->get_mailtext ();

      $header = 'From: ' . $I[ 'email' ]->get_from () . "\r\n";
      $header .= 'Bcc: ' . $I[ 'email' ]->get_bcc () . "\r\n";
      # $header .= 'Reply-To: '     . $I[ 'email' ]->get_replay_to( ) . "\r\n" ;
      $header .= "Mime-Version: 1.0\r\n";

      $header .= "Content-type: text/plain; charset=iso-8859-1";
      $header .= 'X-Mailer: Greetings from ELSE/HIBS - /' . phpversion ();

      $collection_id = $I[ 'currentCollection' ]->get_collection_id ();
      $collection = $this->SQL->getCollection ( $collection_id );
      $medium = $collection[ $collection_id ]->get_media () [ $I[ 'medium' ]->get_id () ];

      $I[ 'medium' ]->set_notes_to_staff ( $medium->get_notes_to_staff () . ' MAIL: ' . $message );

      $r = $I[ 'currentUser' ]->get_role_name ();
      if ( $r == 'staff' OR $r == 'admin' OR $r == 'edit' ) {
        $sendok = mail ( $to , $subject , $message , $header );
      }

      if ( $sendok ) {
        $I[ 'operator' ]->set_msg ( "Mail gesendet <br><br> weiter " );
        $this->SQL->updateMediaMetaData ( $I[ 'medium' ] );
      } else {
        $I[ 'operator' ]->set_msg ( "ERROR: Mail nicht versendet!" );
      }

      $I[ 'operator' ]->set_url ( "index.php?item=collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&action=show_collection&r=" . $I[ 'currentUser' ]->get_role_id () );

      $tpl_vars[ 'collection' ] = $collection[ $collection_id ]->obj2array ();
      $tpl_vars[ 'medium' ] = $I[ 'medium' ]->obj2array ();
      $tpl_vars[ 'user' ] = $I[ 'currentUser' ]->obj2array ();
      $tpl_vars[ 'operator' ] = $I[ 'operator' ]->obj2array ();
      $tpl_vars[ 'filter' ] = $I[ 'filter' ]->obj2array ();
      $tpl_vars[ 'SEMESTER' ] = array_keys ( $_SESSION[ 'CFG' ][ 'SEM' ] );

      $this->RENDERER->do_template ( 'email.tpl' , $tpl_vars );
    }

    # ---------------------------------------------------------------------------------------------
    function showIconList( $I )
    {
      $MEDIA_STATE = $_SESSION[ 'MEDIA_STATE' ];

      $user = $_SESSION[ 'user' ];

      $collection_info = $this->SQL->getCollection ( $I[ 'W' ][ 'collection_id' ] );

      $ci = array_pop ( $collection_info );   ## ??

      $di = $ci[ 'document_info' ][ 0 ];  ## ??

      $tpl_vars[ 'state' ] = $MEDIA_STATE[ $di[ 'state_id' ] ][ 'name' ];
      $tpl_vars[ 'role_encode' ] = $user[ 'role_encode' ];
      $tpl_vars[ 'mode' ] = $user[ 'role_name' ];
      $tpl_vars[ 'dc_collection_id' ] = $ci[ 'dc_collID' ];
      $tpl_vars[ 'item' ] = $di[ 'item' ];
      $tpl_vars[ 'document_id' ] = $di[ 'id' ];
      $tpl_vars[ 'url' ] = $di[ 'url' ];
      $tpl_vars[ 'protected' ] = $di[ 'protected' ];
      $tpl_vars[ 'ppn' ] = $di[ 'ppn' ];
      $tpl_vars[ 'ACTION_INFO' ] = $_SESSION[ 'ACTION_INFO' ];

      $this->RENDERER->do_template ( 'action_button_bar.tpl' , $tpl_vars , 0 );

      # echo date("d.m.Y H:i:s") . ": AjAX-Request wurde erfolgreich ausgeführt :)";

      exit( 0 );
    }

    # ---------------------------------------------------------------------------------------------
    function getPersons( $pers )  ## Wenn im Patensatz im Attribut: "d100 -> sa" keine Eintrag vorhanden ist, wird  Datensatz 'Persons' genommen ("d100 -> sa")
    {
      $ret = '';
      foreach ( $pers as $p ) {
        $ret .= $p->sa . ' ' . $p->sb . '; ';
      }
      $ret = substr ( $ret , 0 , -2 );
      return $ret;
    }

###############################################################################################
    function activateMedia( $I )
    {
   
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 3 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
      #else                                   { $this -> RENDERER -> doRedirect ( $_SESSION[ 'history'   ][1] ); }
    }

###############################################################################################
    function deactivateMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 5 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
    function cancel_release( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 2 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
# ---------------------------------------------------------------------------------------------
    function returnDoneMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 5 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
# ---------------------------------------------------------------------------------------------
    function reviveMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 1 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
# ---------------------------------------------------------------------------------------------
    function acceptMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 2 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
# ---------------------------------------------------------------------------------------------
    function doneMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 3 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
# ---------------------------------------------------------------------------------------------
    function releaseMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 4 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
# ---------------------------------------------------------------------------------------------
    function cancelMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 5 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
    function deleteMedia( $I )
    {
      $this->SQL->setMediaState ( $I[ 'medium' ]->get_id () , 6 );

      $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
      if ( $this->CFG->CFG[ 'ajaxON' ] ) {
        $this->showSA ( $I );
      } else {
        $this->RENDERER->doRedirect ( $url );
      }
    }

###############################################################################################
  function ereaseMedia( $I )
  {
    $this->SQL->deleteMedia ( $I  );
   # $url = "index.php?item=collection&action=show_collection&dc_collection_id=" . $I[ 'currentCollection' ]->get_dc_collection_id () . "&r=" . $I[ 'currentUser' ]->get_role_id ();
    $url = "index.php?item=collection&action=show_media_list&mode=filterState&category=6";
    #deb($url,1);
    if ( $this->CFG->CFG[ 'ajaxON' ] ) {
      $this->showSA ( $I );
    } else {
      $this->RENDERER->doRedirect ( $url );
    }
  }
  
  
}
