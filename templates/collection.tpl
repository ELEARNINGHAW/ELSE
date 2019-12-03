{* $operator, $user, $collection_info                                       *}
{* header.tpl            :   $filter, $semester                             *}
{* SA.tpl                :   $CFG, $MEDIA_STATE                             *}
{* action_button_bar.tpl :   $ACTION_INFO , $dc_collection_id, $role_encode *}
{* footer.tpl            :                                                  *}
  
{$user_role_name     = $user.role_name  }
{$user_role_id       = $user.role_id    }
{$operator_action    = $operator.action }

{$edit_mode  = "0"}
{$staff_mode = "0"}

{if $user_role_name == "admin"  OR  $user_role_name == "staff" OR  $user_role_name == "edit"  } {$edit_mode  = "1"} {/if}
{if $user_role_name == "admin"  OR  $user_role_name == "staff"                                } {$staff_mode = "1"} {/if}

<div class="column">
    {foreach key=cid item=ci from=$collection}

    {if $ci.dc_collection_id != "" OR  $operator_action == 'show_media_list'}
    <div class="SAMeta bgDef bg{$ci.bib_id}">

    {if ( $staff_mode )}
    <div style="width:630px; display: inline-block; position:absolute; padding-top:5px; left:50px; line-height: 80% ">
    <a class = "medHead2"  style="float:left;"  > {$ci.title|truncate:70:"...":true} </a><br/>
    <a class = "medHead2"  style="float:left;"  >von: {$ci.Owner.forename|escape} {$ci.Owner.surname} </a>
    <a class = "medHead2"  style="float:left;"  >&nbsp;&nbsp;/&nbsp;&nbsp;Dep:   {$ci.Owner.dep_name}</a>
    </div>

    {else}
    <div class="SAdozName" style="margin-left: 25px;">ELSE<br />Der elektronische Semesterapparat </div>
    {/if}

    {if ($operator.mode != "print" AND ($edit_mode OR $staff_mode)) }

    <a target="help_win" class="modalLink" href="#helpit" rel="modal:open"  title="Weitere Informationen über ELSE"                  ><img src="img/svg/help.svg"        width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;"  /></a>

    <a target="_blank" href="index.php?item=collection&amp;dc_collection_id={$ci.dc_collection_id}&amp;action=show_collection&amp;mode=print&amp;r={$user_role_id}">
    <img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:6px;" title="Druckversion SA"   /></a>

    {if ($edit_mode OR $staff_mode)}
    <a href="index.php?item=collection&amp;action=coll_meta_edit&amp;dc_collection_id={$ci.dc_collection_id}&amp;redirect=SA&amp;r={$user_role_id}" title="Bearbeiten der allgemeinen Infos des Semesterapparats">
    <img src="img/svg/settings_w.svg"  width="32"  height="32" style="position:relative; float:right;  margin:2px;  margin-right:0px" /></a>
    {/if}

    {if $edit_mode AND $ci.dc_collection_id != "" }
    <a href="index.php?dc_collection_id={$ci.dc_collection_id}&amp;item=collection&amp;action=add_media&amp;r={$user_role_id}"  title="Neues Medium (Buch, E-Book,...) diesem Semesterapparat hinzufügen"  >
    <img src="img/svg/addBook_w.svg"   width="64"  height="32" style="position:relative; float:right;  margin:2px;  margin-right:6px; " /></a>
    {/if}

    {else}
    <a target="_blank" href="#"  onclick="window.print(); return false;">
    <img src="img/svg/print_w.svg"    width="32"  height="32" style="position:relative; float:right; padding-right: 2px; margin:2px; margin-right:-1px;" title="Zum Drucker senden"   /></a>
    {/if}

    {if ($edit_mode OR $staff_mode)}
    <div class="medHeadBlock">
    <a class = "medHead3"  href="index.php?mode=filterBib&r=2&category={$ci.bib_id}" >{$ci.bib_id}</a>
    <a class = "medHead3"  href="index.php??category={$ci.sem}&mode=filterSem&r=2">{$ci.sem}</a>
    </div>
    {/if}

    </div>
    {/if}

    {if $ci.notes_to_studies_col != '' AND $operator_action != 'show_media_list'}

    <div class="studihint"><div style="color:red;" >Hinweise zur Vorlesung</div>{$ci.notes_to_studies_col|replace:'':' '|nl2br} </div>

    {/if}

    {if isset($ci.media)}
    {foreach from=$ci.media item=di}
    {if    $edit_mode == 0  AND  $di.state_id == 3  OR $edit_mode   == 1 AND $di.state_id != 6 OR $operator_action == 'show_media_list' }

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