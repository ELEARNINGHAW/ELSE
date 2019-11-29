{if $user.role_name == "admin"  OR  $user.role_name == "staff" OR  $user.role_name == "edit"  } {assign var="edit_mode"  value="1"} {else}  {assign var="edit_mode"   value="0"}  {/if}
{if $user.role_name == "admin"  OR  $user.role_name == "staff"                                } {assign var="staff_mode" value="1"} {else}  {assign var="staff_mode"  value="0"}  {/if}
  
<div class="column">
{foreach key=cid item=ci from=$collection_info}

    {if $ci.dc_collID != "" OR  $work.action == 'show_media_list'}
<div class="SAMeta bgDef bg{$ci.coll_bib_id}">

{if ( $staff_mode )}
<div  style="width:630px; display: inline-block; padding-top:5px; line-height: 80% ">
<a class = "medHead2"  style="float:left;" href="index.php?item=collection&action=show&dc_collection_id={$ci.dc_collID}&r=2"           >{$ci.title|truncate:70:"...":true} </a><br/>
<a class = "medHead2"  style="float:left;" href="index.php?category=ALLE&mode=filterBib&r=2&user={$ci.user_info.u_hawaccount|escape}">von: {$ci.user_info.u_forename|escape} {$ci.user_info.u_surname|escape} </a>
<a class = "medHead2"  style="float:left;" href="index.php?mode=filterBib&r=2&category={$ci.user_info.u_department_id}"              >&nbsp;&nbsp;/&nbsp;&nbsp;Dep: {$ci.user_info.DepName|escape}</a>
</div>

{else}

<div class="SAdozName" style="margin-left: 25px;>ELSE<br />Der elektronische Semesterapparat </div>

{/if}

{if ($work.mode != "print" AND ($edit_mode OR $staff_mode)) }

<a target="help_win" class="modalLink" href="#helpit" rel="modal:open"  title="Weitere Informationen über ELSE"                  ><img src="img/svg/help.svg"        width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;"  /></a>
<a target="_blank" href="index.php?item=collection&amp;dc_collection_id={$ci.dc_collID|escape:"url"|escape}&amp;action=show&amp;mode=print&amp;r={$user.role}">
<img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;" title="Druckversion"   /></a>

{if ($edit_mode OR $staff_mode)}
<a href="index.php?item=collection&amp;action=coll_meta_edit&amp;dc_collection_id={$ci.dc_collID}&amp;redirect=SA&amp;r={$user.role}" title="Bearbeiten der allgemeinen Infos des Semesterapparats">
<img src="img/svg/settings_w.svg"  width="32"  height="32" style="position:relative; float:right;  margin:2px; " /></a>
{/if}

{if $edit_mode AND $ci.dc_collID != "" }
<a href="index.php?dc_collection_id={$ci.dc_collID}&amp;item=book&amp;action=coll_meta_save&amp;coll_meta_save=neu+anlegen&amp;r={$user.role}"  title="Neues Medium (Buch, E-Book,...) dem Semesterapparat hinzufügen"  >
<img src="img/svg/addBook_w.svg"   width="32"  height="32" style="position:relative; float:right;  margin:2px; " /></a>
{/if}

{else}
<a target="_blank" href="#"  onclick="window.print(); return false;">
<img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;" title="Zum Drucker senden"   /></a>
{/if}

<div class="medHeadBlock">
<a class = "medHead3"  href="index.php?mode=filterBib&r=2&category={$ci.coll_bib_id}" >{$ci.coll_bib_id}</a>
<a class = "medHead3"  href="index.php??category={$ci.sem}&mode=filterSem&r=2">{$ci.sem}</a>
</div>

</div>
{/if}



{if $ci.notes_to_studies_col != '' AND $work.action != 'show_media_list'}
<div class="studihint"><div style="color:red;" >Hinweise zur Vorlesung</div>{$ci.notes_to_studies_col|replace:'':' '|nl2br} </div>
{/if}


{if isset($ci.document_info)}
{foreach from=$ci.document_info item=di}

{if    $edit_mode == 0  AND  $di.state_id == 3  OR $edit_mode   == 1 AND $di.state_id != 6 OR $work.action == 'show_media_list' }
  <div class='{$di.id}'>
      {include file = "SA.tpl" }
  </div>

</div>
{/if}

{/foreach}
{/if}




{foreachelse} <div style="padding:10px; margin:10px; margin-top:0px; color: #000; font-size: 14px; border: solid #AAA 2px; background-color:#efe96d; ">Es ist kein  Dokument  vorhanden.</div>

{/foreach}
</div>