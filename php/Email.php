<?php

class Email
{
public $to;
public $replay_to;
public $cc;
public $bc;
public $from;
public $subject;
public $mailtext;
public $collection_id;
public $document_id;


function __construct()
{
  $this -> to               = '' ;
  $this -> replay_to        = '' ;
  $this -> cc               = '' ;
  $this -> bcc              = '' ;
  $this -> from             = '' ;
  $this -> subject          = '' ;
  $this -> mailtext         = '' ;
  $this -> collection_id    = '' ;
  $this -> document_id      = '' ;
}

  function get_to                ( )       { return $this -> to              ; }
  function get_replay_to         ( )       { return $this -> replay_to       ; }
  function get_cc                ( )       { return $this -> cc              ; }
  function get_bcc               ( )       { return $this -> bcc             ; }
  function get_from              ( )       { return $this -> from            ; }
  function get_subject           ( )       { return $this -> subject         ; }
  function get_mailtext          ( )       { return $this -> mailtext        ; }
  function get_collection_id     ( )       { return $this -> collection_id   ; }
  function get_document_id       ( )       { return $this -> document_id     ; }

  function set_to                ( $val )  { return $this -> to              =  $val ; }
  function set_replay_to         ( $val )  { return $this -> replay_to       =  $val ; }
  function set_cc                ( $val )  { return $this -> cc              =  $val ; }
  function set_bcc               ( $val )  { return $this -> bcc             =  $val ; }
  function set_from              ( $val )  { return $this -> from            =  $val ; }
  function set_subject           ( $val )  { return $this -> subject         =  $val ; }
  function set_mailtext          ( $val )  { return $this -> mailtext        =  $val ; }
  function set_collection_id     ( $val )  { return $this -> collection_id   =  $val ; }
  function set_document_id       ( $val )  { return $this -> document_id     =  $val ; }

  function obj2array()           { return json_decode(json_encode( $this  ), true);  }
  function array2obj( $array )   { foreach ( $array as $k => $v )  { $this -> $k = $v;      } }
}