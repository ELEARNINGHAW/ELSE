{if $user.role_name == "staff" OR  $user.role_name == "admin" OR  $operator.mode == 'suggest' } {$restricted  = "" }  {else}  {$restricted = "disabled=\"yes\""}  {/if}
  
{if  $operator.msg == "shelf_remain"} {$color= "red"}
{else}                                {$color= "AAA"}
{/if}

{$SAready    = $DOC_TYPE[ $medium.doc_type_id ][ 'SA-ready'    ] }
{$doctypetxt = $DOC_TYPE[ $medium.doc_type_id ][ 'description' ] }

    {if ($medium.shelf_remain == 0)}  {$c0 = 'checked="checked"' } {else} {$c0 = ''} {/if}
    {if ($medium.shelf_remain == 1)}  {$c1 = 'checked="checked"' } {else} {$c1 = ''} {/if}
    {if ($medium.shelf_remain == 2)}  {$c2 = 'checked="checked"' } {else} {$c2 = ''} {/if}

    {if $medium.doc_type_id == 16 }
        <h3 style="margin:10px; margin-bottom:0px; margin-top:0px; padding:10px; color: #FFF;" class="bgDef bg{$collection.bib_id}">Erwerbungsvorschlag für: {$collection.title}
            {if $operator.action != 'annoteNewMedia' }  <a style="float:right;" href="index.php"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a> {/if}</h3>
        <div style="margin:10px;  padding:10px; border:solid 1px black; ">
            Wenn Sie ein Medium zur Anschaffung in der Bibliothek vorschlagen m&ouml;chten und dieses in  Ihren Semesterapparat aufgenommen werden soll, benutzen Sie bitte dieses Formular.
        </div>
    {else}
        <h5 style="margin:10px; padding:10px; color: #FFF"  class="bgDef bg{$collection.bib_id}" >{$collection.title}<br/> {$doctypetxt} bearbeiten
            {if $operator.action != 'annoteNewMedia' AND $operator.action != 'save' }
              <a style="float:right;" href="index.php{$operator.url}">x<img  class="icon" style="margin-top:-15px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a>{/if}
            <span   style="position:relative; font-size:25px; float:right; top:-18px; padding-right: 15px; " >  {$currentElement+1}/{$maxElement} </span>
        </h5>
    {/if}

    <div style="margin:10px; margin-top:0px;  padding:10px; border:solid 1px black; ">
        <form  action="index.php" method="get">

<input type = "hidden" name = "dc_collection_id" value = "{$collection.dc_collection_id}" >
<input type = "hidden" name = "item"             value = "media"                          >
<input type = "hidden" name = "loc"              value = "1"                              >   {* loc ist an dieser Stelle noch nicht definiert, deshalb standard = 1 *}
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

<tr><td class = "editmedia">Medientyp: </td><td> {$doctypetxt} </td></tr>

{if ( $SAready== 1 AND  ( $operator.mode == 'new'  OR $medium.doc_type_id == 16 ) ) }
    <tr><td  class = "editmedia" style="vertical-align: top; font-weight: bold;">  Ort:  <span style="color: {$color}; vertical-align: top; font-weight: bold;">(bitte auswählen)</span> </td><td>
            <div style="border:1px solid {$color}; float: left;    height:59px; padding: 5px; font-size: 13px; width: calc(100% - 165px); ">
                <input {$c2} value="2" class='i' type="radio" name="shelf_remain" id="radio-2"><label for="radio-2"><div style="display: inline-block;  font-weight:700; width:120px; text-align:left; ">Literaturhinweis: </div> <div style="display: inline-block; width:300px; text-align:left;" > Buch verbleibt im Regal der Bibliothek.   </div></label><br/>
                <input {$c1} value="1" class='i' type="radio" name="shelf_remain" id="radio-1"><label for="radio-1"><div style="display: inline-block;  font-weight:700; width:120px; text-align:left; ">Handapparat:      </div> <div style="display: inline-block; width:300px; text-align:left;" >Buch wird in Ihren Handapparat eingestellt.</div></label>
                <input {$c0} value="0"           type="radio" name="shelf_remain" id="radio-1" style="visibility: hidden;">
            </div>
            <input style="float: right; width:150px; height:50px;" name="ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit">
        </td></tr>
{/if}


{if ($medium.doc_type_id != 16 ) }

<tr><td class = "editmedia">Titel:    </td><td><textarea  cols="80" rows="2"    name="title">{$medium.title}</textarea>   </td></tr>
<tr><td class = "editmedia">Autor:    </td><td><input size="80" value="{$medium.author}"     {$restricted} name="author"> </td></tr>

{if ($medium.doc_type_id == 1)  }
<tr><td class = "editmedia">ISBN:    </td><td><input size="80" value="{$medium.ISBN}"               {$restricted} name="ISBN">   </td></tr>
<tr><td class = "editmedia">Signatur:</td><td><input size="20" value="{$medium.signature|escape}" {$restricted} name="signature"></td></tr>
{/if}

<tr><td class = "editmedia">Anmerkung <br>f&uuml;r Studierende:)<br/>(Optional)</td><td><textarea  cols="80" rows="5"    name="notes_to_studies">{$medium.notes_to_studies|escape}</textarea></td></tr>

{/if}

{if ($medium[ 'item' ] == 'physical' OR $medium.doc_type_id == 16 )} {* doc_type 1 = Buch oder CD im SA *}
<tr>


    {if ($medium.doc_type_id == 16 ) }
        <td class = "editmedia"> Ihr Erwerbungs-</br>vorschlag:</td>
    {else}
        <td class = "editmedia"> Anmerkung <br>f&uuml;r die Bibliothek:<br/>(Optional)</td>
    {/if}
    <td><textarea cols="80" rows="5" name="notes_to_staff">{$medium.notes_to_staff|escape}</textarea></td></tr>
{/if}


</tbody>
</table>
<br>
  <script>$( function() {		$( ".i" ).checkboxradio();	} ); </script>
<input style="float: right;" name="ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit">
</form>
</div>