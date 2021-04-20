<?php
class Collection
{  
  public $id;
  public $state_id;
  public $state_name;
  public $title;
  public $title_short;
  public $sem;
  public $created;
  public $last_modified;
  public $last_state_change;
  public $expiry_date;
  public $notes_to_studies_col;
  public $notes_to_staff_col;
  public $user_id;
  public $course_id;
  public $modul_id;
  public $sortorder;
  public $collection_id;
  public $dc_collection_id;
  public $to_collection_id;
  public $bib_id;
  public $media;

  public $Owner;
  public $MedState;
  public $Bib;

  function __construct(  )
  {
    $this -> id                    = null;
    $this -> title                 = null;
    $this -> title_short           = null;
    $this -> sem                   = null;
    $this -> state_id              = null;
    $this -> state_name            = null;
    $this -> created               = null;
    $this -> last_modified         = null;
    $this -> last_state_change     = null;
    $this -> expiry_date           = null;
    $this -> notes_to_studies_col  = null;
    $this -> notes_to_staff_col    = null;
    $this -> user_id               = null;
    $this -> course_id             = null;
    $this -> modul_id              = null;
    $this -> sortorder             = null;
    $this -> collection_id         = null;
    $this -> dc_collection_id      = null;
    $this -> to_collection_id      = null;
    $this -> bib_id                = null;
    $this -> Owner                 = new User();
    $this -> MedState              = new MedState();
    $this -> Bib                   = new Bib();
    $this -> media                 = null;
  }

  function get_id()                    { return $this -> id                   ; }
  function get_title()                 { return $this -> title                ; }
  function get_title_short()           { return $this -> title_short          ; }
  function get_sem()                   { return $this -> sem                  ; }
  function get_state_id()              { return $this -> state_id             ; }
  function get_state_name()            { return $this -> state_name           ; }
  function get_created()               { return $this -> created              ; }
  function get_last_modified()         { return $this -> last_modified        ; }
  function get_last_state_change()     { return $this -> last_state_change    ; }
  function get_expiry_date()           { return $this -> expiry_date          ; }
  function get_notes_to_studies_col()  { return $this -> notes_to_studies_col ; }
  function get_notes_to_staff_col()    { return $this -> notes_to_staff_col   ; }
  function get_user_id()               { return $this -> user_id              ; }
  function get_course_id()             { return $this -> course_id            ; }
  function get_modul_id()              { return $this -> modul_id             ; }
  function get_sortorder()             { return $this -> sortorder            ; }
  function get_collection_id()         { return $this -> collection_id        ; }
  function get_dc_collection_id()      { return $this -> dc_collection_id     ; }
  function get_to_collection_id()      { return $this -> to_collection_id     ; }
  function get_bib_id ()               { return $this -> bib_id               ; }
  function get_Owner()                 { return $this -> Owner                ; }
  function get_Medstate()              { return $this -> MedState             ; }
  function get_Bib()                   { return $this -> Bib                  ; }
  function get_media()                 { return $this -> media                ; }

  function set_id                      ( $val ) { $this -> id                   =  $val ; }
  function set_title                   ( $val ) { $this -> title                =  $val ; }
  function set_title_short             ( $val ) { $this -> title_short          =  $val ; }
  function set_sem                     ( $val ) { $this -> sem                  =  $val ; }
  function set_state_id                ( $val ) { $this -> state_id             =  $val ; }
  function set_state_name              ( $val ) { $this -> state_name           =  $val ; }
  function set_created                 ( $val ) { $this -> created              =  $val ; }
  function set_last_modified           ( $val ) { $this -> last_modified        =  $val ; }
  function set_last_state_change       ( $val ) { $this -> last_state_change    =  $val ; }
  function set_expiry_date             ( $val ) { $this -> expiry_date          =  $val ; }
  function set_notes_to_studies_col    ( $val ) { $this -> notes_to_studies_col =  $val ; }
  function set_notes_to_staff_col      ( $val ) { $this -> notes_to_staff_col   =  $val ; }
  function set_user_id                 ( $val ) { $this -> user_id              =  $val ; }
  function set_course_id               ( $val ) { $this -> course_id            =  $val ; }
  function set_modul_id                ( $val ) { $this -> modul_id             =  $val ; }
  function set_sortorder               ( $val ) { $this -> sortorder            =  $val ; }
  function set_collection_id           ( $val ) { $this -> collection_id        =  $val ; }
  function set_dc_collection_id        ( $val ) { $this -> dc_collection_id     =  $val ; }
  function set_to_collection_id        ( $val ) { $this -> to_collection_id     =  $val ; }
  function set_bib_id                  ( $val ) { $this -> bib_id               =  $val ; }
  function set_Owner                   ( $val ) { $this -> Owner                =  $val ; }
  function set_Medstate                ( $val ) { $this -> MedState             =  $val ; }
  function set_Bib                     ( $val ) { $this -> Bib                  =  $val ; }
  function set_media                   ( $val ) { $this -> media                =  $val ; }

  function obj2array()
  {
      $tmp = (array) $this;
      $tmp[ 'Owner'    ] = (array) $this->Owner;
      $tmp[ 'MedState' ] = (array) $this->MedState;
      $tmp[ 'Bib'      ] = (array) $this->Bib;

      unset($tmp['media']);
      if (isset($this -> media))
      foreach ($this -> media as $med)
      {   $m = (array)$med;

        $tmp['media'][] = $m;
      }
      return  $tmp ;
  }

  function array2obj( $array )
  { $colltmp = new Collection();

    $colltmp->Owner     =  $this->Owner ;
    $colltmp->MedState  =  $this->MedState;
    $colltmp->Bib       =  $this->Bib;

    foreach ( $array as $k => $v )  {  $this->$k = $v;    }

    $this -> Owner              = $colltmp -> Owner    -> array2obj ( $colltmp->Owner    );
    $this -> MedState           = $colltmp -> MedState -> array2obj ( $colltmp->MedState );
    $this -> Bib                = $colltmp -> Bib      -> array2obj ( $colltmp->Bib      );

  }
}