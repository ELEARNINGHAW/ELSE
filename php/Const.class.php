<?php
class CONSTANT
{
var $default_role_id;
var $sem;
var $debug_level  = 1;
var $bib_id ;
var $CONST_letter_header;
var $CONST_ACTION_INFO ;

function __construct()
{
#$this->sem[ 'W12' ] = array( '2012_09_01' , '2013_02_28' );
#$this->sem[ 'S13' ] = array( '2013_03_01' , '2013_08_31' );
#$this->sem[ 'W13' ] = array( '2013_09_01' , '2014_02_28' );
#$this->sem[ 'S14' ] = array( '2014_03_01' , '2014_08_31' );
#$this->sem[ 'W14' ] = array( '2014_09_01' , '2015_02_28' );
#$this->sem[ 'S15' ] = array( '2015_03_01' , '2015_08_31' );
#$this->sem[ 'W15' ] = array( '2015_09_01' , '2016_02_29' );
#$this->sem[ 'S16' ] = array( '2016_03_01' , '2016_08_31' );
#$this->sem[ 'W16' ] = array( '2016_09_01' , '2017_02_28' );
#$this->sem[ 'S17' ] = array( '2017_03_01' , '2017_08_31' );
#$this->sem[ 'W17' ] = array( '2017_09_01' , '2018_02_03' );
#$this->sem[ 'S18' ] = array( '2018_02_03' , '2018_07_14' );
    $this->sem[ 'W18' ] = array( '2018_07_14' , '2019_02_02' );
    $this->sem[ 'S19' ] = array( '2019_02_02' , '2019_07_13' );
    $this->sem[ 'W19' ] = array( '2019_09_10' , '2020_01_31' );
#$this->sem[ 'S20' ] = array( '2020_03_09' , '2020_07_10' );
#$this->sem[ 'W20' ] = array( '2020_09_14' , '2021_01_29' );
#$this->sem[ 'S21' ] = array( '2021_03_15' , '2021_07_16' );
#$this->sem[ 'W21' ] = array( '2021_09_20' , '2022_02_04' );
#$this->sem[ 'S22' ] = array( '2022_03_21' , '2022_07_22' );


if   ( !isset ($this->default_role_id)) $this->default_role_id = 3;

if   ( isset ( $_POST[ 'bib_id' ] ) )  { $this->bib_id = $_POST[ 'bib_id' ] ; }
else                                   { $this->bib_id = 'HAW' ;              }


$this->CONST_letter_header = array ( 'A' , 'B' , 'C' , 'D' , 'E' , 'F' , 'G' , 'H' , 'I' , 'J' , 'K' , 'L' , 'M' , 'N' , 'O' , 'P' , 'Q' , 'R' , 'S' , 'T' , 'U' , 'V' , 'W' , 'X' , 'Y' , 'Z' ) ;


## actions info ## Zur Zeit nur genutzt für die Buttons zur Medienbearbeitung
$this->CONST_ACTION_INFO = array
(    'coll_meta_save'                 => array
    (                                                                           /* Neues Medium anlegen (suchen, annotieren, in SA speichern) ## Buch (- -> 1): [Neu bestellt] ## E-Book (- -> 3): [Ist aktiv]     /   Neuer SA anlegen, annotieren, speichern,  SA ## (- -> 3): [ist aktiv] */
        'button'            => 'coll_meta_save' ,
        'button_label'      => 'neu anlegen' ,
        'input'             => array ( "mode" => "new" ) ,
        'acl'               => array 
        (
            "user"       => "any" ,
            "collection" => "role=admin,role=edit,role=staff" ,
            "physical"   => "role=admin,role=edit,role=staff" ,
            "online"     => "role=admin,role=edit,role=staff" ,
          ) ,
    ) ,

    'coll_meta_edit'      => array
    (                                                                           #  Metadaten des SA bearbeiten              (3 -> 3): [ist aktiv]  #
        'button'            => 'coll_meta_edit' ,
        'button_label'      => 'Bearbeiten' ,
        'input'             => array ( "mode" => "edit" ) ,
        'acl'               => array (  "collection" => "role=admin,owner=true,role=staff",  ) ,
        'button_visible_if' => array 
        (
            "item"       => array ( "collection" ) ,
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

  'delete'             => array
      (                                                                         /*  Medium (E-Book, Buch) oder SA  wird gelöscht  (5 -> 6): Erscheint nicht mehr   */
        'button'            => 'delete' ,
        'button_label'      => 'Löschen' ,
        'input'             => array () ,
        'button_visible_if' => array
        (
            "state"    => array ( "inactive" ) ,
            "mode"     => array ( "edit" , "admin" , "staff" ) ,
            "loc"      => array ( "1", "2", "3"  ) ,
        ) ,
        'acl' => array
        (
            "physical"       => "role=admin,owner=true,role=staff" ,
            "online"         => "role=none,owner=none" ,
        ) ,
    ) ,
    
    'accept'                => array
    (                                                                           /*  Bestellwunsch (oder Kaufvorschlag) wurde akzeptiert und wir von HIBS bearbeitet (1 -> 2): [Wird bearbeitet]   */
        'button'            => 'accept' ,
        'button_label'      => 'Bestellung annehmen' ,
        'input'             => array ( "state" => "open" ) ,
        'acl' => array (  "physical" => "role=admin,role=staff" ,  ) ,
        'button_visible_if' => array 
        (
            "loc"      => array ( "1"   ) ,
            "state"    => array ( "new", "suggest" ) ,
            "mode"     => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
    'cancel_order'        => array
    (                                                                           /*  Bestellwunsch wurde von HIBS (oder owner) abgelehnt/storniert   (1 -> 5): [Ist inaktiv] */
        'button'            => 'cancel_order' ,
        'button_label'      => 'Bestellung stornieren' ,
        'input'             => array ( "state" => "inactive" ) ,
        'acl' => array ( "physical" => "role=admin,owner=true,role=staff" ,  ) ,
        'button_visible_if' => array 
        (
            "state"    => array ( "new" ) ,
            "loc"      => array ( "1"    ) ,
            "mode"     => array ( "edit" , "staff" , "admin" ) ,
        ) ,
    ) ,
    
    'finished'            => array
    (                                                                           /*  Bestellwunsch wurde erledigt und steht nun (im Bücherregal SA) zur Verfügung  (2 -> 3): [Ist aktiv]   */
        'button'            => 'finished' ,
        'button_label'      => 'Bestellung erledigt' ,
        'input'             => array ( "state" => "active" ) ,
        'button_visible_if' => array 
        (
            "state" => array ( "open" ) ,
            "mode"  => array ( "staff" , "admin" ) ,
        ) ,
        'acl'               => array ( "physical" => "role=admin,role=staff" ,   ) ,
    ) ,
    
    'release'             => array
    (                                                                           /* Buch kann zurückgegeben werden  (3 -> 4): [Wird entfernt] */
        'button'            => 'release' ,
        'button_label'      => 'Rückgabe' ,
        'input'             => array ( "state" => "obsolete" ) ,
        'acl'               => array ( "physical" => "role=admin,owner=true" , ) ,
        'button_visible_if' => array
        (
            "loc"      => array ( "1"    ) ,
            "state" => array ( "active" ) ,
            "mode" => array ( "edit" , "admin" ,"staff" ) ,
        ) ,
    ) ,
    
    'return'                => array
    (                                                                           /* Buch ist zurückgegeben worden   (4 -> 5): [Inaktiv]   */
        'button'            => 'return' ,
        'button_label'      => 'Rückgabe erledigt' ,
        'input'             => array ( "state" => "inactive" ) ,
        'acl'               => array ( "physical" => "role=admin,role=staff" , ) ,
        'button_visible_if' => array 
        (
            "loc"      => array ( "1"   ) ,
            "state"     => array ( "obsolete" , "delete") ,
            "mode"      => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
    'cancel_release'        => array
    (                                                                           /* Buch wird doch nicht zurückgegeben (sondern sogar verlängert)  (4 -> 2): [Wird bearbeitet]   */
        'button'            => 'cancel_release' ,
        'button_label'      => 'Bestellung verlängern' ,
        'input'             => array ( "state" => "open" ) ,
        'button_visible_if' => array 
        (
            "loc"      => array ( "1"    ) ,
            "state"     => array ( 'obsolete' ) ,
            "mode"      => array ( 'edit', 'staff' , 'admin' ) ,
        ) ,
        'acl' => array 
        (
            "physical"      => "role=admin, owner=true, role=staff" ,
        ) ,
    ) ,

    'revive'              => array
    (                                                                           /*  Bestellwunsch wird erneuert   (5 -> 1): [Neu bestellt]   */
        'button'            => 'revive' ,
        'button_label'      => 'Bestellung erneuern' ,
        'input'             => array ( "state" => "new" ) ,
        'button_visible_if' => array 
        (
            "loc"      => array ( "1"   ) ,
            "state"      => array ( "inactive" ) ,
            "mode"       => array (  "admin", 'staff' ) ,
        ) ,
        'acl' => array 
        (
            "article" => "role=admin,owner=true,role=staff" ,
            "physical"    => "role=admin,owner=true,role=staff" ,
        ) ,
    ) ,
    
    'deactivate'            => array
    (                                                                           /*  E-Book wird deaktiviert            (3 -> 5): [Ist inaktiv]     */
        'button'            => 'deactivate' ,
        'button_label'      => 'Deaktivieren' ,
        'input'             => array ( "state" => "inactive" ) ,
        'button_visible_if' => array
        (
            "state"  => array ( "active" ) ,
            "loc"      => array (  "2", "3"  ) ,
            "mode"   => array ( "edit" , "admin" ,'staff' ) ,
        ) ,
        'acl' => array 
        (

            "online"  => "owner=true,role=admin, role=staff",
        ) ,
    ) ,
    
    
    'activate'              => array
    (                                                                           /*  E-Book wird wieder aktiviert         (5 -> 3): [Ist aktiv]       */
        'button'            => 'activate' ,
        'button_label'      => 'Aktivieren' ,
        'input'             => array ( "state" => "active" ) ,
        'button_visible_if' => array
         (

            "state"   => array ( "inactive" ,"delete" ) ,
            "loc"      => array (  "2", "3"  ) ,
            "mode"   => array ( "edit" , "admin" ,'staff' ) ,
        ) ,
        'acl' => array 
        (

            "online"  => "owner=true,role=admin, role=staff",
        ) ,
    ) ,
    
    'edit'                => array
    (                                                                           /* Metadaten des Mediums (Buch, E-Book) wird bearbeiten    (a -> a )State ändert sich nicht */
        'button'            => 'edit' ,
        'button_label'      => 'Bearbeiten' ,
        'input'             => array ( "mode" => "edit" ) ,
        'button_visible_if' => array 
        (
            "loc"      => array ( "1" ,  "2", "3"  ) ,
            "mode"     => array ( "edit" , "staff" , "admin" ) ,
        ) ,
        'acl'               => array 
        (
            "physical"    => "role=admin,role=staff,owner=true" ,

            "online"   => "role=admin,role=staff,owner=true" ,
        ) ,
    ) ,
    
   'new_email'              => array
   (
        'button'            => 'new_email' ,
        'button_label'      => 'E-Mail senden' ,
        'input'             => array ( "mode" => "new" , "item" => "email" ) ,
        'acl'               => array ( "any" => "role=admin,role=staff" , ) ,
        'button_visible_if' => array 
        (
            "loc"      => array ( "1" ,  "2", "3"  ) ,
            "mode" => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
   'kill'              => array
   (
        'button'            => 'kill' ,
        'button_label'      => 'ENDGÜLTIG LÖSCHEN!' ,
        'input'             => array ( "state" => "delete" ) ,
        'acl'               => array ( "any" => "role=admin,role=staff" , ) ,
        'button_visible_if' => array 
        (
            "state"  => array ( "delete" ) ,
            "loc"      => array ( "1" ,  "2", "3"  ) ,
            "mode"   => array ( "staff" , "admin" ) ,
        ) ,
    ) ,
    
) ;

}


}
?>
