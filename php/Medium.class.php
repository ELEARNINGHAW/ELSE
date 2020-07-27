<?php
class Medium
{
  public  $id;
  public  $title;
  public  $author;
  public  $publisher;
  public  $edition;
  public  $signature;
  public  $ISBN;
  public  $ppn;
  public  $sigel;
  public  $leader;
  public  $format;
  public  $item;
 
  public  $doc_type_id;
  public  $doc_type;
  public  $in_SA;
  public  $physicaldesc;
  public  $collection_id;
  public  $location_id;
  public  $state_id;
  public  $shelf_remain;
  public  $notes_to_studies;
  public  $notes_to_staff;
  public  $created;
  public  $last_modified;
  public  $last_state_change;

function __construct( )
{
  $this -> id                           = '0';
  $this -> title                        = '';
  $this -> author                       = '';
  $this -> publisher                    = '';
  $this -> edition                      = '';
  $this -> signature                    = '';
  $this -> ISBN                         = '';
  $this -> ppn                          = '';
  $this -> sigel                        = '';
  $this -> leader                       = '';
  $this -> format                       = '';
  $this -> item                         = '';
  $this -> doc_type_id                  = '';
  $this -> doc_type                     = '';
  $this -> in_SA                        = '';
  $this -> physicaldesc                 = '';
  $this -> collection_id                = '';
  $this -> state_id                     = '';
  $this -> location_id                     = '';
  $this -> shelf_remain                 = '';
  $this -> notes_to_studies             = '';
  $this -> notes_to_staff               = '';
  $this -> created                      = '';
  $this -> last_modified                = '';
  $this -> last_state_change            = '';
}

function get_id               ()   { return $this -> id                   ; }
function get_title            ()   { return $this -> title                ; }
function get_author           ()   { return $this -> author               ; }
function get_publisher        ()   { return $this -> publisher            ; }
function get_edition          ()   { return $this -> edition              ; }
function get_signature        ()   { return $this -> signature            ; }
function get_ISBN             ()   { return $this -> ISBN                 ; }
function get_ppn              ()   { return $this -> ppn                  ; }
function get_sigel            ()   { return $this -> sigel                ; }
function get_leader           ()   { return $this -> leader               ; }
function get_format           ()   { return $this -> format               ; }
function get_item             ()   { return $this -> item                 ; }
function get_doc_type_id      ()   { return $this -> doc_type_id          ; }
function get_doc_type         ()   { return $this -> doc_type             ; }
function get_in_SA            ()   { return $this -> in_SA                ; }
function get_physicaldesc     ()   { return $this -> physicaldesc         ; }
function get_collection_id    ()   { return $this -> collection_id        ; }
function get_state_id         ()   { return $this -> state_id             ; }
function get_location_id      ()   { return $this -> location_id          ; }
function get_shelf_remain     ()   { return $this -> shelf_remain         ; }
function get_notes_to_studies ()   { return $this -> notes_to_studies     ; }
function get_notes_to_staff   ()   { return $this -> notes_to_staff       ; }
function get_created          ()   { return $this -> created              ; }
function get_last_modified    ()   { return $this -> last_modified        ; }
function get_last_state_change()   { return $this -> last_state_change    ; }


function set_id                          ( $val )  { return $this -> id                          =  $val ; }
function set_title                       ( $val )  { return $this -> title                       =  $val ; }
function set_author                      ( $val )  { return $this -> author                      =  $val ; }
function set_publisher                   ( $val )  { return $this -> publisher                   =  $val ; }
function set_edition                     ( $val )  { return $this -> edition                     =  $val ; }
function set_signature                   ( $val )  { return $this -> signature                   =  $val ; }
function set_ISBN                        ( $val )  { return $this -> ISBN                        =  $val ; }
function set_ppn                         ( $val )  { return $this -> ppn                         =  $val ; }
function set_sigel                       ( $val )  { return $this -> sigel                       =  $val ; }
function set_leader                      ( $val )  { return $this -> leader                      =  $val ; }
function set_format                      ( $val )  { return $this -> format                      =  $val ; }
function set_item                        ( $val )  { return $this -> item                        =  $val ; }
function set_doc_type_id                 ( $val )  { return $this -> doc_type_id                 =  $val ; }
function set_doc_type                    ( $val )  { return $this -> doc_type                    =  $val ; }
function set_in_SA                       ( $val )  { return $this -> in_SA                       =  $val ; }
function set_physicaldesc                ( $val )  {        $this -> physicaldesc                =  $val ;                     return $this -> format    =  $val ; }
function set_collection_id               ( $val )  { return $this -> collection_id               =  $val ; }
function set_state_id                    ( $val )  { return $this -> state_id                    =  $val ; }
function set_location_id                 ( $val )  { return $this -> location_id                 =  $val ; }
function set_shelf_remain                ( $val )  { return $this -> shelf_remain                =  $val ; }
function set_notes_to_studies            ( $val )  { return $this -> notes_to_studies            =  $val ; }
function set_notes_to_staff              ( $val )  { return $this -> notes_to_staff              =  $val ; }
function set_created                     ( $val )  { return $this -> created                     =  $val ; }
function set_last_modified               ( $val )  { return $this -> last_modified               =  $val ; }
function set_last_state_change           ( $val )  { return $this -> last_state_change           =  $val ; }


function obj2array()           {  return json_decode(json_encode( $this  ), true);  }
function array2obj( $array )   {  foreach ($array as $k => $v )  { $this->$k = $v;       } }


function calcDocType()
{
  $pos = $this -> get_doc_type_id() ;

  #deb($_SESSION[ 'DOC_TYPE' ],1);
  $dt = $_SESSION[ 'DOC_TYPE' ][ $pos  ];
 # deb($pos);
 # deb($this);
  $this -> set_doc_type( $dt[ 'doc_type'   ] );
}


function calcDocTypeID()  ## docTypeID and Item
{
  foreach ( $_SESSION[ 'DOC_TYPE' ]  as $dt )
  {    if ($dt[ 'doc_type' ] == $this->get_doc_type() )
    {  $this -> set_doc_type_id( $dt[ 'id'   ] );
    }
  }
}

function calcItem()  ## docTypeID and Item
{
  foreach ( $_SESSION[ 'DOC_TYPE' ]  as $dt )
  { if ($dt[ 'doc_type' ] == $this->get_doc_type() )
    {  $this -> set_item(  $dt[ 'item' ] ); }
  }
}

}