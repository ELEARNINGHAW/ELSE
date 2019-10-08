<?php

class User
{
public $id;
public $forename;
public $surname;
public $hawaccount;
public $sex;
public $email;
public $department;
public $tmpcat;
public $role_id;
public $role_encode;
public $role_name;
public $dep_id;
public $dep_name;
public $dep_2_fak;
public $DEP_2_BIB;
public $fak_id;
public $fak_name;
public $fak_abk;
public $bib_id;
public $bib_name;
public $bib_ap_name;
public $bib_ap_mail;
public $state_id;

function __construct()
{
$this -> id             = '';
$this -> forename       = '';
$this -> surname        = '';
$this -> hawaccount     = '';
$this -> sex            = '';
$this -> email          = '';
$this -> department     = '';
$this -> tmpcat         = '';
$this -> role_id        = '';
$this -> role_encode    = '';
$this -> role_name      = '';
$this -> dep_id         = '';
$this -> dep_name       = '';
$this -> dep_2_fak      = '';
$this -> DEP_2_BIB      = '';
$this -> fak_id         = '';
$this -> fak_name       = '';
$this -> fak_abk        = '';
$this -> bib_id         = '';
$this -> bib_name       = '';
$this -> bib_ap_name    = '';
$this -> bib_ap_mail    = '';
$this -> state_id       = '';
}


function get_id()           { return $this-> id           ; }
function get_forename()     { return $this-> forename     ; }
function get_surname()      { return $this-> surname      ; }
function get_hawaccount()   { return $this-> hawaccount   ; }
function get_sex()          { return $this-> sex          ; }
function get_email()        { return $this-> email        ; }
function get_department()   { return $this-> department   ; }
function get_tmpcat()       { return $this-> tmpcat       ; }
function get_role_id()      { return $this-> role_id      ; }
function get_role_encode( ) { return $this-> role_encode  ; }
function get_role_name()    { return $this-> role_name    ; }
function get_dep_id()       { return $this-> dep_id       ; }
function get_dep_name()     { return $this-> dep_name     ; }
function get_dep_2_fak()    { return $this-> dep_2_fak    ; }
function get_DEP_2_BIB()    { return $this-> DEP_2_BIB    ; }
function get_fak_id()       { return $this-> fak_id       ; }
function get_fak_name()     { return $this-> fak_name     ; }
function get_fak_abk()      { return $this-> fak_abk      ; }
function get_bib_id()       { return $this-> bib_id       ; }
function get_bib_name()     { return $this-> bib_name     ; }
function get_bib_ap_name()  { return $this-> bib_ap_name  ; }
function get_bib_ap_mail()  { return $this-> bib_ap_mail  ; }
function get_state_id()     { return $this-> state_id    ; }
#function get_()   { return $this->    ; }


function set_id            ( $val )  { return $this-> id            =  $val ; }
function set_forename      ( $val )  { return $this-> forename      =  $val ; }
function set_surname       ( $val )  { return $this-> surname       =  $val ; }
function set_hawaccount    ( $val )  { return $this-> hawaccount    =  $val ; }
function set_sex           ( $val )  { return $this-> sex           =  $val ; }
function set_email         ( $val )  { return $this-> email         =  $val ; }
function set_department    ( $val )  { return $this-> department    =  $val ; }
function set_tmpcat        ( $val )  { return $this-> tmpcat        =  $val ; }
function set_role_id       ( $val )  { return $this-> role_id       =  $val ; }
function set_role_encode   ( $val )  { return $this-> role_encode   =  $val ; }
function set_role_name     ( $val )  { return $this-> role_name     =  $val ; }
function set_dep_id        ( $val )  { return $this-> dep_id        =  $val ; }
function set_dep_name      ( $val )  { return $this-> dep_name      =  $val ; }
function set_dep_2_fak     ( $val )  { return $this-> dep_2_fak     =  $val ; }
function set_DEP_2_BIB     ( $val )  { return $this-> DEP_2_BIB     =  $val ; }
function set_fak_id        ( $val )  { return $this-> fak_id        =  $val ; }
function set_fak_name      ( $val )  { return $this-> fak_name      =  $val ; }
function set_fak_abk       ( $val )  { return $this-> fak_abk       =  $val ; }
function set_bib_id        ( $val )  { return $this-> bib_id        =  $val ; }
function set_bib_name      ( $val )  { return $this-> bib_name      =  $val ; }
function set_bib_ap_name   ( $val )  { return $this-> bib_ap_name   =  $val ; }
function set_bib_ap_mail   ( $val )  { return $this-> bib_ap_mail   =  $val ; }
function set_state_id      ( $val )  { return $this-> state_id      =  $val ; }
#function set_    ( $val )  { return $this->     =  $val ; }

function obj2array()           {  return (array) ( $this   );               }
function array2obj( $array )   {  foreach ($array as $k => $v )  { $this->$k = $v;  }      }

}