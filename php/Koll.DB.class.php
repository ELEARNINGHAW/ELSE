<?php

class KollDB
{

  var $db ;
  var $log ;
  var $status ;
  var $salt ; 
  var $URL ; 

  function __construct($conf )
  {
    {
      $this->db = new SQLite3( 'inc/DB/ER_anfrage.sqlite.s3db' ) ;
      if ( $this->db )
      {
        $this->log = fopen( "inc/ER-anfrage.log" , "a" ) ;
      } else
      {
        die( "<b>KEINE Verbindung zur Datenbank möglich</b>" ) ;
      }
    }
    $this->status = $conf->status ;
    $this->salt   = $conf->salt ;
    $this->URL    = $conf->URL ;

  }

  function insertLRData( $SESS )// Neuer Satz in DB anlegen mit Standarddaten   
  {
    $LR = $SESS[ 'LR' ] ;
    $US = $SESS[ 'US' ] ;
    // Mapping der Courskategorie auf HAW-Code !!!! TODO checken wenn ccatagorie == haw code

    $SQL = "INSERT INTO LR_Anfrage  
     ( 
	    	 uid  
	    	,ukurzel  
	    	,uusername  
			,ufirstname  
			,ulastname  
			,uemail  
			,uinstitution  
			,ufakultaet  
			,udepartment  
			,cid  
			,ccategory  
			,csortorder  
			,cfullname 
			,cshortname  
			,initdate 
			,changedate 
			,changetime 
			,state 
			,level 			
			,info 
			,cdest 
		)
		VALUES
		(
				 \"" . $US[ 'uid'           ] . "\"
				,\"" . $LR[ 'ukurzel'       ] . "\"
				,\"" . $US[ 'uusername'     ] . "\"
				,\"" . $US[ 'ufirstname'    ] . "\"
				,\"" . $US[ 'ulastname'     ] . "\"
				,\"" . $US[ 'uemail'        ] . "\"
				,\"" . $US[ 'uinstitution'  ] . "\"
				,\"" . $US[ 'ufakultaet'    ] . "\"
				,\"" . $US[ 'udepartment'   ] . "\"
				,\"" . $LR[ 'cid'           ] . "\"
				,\"" . $LR[ 'ccategory'     ] . "\"
				,\"" . $LR[ 'csortorder'    ] . "\"
				,\"" . $LR[ 'cfullname'     ] . "\"
				,\"" . $LR[ 'cshortname'    ] . "\"
				,\"" . date( "d.m.Y" )        . "\"
				,\"" . date( "d.m.Y" )        . "\"
				,\"" . date( "H:i:s" )        . "\"
				,\"" . $LR[ 'state'         ] . "\"
				,\"" . $LR[ 'level'         ] . "\"
				,\"" . $LR[ 'info'          ] . "\"
				,\"" . $LR[ 'servername'    ] . "\"
  		) 
		" ;
    $logEntry = " -insert new   - , " . $US[ 'ufirstname' ] . " " . $US[ 'ulastname' ] . " , " . date( "d.m.Y" ) . " , " . date( "H:i:s" ) . " , " . $this->status[ 2 ][ 'msg' ] . " , " . $LR[ 'cshortname' ] . "\r\n" ;
    fwrite( $this->log , $logEntry ) ;
    #  echo $SQL;
    return $this->db->exec( $SQL ) ;
  }

  function updateLRStatus( $SESS )   // Status updaten
  {
    $LR = $SESS[ 'LR' ] ;
    $US = $SESS[ 'US' ] ;

    $URL =   $this->URL;
    
    $URL .= "?item=collection&ro=NQ%3D%3D&action=renew&uid=1";
    $URL .= "&collection_id=". base64_encode( $LR[ 'cshortname' ] );
    $URL .= "&mode=".   sha1 ( base64_encode( $LR[ 'cshortname' ]  ) . $this->salt   );

    #deb($URL);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $URL  );
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    #die($URL);
    
    $ret = curl_exec($ch);
    #die($ret);
    curl_close($ch);

    $SQL = 
"UPDATE LR_Anfrage SET 
 state 	           =	\"" . $LR [ 'state' ] . "\"
,changedate        =    \"" . date( "d.m.Y" ) . "\"
,changetime        =    \"" . date( "H:i:s" ) . "\"
WHERE  cshortname  = 	\"" . $LR[ 'cshortname' ] . "\" " ;

     $logEntry = " -update status- , " . $US[ 'ufirstname' ] . " " . $US[ 'ulastname' ] . " , " . date( "d.m.Y" ) . " , " . date( "H:i:s" ) . " , " . $this->status[ $LR [ 'state' ] ][ 'msg' ] . " , " . $LR[ 'cshortname' ] . "\r\n" ;
      fwrite( $this->log , $logEntry ) ;
      

      #deb($SQL);
#      die ( $LR [ 'state' ]);

      return $this->db->exec( $SQL ) ;
  }

  function updateLRInfo( $SESS )   // Infofeld updaten 
  {
    $LR = $SESS[ 'LR' ] ;
    $US = $SESS[ 'US' ] ;
    $SQL = "UPDATE LR_Anfrage SET 
         info 	           =	\"" . $LR[ 'info' ] . "\"
   		WHERE  cshortname  = 	\"" . $LR[ 'cshortname' ] . "\"
 		" ;
    $logEntry = " -update r-info- , " . $US[ 'ufirstname' ] . " " . $US[ 'ulastname' ] . " , " . date( "d.m.Y" ) . " , " . date( "H:i:s" ) . " , " . $LR [ 'info' ] . " , " . $LR[ 'cshortname' ] . "\r\n" ;
    fwrite( $this->log , $logEntry ) ;

    return $this->db->exec( $SQL ) ;
  }

  function isLRInList( $LR )
  {
    $r = NULL ;
    $SQL = "
		SELECT * FROM LR_Anfrage 
		WHERE cshortname =='" . $LR[ 'cshortname' ] . "'" ;

    $result = $this->db->query( $SQL ) ;

    while ( $LRoq = $result->fetchArray() )          // Daten zeilenweise in Array speichern  
    {
      $r[] = $LRoq ;
    }
    if ( $r[ 0 ][ 0 ] )
      return true ;
    else
      return false ;
  }

  function isUSInList( $US )
  {
    $r = NULL ;
    $SQL = "
		SELECT uusername FROM LR_Anfrage 
		WHERE uusername =='" . $US[ 'uusername' ] . "'" ;

    $result = $this->db->query( $SQL ) ;

    while ( $LRoq = $result->fetchArray() )          // Daten zeilenweise in Array speichern  
    {
      $r[] = $LRoq ;
    }
    if ( $r[ 0 ][ 0 ] )
      return true ;
    else
      return false ;
  }

  function getLRData( $LR )
  {
    $r = NULL ;
    $SQL = "
		SELECT * FROM LR_Anfrage 
		WHERE cshortname =='" . $LR[ 'cshortname' ] . "'" ;

    $result = $this->db->query( $SQL ) ;

    while ( $LRoq = $result->fetchArray() )          // Daten zeilenweise in Array speichern  
    {
      $r[] = $LRoq ;
    }

    if ( $r[ 0 ] )
      return $r[ 0 ] ;
    else
      return false ;
  }

  function getListOfLR()
  {
    $r = NULL ;
    $SQL = "
		SELECT * FROM LR_Anfrage  ORDER BY  'ufakultaet'" ;

    $result = $this->db->query( $SQL ) ;

    while ( $LRoq = $result->fetchArray() )          // Daten zeilenweise in Array speichern  
    {
      $r[] = $LRoq ;
    }

    if ( $r[ 0 ] )
      return $r ;
    else
      return false ;
  }

  function getListOfNewLR( $username )
  {
    $r = NULL ;
    $SQL = 'SELECT * FROM LR_Anfrage  WHERE  uusername = \'' . $username . '\'' ;
    $result = $this->db->query( $SQL ) ;

    while ( $LRoq = $result->fetchArray() )          // Daten zeilenweise in Array speichern  
    {
      $r[] = $LRoq ;
      $tmp[ 0 ] = 0 ;
      $tmp[ 1 ] = $LRoq[ 'ufakultaet' ] ;
      $tmp[ 2 ] = $LRoq[ 'cshortname' ] ;
      $tmp[ 3 ] = $LRoq[ 'cfullname' ] ;
      $t[] = $tmp ;
    }

    if ( $r[ 0 ] )
    {
      $ret[ 'long' ] = $r ;
      $ret[ 'short' ] = $t ;
      return $ret ;
    } else
      return false ;
  }

  function getStatus( $cshortname )
  {
    $SQL = "
		SELECT state from LR_Anfrage 
		WHERE cshortname == '" . $cshortname . "'" ;

    $result = $this->db->query( $SQL ) ;

    while ( $studi = $result->fetchArray() ) // TODO -- kann optimiert werden --
    {
      $list[] = $studi ;
    }

    if ( isset( $list[ 0 ][ 0 ] ) )
      return $list[ 0 ][ 0 ] ;
    else
      return 0 ;
  }

  function getERStatusListe()
  {
    $SQL = "SELECT state, udepartment , COUNT (*) from LR_Anfrage GROUP BY state, udepartment" ;
    $result = $this->db->query( $SQL ) ;

    while ( $tmp = $result->fetchArray() ) // TODO -- kann optimiert werden --
    {
      $list[] = $tmp ;
    }
    return $list ;
  }

  function getInfo( $cshortname )
  {
    $SQL = "
		SELECT info from LR_Anfrage 
		WHERE cshortname == '" . $cshortname . "'" ;

    $result = $this->db->query( $SQL ) ;

    while ( $studi = $result->fetchArray() ) // TODO -- kann optimiert werden --
    {
      $list[] = $studi ;
    }
    if ( isset( $list[ 0 ][ 0 ] ) )
      return $list[ 0 ][ 0 ] ;
    else
      return false ;
  }

}

?>