<?php
class Operator
{
  public $item;
  public $loc;
  public $action;
  public $category;
  public $mode;
  public $sem;
  public $msg;
  public $url;
  public $history;
  public $mediaListID;
  public $shelf_remain;

  function __construct()
  {
    $this -> item             = 'collection' ;
    $this -> loc              = '' ;
    $this -> action           = '' ;
    $this -> category         = '' ;
    $this -> mode             = '' ;
    $this -> sem              = '' ;
    $this -> msg              = '' ;
    $this -> url              = '#' ;
    $this -> history          = '' ;
    $this -> mediaListID      = '' ;
    $this -> shelf_remain     = '' ;
  }

  function get_item               ()   { return $this -> item          ; }
  function get_loc                ()   { return $this -> loc           ; }
  function get_action             ()   { return $this -> action        ; }
  function get_category           ()   { return $this -> category      ; }
  function get_mode               ()   { return $this -> mode          ; }
  function get_sem                ()   { return $this -> sem           ; }
  function get_msg                ()   { return $this -> msg           ; }
  function get_url                ()   { return $this -> url           ; }
  function get_history            ()   { return $this -> history       ; }
  function get_mediaListID        ()   { return $this -> mediaListID   ; }
  function get_shelf_remain       ()   { return $this -> shelf_remain  ; }


  function set_item                 ( $val )  { return $this -> item          =  $val ; }
  function set_loc                  ( $val )  { return $this -> loc           =  $val ; }
  function set_action               ( $val )  { return $this -> action        =  $val ; }
  function set_category             ( $val )  { return $this -> category      =  $val ; }
  function set_mode                 ( $val )  { return $this -> mode          =  $val ; }
  function set_sem                  ( $val )  { return $this -> sem           =  $val ; }
  function set_msg                  ( $val )  { return $this -> msg           =  $val ; }
  function set_url                  ( $val )  { return $this -> url           =  $val ; }
  function set_history              ( $val )  { return $this -> history       =  $val ; }
  function set_mediaListID          ( $val )  { return $this -> mediaListID   =  $val ; }
  function set_shelf_remain         ( $val )  { return $this -> shelf_remain  =  $val ; }


  function obj2array()              {  return json_decode(json_encode( $this  ), true);  }
  function array2obj( $array )      {  foreach ($array as $k => $v )  { $this->$k = $v;       } }

}