<div id="basic-modal-content">
 <img src="img/loader.gif" style="width:80px; heigth:80px" /></div>
<script type='text/javascript' src='lib/jquery.simplemodal.js'></script>
  
<h3 style="margin:10px; padding:10px; color: #FFF;" class="bgDef bg{$collection.bib_id}" >
    {$collection.title} : Suche im HAW-Katalog <a style="float:right;" href="{$back_URL}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a>
</h3>

<div id='basic-modal'>
{* -------------------------------------------------------------------------------------------------------- *}
{* --------------- Eingabefelder Titel/Autor/Signatur für die Buch-Suchmaske ------------------------------ *}
{* -------------------------------------------------------------------------------------------------------- *}

{if $page == "1"}
{if $searchHits < 1}
 <h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; color: #FFF; background-color: #600000;">Suchergebniss: {$searchHits} Treffer  für {$book.title}{$book.author}{$book.signature}</h3>
 <div style="margin:10px;  padding:0px;">Sie k&ouml;nnen nun:</div>
 <div style="margin:10px; margin-bottom: 10px; padding:10px;  padding-bottom:20px;  text-height: 150%; border:solid 1px black; ">
 <div style="font-size:35px; float:left; padding:10px; margin:5px; display:block;   background-color:#EFEFEF">A</div> Einen Bestellwunsch über einen Erwerbungsvorschlag vornehmen.<br/><br> Die Bearbeitung kann 2-3 Wochen dauern.<br><br>
 <div style="display:block; padding:4px; margin-left:55px; width:450px;" >
 <a style ="text-decoration: none;"href="index.php?item=book&action=purchase_suggestion&dc_collection_id={$collection.dc_collection_id}&r={$user.role_id}"><div style="border:1px solid black; font-weight: 700; 14px; color:#000; background-color: #EFEFEF; padding:3px; " >Zum Erwerbungsvorschlag</div></a>
 </div>
 </div>
{/if}

 <div style="margin:10px; margin-bottom: 0px; padding:10px;  padding-bottom:30px; border:solid 1px black; ">
 {if $searchHits < 1}
  <div style="font-size:35px; float:left; padding:10px; margin:5px; margin-bottom:100px;display:block;   background-color:#EFEFEF">B</div>Eine neue Suche starten:<br><br>
 {/if}
  <div class="text2">
  <h3> Medien hinzufügen </h3>
<ul>
  <li>Wechseln Sie über den Button unten zum HAW-Katalog und melden Sie sich dort mit Ihrer Bibliothekskennung an.</li>
  <li> Im HAW-Katalog erstellen Sie eine Literaturliste (weitere Infos finden Sie dort).</li>
  <li>Wechseln Sie dann zurück zu dieser Seite und fügen Sie den link der Literaturliste in das Feld unten ein.</li>
  <li> Nun werden die Medien nacheinander dem Semesterapparat hinzugefügt und Sie können diese annotieren und bearbeiten. </li>
</ul>

<div class="text2" style="text-align: center">
   <a  target="_blank"   id ="FButton2"    href="{$VUFIND.vuFindReserchURL}"> <button style="padding:20px;" class="ui-button ui-widget ui-corner-all" > Im HAW-Katalog recherchieren und die Literaturliste erstellen       </button></a>
</div>
<hr>

<div class="text2">
  <form action="index.php" method="get">
  <span class="text2" style="text-align: center;"> Link Literaturliste: <input type="text" name="mediaListID">
  <input type="submit" value="OK" style="padding: 15px;     -webkit-border-radius: 5px; border-radius: 5px; ">   </span>
  <input type="hidden" name="item"             value="collection">
  <input type="hidden" name="action"           value="getMediaList" >
  <input type="hidden" name="loc"              value="1">
  <input type="hidden" name="r"                value="{$user.role_encode}">
  <input type="hidden" name="dc_collection_id" value="{$collection.dc_collection_id}">
  </form>
  </div>
<!--
<hr>
<div class="text2"  style="display: block;" >
<ul><li> Um ein Medium direkt in Ihren Semesterapparat hinzuzufügen,<br />recherchieren Sie nun bitte im HAW Bibliothekskatalog.</li>
    <li> Durch einen Klick auf den <strong>Stern</strong> <img style="position:relative;  top:5px;" src="img/sternchen.png"> des gewünschten Mediums,<br />  übernehmen Sie dieses dann dort in Ihre  in Ihre <strong>"Merkliste"</strong>. </li>
</ul>
</div>

<div class="text2" style="text-align: center">
<a  onClick="$('#FButton').spin('modal');"  id ="FButton"  href="$CONF.VUFIND.vuListURL}?lmsid={$URLID}&lmsurl={$URL}"> <button style="padding:20px;" class="ui-button ui-widget ui-corner-all" > Im HAW-Katalog recherchieren und die Merkliste füllen      </button></a>
</div>
-->
<hr>
<div class="text2">
<ul><li> Haben Sie im HAW-Katalog nicht das Gewünschte gefunden?<BR></li></ul>
<div class="text2" style="text-align: center;">
 <a   onClick="$('#FButton').spin('modal');"  href="index.php?msg=&action=purchase_suggestion&loc=1&lmsid={$collection.dc_collection_id}">  <button  style="padding:20px;" class="ui-button ui-widget ui-corner-all">Erwerbungsvorschlag für den Semesterapparat </button></a>
</div>
</div>
  {if $CONF.SRU.SRUenabled}
  <hr>
  <div style="display: block;" class="text2"> <h3> oder Medien im OPAC suchen.</h3>
    <ul>
      <li>Bitte geben Sie in dieser Suchmaske <b>Titel</b> und / oder <b>Autor</b> und / oder <b>Signatur</b> ein. </li>
      <li>Das Buch wird dann im HIBS Online-Katalog gesucht. </li>
      <li>Bei mehreren Treffern erscheint eine Auswahlliste.<br>Es werden maximal {$maxRecords} Treffer angezeigt. </li>
      <li>Ihre Auswahl wird  &uuml;bernommen und erscheint in Ihrer Literaturliste. </li>
    </ul>

     <form action="index.php" method="get">
     <table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
       <tbody>
        <tr><td class="head1">Titel (Stichwort): </td><td><input class="txtin"  size="80" value="{$book.title|escape}"     name="title"></td></tr>
        <tr><td class="head1">Autor (Nachname):  </td><td><input class="txtin"  size="80" value="{$book.author|escape}"    name="author"></td></tr>
        <tr><td class="head1">Signatur:          </td><td><input class="txtin"  size="80" value="{$book.signature|escape}" name="signature"></td></tr>
       </tbody>
       <input type="hidden" name="loc"              value="1" >
       <input type="hidden" name="action"           value="search" >
       <input type="hidden" name="item"             value="book">
       <input type="hidden" name="r"                value="{$user.role_encode}">
       <input type="hidden" name="dc_collection_id" value="{$collection.dc_collection_id}">
      </table>
     <input style="float: right;" name="basic"  class="basic" value="&nbsp;&nbsp;&nbsp;SUCHE&nbsp;&nbsp;&nbsp;" type="submit">
     </form>
     </div>   {/if}
   <!-- -->
     {/if}

     {* -------------------------------------------------------------------------------------------------------- *}
     {* ----------------------------------   Tefferliste der Suche   ------------------------------------------- *}
     {* -------------------------------------------------------------------------------------------------------- *}

     {if $page == "2"}
      <h3 style="margin:10px; margin-bottom: 0px; margin-top: 0px; padding:10px; color: #FFF; background-color: #600000; font-size: 11px; ">Suchergebniss: {$searchHits} Treffer</h3>
      <div style="margin:0px; margin-left:10px; margin-right:10px; padding:10px; font-weight: bold; color:#990000; border: 1px solid #444; font-size: 11px; ">Bitte w&auml;hlen Sie das gew&uuml;nschte Medium aus der Liste aus </div>

         {foreach from=$books_info item=b}
             {if (isset ( $b.title ))}
<hr>
          <a class="hitlink_{$b.doc_type}" href="index.php?ppn={$b.ppn}&item={$b.item}&action=annoteNewMedia&dc_collection_id={$collection.dc_collection_id}&mode=new&r={$user.role_id}&loc=2">
           <table>
               {if (isset ( $b.title        ) AND $b.title        != "" )}<tr><td><div class="mediaListHeader">Titel:    </div></td><td><span class="mediaTxt">{$b.title|escape}                           </span></td></tr>{/if}
               {if (isset ( $b.author       ) AND $b.author       != "" )}<tr><td><div class="mediaListHeader">Autor:    </div></td><td><span class="mediaTxt">{$b.author|escape}                          </span></td></tr>{/if}

               {if ($b.doc_type == 'electronic' )}
                <tr><td><div class="mediaListHeader">Format: </div></td><td><span class="mediaTxt">Online-Ressource                              </span></td></tr>
               {/if}

               {if ($b.doc_type == 'print') }
                   {if (isset ( $b.signature  ) AND $b.signature    != "" )}<tr><td><div class="mediaListHeader">Format: </div></td><td><span class="mediaTxt">Print - Sig:{$b.signature|escape}        </span></td></tr>{/if}
                   {if (isset ( $b.ISBN       ) AND $b.ISBN         != "" )}<tr><td><div class="mediaListHeader">ISBN:   </div></td><td><span class="mediaTxt">{$b.ISBN|escape:"br"}                     </span></td></tr>{/if}
               {/if}

               {if (isset ( $b.publisher    ) AND $b.publisher    != "" )}<tr><td><div class="mediaListHeader">Verlag:   </div></td><td><span class="mediaTxt">{$b.publisher|escape}                       </span></td></tr>{/if}
               {if (isset ( $b.year         ) AND $b.year         != "" )}<tr><td><div class="mediaListHeader">:         </div></td><td><span class="mediaTxt">{$b.year|escape}                            </span></td></tr>{/if}
               {if (isset ( $b.volume       ) AND $b.volume       != "" )}<tr><td><div class="mediaListHeader">:         </div></td><td><span class="mediaTxt">{$b.volume|escape}                          </span></td></tr>{/if}
           </table>
          </a>
             {/if}
             {foreachelse}   <h3 style="color:red; text-align:center; width:100%; height:50px;  vertical-align: middle; border: 2px solid red;">ERROR: Zur Zeit keine Verbindung zum Bibliotheksserver möglich.<br>(OPAC)</h3>
          {/foreach}

      <div class="text">
       Haben Sie nicht das Gewünschte gefunden?<br/> Machen Sie doch einen
       <a  class="erwebungsvorschlag"  href="index.php?item=book&action=purchase_suggestion&dc_collection_id={$collection.dc_collection_id}&r={$user.role_id}">Erwerbungsvorschlag</a>
      </div>
     {/if}
 </div>
</div>

<div id="basic-modal-content">

 <img src="img/loader.gif" style="width:80px; heigth:80px" /></div>
<script type='text/javascript' src='lib/jquery.simplemodal.js'></script>