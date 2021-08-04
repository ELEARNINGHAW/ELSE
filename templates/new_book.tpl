<div id="basic-modal-content">
 <img src="img/loader.gif" style="width:80px; heigth:80px" /></div>
<script type='text/javascript' src='lib/jquery.simplemodal.js'></script>
<script src="lib/dropzone.min.js"></script>
<link rel="stylesheet" href="lib/dropzone.min.css">

<h3 style="margin:10px; padding:10px;  " class="bgDef bg{$collection.bib_id}" >
    {$collection.title} : Medien hinzufügen <a style="float:right;" href="{$back_URL}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left.svg" /></a>
</h3>

<div id='basic-modal'>
{* -------------------------------------------------------------------------------------------------------- *}
{* --------------- Eingabefelder Titel/Autor/Signatur für die Buch-Suchmaske ------------------------------ *}
{* -------------------------------------------------------------------------------------------------------- *}

{if $page == "1"}
{if $searchHits < 1}
 <h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; ">Suchergebniss: {$searchHits} Treffer  für {$book.title}{$book.author}{$book.signature}</h3>
 <div style="margin:10px;  padding:0px;">Sie k&ouml;nnen nun:</div>
 <div style="margin:10px; margin-bottom: 10px; padding:10px;  padding-bottom:20px;  text-height: 150%; border:solid 1px black; ">
 <div style="font-size:35px; float:left; padding:10px; margin:5px; display:block;   background-color:#EFEFEF">A</div> Einen Bestellwunsch über einen Erwerbungsvorschlag vornehmen.<br/><br> Die Bearbeitung kann 2-3 Wochen dauern.<br><br>
 <div style="display:block; padding:4px; margin-left:55px; width:450px;" >
 <a style ="text-decoration: none;"href="index.php?item=book&action=purchase_suggestion&dc_collection_id={$collection.dc_collection_id}&r={$user.role_id}"><div style="border:1px solid black; font-weight: 700; 14px; color:#000; background-color: #EFEFEF; padding:3px; " >Zum Erwerbungsvorschlag</div></a>
 </div>
 </div>
{/if}

 <div style="margin:10px; margin-bottom: 20px; padding:10px;  padding-bottom:30px; border:solid 1px black; ">
 {if $searchHits < 1}
  <div style="font-size:35px; float:left; padding:10px; margin:5px; margin-bottom:100px;display:block;   background-color:#EFEFEF">B</div>Eine neue Suche starten:<br><br>
 {/if}

   {if  $CONF['CONF']['show_get_media_by_katalog'] }
     <div class="text2">
  <h2> Medien hinzuf&uuml;gen </h2>
         <hr>
  <h3>&Uuml;ber den HAW-Katalog</h3>
<ol>
    {foreach from= $CONF['TXT']['katalog'] item=row}
        <li >{$row}</li>
    {/foreach}



</ol>

<div class="text2" style="text-align: left">
   <a  target="_blank"   id ="FButton2"    href="{$VUFIND.vuFindReserchURL}">

    <button style="padding:20px; width: 650px; margin-left: 30px; " class="ui-button ui-widget ui-corner-all" > Im HAW-Katalog recherchieren und Literaturliste erstellen. </button></a>
</div>
<br/>

<div class="text2" style="padding-left: 30px;">
  <form action="index.php" method="get">
  <span class="text2" > Link Literaturliste aus dem HAW-Katalog hier einfügen:<input style="text-align: left; width: 650px; margin-left: 2px; " type="text" name="mediaListID">
  <input type="submit" value="OK"  class="ui-button ui-widget ui-corner-all"  style="padding: 15px; width: 650px;     -webkit-border-radius: 5px; border-radius: 5px; ">   </span>
  <input type="hidden" name="item"             value="collection">
  <input type="hidden" name="action"           value="getMediaList" >
  <input type="hidden" name="loc"              value="1">
  <input type="hidden" name="r"                value="{$user.role_encode}">
  <input type="hidden" name="dc_collection_id" value="{$collection.dc_collection_id}">
  </form>
</div>
      <br/>    <br/>
<hr>
{/if}

{if  $col_predecessors|@count gt 0   AND $CONF['CONF']['takeover_col_predecessor'] }
<div class="text2">
    <h3>Importieren Sie die Medien aus einem Ihrer früheren Veranstaltungen</h3>
    {foreach key=cid item=c name=col_predecessors from=$col_predecessors }
  <div class="text2" style="text-align: left;">
  <a onClick="$('#FButton').spin('modal');"  href="index.php?item=collection&action=takeover&r={$user.role_id}&dc_to_collection_id={$c['dc_id']}">
     <button  style="padding:20px; width: 650px; margin-left: 30px;" class="ui-button ui-widget ui-corner-all">{$c['title']} </button></a>
  </div>
 {/foreach}<br/><br/>
 </div>
 <hr>
{/if}

{if  $CONF['CONF']['show_purchase_suggestion'] }
<div class="text2">
    <h3>Haben Sie im HAW-Katalog nicht das Gewünschte gefunden?</h3>
<div class="text2" style="text-align: left;">
 <a   onClick="$('#FButton').spin('modal');"  href="index.php?msg=&action=purchase_suggestion&loc=1&lmsid={$collection.dc_collection_id}">
      <button  style="padding:20px; width: 650px; margin-left: 30px;" class="ui-button ui-widget ui-corner-all">Erwerbungsvorschlag für den Semesterapparat </button></a>
</div>
<br/><br/>
    <hr>
</div>
{/if}

{if  $CONF['CONF']['show_import'] }
     <div class="text2">
       <h3>Import von Medien aus der Exportdatei eines vorhandenen Semesterapparates </h3>
         Den Export-Button finden Sie unter <img src="img/edit.png" style=" vertical-align: text-top;"  /> "Bearbeiten der allgemeinen Infos..." des Semesterapparates aus dem Sie Medien importieren möchten.
         <div style=" margin:20px; margin-top:0px;  padding:10px; border:solid 0px black;height:100px ">
     <span style="position: absolute;  left:30px); height: 100px; width:650px;   margin:1px; padding:10px; vertical-align: middle;font-size: 17px; color: #3383bb; font-weight: bold; border:solid 0px black;">

<form action="index.php?item=collection&amp;action=import&amp;dc_collection_id={$collection.dc_collection_id}" method="GET"
      class="dropzone"
      id="mydropzone"
      style="position: absolute; height: 75px; width:650px;   margin:2px;"
>

</form>
</span>

          <script>
              Dropzone.options.mydropzone =
                  {
                      paramName:   "file", // The name that will be used to transfer the file
                      maxFilesize: 0.05, // MB
                      accept: function(file, done)
                      {
                          if (file.name.substring(0, 5) == 'ELSE_' && file.name.substring(19, 23)=='.exp' )
                          {    done();
                          }
                          else
                          {
                              done("Wrong File!");
                          }
                      },
                      maxFiles:    1,
                      dictDefaultMessage: "Import / Datei hier einfügen"
                  };
          </script>
         </div>
         <br/><br/>
         <hr>
         {/if}


         {if $CONF.SRU.SRUenabled}

  <div style="display: block;" class="text2"> <h3>Medien hinzuf&uuml;gen &ndash; <span style="color: #ff0000;">Alternativ &uuml;ber Suche im alten online-Katalog (OPAC): <br> ACHTUNG: Dieser Service steht nur noch übergangsweise bis 31.12.2020 zur Verfügung.</span></h3>
    <ol>
      <li>Bitte geben Sie in dieser Suchmaske <b>Titel</b> und/oder <b>Autor*in</b> und/oder <b>Signatur</b> ein.</li>
        <li> Bei mehreren Treffern erscheint eine Auswahlliste (es werden max. 50 Treffer angezeigt).</li>
        <li>Ihre Auswahl wird übernommen und Sie können die einzelnen Medien annotieren und bearbeiten. </li>
    </ol>

     <form action="index.php" method="get">
     <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
       <tbody>
        <tr><td class="head1">Titel (Stichwort): </td><td><input class="txtin"  size="80" value="{$book.title|escape}"     name="title"></td></tr>
        <tr><td class="head1">Autor*in (Nachname):  </td><td><input class="txtin"  size="80" value="{$book.author|escape}"    name="author"></td></tr>
        <tr><td class="head1">Signatur:          </td><td><input class="txtin"  size="80" value="{$book.signature|escape}" name="signature"></td></tr>
       </tbody>
       <input type="hidden" name="loc"              value="1" >
       <input type="hidden" name="action"           value="search" >
       <input type="hidden" name="item"             value="book">
       <input type="hidden" name="r"                value="{$user.role_encode}">
       <input type="hidden" name="dc_collection_id" value="{$collection.dc_collection_id}">
      </table>
     <input style="float: right;" name="basic"  class="ui-button ui-widget ui-corner-all" value="&nbsp;&nbsp;&nbsp;SUCHE&nbsp;&nbsp;&nbsp;" type="submit">
     </form>
     </div>   {/if}
   <!-- -->
     {/if}

     {* -------------------------------------------------------------------------------------------------------- *}
     {* ----------------------------------   Tefferliste der Suche   ------------------------------------------- *}
     {* -------------------------------------------------------------------------------------------------------- *}

     {if $page == "2"}
      <h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px;  font-size: 11px; ">Suchergebniss: {$searchHits} Treffer</h3>
      <div style="margin:0px; margin-left:10px; margin-right:10px; padding:10px; font-weight: bold; color:#990000; border: 1px solid #444; font-size: 11px; ">Bitte w&auml;hlen Sie das gew&uuml;nschte Medium aus der Liste aus </div>

         {foreach from=$books_info item=b}
           {if (isset ( $b.title ))}
           <hr>
           <a class="hitlink_{$b.doc_type}" href="index.php?ppn={$b.ppn}&item={$b.item}&action=annoteNewMedia&dc_collection_id={$collection.dc_collection_id}&mode=new&r={$user.role_id}&loc=2">
           <table>
               {if (isset ( $b.title        ) AND $b.title        != "" )}<tr><td><div class="mediaListHeader">Titel:    </div></td><td><span class="mediaTxt">{$b.title|escape}                           </span></td></tr>{/if}
               {if (isset ( $b.author       ) AND $b.author       != "" )}<tr><td><div class="mediaListHeader">Autor:    </div></td><td><span class="mediaTxt">{$b.author|escape}                          </span></td></tr>{/if}

               {if ($b.doc_type == 'eBook' )}
                <tr><td><div class="mediaListHeader">Format: </div></td><td><span class="mediaTxt"> {$b.doc_type}               </span></td></tr>
               {/if}

               {if ($b.doc_type == 'Book') }
                   {if (isset ( $b.signature  ) AND $b.signature    != "" )}<tr><td><div class="mediaListHeader">Format: </div></td><td><span class="mediaTxt">Print - Sig:{$b.signature|escape}        </span></td></tr>{/if}
                   {if (isset ( $b.ISBN       ) AND $b.ISBN         != "" )}<tr><td><div class="mediaListHeader">ISBN:   </div></td><td><span class="mediaTxt">{$b.ISBN|escape:"br"}                     </span></td></tr>{/if}
               {/if}

               {* {if (isset ( $b.publisher    ) AND $b.publisher    != "" )}<tr><td><div class="mediaListHeader">Verlag:   </div></td><td><span class="mediaTxt">{$b.publisher|escape}                       </span></td></tr>{/if} *}
               {if (isset ( $b.year         ) AND $b.year         != "" )}<tr><td><div class="mediaListHeader">:         </div></td><td><span class="mediaTxt">{$b.year|escape}                            </span></td></tr>{/if}
               {if (isset ( $b.volume       ) AND $b.volume       != "" )}<tr><td><div class="mediaListHeader">:         </div></td><td><span class="mediaTxt">{$b.volume|escape}                          </span></td></tr>{/if}
           </table>
          </a>
             {/if}
             {foreachelse}   <h3 style="color:red; text-align:center; width:100%; height:50px;  vertical-align: middle; border: 2px solid red;">ERROR: Zur Zeit keine Verbindung zum Bibliotheksserver möglich.<br>(OPAC)</h3>
          {/foreach}
     {/if}
 </div>
</div>

<div id="basic-modal-content">

 <img src="img/loader.gif" style="width:80px; heigth:80px" /></div>
<script type='text/javascript' src='lib/jquery.simplemodal.js'></script>