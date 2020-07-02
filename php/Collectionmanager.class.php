<?php
class COLLECTIONMANAGER
{  
var $RENDERER;
var $SQL;
var $UTIL;

private $conf;
private $CONST;

# ---------------------------------------------------------------------------------------------
function __construct( $CONFIG, $SQL, $RENDERER, $UTIL )
{
  $this -> conf  = $_SESSION[ 'CFG'   ];
  $this -> CON   = $_SESSION[ 'CON' ];

  $this -> SQL        = $SQL;
  $this -> RENDERER   = $RENDERER;
  $this -> UTIL       = $UTIL;
}

###############################################################################################
function  showMediaList( $I )  ##---------- Medien gefiltert nach Status
{
  ## getAllMediaFromCollection - Liefert alle Medien Daten:

  ## $colID:       0 = ALLE,
  ## $state_id:    1 = neu bestellt, 2 wird bearbeitet, 3 aktiv, 4 wird entfernt, 5 inaktiv, 6 gelöscht, 9 Erwerbvorschlag
  ## $doc_type_id: 1 = Buch, 3, = CD, 4 = E-Book,

  #$I[ 'filter' ] -> set_type( 1 );  ## NUR Semesterappartat Medien/Bücher werden angezeigt

  $collection = $this -> SQL -> getCollection( null,  $I[ 'filter' ]  , true );

  #deb($collection);
  $col_list = null;
  if( isset( $collection ) )  foreach ($collection as $c )   { $col_list[] = $c -> obj2array( );  }

  $tpl_vars[ 'collection'      ]  = $col_list;
  $tpl_vars[ 'user'            ]  = $I[ 'currentUser'                     ] -> obj2array ( );
  $tpl_vars[ 'operator'        ]  = $I[ 'operator'                        ] -> obj2array ( );
  $tpl_vars[ 'medium'          ] =  $I[ 'medium'                          ] -> obj2array ( ) ;
  $tpl_vars[ 'filter'          ]  = $I[ 'filter'                          ] -> obj2array ( )  ;

  $tpl_vars[ 'DOC_TYPE'        ]  = $_SESSION[ 'DOC_TYPE'                 ] ;
  $tpl_vars[ 'MEDIA_STATE'     ]  = $_SESSION[ 'MEDIA_STATE'              ]  ; #
  $tpl_vars[ 'ACTION_INFO'     ]  = $_SESSION[ 'ACTION_INFO'              ]  ; # ===================== ACTION INFO BESSER HIER IM PHP AUSWERTEN, NICHT IM TEMPLATE
  $tpl_vars[ 'CFG'             ]  = $this -> conf                            ; # aus config.class.php
  $tpl_vars[ 'DEP'             ]  = $_SESSION[ 'DEP_2_BIB'                ]  ; # Liste aller Departments (Categories)
  $tpl_vars[ 'FAK'             ]  = $_SESSION[ 'FAK'                      ]  ; # Liste aller Fakultäten
  $tpl_vars[ 'FACHBIB'         ]  = $_SESSION[ 'FACHBIB'                  ]  ; # Liste aller Fachbibs
  $tpl_vars[ 'SEMESTER'        ]  = array_keys( $this -> conf [ 'SEM'  ] ); #

  #deb($tpl_vars,1);

   $this->RENDERER -> do_template( 'collection.tpl', $tpl_vars );
}

# ---------------------------------------------------------------------------------------------
  /*
   * - Liste aller SAs
   * - Admin sieht alle SAs         (und kann alle SA bearbeiten)
   * - Dozent sieht nur eigene SAs  (und kann seine SA bearbeiten)
   * - Studi sieht (noch) alle SAs  (und kann NICHTS bearbeiten)
   *   index.tpl
   *  */

function showCollectionList( $I  ) //  1 ++ Liste der Semesterapparate, sortiert nach Dozenten,  Fakuläten, Departtments, Status
{
  /* ----------------- LISTE DER INPUTPARAMETER  ------------------ */

  #$I[ 'operator' ] -> set_url( $I[ 'operator' ] -> get_history( )[ 1 ]  );                  ##  Link für den "zurück"- Button
  $tpl_vars[ 'collectionList' ]                  = $this -> getAllCollection ( $I )      ;
  $tpl_vars[ 'user'           ]                  = $I[ 'currentUser'                     ] -> obj2array ();
  $tpl_vars[ 'operator'       ]                  = $I[ 'operator'                        ] -> obj2array ();
  $tpl_vars[ 'SEMESTER'       ]    =   array_keys( $this -> conf [ 'SEM'                      ] ); #$conf[ 'SEMESTER' ] ;
  $tpl_vars[ 'html_options'   ][ 'DEP'     ]     = $_SESSION[ 'DEP_2_BIB'                ]; ## $this -> SQL -> getAllDepartments() ;             ## Liste aller Departments (Categories)
  $tpl_vars[ 'html_options'   ][ 'FAK'     ]     = $_SESSION[ 'FAK'                      ]; ## Liste aller Fakultäten
  $tpl_vars[ 'html_options'   ][ 'FACHBIB' ]     = $_SESSION[ 'FACHBIB'                  ]; ## Liste aller Fachbibs
  $tpl_vars[ 'filter'         ]                  = $_SESSION[ 'filter'                   ] ;
  $tpl_vars[ 'ACTION_INFO'    ]                  =  $_SESSION[ 'ACTION_INFO'             ] ;                                 # aus const.php ##===================== ACTION INFO BESSER HIER IM PHP AUSWERTEN, NICHT IM TEMPLATE
  $tpl_vars[ 'CFG'            ]                  = $this -> conf                           ;                                                 # aus config.class.php ##===================== ACTION INFO BESSER HIER IM PHP AUSWERTEN, NICHT IM TEMPLATE
  $tpl_vars[ 'source'         ]                  = 'index.php'                            ;
  $tpl_vars[ 'toLastPage'     ]                  =  true                                  ;
  $tpl_vars[ 'back_URL'       ]  = "#";

  ##-------------------------------------------------------------------------------------------------------------------

  $this -> RENDERER -> do_template ( 'index.tpl' , $tpl_vars , TRUE ) ;
}


###############################################################################################
# ---------------------------------------------------------------------------------------------
    function showNewMediaForm( $I, $toSearch = NULL, $searchHits = 1 )
    {  # deb( $this -> conf);
        $collection_id                                      = $I[ 'currentCollection'               ] -> get_collection_id( );
        $bc_urlID                                           = $_SESSION['bc_urlID'] = $this -> UTIL -> b64en($collection_id.'###'. $I[ 'currentUser']-> get_hawaccount() );
        $collection                                         = $this -> SQL -> getCollection ( $collection_id );
        $tpl_vars[ 'collection'        ]                    = $collection[ $collection_id           ] -> obj2array ( );
        $tpl_vars[ 'user'              ]                    = $I[ 'currentUser'                     ] -> obj2array ( );
        $tpl_vars[ 'operator'          ]                    = $I[ 'operator'                        ] -> obj2array ( );
        $tpl_vars[ 'filter'            ]                    = $I[ 'filter'                          ] -> obj2array ( ) ;
        $tpl_vars[ 'SEMESTER'          ]                    = array_keys( $this -> conf [ 'SEM' ] );                                      # $conf[ 'SEMESTER' ] ;
        $tpl_vars[ 'page'              ]                    = 1;                                                                     # Seite 1 = Eingabemaske für die Suchbegriffe bei der Mediensuche */
        $tpl_vars[ 'searchHits'        ]                    = $searchHits;
        $tpl_vars[ 'book'              ][ 'title'       ]   = $toSearch[ 'title'         ];
        $tpl_vars[ 'book'              ][ 'author'      ]   = $toSearch[ 'author'        ];
        $tpl_vars[ 'book'              ][ 'signature'   ]   = $toSearch[ 'signature'     ];
        $tpl_vars[ 'maxRecords'        ]                    = $this -> conf  [ 'SRU' ][ 'maxRecords' ];
        $tpl_vars[ 'URLID'             ]                    = $bc_urlID;
        $tpl_vars[ 'URL'               ]                    = $this -> conf [ 'SERVER' ][ 'URL' ].'/htdocs/' ;
        $tpl_vars[ 'back_URL'          ]                    = "index.php?item=collection&action=show_collection&dc_collection_id=".$collection[ $collection_id           ]->get_dc_collection_id()."&r=".$I[ 'currentUser'  ]->get_role_id();
        $_SESSION[ 'currentCollection' ] = $collection[ $collection_id ] -> obj2array ( );

        $this -> RENDERER -> do_template ( 'new_book.tpl' , $tpl_vars ) ;
        exit(0);
    }

/*
###############################################################################################
  function showCollectionList( $I )
  {
    $_SESSION[ 'operator' ][ 'off' ] = true;
    $tpl_vars[ 'doc_type'        ]         =  $_SESSION[ 'DOC_TYPE'     ];
    $tpl_vars[ 'user'            ]         = $I[ 'U' ]-> obj2array ();
   # $tpl_vars[ 'work'            ]         = $I[ 'W' ];
    $tpl_vars[ 'collection_info' ]         = $this->SQL->getCollection( $I[ 'C' ][ 'title_short' ] );
    $tpl_vars[ 'MEDIA_STATE'     ]         = $_SESSION[ 'MEDIA_STATE'  ];
    $tpl_vars[ 'FACHBIB'         ]         = $_SESSION[ 'FACHBIB' ]; # $ $this->SQL->getBibInfos();
    $tpl_vars[ 'department'      ]         = $_SESSION[ 'DEP_2_BIB' ];
    $tpl_vars[ 'operator'        ]         = $_SESSION[ 'operator'  ];
    $tpl_vars[ 'errors_info'     ][]       = '';
    $tpl_vars[ 'ACTION_INFO'     ]         = $_SESSION[ 'ACTION_INFO' ];
    $tpl_vars[ 'filter'          ]         = $_SESSION[ 'filter'  ] ;
    $conf = $this -> CFG -> getConf();

    $tpl_vars[ 'SEMESTER'               ]  = $conf[ 'SEMESTER'  ] =  array_keys( $_SESSION[ 'CFG' ][ 'SEM' ] );
    $tpl_vars[ 'CFG'                    ] = $this->CFG->getConf();

    $this->RENDERER->do_template( 'collection_list.tpl', $tpl_vars );
    unset($_SESSION[ 'operator' ][ 'off' ]);
  }

*/

###############################################################################################
  function showCollection( $I )
  {
    $tpl_vars[ 'SEMESTER'               ] = array_keys( $this -> conf [ 'SEM' ] );

    $collection_id                        = $I[ 'currentCollection' ] -> get_collection_id();
    $collection                           = $this -> SQL-> getCollection ( $collection_id );



   # $I[ 'operator' ] -> set_url( $I[ 'operator' ] -> get_history( )[ 1 ]  );                  ##  Link für den "zurück"- Button

    $_SESSION['url']['currentCollection'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

    $tpl_vars[ 'collection'        ][ $collection_id ] =  $collection[ $collection_id ] -> obj2array( );

    $tpl_vars[ 'medium'            ] = $I[ 'medium'                     ] -> obj2array( ) ;
    $tpl_vars[ 'user'              ] = $I[ 'currentUser'                ] -> obj2array( ) ;
    $tpl_vars[ 'operator'          ] = $I[ 'operator'                   ] -> obj2array( ) ;
    $tpl_vars[ 'doc_type'          ] = $_SESSION[ 'DOC_TYPE'            ] ;
    $tpl_vars[ 'MEDIA_STATE'       ] = $_SESSION[ 'MEDIA_STATE'         ] ;
    $tpl_vars[ 'FACHBIB'           ] = $_SESSION[ 'FACHBIB'             ] ;
    $tpl_vars[ 'department'        ] = $_SESSION[ 'DEP_2_BIB'           ] ;
    $tpl_vars[ 'filter'            ] = $_SESSION[ 'filter'              ] ;
    $tpl_vars[ 'ACTION_INFO'       ] = $_SESSION[ 'ACTION_INFO'         ];
    $tpl_vars[ 'DOC_TYPE'          ] = $_SESSION[ 'DOC_TYPE'            ] ;
    $tpl_vars[ 'CFG'               ] = $this -> conf ;
    $tpl_vars[ 'errors_info'       ][] = '';
    $tpl_vars[ 'back_URL'          ]  = $_SESSION[ 'history' ][ 0 ];
   # $tpl_vars[ 'medIndex'          ]  = $_SESSION[ 'medIndex' ];
   # $tpl_vars[ 'back_URL'          ]  = "index.php?item=collection&action=show_collection&dc_collection_id=".$collection[ $collection_id  ] -> get_dc_collection_id()."&r=".$I[ 'currentUser'  ] -> get_role_id();
   # $this -> RENDERER -> do_template( 'collection.tpl', $tpl_vars, ( $I[ 'operator' ] -> get_mode() != 'print' ) );
   # deb( $_SESSION );
   # deb( $tpl_vars ,1   );
   $this -> RENDERER -> do_template( 'collection.tpl', $tpl_vars );
  }

function saveNewCollection( $I )
{
    $this -> SQL -> initCollection( $I[ 'W' ], $I[ 'U' ] );
    $url       = "index.php?collection=0";
    $this -> RENDERER -> doRedirect( $url );
}


###############################################################################################
function editColMetaData( $I )
{
  $collection_id = $I[ 'currentCollection'    ] -> get_collection_id();
  $collection    = $this -> SQL -> getCollection( $collection_id ); #( $colID , $filter , $short = null )

  $tpl_vars[ 'collection'        ]  = $collection[  $collection_id          ] -> obj2array (); ;
  $tpl_vars[ 'user'              ]  = $I[ 'currentUser'                     ] -> obj2array ();
  $tpl_vars[ 'operator'          ]  = $I[ 'operator'                        ] -> obj2array ();

  $tpl_vars[ 'SEMESTER'          ]  = array_keys($this -> conf [ 'SEM' ] );
  $tpl_vars[ 'filter'            ]  = $_SESSION[ 'filter'                   ]  ;
  $tpl_vars[ 'MEDIA_STATE'       ]  = $_SESSION[ 'MEDIA_STATE'              ]  ; #
  $tpl_vars[ 'ACTION_INFO'       ]  = $_SESSION[ 'ACTION_INFO'              ]  ; # aus const.php ##===================== ACTION INFO BESSER HIER IM PHP AUSWERTEN, NICHT IM TEMPLATE
  $tpl_vars[ 'CFG'               ]  = $this -> conf                            ; # aus config.class.php
  $tpl_vars[ 'DEP'               ]  = $_SESSION[ 'DEP_2_BIB'                ]  ; # Liste aller Departments
  $tpl_vars[ 'FAK'               ]  = $_SESSION[ 'FAK'                      ]  ; # Liste aller Fakultäten
  $tpl_vars[ 'FACHBIB'           ]  = $_SESSION[ 'FACHBIB'                  ]  ; # Liste aller Fachbibs
  $tpl_vars[ 'SEMESTER'          ]  = array_keys( $this -> conf [ 'SEM'          ] ); #
  $tpl_vars[ 'back_URL'          ]  = "index.php?item=collection&action=show_collection&dc_collection_id=".$collection[ $collection_id  ] -> get_dc_collection_id()."&r=".$I[ 'currentUser'  ] -> get_role_id();

  $this -> conf[ 'SEMESTER' ] =  array_keys( $this -> conf [ 'SEM' ] );

  foreach ( $this -> conf[ 'SEMESTER'      ] as $semTMP )   {  $semesterA[ $semTMP ] = $semTMP;                     } # Liste aller Semesterkürzel

  foreach ( $_SESSION[ 'FACHBIB'   ] as $FBI    )   {  $bib_info[ $FBI[ 'bib_id' ] ] = $FBI[ 'bib_name' ];  }

  foreach ( $_SESSION[ 'DEP_2_BIB' ] as $DEPA   )                                                           # Nur Departments mit ID > 1000 kommen in die Liste
  { if($DEPA[ 'dep_id' ] < 1000  AND $DEPA[ 'dep_name' ] != 'XXX'  )
    { $departments[ $DEPA[ 'dep_id' ] ] = $DEPA[ 'dep_name' ];
    }
  }

  $tpl_vars[ 'tpl' ][ 'semlist'     ] = $semesterA;
  $tpl_vars[ 'tpl' ][ 'departments' ] = $departments; #$_SESSION[ 'DEP_2_BIB' ];
  $tpl_vars[ 'tpl' ][ 'bib_info'    ] = $bib_info;
  $tpl_vars[ 'tpl' ][ 'role_info'   ] = $this -> SQL -> getRoleInfos('name');

  $this -> RENDERER->do_template ( 'edit_collection.tpl' , $tpl_vars ) ;
  exit(0);
}


function deleteCollection( $I )
{
  trigger_error ( "Deprecated function called: deleteCollection()" , E_USER_NOTICE );
  $I[ 'C' ] = $_SESSION[ 'coll' ];

  $tpl_vars[ 'errors_info'    ][] = '';
  $tpl_vars[ 'ACTION_INFO'    ]   =  $_SESSION[ 'ACTION_INFO'              ];

  $this -> RENDERER -> do_template( 'collection.tpl', $tpl_vars, ( $I[ 'operator' ][ 'mode' ] != 'print' ) );
}

/*

###############################################################################################
function setCollectionState_delete( $I )
{
  trigger_error ( "Deprecated function called: setCollectionState_delete" , E_USER_NOTICE );
  $colInfo = $this -> SQL -> getCollection ( $I[ 'W' ][ 'collection_id' ]);
  foreach ( $colInfo[$I[ 'W' ][ 'collection_id' ]][ 'document_info' ] as $di )
  {
    if ( $di[ 'doc_type_id' ] == 1  )   # Medium ist Buch
    {
       if ($di[ 'state_id' ] ==  1   ) {  $this -> SQL -> setMediaState( $I[ 'W' ][ 'document_id' ], 6 ); }  #  'Neu Bestellt'    -> 'Gelöscht'
       if ($di[ 'state_id' ] ==  2   ) {  $this -> SQL -> setMediaState( $I[ 'W' ][ 'document_id' ], 4 ); }  #  'Wird bearbeitet' -> 'Wird entfernt'
       if ($di[ 'state_id' ] ==  3   ) {  $this -> SQL -> setMediaState( $I[ 'W' ][ 'document_id' ], 4 ); }  #  'aktiv'           -> 'Wird entfernt'
    }
    else if ( $di[ 'doc_type_id' ] == 4  )   # Medium ist Ebook
    {
       $this -> SQL -> setMediaState(  $I[ 'W' ][ 'document_id' ], 6 );
    }
  }
  $this -> SQL -> setCollectionState( $I[ 'W' ][ 'collection_id' ], 6 );
  $this -> RENDERER -> doRedirect( $I[ 'W' ][ 'last_page' ] );
}

###############################################################################################
function setCollectionState_active( $I )
{
  trigger_error ( "Deprecated function called: setCollectionState_active" , E_USER_NOTICE );
  $this->SQL->setCollectionState( $I[ 'W' ][ 'collection_id' ], 3 );
  $this->RENDERER->doRedirect( $I[ 'W' ][ 'last_page' ] );
}

###############################################################################################
function etCollectionState_inactive( $I )
{
  trigger_error ( "Deprecated function called: setCollectionState_inactive" , E_USER_NOTICE );
  $this->SQL->setCollectionState( $I[ 'W' ][ 'collection_id' ], 5 );
  $this->RENDERER->doRedirect( $I[ 'W' ][ 'last_page' ] );
}
*/

/*
function  saveColMetaData( $I )
{
  $url = "index.php?[ $PPN ]=collection&collection_id=".$I[ 'W' ][ 'collection_id' ]."&ro=".$I[ 'U' ][ 'role_encode' ]."&item=collection&action=show_collection";
  $this->RENDERER->doRedirect( $url );
}
*/

###############################################################################################
function updateColMetaData( $I )
{
  $this -> SQL-> updateColMetaData( $I[ 'currentCollection' ] );
  $url = "index.php?item=collection&dc_collection_id=". $I[ 'currentCollection' ] -> get_dc_collection_id() ."&r=".$I[ 'currentUser' ] -> get_role_id() ."&item=collection&action=show_collection";
  $this -> RENDERER -> doRedirect( $url );
}


###############################################################################################
function resortCollection( $I )
{
  $this -> SQL -> updateCollectionSortOrder( $I[ 'currentCollection' ] );
  #$_SESSION['operator']['action'] = '';
  exit(0);
}

###############################################################################################
function setCollectionForNextSem( $I )
{
    $currentSem = $this->UTIL->getCurrentSem();

    $collection_id  = $I[ 'W' ][ 'cid2' ];
    $conf = $this->CFG->getConf();

    $sem =  $this -> conf[ 'SEMESTER' ] ;   #     array( 'S16', 'W16', 'S17'  );

    $collectionData = $this->SQL->getCollectionMetaData( $collection_id );

    if ( isset(  $collectionData[ 'id' ] ) ) # Angeforderter EMIL Raum hatte im vergangenen Semester ein Semesterapparat
    {
        echo "<br/>\r\n-------------------------------------------------------------------------";
        echo "<br/>\r\n".$collectionData[ 'title' ]." von ". $collectionData[ 'user' ][ 'forename' ] ." ". $collectionData[ 'user' ][ 'surname' ]  ;
        echo "<br/>\r\nAngeforderter EMIL Raum hatte im vergangenen Semester ein Semesterapparat";

        $collection_id_NEW    = str_replace( $sem , $currentSem , $collection_id );  ## -> Alle inzwischen vom User selbst wiederhergestellten ELSE SAs

        $collectionDataNew = $this->SQL->getCollectionMetaData( $collection_id_NEW );

        if ( isset( $collectionDataNew[ 'id' ] ) ) # Angeforderter SA wurde schon manuell angelegt
        {
            echo "<br/>\r\nAngeforderter SA existiert schon ";
        }

        if (!isset ($collectionDataNew[ 'm' ][ 'GE' ]) OR $collectionDataNew[ 'm' ][ 'GE' ] == 0 )
        {
            echo "<br/>Angeforderter SA wird aus altem Semester übernommen ";

            ### Alle Medien des alten SA werden in einen neu erstellten SA kopiert.
            ### Bücher im alten phys. SA werden zu Literaturhinweise
            ### Bib Mitarbeiter werden per Mail benachrichtigt

            $SA = $this->SQL->getCollectionMetaData( $collection_id );

            $SA[ 'expiry_date'      ] = $this->UTIL->get_new_expiry_date ();
            $SA[ 'id'               ] = str_replace( $sem , $currentSem ,$collectionData[ 'id'            ]);
            $SA[ 'collection_id'    ] = str_replace( $sem , $currentSem ,$collectionData[ 'collection_id' ]);
            $SA[ 'shortname'        ] = str_replace( $sem , $currentSem ,$collectionData[ 'title_short'   ]);  ## TODO: shortname????
            $SA[ 'title_short'      ] = str_replace( $sem , $currentSem ,$collectionData[ 'title_short'   ]);

            $SA[ 'title'            ] = str_replace( $sem , $currentSem ,$collectionData[ 'title'         ]);  ## TODO: aus ERA-Tool übernehmen!
            $SA[ 'sem'              ] = $currentSem;

            $this->SQL->setCollection( $SA );                                                     # neuer SA in DB geschrieben

            unset($SA);

            $collectionDataNew = $this->SQL->getCollectionMetaData( $collection_id_NEW );             # eben erstellter SA wird aus der DB ausgelesen

            $SAold = $this->SQL->exportCollection( $collection_id  );
            $SAO = explode("\n\r", $SAold);
            array_pop($SAO); # letztes Element ist immer leer, wird deshalb entfernt.

            foreach($SAO as $SAO1)
            {
                $ret3 = $this->SQL->importCollection($collection_id_NEW, $SAO1);
            }

            $collectionDataNew = $this->SQL->getCollectionMetaData( $collection_id_NEW );

            echo "<br/>\nNeuer SA:".$collectionDataNew[ 'title' ]. "<br/>\nbeinhaltet folgende Medien:  ";
            echo "<br/>\nTotal:"  . $collectionDataNew[ 'm' ][ 'GE' ] ;
            echo "<br/>\nSA:"     . $collectionDataNew[ 'm' ][ 'SA' ] . " LH:" . $collectionDataNew[ 'm' ][ 'LH' ] . " EB:" . $collectionDataNew[ 'm' ][ 'EB' ] ;

            #if  ( $collectionDataNew[ 'm' ][ 'SA' ] > 0 )  # SA enthielt ein physikalischer SA
            {
                $this->SQL->setSAToLH( $collectionData ); # Status der Bücher des alten phys. SA ändern: von SA zu LH
                $this->UTIL->sendBIB_SARenewMail( $collectionData, $collectionDataNew );

                # alter, physikalischer SA wird zu LH (LiteraturHinweis)
                # zuständige Ansprechpartnerin wird per mail benachrichtigt.
            }
        }
    }
    else
    {
        echo "<br/>\nAngeforderter EMIL Raum hatte im vergangenen Semester KEINEN Semesterapparat";
    }
}



function lmsDownload( $I )
{
  $conf = $this -> conf;
 
  $url  =  $I[ 'operator' ] -> get_url() ;

  $lmsDownload = explode( '?lmsid=', $url );                                             # deb( $lmsDownload );

  #$lms = explode ( '###', $lms = $this -> UTIL -> b64de( $lmsDownload[ 1 ] ) );          # deb( $lms );

  $url = $url ."&format=".$this -> conf [SRU][ 'recordSchema' ];                                                # deb( $url );
 # deb($url,1);
  
  $medList = $this -> LMSLoader( $url );                                                          # deb( $medList,1 );

  $medList = $this -> UTIL -> xml2array( $medList );

  foreach ( $medList as $med )
  {
    $m = new Medium();

    if( isset( $med[ 'title'       ][ 0 ] ) ) { $m -> set_title         ( trim ( $med[ 'title'        ]  ) ); }
    if( isset( $med[ 'author'      ][ 0 ] ) ) { $m -> set_author        ( trim ( $med[ 'author'       ]  ) ); }
    if( isset( $med[ 'publisher'   ][ 0 ] ) ) { $m -> set_publisher     ( trim ( $med[ 'publisher'    ]  ) ); }
    if( isset( $med[ 'physicaldesc'][ 0 ] ) ) { $m -> set_physicaldesc  ( trim ( $med[ 'physicaldesc' ]  ) ); }
    if( isset( $med[ 'ppn'         ][ 0 ] ) ) { $m -> set_ppn           ( trim ( $med[ 'ppn'          ]  ) ); }
    if( isset( $med[ 'leader'      ][ 0 ] ) ) { $m -> set_leader        ( trim ( $med[ 'leader'       ]  ) ); }
    if( isset( $med[ 'ISBN'        ][ 0 ] ) ) { $m -> set_ISBN          ( trim ( $med[ 'ISBN'         ]  ) ); }
    if( isset( $med[ 'signature'   ][ 0 ] ) ) { $m -> set_signature     ( trim ( $med[ 'signature'    ]  ) ); }
    if( isset( $med[ 'format'      ][ 0 ] ) ) { $m -> set_format        ( trim ( $med[ 'format'       ]  ) );
                                                $m -> set_doc_type      ( trim ( $med[ 'format'       ]  ) );
                                                $m -> calcDocTypeID();
                                                $m -> calcItem();
    }
  
    $isDublette = $this->checkDoublette($I['currentCollection']->get_collection_id(),  $m -> get_ppn() );
    if (! $isDublette )
    {
      $ret[] = $m;
    }
  }
 
  $_SESSION[ 'books' ][ 'currentCollection' ] = $lmsDownload[ 1 ];
  $_SESSION[ 'books' ][ 'booksHitList'      ] = $this -> UTIL -> xml2array( $ret );
  $_SESSION[ 'books' ][ 'currentElement'    ] = 0;
  $_SESSION[ 'books' ][ 'maxElement'        ] = sizeof($_SESSION[ 'books' ][ 'booksHitList' ]);
 
  $collection_dc_collection_id = $I[ 'currentCollection' ] -> get_dc_collection_id();
  $user_role_id                = $I[ 'currentUser'       ] -> get_role_encode();
  $b_ppn                       = $_SESSION[ 'books'      ][ 'booksHitList' ][ 0 ][ 'ppn' ];
 
  $_SESSION[ 'books' ][ 'url' ] =  "index.php?ppn=$b_ppn&item=media&loc=1&action=annoteNewMedia&dc_collection_id=$collection_dc_collection_id&mode=new&r=$user_role_id";
  $this -> RENDERER -> doRedirect( $_SESSION[ 'books' ][ 'url' ]  );
}


function getMediaList( $I )
{
    $conf = $this -> conf;

    $mediaListID  =  $I[ 'operator' ] -> get_mediaListID() ;
 

    $url =  $this -> conf ['SRU'][ 'vuFindListURL'    ]  .$mediaListID  . $this -> conf ['SRU'][ 'vuFindListParams' ];                                                # deb( $url );

    $medList = $this -> LMSLoader( $url );                                                          # deb( $medList,1 );
 
    $medList = $this -> UTIL -> xml2array( $medList );

    foreach ( $medList as $med )
    {
        $m = new Medium();

        if( isset( $med[ 'title'       ][ 0 ] ) ) { $m -> set_title         ( trim ( $med[ 'title'        ]  ) ); }
        if( isset( $med[ 'author'      ][ 0 ] ) ) { $m -> set_author        ( trim ( $med[ 'author'       ]  ) ); }
        if( isset( $med[ 'publisher'   ][ 0 ] ) ) { $m -> set_publisher     ( trim ( $med[ 'publisher'    ]  ) ); }
        if( isset( $med[ 'physicaldesc'][ 0 ] ) ) { $m -> set_physicaldesc  ( trim ( $med[ 'physicaldesc' ]  ) ); }
        if( isset( $med[ 'ppn'         ][ 0 ] ) ) { $m -> set_ppn           ( trim ( $med[ 'ppn'          ]  ) ); }
        if( isset( $med[ 'leader'      ][ 0 ] ) ) { $m -> set_leader        ( trim ( $med[ 'leader'       ]  ) ); }
        if( isset( $med[ 'ISBN'        ][ 0 ] ) ) { $m -> set_ISBN          ( trim ( $med[ 'ISBN'         ]  ) ); }
        if( isset( $med[ 'signature'   ][ 0 ] ) ) { $m -> set_signature     ( trim ( $med[ 'signature'    ]  ) ); }
        if( isset( $med[ 'format'      ][ 0 ] ) ) { $m -> set_format        ( trim ( $med[ 'format'       ]  ) );
            $m -> set_doc_type      ( trim ( $med[ 'format'       ]  ) );
            $m -> calcDocTypeID();
            $m -> calcItem();
        }
 
        $isDublette = $this->checkDoublette($I['currentCollection']->get_collection_id(),  $m -> get_ppn() );
        if (! $isDublette )
        {
          $ret[] = $m;
        }
    }

 
    $_SESSION[ 'books' ][ 'booksHitList'      ] = $this -> UTIL -> xml2array( $ret );
    $_SESSION[ 'books' ][ 'currentElement'    ] = 0;
    $_SESSION[ 'books' ][ 'maxElement'        ] = sizeof($_SESSION[ 'books' ][ 'booksHitList' ]);

    $collection_dc_collection_id = $I[ 'currentCollection' ] -> get_dc_collection_id();
    $user_role_id                = $I[ 'currentUser'       ] -> get_role_encode();
    $b_ppn                       = $_SESSION[ 'books'      ][ 'booksHitList' ][ 0 ][ 'ppn' ];

 

    $_SESSION[ 'books' ][ 'url' ] =  "index.php?ppn=$b_ppn&item=media&loc=1&action=annoteNewMedia&dc_collection_id=$collection_dc_collection_id&mode=new&r=$user_role_id";
 
    $this -> RENDERER -> doRedirect( $_SESSION[ 'books' ][ 'url' ]  );
}

function LMSLoader( $url )
{
	$arrContextOptions=array(
        "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
         ), );
  $medium = null;
  #$url = 'https://haw.beluga-core.de/vufind/Cart/lmsdownload?lmsid='.$_SESSION['bc_urlID'].'&format=marc21';

  ### ------ TEST -------
  #$url = 'X:\xampp\htdocs\ELSE-DEV\htdocs\haw-marc21.xml';
  ### ------ TEST -------
   # deb($url,1);
  $strXml = file_get_contents( $url , false, stream_context_create($arrContextOptions));
  #deb($strXml,1);
  $xml = simplexml_load_string( $strXml);
  #deb($xml,1);
  $i = 0;
  foreach( $xml -> record as $xmlrec )
  {
    foreach ( $xmlrec -> controlfield as $a => $b )
    {# deb( $b);
      ## --- PPN
      if ( $b[ 'tag' ]       == '001' )
      {
        $PPN            = (string)$b;
        $medium[(string)$PPN][ 'ppn'          ] = $PPN;      }
    }

    $hasAuthor = false;
    foreach ( $xmlrec -> datafield as $a => $b )
    {
      $b_att = $b -> attributes ();

      ## -- Autor --
      if ( $b_att == '100' )
      {
        foreach ( $b -> subfield as $sf )
        {
          if( $sf -> attributes() -> code == 'a'  )  {  $medium[ $PPN ][ 'author'        ] = (string)$sf  ; $hasAuthor = true;  }
        }
      }

      if ( $hasAuthor == false AND $b_att == '245' )
      {
        foreach ($b -> subfield as $sf )
        {
          if( $sf -> attributes() -> code == 'c'  )  {  $medium[ $PPN ][ 'author'        ] =  (string)$sf ;   }
        }
      }


      ## -- Titel --
      if ( $b_att == '245' )
      {
        foreach ( $b -> subfield as $sf   )
        {
          if( $sf -> attributes() -> code == 'a'  )  {  $medium[ $PPN ][ 'title'        ]  =  (string)$sf;   }
          if( $sf -> attributes() -> code == 'b'  )  {  $medium[ $PPN ][ 'title'        ] .= ' - '.  (string)$sf;   }
          if( $sf -> attributes() -> code == 'n'  )  {  $medium[ $PPN ][ 'title'        ] .= ' - '.  (string)$sf;   }
        }
      }


      ## -- Physical Description --
      if ( $b_att == '300' )
      {
          foreach ($b -> subfield as $sf   )
          {
              if( $sf -> attributes() -> code == 'a' )  {  $medium[ $PPN ][ 'physicaldesc'        ] =  (string)$sf;   }
          }
      }


      ## -- Signatur --
      if ( $b_att == '980' )
      { $hit = false;
        foreach ( $b -> subfield as $sf   )
        { if( $sf -> attributes() -> code == 2  )    {  if ( $sf  == 34 ){ $hit = true; }  }  }

        if ( $hit )
        foreach ( $b -> subfield as $sf   )
        { if( $sf -> attributes() -> code == 'd' )    { $medium[ $PPN ][ 'signature' ] =  (string)$sf; }  }
      }


      ## -- Verlag --
      if ( $b_att == '264' )
      {
          foreach ($b -> subfield as $sf   )
          {
              if( $sf -> attributes() -> code == 'a'  )  {  $medium[ $PPN ][ 'publisher'        ] =   (string)$sf;   }
           }
      }

      ## -- ISBN  --
      if ( $b_att == '020' )
      {
          foreach ($b -> subfield as $sf   )
          {
              if( $sf -> attributes() -> code == '9'  )  {  $medium[ $PPN ][ '$ISBN'        ] =  (string)$sf;   }
          }
      }
    }

    $medium[(string)$PPN][ 'leader'          ] =   (string)$xmlrec -> leader;
    $medium[(string)$PPN][ 'format'          ] =   (string)$xmlrec -> format;

  }
  return  $medium;
}




###############################################################################################
function exportCollection( $I )
{

$csv_filename = 'ELSE_' . date("YmdHis") . '.exp';

// Export the data and prompt a csv file for download

$csv_export =  $this -> SQL -> exportCollection( $I[ 'currentCollection' ]->get_collection_id() );
header("Content-type: text/x-csv");
header("Content-Disposition: attachment; filename=".$csv_filename."");

echo $csv_export;

exit(0);

return;
}

###############################################################################################
function importCollection( $I )
{
$ds          = DIRECTORY_SEPARATOR;  //1

$storeFolder = 'uploads';   //2

#if (!empty($_FILES))
{
  $tempFile = "ELSE_20200520162839.exp";          //3

  #$tempFile =  ($_FILES['file']['tmp_name']);

  $fp       = fopen('data.txt', 'w');
  fwrite($fp, $I[ 'currentCollection' ] -> get_collection_id ()  ) ;

  #$targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
  #$targetFile =  $targetPath. $_FILES[ 'file' ][ 'name' ];  //5
  #move_uploaded_file($tempFile,$targetFile); //6

  $newSA = file ($tempFile , FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  #deb($newSA,1);
  foreach( $newSA as $medium )
  {
    $this -> SQL -> importMedium( $I[ 'currentCollection' ] -> get_collection_id (), $medium , $fp);
   fwrite($fp, $medium ) ;
  }
  fclose($fp);
}

}




#-------------------------------------------------------------------------
    function updateSASem( $I )
    {
        # -- Aktuelles und vorheriges Semester wird bestimmt.
        $SEM = array_keys( $this -> conf [ 'SEM' ] );

        WHILE (in_array($_SESSION[ 'CUR_SEM' ], $SEM ))
        {
            (array_pop( $SEM ));
        }
        $oldSEM =  array_pop( $SEM ) ;
        $newSEM =  $_SESSION[ 'CUR_SEM' ];
        # ---------------------------------



#  $oldSA_ID = 'DMI.DS.W18 DMDK';       # RE1JLkRTLlcxOCBETURL
#  $oldSA_ID = 'TI..IN.W17 WAUD';       # VEkuLklOLlcxNyBXQVVE
#  $oldSA_ID = 'DMI.MD.S18 NAT.LAB_08'; # RE1JLk1ELlMxOCBOQVQuTEFCXzA4                  # 0 Materialien
#  $oldSA_ID = 'WS..SA.S18 GKA_BASA';   # V1MuLlNBLlMxOCBHS0FfQkFTQQ%3D%3D              # 168 Materialien

        $oldSA_ID = $I[ 'C' ][ 'collection_id' ];
        $oldSA    = $this -> SQL->getCollectionMetaData( $oldSA_ID );

        if ( isset( $oldSA[ 'id' ] ) )
        {
            $newSA =  $this ->renewSA( $oldSA, $oldSEM ,$newSEM );

            $newSA_ID = $newSA[ 'title_short' ];
            if (! $newSA[ 'error' ] == 1 )
            {  if ( isset( $newSA_ID ) )
            { if ( $oldSA[ 'm' ][ 'GE' ] > 0 )  ## Orig SA enthält > 0 Medien
            { $ret = $this ->renewDoclist( $oldSA_ID, $newSA );
                if ( $ret )
                { $msg = "INFO: [" . $newSA_ID . "] wurde von [" . $oldSA_ID . "] mit  " . $oldSA[ 'm' ][ 'GE' ] . " Medien angelegt "; }
                else
                { $msg =  "ERROR:[" . $newSA_ID . "] wurde NICHT von [" . $oldSA_ID . "] mit  " . $oldSA[ 'm' ][ 'GE' ] . " Medien angelegt "; }
            }
            else{  $msg = "INFO: [" . $newSA_ID . "] wurde von [" . $oldSA_ID . "] OHNE Medien angelegt "; }
            }
            else{ $msg =  "ERROR: neuer ER [" . $newSA_ID . "] konnte nicht definiert werden  ";}
            }
            else{   $msg =  "ERROR: neuer ER [" . $newSA_ID . "] existiert schon  ";}
        }
        else{       $msg = "ERROR: angefragter ER [" .$oldSA_ID . "] existiert nicht  ";}

        $msg =  date('Y-m-d'). " | ". $msg;


        return $msg;
    }


#-------------------------------------------------------------------------
    function renewSA( $SA, $oldSEM, $newSEM )  # Aus den Daten des alten SA wird der SA für das nächste Semester erzeugt.
    {
        ### prepare SA ###
        $newCourse = Array();
        $newCourse[ 'sem'                  ] = $newSEM;
        $newCourse[ 'expiry_date'          ] = $this->UTIL->get_new_expiry_date();
        $newCourse[ 'shortname'            ] = str_replace($oldSEM, $newSEM, $SA[ 'title_short' ]);
        $newCourse[ 'title'                ] = str_replace($oldSEM, $newSEM, $SA[ 'title' ]);
        $newCourse[ 'title_short'          ] = str_replace($oldSEM, $newSEM, $SA[ 'title_short' ]);
        $newCourse[ 'id'                   ] = str_replace($oldSEM, $newSEM, $SA[ 'id' ]);
        $newCourse[ 'created'              ] = $SA[ 'created'          ];
        $newCourse[ 'notes_to_studies_col' ] = $SA[ 'notes_to_studies_col' ];
        $newCourse[ 'bib_id'               ] = $SA[ 'bib_id'           ];
        $newCourse[ 'user_id'              ] = $SA[ 'user_id'          ];
        $newCourse[ 'sortorder'            ] = $SA[ 'sortorder'        ];
        $newCourse[ 'error'                ] = 0;

        $newSA = $this -> SQL -> getCollectionMetaData( $newCourse[ 'title_short' ] );
        if (! isset( $newSA[ 'title_short' ] ) ) # Wenn neuer SA noch nicht exisitert, wird er anglegt.
        {
            $this -> SQL -> setCollection( $newCourse );
        }
        else
        {
            $newCourse[ 'error' ] = 1;
        }
        ### prepare SA ###

        return $newCourse;
    }


    function renewDoclist( $oldSA_ID, $newSA)
    {
        $olddoclist = $this -> SQL->getDokumentList( $oldSA_ID );
        foreach ( $olddoclist as $book )
        {
            if ( $book[ 'doc_type_id' ] == 1 )  ## Ist das Medium ein SA-Buch (doctype = 1) , wird dieses im altem SA zum Literaturhinweis (doctype = 2)
            {
                $this -> SQL-> setMediaType( $book[ 'id' ], 2 );
            }

            $book[ 'collection_id' ] = $newSA[ 'id' ];
            $newdoclist[] = $this -> SQL -> initMediaMetaData( $book );
        }
        if ( sizeof( $olddoclist ) == sizeof( $newdoclist ) )  { $ret = 1; }
        else                                                   { $ret = 0; }

        return $ret;
    }


    function reNewCollection( $I )
    {
        $conf = $this->CFG->getConf();

        if(  $I[ 'W' ][ 'mode' ] != sha1( $I[ 'C' ][ 'dc_collection_id' ].$conf[ 'salt' ] ))
        {  echo "ERROR: 12";  # Falscher HASH über URL
        }
        else
        {
            $conf[ 'SEMESTER' ] =  array_keys( $this -> conf [ 'SEM' ] );
            $I[ 'C' ][ 'currentSemester' ]= array_pop(  $conf[ 'SEMESTER' ] ); # aktuelle Semester ist an erster Stelle des Arrays
            $conf[ 'SEMESTER' ] =  array_keys( $_SESSION[ 'SEM' ] );
            foreach ( $conf[ 'SEMESTER' ]  as $sem )
            {
                if ( isset( $I[ 'C' ][ 'collection_id' ] ) ) { $I[ 'C' ][ 'newCollection_id' ] = str_replace( $sem , $I[ 'C' ][ 'currentSemester' ] , $I[ 'C' ][ 'collection_id' ], $cnt);}
                if ( isset( $I[ 'C' ][ 'title'         ] ) ) { $I[ 'C' ][ 'newTitle'         ] = str_replace( $sem , $I[ 'C' ][ 'currentSemester' ] , $I[ 'C' ][ 'title'         ], $cnt);}
                if ( isset( $I[ 'C' ][ 'title_short'   ] ) ) { $I[ 'C' ][ 'newTitle_short'   ] = str_replace( $sem , $I[ 'C' ][ 'currentSemester' ] , $I[ 'C' ][ 'title_short'   ], $cnt);}
                if ( $cnt ) break;
            }

            $I[ 'C' ][ 'newExpire_date'   ] =  $this -> UTIL -> get_new_expiry_date ();

            if ( isset( $I[ 'C' ][ 'newCollection_id' ] ) )
            {
                $this -> SQL -> renewCollection( $I[ 'C' ], $I[ 'W' ]  );
            }
        }

        $_SESSION = "";
        exit(0);
    }



    function redirToCollection( $I )
    {
        $this->RENDERER->doRedirect( $I[ 'W' ][ 'last_page' ] );
        exit(0);
    }



  function getAllCollection( $I )
  {
   # if ( $I[ 'filter' ] -> get_user () != "" )  $user = '';
   # else                                        $user = ( $I[ 'filter' ] -> get_user () );
#    $userlist = $this -> SQL -> getUserList ( $I[ 'filter' ] -> get_user () );                                                                         ## Array der Metadaten aller ELSE Owner
    $userlist = $this -> SQL -> getUserList ( );                                                                         ## Array der Metadaten aller ELSE Owner

    # deb($userlist,1);
     $collectionList = null;

    foreach ( $userlist as $user )                                                                                            ## Liste wird mit entsprechenden SAs erweitert
    {
      $SAlistTMP  =  $this -> SQL -> getSAlist( $user, $I  );

      # deb($SAlistTMP);
      $SAlist     =  array();

      if ( $SAlistTMP )
      {
        $dns =  explode("," ,  str_replace( " ", "", $this -> conf ['CONF'] [ 'doNotShow'  ]   ) );    #  --- Optimierung??  nicht jedes Mal in ein Array umwandeln?
        foreach ( $SAlistTMP as $SA )
        { if  ( !in_array( $SA -> get_id(),  $dns ) )                                          ## SA die nicht angzeigt werden sollen, werden aus der Liste entfernt
          {  $SAlist[] = $SA -> obj2array ();
          }

        }


        $collectionList[ ] = $SAlist  ;
      }
      else
      { $collectionList[ ] = 0 ;                                                                  }
    }

  #  deb($collectionList,1);
    return $collectionList;
  }
  
  
  ###############################################################################################
  function checkDoublette( $colID, $docID)
  {
    $docs = $this -> SQL -> getDokumentList( $colID );
    if ($docs)
      foreach( $docs as $d )
      {
        if ($d->ppn == $docID) { $ret = 1 ; break; }  #  1 = Doublette
        else                   { $ret = 0 ;}          #  0 = keine Doublette
      }
      return $ret;
  }
  
  
# ---------------------------------------------------------------------------------------------
  function getKey( $u )
  {
    return trim ( $u->get_hawaccount().'::'.  trim ( $u->get_surname()) ) .'::'. trim ( $u->get_forename() ) ;
  }
 }
