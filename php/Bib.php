<?php

class Bib
{
  public $bib_APMail  ;
  public $bib_id      ;
  public $bib_Name    ;
  public $bib_APName  ;

  function __construct()
  {
    $this ->bib_APMail    = '';
    $this ->bib_id        = '';
    $this ->bib_Name      = '';
    $this ->bib_APName    = '';
  }
    function get_bib_APMail     ()        { return $this -> bib_APMail       ; }
    function get_bib_id         ()        { return $this -> bib_id           ; }
    function get_bib_Name       ()        { return $this -> bib_Name         ; }
    function get_bib_APName     ()        { return $this -> bib_APName       ; }

    function set_bib_APMail     ( $val )  { $this -> bib_APMail      =  $val ; }
    function set_bib_id         ( $val )  { $this -> bib_id          =  $val ; }
    function set_bib_Name       ( $val )  { $this -> bib_Name        =  $val ; }
    function set_bib_APName     ( $val )  { $this -> bib_APName      =  $val ; }

    function obj2array()           {  return json_decode(json_encode( $this  ), true);  }
    function array2obj( $array )   {  foreach ($array as $k => $v )  { $this->$k = $v;  } }
}

