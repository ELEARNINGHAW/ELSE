<?php
session_start();   # session_destroy (); unset($_SESSION);

require_once ( '../php/Const.class.php'               );
require_once ( '../php/Config.class.php'              );
require_once ( '../php/SQL.class.php'                 );
require_once ( '../php/SQL.HAW.class.php'             );
require_once ( '../php/Util.class.php'                );
require_once ( '../php/Collectionmanager.class.php'   );
require_once ( '../php/Mediamanager.class.php'        );
require_once ( '../php/Renderer.class.php'            );
require_once ( '../php/Medium.class.php'              );
require_once ( '../php/User.class.php'                );
require_once ( '../php/Email.class.php'               );
require_once ( '../php/Collection.class.php'          );
require_once ( '../php/Operator.class.php'            );
require_once ( '../php/MedState.class.php'            );
require_once ( '../php/Bib.php'                       );
require_once ( '../php/Filter.class.php'              );

$CFG        = new ConfigELSE( new CONSTANT()                                 );
$SQL        = new SQL(                                                       );
$UTIL       = new UTIL(                    $SQL                              );
$RENDERER   = new RENDERER(                                 $UTIL            );
$COLLMGR    = new COLLECTIONMANAGER( $CFG, $SQL, $RENDERER, $UTIL            );
$MEDIAMGR   = new MEDIAMANAGER(      $CFG, $SQL, $RENDERER, $UTIL, $COLLMGR  );

## ----------------------------------------------------------------------------------------
$I = $UTIL -> getInput();                                #--- GET ALL INPUT (GET) ---
## ----------------------------------------------------------------------------------------

$cu = $I[ 'currentUser' ];
$ca = $I[ 'operator'    ] -> get_action();   # ACTION
$ci = $I[ 'operator'    ] -> get_item();     # ITEM
$cl = $I[ 'operator'    ] -> get_loc();      # LOCATOR

# $ca = 'import';
#deb( $_GET  );
#deb($ci );
#deb($cu );
#deb($ca );
#deb( $cl );
#deb($_SESSION[ 'books' ][ 'booksHitList' ]);
#deb( $I,1  );

# -- Default: item = collection --
# --(bei role = user)  action = show_collection,
# --(bei role = staff) action = show_collection_list

## --- AKTIONEN DES SEMESTERAPPARATS ---
if ( $ci  == 'collection'  AND   $UTIL -> hasRole( $cu,'admin', 'staff', 'edit') )
{
  if      ( 1 == 2 ) {;}
  else if ( $ca  == 'show_collection_list'   )  { $COLLMGR -> showCollectionList        ( $I ) ; }  ## ++ 1 STAFF:: SA wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)
  else if ( $ca  == 'show_collection'        )  { $COLLMGR -> showCollection            ( $I ) ; }  ## ++ 2 SA wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)
  else if ( $ca  == 'show_media_list'        )  { $COLLMGR -> showMediaList             ( $I ) ; }  ## ++    STAFF:: Zeigt die Liste der Medien der SAs, gefiltert nach deren STATUS (und ev. Sem)
  else if ( $ca  == 'add_media'              )  { $COLLMGR -> showNewMediaForm          ( $I ) ; }  ## ++ 3 Eingabemaske für Mediensuche anzeigen   [Neues Medium dem SA hinzufügen]
  else if ( $ca  == 'new_init'               )  { $COLLMGR -> saveNewCollection         ( $I ) ; }  ## Metadaten des NEUEN SA wird gepeichert -> SA wird angelegt
  else if ( $ca  == 'coll_meta_edit'         )  { $COLLMGR -> editColMetaData           ( $I ) ; }  ## Anzeigen des Formulars um Metadaten des SA zu bearbeiten
  else if ( $ca  == 'coll_meta_save'         )  { $COLLMGR -> updateColMetaData         ( $I ) ; }  ## Metadaten des SA updaten
  else if ( $ca  == 'resort'                 )  { $COLLMGR -> resortCollection          ( $I ) ; }  ## Setzt neue Reihenfolge der Medien im SA
  else if ( $ca  == 'export'                 )  { $COLLMGR -> exportCollection          ( $I ) ; }  ## Exportiert den SA
  else if ( $ca  == 'import'                 )  { $COLLMGR -> importCollection          ( $I ) ; }  ## Importiert den SA
  else if ( $ca  == 'takeover'               )  { $COLLMGR -> takeoverCollection        ( $I ) ; }  ## Übernimmt ausgwählten Vorläufer SA
  else if ( $ca  == 'lms-download'           )  { $COLLMGR -> lmsDownload               ( $I ) ; }  ## Importiert Medien-Metadaten direkt aus Beluga Core
  else if ( $ca  == 'getMediaList'           )  { $COLLMGR -> getMediaList              ( $I ) ; }  ## Importiert Medien-Metadaten direkt aus Beluga Core
}

 if (  ( $cl == 1  OR  $cl == 0  OR $cl == 2  OR $cl  == 3 OR $cl  == 5)   AND   $UTIL -> hasRole( $cu,'admin', 'staff', 'edit'))  ## cl = 2 Location =  Bibliothek  --  3 Loc =  online --  5 Loc = externe Biblio
{
  if      ( 1 == 2 ) {;}
  else if ( $ca  == 'search'                )  {  $MEDIAMGR -> searchMediaOnLibraryServer    ( $I ); }   ## ++ 4 OPAC -- Suchprozess des Mediums wird gestartet
  else if ( $ca  == 'annoteNewMedia'        )  {  $MEDIAMGR -> annoteNewMedia_showForm       ( $I ); }   ## ++ 5 Eingabemaske Metadaten für Buch Annotation anzeigen
  else if ( $ca  == 'save'                  )  {  $MEDIAMGR -> saveMediaMetaData             ( $I ); }   ## ++ 6 Metadaten eines neues Buch speichern
  else if ( $ca  == 'suggest'               )  {  $MEDIAMGR -> saveNewMediaSuggest           ( $I ); }   ## Metadaten eines Literaturvoschlag speichern
  else if ( $ca  == 'edit'                  )  {  $MEDIAMGR -> editMediaMetaData             ( $I ); }   ## ActionHandler: Formular zur Bearbeitung der Metadaten des Buchs wird gezeigt
  else if ( $ca  == 'kill'                  )  {  $MEDIAMGR -> ereaseMedia                   ( $I ); }   ## ActionHandler: Buch wird endgültig aus SA gelöscht
  else if ( $ca  == 'accept'                )  {  $MEDIAMGR -> acceptMedia                   ( $I ); }   ## ActionHandler: angefordertes Buch wird akzeptiert zur Bearbeitung
  else if ( $ca  == 'finished'              )  {  $MEDIAMGR -> doneMedia                     ( $I ); }   ## ActionHandler: angefordertes Buch steht für die Studies bereit
  else if ( $ca  == 'release'               )  {  $MEDIAMGR -> releaseMedia                  ( $I ); }   ## ActionHandler: Buch wird zurückgegeben
  else if ( $ca  == 'revive'                )  {  $MEDIAMGR -> reviveMedia                   ( $I ); }   ## ActionHandler: storierte Buchbestellung wird erneuert
  else if ( $ca  == 'delete'                )  {  $MEDIAMGR -> deleteMedia                   ( $I ); }   ## ActionHandler: Buch wird aus SA gelöscht
  else if ( $ca  == 'return'                )  {  $MEDIAMGR -> returnDoneMedia               ( $I ); }   ## ActionHandler: Buchrückgabe ist erledigt
  else if ( $ca  == 'cancel_release'        )  {  $MEDIAMGR -> cancel_release                ( $I ); }   ## ActionHandler: Buch verlängern / Buchrückgabe abbrechen
  else if ( $ca  == 'activate'              )  {  $MEDIAMGR -> activateMedia                 ( $I ); }   ## ActionHandler: Medium Aktivieren
  else if ( $ca  == 'new_email'             )  {  $MEDIAMGR -> showMailForm                  ( $I ); }   ## ActionHandler: Mailformular für Infomail an Nutzer
  else if ( $ca  == 'cancel_order'          )  {  $MEDIAMGR -> cancelMedia                   ( $I ); }   ## ActionHandler: Buchbestellung wird storiert
  else if ( $ca  == 'deactivate'            )  {  $MEDIAMGR -> deactivateMedia               ( $I ); }   ## ActionHandler: Medium Deaktivieren
  else if ( $ca  == 'delete_ebook'          )  {  $MEDIAMGR -> deleteMedia                   ( $I ); }   ## ActionHandler: Medium wird aus SA gelöscht
  else if ( $ca  == 'new_email'             )  {  $MEDIAMGR -> showMailForm                  ( $I ); }   ## ActionHandler: Erwebungsvorschlag (nach 0 Suchtreffern)
}


else if (  ( $cl  == 4)   AND   $UTIL -> hasRole( $cu,'admin', 'staff', 'edit'))   ## cl = 4  Location =  Scanservice
{
  if      ( 1 == 2 ) {;}
  else if ( $ca  == 'annoteNewMedia'        )  {  $MEDIAMGR -> annoteNewMedia_showForm       ( $I ); }   ## Eingabemaske Metadaten für Buch Annotation anzeigen
  else if ( $ca  == 'edit'                  )  {  $MEDIAMGR -> editMediaMetaData             ( $I ); }   ## ActionHandler: Metadaten des SA bearbeiten
  else if ( $ca  == 'save'                  )  {  $MEDIAMGR -> saveMediaMetaData             ( $I ); }   ## Speichern der Metadaten des Mediums
  else if ( $ca  == 'accept'                )  {  $MEDIAMGR -> acceptMedia                   ( $I ); }   ## ActionHandler: angefordertes Buch wird akzeptiert zur Bearbeitung
  else if ( $ca  == 'finished'              )  {  $MEDIAMGR -> doneMedia                     ( $I ); }   ## ActionHandler: angefordertes Buch steht für die Studies bereit
  else if ( $ca  == 'kill'                  )  {  $MEDIAMGR -> ereaseMedia                   ( $I ); }   ## Buch wird endgültig aus SA gelöscht
  else if ( $ca  == 'deactivate'            )  {  $MEDIAMGR -> deactivateMedia               ( $I ); }   ## ActionHandler: Medium Deaktivieren
  else if ( $ca  == 'activate'              )  {  $MEDIAMGR -> activateMedia                 ( $I ); }   ## ActionHandler: Medium Aktivieren
  else if ( $ca  == 'delete'                )  {  $MEDIAMGR -> deleteMedia                   ( $I ); }   ## ActionHandler: Medium wird aus SA gelöscht
  else if ( $ca  == 'delete_ebook'          )  {  $MEDIAMGR -> deleteMedia                   ( $I ); }   ## ActionHandler: Medium wird aus SA gelöscht
  else if ( $ca  == 'new_email'             )  {  $MEDIAMGR -> showMailForm                  ( $I ); }   ## ActionHandler: Erwebungsvorschlag (nach 0 Suchtreffern)
}


## ----------------------------------------------------------------------------------------
## --------- Aktionen nur für Dozenten oder Staff oder Mailuser ---------------------------
## ----------------------------------------------------------------------------------------

if ( $ci  == 'email' AND $UTIL->hasRole( $cu,'staff', 'edit', 'mailuser'  ) )
{ if      ( 1 == 2 ) {;}
  else if ( $ca  == 'sendmail'              )  {  $MEDIAMGR -> send_email                    ( $I ); }    ## Email wird verschickt
  else if ( $ca  == 'HIBSAPmail'            )  {  $UTIL     -> sendBIB_APmails               (    ); }    ## Cronjob: HIBS Ansprechpartner Infomail
}


if ( $ci  == 'collection' AND $UTIL->hasRole( $cu,'staff', 'edit', 'mailuser'  ))
{ if      ( 1 == 2 ) {;}
  else if ( $ca  == 'updateSem'             )  {  $COLLMGR  -> updateSASem                   ( $I ); }   ##  SA wird für das nächste Semester genutzt. Bisheriger SA - Bücher im physSA werden zu reienen Literatur Hinweisen
  else if ( $ca  == 'ERASA'                 )  {  $COLLMGR  -> setCollectionForNextSem       ( $I ); }   ##  Email wird verschickt
  else if ( $ca  == 'ims-download'          )  {  $MEDIAMGR -> searchMediaOnLibraryServer    ( $I ); }   ##  IMPORT DER BELUGA Liste
  else if ( $ca  == 'purchase_suggestion'   )  {  $MEDIAMGR -> purchase_suggestion           ( $I ); }   ##  Erwebungsvorschlag
}

## ----------------------------------------------------------------------------------------
## --------- Aktionen für Studis / Alle erlaubt --------------------------------------------------
## ----------------------------------------------------------------------------------------

else
{ if ( $ci  == 'collection' );
  { if      ( 1 == 2 ) {;}
    else if ( $ca  == 'show_collection'      )  { $COLLMGR -> showCollection                  ( $I ); }    ## SAs wird angezeigt (deren Editierbarkeit ist abhängig von der Rolle des Nuters)
    else if ( $ca  == 'show_media_list'      )  { $COLLMGR -> showMediaList                   ( $I ); }    ## Zeigt die Liste der SAs, geoperatort nach deren Zustand
  }
}

?>