<?php

class Operator
{
  public $item;
  public $action;
  public $category;
  public $mode;
  public $sem;
  public $msg;
  public $url;
  public $history;
  public $sortorder;
#  public $role;
#  public $off;

  #  public $collection_id;
#  public $document_id;
#  public $;

  function __construct()
  {
    $this -> item             = 'collection' ;
    $this -> action           = '' ;
    $this -> category         = '' ;
    $this -> mode             = '' ;
    $this -> sem              = '' ;
    $this -> msg              = '' ;
    $this -> url              = '#' ;
    $this -> history          = '' ;
#    $this -> sortorder        = '' ;
#    $this -> redirect         = '' ;
#    $this->    = '' ;
}

  function get_item               ()   { return $this -> item          ; }
  function get_action             ()   { return $this -> action        ; }
  function get_category           ()   { return $this -> category      ; }
  function get_mode               ()   { return $this -> mode          ; }
  function get_sem                ()   { return $this -> sem           ; }
  function get_msg                ()   { return $this -> msg           ; }
  function get_url                ()   { return $this -> url           ; }
  function get_history            ()   { return $this -> history       ; }
#  function get_sortorder          ()   { return $this -> sortorder     ; }
#  function get_redirect           ()   { return $this -> redirect      ; }
#
#  function get_role               ()   { return $this -> role          ; }
#  function get_off                ()   { return $this -> off           ; }
#  function get_document_id        ()   { return $this -> document_id    ; }
#  function get_                   ()   { return $this ->           ; }


  function set_item                 ( $val )  { return $this -> item          =  $val ; }
  function set_action               ( $val )  { return $this -> action        =  $val ; }
  function set_category             ( $val )  { return $this -> category      =  $val ; }
  function set_mode                 ( $val )  { return $this -> mode          =  $val ; }
  function set_sem                  ( $val )  { return $this -> sem           =  $val ; }
  function set_msg                  ( $val )  { return $this -> msg           =  $val ; }
  function set_url                  ( $val )  { return $this -> url           =  $val ; }
  function set_history              ( $val )  { return $this -> history       =  $val ; }
#  function set_sortorder            ( $val )  { return $this -> sortorder     =  $val ; }
#  function set_off                  ( $val )  { return $this -> off           =  $val ; }
#  function set_redirect             ( $val )  { return $this -> redirect      =  $val ; }
#
#  function set_role                 ( $val )  { return $this -> role          =  $val ; }
#  function set_document_id          ( $val )  { return $this -> document_id   =  $val ; }
#  function set_                    ( $val )  { return $this ->         =  $val ; }

  function obj2array()              {  return json_decode(json_encode( $this  ), true);  }
  function array2obj( $array )      {  foreach ($array as $k => $v )  { $this->$k = $v;       } }

}