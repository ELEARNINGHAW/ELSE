<?php
$path      =  'config/';
$filename  =  'config.ini';
$file      =  $path.$filename;
$filename2 =  $filename. '.' .date( "Y-m-d-H-i-s" );
$file2     =  $file.     '.' .date( "Y-m-d-H-i-s" );

$conf = file ($file);

if( isset( $_POST[ 'submit'] ) )
{
  $conffile = '';

  foreach ( $conf as $c )   { 	$conffile .= $c;  } 

  $fp = fopen( $file2, "w" );  fwrite( $fp, $conffile              ) ;  fclose( $fp );
  $fp = fopen( $file , "w" );  fwrite( $fp, $_POST[ 'editconfig' ] ) ;  fclose( $fp );
}

echo '<form action="editconf.php" method="post">
<img src="../htdocs/img/svg/home_b.svg" width="32" height="32"/>
<input type="submit" name="submit"  /> 
<a href=\'../htdocs/index.php\' title="HOME" ><img src="../htdocs/img/svg/home_b.svg" width="32" height="32"/></a>


<br /> 
<textarea name="editconfig" cols="100" rows="50">';

$conf = file ($file);
foreach ( $conf as $c ) {	echo $c; } 

echo '</textarea>';

echo '</form>';