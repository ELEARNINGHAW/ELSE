<?php
class CONSTANT
{  
var $default_role_id;
var $bib_id ;
var $CONST_letter_header;
var $CONST_ACTION_INFO ;

function __construct()
{
if   ( !isset ( $this -> default_role_id ) ) $this -> default_role_id = 3;

if   ( isset ( $_POST[ 'bib_id' ] ) )  { $this -> bib_id = $_POST[ 'bib_id' ] ; }
else                                   { $this -> bib_id = 'HAW' ;              }

$this -> CONST_letter_header = array ( 'A' , 'B' , 'C' , 'D' , 'E' , 'F' , 'G' , 'H' , 'I' , 'J' , 'K' , 'L' , 'M' , 'N' , 'O' , 'P' , 'Q' , 'R' , 'S' , 'T' , 'U' , 'V' , 'W' , 'X' , 'Y' , 'Z' ) ;

## actions info ## Zur Zeit nur genutzt für die Buttons zur Medienbearbeitung
$this -> CONST_ACTION_INFO = array
(
# 0 	unknow   nicht bekannt
# 1   new 	   neu bestellt
# 2 	open 	   wird bearbeitet
# 3 	active 	 ist aktiv
# 4 	obsolete wird entfernt
# 5 	inactive ist inaktiv
# 6 	delete 	 IST GELÖSCHT!
# 9 	suggest  Vorschlag

##  1 -> 2  ##  new     -> open
##  9 -> 2  ##  suggest -> open
##  Bestellwunsch (oder Kaufvorschlag) wurde akzeptiert und wird von HIBS bearbeitet : [Wird bearbeitet]
'accept'              => array
( 'button'            => 'accept' ,
  'button_label'      => 'Bestellung annehmen' ,
  'input'             => array ( "state" => "open" ) ,
  'acl' => array ( /* "physical" => "role=admin,role=staff" , */ ) ,
  'button_visible_if' => array
  (   "loc"      => array ( "0", "1" ,"2" ,"4" ,"5" ) ,
      "state"    => array ( "new", "suggest"        ) ,
      "mode"     => array ( "staff" , "admin"       ) ,
  ) ,
) ,

##  2 -> 3  ##  open -> active
##  Bestellwunsch wurde erledigt und steht nun (im Bücherregal SA) zur Verfügung  (2 -> 3): [Ist aktiv]
'finished'            => array
( 'button'            => 'finished' ,
  'button_label'      => 'Bestellung erledigt' ,
  'input'             => array ( "state" => "active" ) ,
  'button_visible_if' => array
  ( "state" => array ( "open" ) ,
    "mode"  => array ( "staff" , "admin" ) ,
    "loc"      => array ( "0", "1" ,"2"  , "4", "5"  ) ,
  ) ,
 'acl'               => array (  /* "physical" => "role=admin,role=staff"  */   ) ,
) ,

##  3 -> 4  ##  active -> obsolete
## Buch kann zurückgegeben werden  (3 -> 4): [Wird entfernt]
'release'             => array
( 'button'            => 'release' ,
  'button_label'      => 'Medium entfernen' ,
  'input'             => array ( "state" => "obsolete" ) ,
  'acl'               => array ( /* "physical" => "role=admin,owner=true"  */ ) ,
  'button_visible_if' => array
  ( "loc"   => array ( "0", "1"                  ) ,
    "state" => array ( "active"                  ) ,
    "mode"  => array ( "edit" , "admin" ,"staff" ) ,
  ) ,
) ,

/*
## Buch ist zurückgegeben worden   (4 -> 5): [Inaktiv]
'return'                => array
( 'button'            => 'return' ,
  'button_label'      => 'Rückgabe erledigt' ,
  'input'             => array ( "state" => "inactive" ) ,
# 'acl'               => array ( "physical" => "role=admin,role=staff"  ) ,
  'button_visible_if' => array
  (  "loc"      => array ( "0", "1"   ) ,
    "state"     => array ( "obsolete" , "delete") ,
    "mode"      => array ( "staff" , "admin" ) ,
  ) ,
) ,
*/

##  4 -> 3  ##  obsolete -> active
## Buch wird doch nicht entfernt
  'cancel_release'      => array
  ( 'button'            => 'cancel_release' ,
    'button_label'      => 'Nicht entfernen' ,
    'input'             => array ( "state" => "active" ) ,
    'button_visible_if' => array
    ( "loc"       => array ( "0", "1"   ) ,
      "state"     => array ( 'obsolete' ) ,
      "mode"      => array ( 'edit', 'staff' , 'admin' ) ,
    ) ,
    'acl' => array
    ( /* "physical"      => "role=admin, owner=true, role=staff" , */
    ) ,
  ) ,

/*
## - 5 -> 1 - ##
##  Bestellwunsch wird erneuert   (5 -> 1): [Neu bestellt]
  'revive'              => array
  (   'button'            => 'revive' ,
    'button_label'      => 'Bestellung erneuern' ,
    'input'             => array ( "state" => "new" ) ,
    'button_visible_if' => array
    (  "loc"      => array ( "0", "1"   ) ,
      "state"      => array ( "inactive" ) ,
      "mode"       => array (  "admin", 'staff' ) ,
    ) ,
    'acl' => array
    (
      #  "article" => "role=admin,owner=true,role=staff" ,
      #  "physical"    => "role=admin,owner=true,role=staff" ,
    ) ,
  ) ,
*/
/*
##  1 -> 6  ##  new -> delete
##  9 -> 6  ##  suggest -> delete
##  Bestellwunsch wurde von HIBS (oder owner) abgelehnt/storniert   (1 -> 6): [Ist gelöscht]
'cancel_order'         => array
(   'button'           => 'cancel_order' ,
    'button_label'     => 'Bestellung stornieren' ,
    'input'            => array ( "state" => "delete" ) ,
    ##'acl' => array ( "physical" => "role=admin,owner=true,role=staff"   ) ,
    'button_visible_if' => array
    ( "state"     => array ( "new", "suggest" ) ,
       "loc"      => array ( "0", "1"   , "4"   ) ,
       "mode"     => array ( "edit" , "staff" , "admin" ) ,
    ) ,
) ,

##  1 -> 6  ##  new -> delete
##  9 -> 6  ##  suggest -> delete
## -> 6  ## löschen - ##  -> delete
'kill'              => array
( 'button'            => 'kill' ,
  'button_label'      => 'LÖSCHEN!' ,
  'input'             => array ( "state" => "delete" ) ,
  #'acl'               => array (  "any" => "role=admin,role=staff" ,  ) ,
  'button_visible_if' => array
  ( "state"  => array ( "new", "suggest", "obsolete", "delete" ) ,
    "loc"      => array ("0",  "1" ,  "2", "3" , "4", "5"  ) ,
    "mode"   => array ( "staff" , "admin" ) ,
  ) ,
) ,
*/

##  1 -> 6  ##  new -> delete
##  9 -> 6  ##  suggest -> delete
##  5 -> 6  ##  inactive -> suggest
##  Medium (E-Book, Buch) oder SA  wird gelöscht
  'delete'             => array
  ( 'button'            => 'delete' ,
    'button_label'      => 'Löschen' ,
    'input'             => array () ,
    'button_visible_if' => array
    ( "state"    => array ( "inactive", 'suggest', "new", "obsolete"  ) ,
      "mode"     => array ( "edit" , "admin" , "staff" ) ,
      "loc"      => array ( "0", "1", "2", "3"  , "4", "5"  ) ,
    ) ,
    'acl' => array
    (   /*  "physical"       => "role=admin,owner=true,role=staff" ,
      "online"         => "role=none,owner=none" ,*/
    ) ,
  ) ,

## - 3 -> 5 - ##
##  E-Book wird deaktiviert            (3 -> 5): [Ist inaktiv]
'deactivate'            => array
(   'button'            => 'deactivate' ,
    'button_label'      => 'Deaktivieren' ,
    'input'             => array ( "state" => "inactive" ) ,
    'button_visible_if' => array
    (  "state"  => array ( "active" ) ,
       "loc"      => array ("0", "2", "3" , "4", "5"   ) ,
       "mode"   => array ( "edit" , "admin" ,'staff' ) ,
    ) ,
    'acl' => array
    ( /*  "online"  => "owner=true,role=admin, role=staff", */
    ) ,
),

## - 5 -> 3 - ##
##  E-Book wird wieder aktiviert         (5 -> 3): [Ist aktiv]
'activate'              => array
( 'button'            => 'activate' ,
  'button_label'      => 'Aktivieren' ,
  'input'             => array ( "state" => "active" ) ,
  'button_visible_if' => array
  ( "state"   => array ("unknown" , 'continue' ) ,
    "loc"      => array ( "0", "1" ) ,
    "mode"   => array (  "admin" ,'staff' ) ,
  ) ,
'acl' => array
( /*  "online"  => "owner=true,role=admin, role=staff",*/
) ,
) ,
##  E-Book wird wieder aktiviert         (5 -> 3): [Ist aktiv]
  'activate'              => array
  ( 'button'            => 'activate' ,
    'button_label'      => 'Aktivieren' ,
    'input'             => array ( "state" => "active" ) ,
    'button_visible_if' => array
    ( "state"   => array ( "unknown" ,"inactive" ,"delete" ) ,
      "loc"      => array (  "0", "2", "3"  , "4" , "5" ) ,
      "mode"   => array ( "edit" , "admin" ,'staff' ) ,
    ) ,
    'acl' => array
    ( /*  "online"  => "owner=true,role=admin, role=staff", */
    ) ,
  ) ,


## Metadaten des Mediums (Buch, E-Book) wird bearbeiten    (a -> a )State ändert sich nicht
'edit'                => array
(  'button'            => 'edit' ,
   'button_label'      => 'Bearbeiten' ,
   'input'             => array ( "mode" => "edit" ) ,
   'button_visible_if' => array
    ( "loc"      => array ("0", "1" ,  "2", "3"  , "4", "5"  ) ,
      "mode"     => array ( "edit" , "staff" , "admin" ) ,
    ) ,
    'acl'               => array
    (  /*  "physical"    => "role=admin,role=staff,owner=true" ,
       "online"   => "role=admin,role=staff,owner=true" , */
    ) ,
) ,

'new_email'              => array
(  'button'            => 'new_email' ,
   'button_label'      => 'E-Mail senden' ,
   'input'             => array ( "mode" => "new" , "item" => "email" ) ,
   'acl'               => array ( /* "any" => "role=admin,role=staff" , */ ) ,
   'button_visible_if' => array
    ( "loc"      => array ("0", "1" ,  "2", "3"  , "4" , "5" ) ,
      "mode" => array ( "staff" , "admin" ) ,
    ) ,
) ,

  
  ## Neues Medium anlegen (suchen, annotieren, in SA speichern) ## Buch (- -> 1): [Neu bestellt]
  ## E-Book (- -> 3): [Ist aktiv]
  ## Neuer SA anlegen, annotieren, speichern,  SA (- -> 3): [ist aktiv]
  'coll_meta_save'    => array
  ( 'button'            => 'coll_meta_save' ,
    'button_label'      => 'neu anlegen' ,
    'input'             => array ( "mode" => "new" ) ,
    'acl'               => array
    ( /* "user"       => "any" ,
    "collection" => "role=admin,role=edit,role=staff" ,
    "physical"   => "role=admin,role=edit,role=staff" ,
    "online"     => "role=admin,role=edit,role=staff" ,*/
    ) ,
  ) ,

##  Metadaten des SA bearbeiten              (3 -> 3): [ist aktiv]  #
  'coll_meta_edit'      => array
  ( 'button'            => 'coll_meta_edit' ,
    'button_label'      => 'Bearbeiten' ,
    'input'             => array ( "mode" => "edit" ) ,
    'acl'               => array ( /* "collection" => "role=admin,owner=true,role=staff",  */ ) ,
    'button_visible_if' => array
    (  "item"       => array ( "collection" ) ,
      "state"      => array ( "active" ) ,
      "mode"       => array ( "edit" , "admin" , 'staff') ,
    ) ,
  ) ,

#   'show_collection_list'            => array (                                          /*  Inhalt des  SA (=Medien) wird angezeigt zum bearbeiten              (3 -> 3): [ist aktiv]   */
#        'button'            => 'show_collection_list' ,
#        'button_label'      => 'Bearbeiten' ,
#        'input'             => array () ,
#        'acl'               => array (  "collection" => "role=admin,owner=true,role=staff" ,  ) ,
#    ) ,



) ;
}
}
?>
