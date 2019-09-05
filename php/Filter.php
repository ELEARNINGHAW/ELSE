<?php

class Filter
{
  public $bib;
  public $sem;
  public $state;
  public $type;
  public $filter;
# public $;

  function __construct()
  {
    $this -> bib    = '' ;
    $this -> sem    = '' ;
    $this -> state  = '' ;
    $this -> type   = '' ;
    $this -> user   = '' ;
#   $this ->      = '' ;

  }

  function get_bib                 ()   { return $this -> bib       ; }
  function get_sem                 ()   { return $this -> sem       ; }
  function get_state               ()   { return $this -> state     ; }
  function get_type                ()   { return $this -> type      ; }
  function get_user                ()   { return $this -> user      ; }
#  function get_                  ()   { return $this ->           ; }


  function set_bib                  ( $val )  { return $this -> bib        =  $val ; }
  function set_sem                  ( $val )  { return $this -> sem        =  $val ; }
  function set_state                ( $val )  { return $this -> state      =  $val ; }
  function set_type                 ( $val )  { return $this -> type       =  $val ; }
  function set_user                 ( $val )  { return $this -> user       =  $val ; }
#  function set_                    ( $val )  { return $this ->         =  $val ; }

  function obj2array()              {  return json_decode(json_encode( $this  ), true);  }
  function array2obj( $array )      {  foreach ($array as $k => $v )  { $this->$k = $v;       } }

}