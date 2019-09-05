{if $user.role_name == "staff" OR  $user.role_name == "admin" OR  $operator.mode == 'suggest' } {$restricted  = "" }  {else}  {$restricted = "disabled=\"yes\""}  {/if}

{if  $operator.msg == "shelf_remain"} {$bordercolor= "red"}
{else}                                {$bordercolor= "AAA"}
{/if}

{if     ($medium.doc_type_id == 4)}  {$itemtxt = "E-Book" }
{elseif ($medium.doc_type_id == 3)}  {$itemtxt = "CD-ROM" }
{else}                               {$itemtxt = "Buch"   }
{/if}

{if ($medium.shelf_remain == 0)}  {$c0 = 'checked="checked"' } {else} {$c0 = ''} {/if}
{if ($medium.shelf_remain == 1)}  {$c1 = 'checked="checked"' } {else} {$c1 = ''} {/if}
{if ($medium.shelf_remain == 2)}  {$c2 = 'checked="checked"' } {else} {$c2 = ''} {/if}


{if $operator.mode == 'suggest' }
<h3 style="margin:10px; margin-bottom:0px; margin-top:0px; padding:10px; color: #FFF;" class="bgDef bg{$collection.bib_id}">Erwerbungsvorschlag für: {$collection.title}
<a style="float:right;" href="index.php"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a></h3>
<div style="margin:10px;  padding:10px; border:solid 1px black; ">
Wenn Sie ein Buch zur Anschaffung in der Bibliothek vorschlagen m&ouml;chten und dieses in  Ihren Semesterapparat aufgenommen werden soll, benutzen Sie bitte diese Formular.<br/><br/>Wir geben Ihnen eine Rückmeldung, ob wir Ihnen das Buch beschaffen k&ouml;nnen. 
</div>

{else}
<h3 style="margin:10px; padding:10px; color: #FFF"  class="bgDef bg{$collection.bib_id}" >{$collection.title} : {$itemtxt} bearbeiten {$currentElement+1}/{$maxElement}<a style="float:right;" href="index.php{$operator.url}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a></h3>
{/if}


<div style="margin:10px; margin-top:0px;  padding:10px; border:solid 1px black; ">
<form  action="index.php" method="get">

<input type = "hidden" name = "dc_collection_id" value = "{$collection.dc_collection_id}" >
<input type = "hidden" name = "item"             value = "{$operator.item}"               >
<input type = "hidden" name = "action"           value = "save"                           >
<input type = "hidden" name = "document_id"      value = "{$medium.id}"                   >
<input type = "hidden" name = "doc_type_id"      value = "{$medium.doc_type_id}"          >
<input type = "hidden" name = "ppn"              value = "{$medium.ppn}"                  >
<input type = "hidden" name = "physicaldesc"     value = "{$medium.physicaldesc}"         >
<input type = "hidden" name = "role"             value = "{$user.role_encode}"            >
{*<input type = "hidden" name = "redirect"         value = "{$operator.redirect}"           > *}


{if $restricted}
<input type = "hidden" name = "title"            value = "{$medium.title}"        >
<input type = "hidden" name = "author"           value = "{$medium.author}"       >
<input type = "hidden" name = "ISBN"             value = "{$medium.ISBN}"         >
<input type = "hidden" name = "edition"          value = "{$medium.edition}"      >
<input type = "hidden" name = "signature"        value = "{$medium.signature}"    >
<input type = "hidden" name = "ppn"              value = "{$medium.ppn}"          >
{/if}               


<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
<tbody>


{if (($medium.doc_type_id == 1 OR $medium.doc_type_id == 3 ) AND $operator.mode == 'new'   OR $operator.mode == 'suggest'  )} {* doc_type 1 = Buch oder CD im SA *}
  <tr><td style="vertical-align: top; font-weight: bold;">  Ort:  <span style="color: #F03; vertical-align: top; font-weight: bold;">(bitte auswählen)</span> </td><td>
     <div  style="border:1px solid {$bordercolor};    height:59px; padding: 5px; font-size: 13px; width: calc(100% - 20px); ">
     <input {$c2} value="2" class='i' type="radio" name="shelf_remain" id="radio-2"><label for="radio-2"><div style="display: inline-block;  font-weight:700; width:125px; text-align:left; ">Literaturhinweis:</div> Buch verbleibt im Regal der Bibliothek.</label><br/>
     <input {$c1} value="1" class='i' type="radio" name="shelf_remain" id="radio-1"><label for="radio-1"><div style="display: inline-block;  font-weight:700; width:125px; text-align:left; ">Handapparat:</div> Buch wird in Ihren Handapparat eingestellt.</label>
     <input {$c0} value="0" type="radio" name="shelf_remain" id="radio-1" style="visibility: hidden;">
     </div>
  </td></tr>
{/if}


<tr><td style="vertical-align: top; font-weight: bold;">Titel:</td><td><textarea  cols="60" rows="2"    name="title">{$medium.title}</textarea></td></tr>
<tr><td style="font-weight: bold;">Autor:   </td><td><input size="80" value="{$medium.author}"    {$restricted} name="author">  </td></tr>
<tr><td style="font-weight: bold;">ISBN:    </td><td><input size="80" value="{$medium.ISBN}"      {$restricted} name="ISBN">    </td></tr>


{if ($medium.doc_type_id == 1) && $operator.mode != 'suggest'  }
<tr><td style="font-weight: bold;">Signatur:</td><td><input size="20" value="{$medium.signature|escape}" {$restricted} name="signature"></td></tr>
{/if}

<tr><td style="vertical-align: top; font-weight: bold;">(Optional)<br/> Bemerkungen <br>f&uuml;r Studis:</td><td><textarea  cols="60" rows="3"    name="notes_to_studies">{$medium.notes_to_studies|escape}</textarea></td></tr>


{if ($medium.doc_type_id == 1 OR $medium.doc_type_id == 3 )} {* doc_type 1 = Buch oder CD im SA *}
<tr><td style="vertical-align: top; font-weight: bold;">(Optional)<br/> Bemerkungen <br>f&uuml;r HIBS Mitarbeiter:</td><td><textarea cols="60" rows="3" name="notes_to_staff">{$medium.notes_to_staff|escape}</textarea></td></tr>
{/if}


</tbody>
</table>
<br>
  <script>$( function() {		$( ".i" ).checkboxradio();	} ); </script>
<input style="float: right;" name="ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit">
</form>
</div>