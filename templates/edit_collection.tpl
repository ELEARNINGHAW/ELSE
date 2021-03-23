{if $user.role_name == "staff" OR  $user.role_name == "admin" OR  $operator.mode == 'suggest' } {$restricted  = "" }  {else}  {$restricted = "disabled=\"yes\""}  {/if}
<meta charset="utf-8">
<script src="lib/dropzone.min.js"></script>
<link rel="stylesheet" href="lib/dropzone.min.css">

<h3 style="margin:20px; padding:10px;   "  class="bgDef bg{$collection.bib_id}"  >
{if $operator.mode == "new"}
Neuen Semesterapparat anlegen für: {$collection.title}
{else}
    {$collection.title}: Metadaten
{/if}
<a style="float:right;" href="index.php?item=collection&action=show_collection&dc_collection_id={$collection.dc_collection_id}&r={$user.role_id}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left.svg" /></a>
</h3>

<div style="margin:20px; margin-top:0px;  padding:10px; border:solid 1px black; ">
<form action="index.php" method="get">

<input type="hidden" name="item"              value="collection"  >
<input type="hidden" name="r"                 value="{$user.role_id}"  >

{if $operator.action == "coll_meta_save"}
<input type="hidden" name="action"            value="new_init">

{else}
<input type="hidden" name="action"            value="coll_meta_save">
<input type="hidden" name="dc_collection_id"  value="{$collection.dc_collection_id}" >
{/if}

{*if $restricted_staff}
<input type="hidden" name="title"           value="{$collection[$coll.title_short].title|escape}" >
<input type="hidden"  name="collection_no"  value="{$collection[$coll.title_short].collection_no|escape}">
{/if*}

<table style="text-align: left; width: 100%;" border="0">
<tr>
<td style="vertical-align: top;"><span class="medHead2">Standort des Semesterapparats:</span></td>
    <td>{html_options name="bib_id" options=$tpl.bib_info selected=$collection.bib_id } <br/><span  style="font-weight: bold; font-size: 12px;" >im Regal &quot;Semesterapparate&quot;</span><br/></td>
     <td rowspan="10" >   <input style="width:125px; height:50px;" name="ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit"> </td>
   </tr>

</tr>

<tr>
<td style="vertical-align: top;"><span  class="medHead2">Bemerkungen für die Studierenden zum Semesterapparat:<br/>(Optional)</span></td>
<td> <textarea name="notes_to_studies_col" cols="40" rows="5" >{$collection.notes_to_studies_col}</textarea> </td>
</tr>

<tr>
  <td style="vertical-align: top;"><span  class="medHead3">Bemerkungen für die HIBS Mitarbeiter/in zum Semesterapparat:<br/>(Optional)</span></td>
  <td> <textarea name="notes_to_staff_col" cols="40" rows="5" >{$collection.notes_to_staff_col}</textarea> </td>
</tr>

<tr>
<td style="vertical-align: top;"><span  class="medHead2">Semester:</span></td>
<td> {html_options name="semester_id" options=$tpl.semlist selected=$collection.sem }</td>
</tr>

</tbody>
</table>


    <hr>
    <h3>Exportieren der Medien aus diesem Semesterapparaten</h3></>

    <div style="margin:calc(40% - 50px); margin-top:0px;  padding:10px; border:solid 1px black;height:245px; width: 260px; ">

<span  style="position: relative; height: 240px; width:160px;  ; margin:2px;font-size: 30px; border:solid 0px black;">
<a class ="exportBt"   href="index.php?item=collection&amp;action=export&amp;dc_collection_id={$collection.dc_collection_id}&amp;redirect=SA&amp;r={$user.role_id}" title="Export des Semesterapparats">
    <img style="width: 240px; height: 240px;margin-left: 10px" src="img/svg/export.svg"  /></a>
</span>

</form>
</div>
