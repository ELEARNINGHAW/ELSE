{* $di, $ci, $user, $operator, $CFG, $MEDIA_STATE *}
{$FB = $FACHBIB[$ci.bib_id].bib_name|escape}
{if $di.location_id == 0  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 0 ]}   {/if}{* unbekannt                            *}
{if $di.location_id == 1  }  {$whereIs =  "{$FB} - {$CFG[ 'MEDIA_LOC'][ 1 ]}"} {/if}{* Semesterapp    / SA Medium           *}
{if $di.location_id == 2  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 2 ]}   {/if}{* Bibliothek     / LitHinweis Buch     *}
{if $di.location_id == 3  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 3 ]}   {/if}{* online         / PDF                 *}
{if $di.location_id == 4  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 4 ]}   {/if}{* Scanservice    / Medienserver        *}
{if $di.location_id == 5  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 5 ]}   {/if}{* externe Biblio / Titel nicht aus HAW-Bestand -LitHinweis Buch             *}
{if $di.doc_type_id == 6  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 6 ]}   {/if}{* Artikel                              *}
{if $di.doc_type_id == 7  }  {$whereIs =            $CFG[ 'MEDIA_LOC'][ 7 ]}   {/if}{* eArtikel                             *}
{$edit_mode  = "0"}
{$staff_mode = "0"}
{$whereIs2 =                $CFG[ 'MEDIA_PRINT'][ $di.location_id ]}
{$doctypedescription = $DOC_TYPE[ $di.doc_type_id ]['description'] }
{$doctype            = $DOC_TYPE[ $di.doc_type_id ]['item'       ] }
{if $user_role_name == "admin"  OR  $user_role_name == "staff" OR  $user_role_name == "edit"  } {$edit_mode  = "1"} {/if}
{if $user_role_name == "admin"  OR  $user_role_name == "staff"                                } {$staff_mode = "1"} {/if}
{if $medium.id == $di.id}  {$current = "currentDoc"} {else}  {$current = "XXX"}   {/if}{* Das zuletzt angeklickte Medium wird zur Unterscheidung in der Liste farblich unterlegt*}
<div id="{$di.ppn}" class="mediaInSA medium_{$di.location_id} {$current} " >
<a name="{$di.ppn}" style="position:relative; top:-220px;"></a>
{if  $di.doc_type_id  == 99}
    <a title="Erwerbungsvorschlag" class="medimove medLink  s_standard state_{$di.state_id} {if $edit_mode == '1'} {/if} " onclick="return -1">
{else}
  <a title="Medium Im Onlinekatalog anzeigen" class="medimove medLink  s_standard state_{$di.state_id} {if $edit_mode == '1'} {/if} " href="{$CFG.CATALOG_URL[$DOC_TYPE[$di.doc_type_id]['indexID']]}{$di.ppn}" target="_blank" onclick="return -1">
{/if}
<div title="Verschieben des Mediums in der Liste" class="handle" style="float: right;"> </div>
<table>   {$preMedTyp = ''}
{if ($di.location_id == 4)}{$preMedTyp = '[SCAN] '  }{/if}
{if $di.title           != ""           }  <tr><td><div class="mediaListHeader">Titel:     </div></td><td><div  class="mediaTxt" >{$di.title}            </div>            {/if}
{if $di.author          != ""           }  <tr><td><div class="mediaListHeader">Autor*in:  </div></td><td><div  class="mediaTxt" >{$di.author}           </div> </td></tr> {/if}
{if $di.doc_type        == 'electronic' }  <tr><td><div class="mediaListHeader">Format:    </div></td><td><span class="mediaTxt" >Online-Ressource       </span></td></tr> {/if}
{if $doctypedescription != ""           }  <tr><td><div class="mediaListHeader">Medientyp: </div></td><td><div  class="mediaTxt" >{$preMedTyp}{$doctypedescription}  {if (isset ( $di.signature  ) AND $di.signature  != "" AND $di.signature  != "E-Book" )} ({$di.signature|escape}){/if} </div>            {/if}
{if $di.doc_type        == 'print'      }
{if (isset ( $di.ISBN       ) AND $di.ISBN       != "" )}<tr><td><div class="mediaListHeader">ISBN:   </div></td><td><span class="mediaTxt">{$di.ISBN|escape:"br"}             </span></td></tr>{/if}
{/if}
</table>
</a>
{if $di.notes_to_studies != "" }   <div class="medhint">Zur Beachtung: {$di.notes_to_studies|nl2br}  </div> {/if}
<div class="bibStandort"> {if $ci.bib_id != "" }  {$whereIs}  {/if}
</div>
{if ($staff_mode or $edit_mode) and ($operator.mode != "print")}
<div class="status s_{$di.state_id}"/>{$MEDIA_STATE[$di.state_id].description}</div>
<div class="iconlist">
{foreach  item=action  key=action_name  name=ACTION_INFO  from=$ACTION_INFO}
{$visible = "0"}
{if isset( $action.button_visible_if )}
{$visible = "1"}
{foreach key=k item=cond from=$action.button_visible_if}
{$match = "0"}
{foreach item=v from=$cond}
{if ($k == "state") and ($v == $MEDIA_STATE[ $di.state_id ].name) }{$match ="1"}{/if}
{if ($k == "mode" ) and ($v == $user.role_name )                  }{$match ="1"}{/if}
{if ($k == "loc"  ) and ($v == $di.location_id )                  }{$match ="1"}{/if}
{/foreach}
{if $match == 0}{$visible = "0"}{/if}
{/foreach}
{/if}
{if $visible == 1}
{if $CFG['CONF'][ 'ajaxON' ]} {* AJAX *} <a class='icon' href='javascript:;' onCLick="{literal}${/literal}.ajax({literal}{{/literal}url: 'index.php?dc_collection_id={$ci.dc_collection_id}&item={$di.item}&action={$action.button}&r={$user.role_encode}&d_info={$di.id}#{$di.id}' ,type: 'GET', success: function(data){literal}{{/literal}{literal}${/literal}('.{$di.id}').html(data);{literal}}}{/literal});"><img  class="icon" title="{$action.button_label}" src="img/svg/{$action.button}.svg" /></a>
{else}                        {* HTTP *} <a class="icon" href="index.php?loc={$di.location_id}&dc_collection_id={$ci.dc_collection_id}&amp;item={$di.item}&amp;action={$action.button}&amp;r={$user.role_encode}&amp;document_id={$di.id}#{$di.id}">                                                                                                                                                                              <img  class="icon" title="{$action.button_label}" src="img/svg/{$action.button}.svg" /></a>
{/if}
{/if}
{/foreach}
{/if}
</div>
{if ($staff_mode or $edit_mode) AND ($di.notes_to_staff != "") } {* and   ($di.state_id == 1 or $di.state_id == 2 or $di.state_id == 9) *}
<div class="staffnote"> {$di.notes_to_staff|escape|nl2br} </div>{/if}