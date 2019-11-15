<?php
class MedState
{  
  public $med_state_GE;
  public $med_state_SA;
  public $med_state_LH;
  public $med_state_EB;

  function __construct()
  {
    $this -> med_state_GE    = '';
    $this -> med_state_SA    = '';
    $this -> med_state_LH    = '';
    $this -> med_state_EB    = '';
  }
  function get_med_state_GE     ()        { return $this ->  med_state_GE             ; }
  function get_med_state_SA     ()        { return $this ->  med_state_SA             ; }
  function get_med_state_LH     ()        { return $this ->  med_state_LH             ; }
  function get_med_state_EB     ()        { return $this ->  med_state_EB             ; }

  function set_med_state_GE     ( $val )  { return $this ->  med_state_GE     =  $val ; }
  function set_med_state_SA     ( $val )  { return $this ->  med_state_SA     =  $val ; }
  function set_med_state_LH     ( $val )  { return $this ->  med_state_LH     =  $val ; }
  function set_med_state_EB     ( $val )  { return $this ->  med_state_EB     =  $val ; }

  function obj2array()           {  return json_decode(json_encode( $this  ), true);  }
  function array2obj( $array )   {  foreach ($array as $k => $v )  { $this->$k = $v;       } }
}

