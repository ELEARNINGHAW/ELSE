{if $user.role_name == "staff" OR  $user.role_name == "admin" OR  $operator.mode == 'suggest' } {$restricted  = "" }  {else}  {$restricted = "disabled=\"yes\""}  {/if}
<meta charset="utf-8">
<script src="lib/dropzone.min.js"></script>
<link rel="stylesheet" href="lib/dropzone.min.css">
  

<h3 style="margin:20px; padding:10px; color: #FFF; "  class="bgDef bg{$collection.bib_id}"  >
{if $operator.mode == "new"}
Neuen Semesterapparat anlegen für: {$collection.title}
{else}
    {$collection.title}: Metadaten
{/if}
<a style="float:right;" href="index.php?item=collection&action=show_collection&dc_collection_id={$collection.dc_collection_id}&r={$user.role_id}"><img  class="icon" style="margin-top:-4px;" title="Zurück" src="img/svg/chevron-left_w.svg" /></a>
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
</tr>

<tr>
<td style="vertical-align: top;"><span  class="medHead2">(Optional)<br/>Bemerkungen für die Studierenden zum Semesterapparat:</span></td>
<td> <textarea name="notes_to_studies_col" cols="60" rows="5" >{$collection.notes_to_studies_col}</textarea> </td>
</tr>


<tr>
<td style="vertical-align: top;"><span  class="medHead2">Semester:</span></td>
<td> {html_options name="semester_id" options=$tpl.semlist selected=$collection.sem }</td>
</tr>

</tbody>
</table>
<input style="float: right;" name="b_ok" value="&nbsp;&nbsp;&nbsp;SPEICHERN&nbsp;&nbsp;&nbsp;" type="submit">
</form>
</div>

<div style=" margin:20px; margin-top:0px;  padding:10px; border:solid 1px black;height:260px ">

<div style="position: absolute; height: 240px; width:160px; left:120px; margin:2px;font-size: 30px; border:solid 0px black;">
<a class ="exportBt"   href="index.php?item=collection&amp;action=export&amp;dc_collection_id={$collection.dc_collection_id}&amp;redirect=SA&amp;r={$user.role_id}" title="Export des Semesterapparats">
    <img style="width: 240px; height: 240px;" src="img/svg/export.svg"  /></a>
</div>

<div style="position: absolute; height: 220px; width:225px; left:400px; margin:2px;font-size: 25px; border:solid 5px black;">
 
<form action="index.php?item=collection&amp;action=import&amp;dc_collection_id={$collection.dc_collection_id}" method="GET"
  class="dropzone"
  id="mydropzone"
  style="position: absolute; height: 180px; width:220px;   margin:2px;"
>

</form>
</div>

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
    dictDefaultMessage: "TO IMPORT Drop file here"
};

</script>


