function mainmenu(){
$(" #nav ul ").css({display: "none"}); // Opera Fix
$(" #nav li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
}

function statemenu(){
$(" #nav2 ul ").css({display: "none"}); // Opera Fix
$(" #nav2 li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
}


function waitimage()
{
  $('#basic-modal .basic').click(function (e) {
	$('#basic-modal-content').modal();
	return false;
	});
}


function showIt(id) {
    document.getElementById(id).style.display = 'block';
}


 $(document).ready(function(){					
	mainmenu();
	statemenu();
 /* waitimage();
  */
  collection_id     = GET('collection_id');
  dc_collection_id  = GET('dc_collection_id');
  ro                = GET('ro').substring(0,2);
  r                 = GET('r');


  /* Drag n Drop - SORTIERFUNKTION der Medien innerhalb des Semesterapparats */
  $(function() {  $( ".column" ).sortable( {  placeholder: "mediaInSA-placeholder", items: ".mediaInSA",  axis: "y"  }); });
  $( "#column li" ).disableSelection();
  $( ".column" ).on( "sortstop", function( event, ui ) {  sortedIDs = $( ".column" ).sortable( "toArray" );   myURL = "index.php?item=collection&action=resort&sortorder="+sortedIDs+"&dc_collection_id="+dc_collection_id;  $.get( myURL  );  });
 
  /* Sortierfunktion der Dokumente innerhalb des Semesterapparats wird deaktiviert */
  /* Bei: Rolle != Admin/Staff/Editor  */ 
  if   ( ro == 'Mw' || ro == 'Mg' || ro == 'MQ' || r == 1 || r ==  2|| r == 3 )  { }
  else                                             { $( ".column" ).sortable( "destroy" ); }  
  /* Bei: Dokumentenlisten mit spezifischen Zuständen (aktiv/wird bearbeitet/... */
  if (GET('action') == 'show_media_list')                 { $( ".column" ).sortable( "destroy" ); }
});


/* Rechteermittlung für Drag n Drop Sortierfunktion, über GET Rechte r */
function GET(v)
{
  if(!HTTP_GET_VARS[v]){return 'undefined';}
  return HTTP_GET_VARS[v];
}
 
HTTP_GET_VARS=[];
strGET = document.location.search.substr( 1,document.location.search.length );

if( strGET!='' )
{
  gArr = strGET.split( '&' );
  for( i = 0 ; i < gArr.length; ++i )
  {
    v= '' ; vArr=gArr[i].split( '=' );
    if( vArr.length > 1 ){ v = vArr[ 1 ]; }
    HTTP_GET_VARS[ unescape( vArr[ 0 ] ) ] = unescape( v );
  }
}
 


