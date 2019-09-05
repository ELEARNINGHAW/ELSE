{* $ACTION_INFO , $dc_collection_id, $user *}
{foreach  item=action  key=action_name  name=ACTION_INFO  from=$ACTION_INFO}
  {$visible = "0"}
  {if isset( $action.button_visible_if )}
    {$visible = "1"}
    {foreach key = k item = cond from = $action.button_visible_if }
      {$match = "0"}
      {foreach item = v from = $cond}
        {if ($k == "state") and ($v == $state) }  {$match = "1"}  {/if}
        {if ($k == "mode" ) and ($v == $mode ) }  {$match = "1"}  {/if}
        {if ($k == "item" ) and ($v == $item ) }  {$match = "1"}  {/if}
      {/foreach}
      {if $match == 0}  {$visible = "0"}  {/if}
    {/foreach}
  {/if}
  {if $visible == 1}{$ci.c_id_b64e}
    {if $CFG[ 'ajaxON' ]} {* AJAX *} <a class='icon' href='javascript:;' onCLick="{literal}${/literal}.ajax({literal}{{/literal}url: 'index.php?collection_id={$dc_collection_id}&item={$item}&action={$action.button}&r={$role_encode}&document_id={$di.id}#{$di.id}' ,type: 'GET', success: function(data){literal}{{/literal}{literal}${/literal}('.{$di.id}').html(data);{literal}}}{/literal});"><img  class="icon" title="{$action.button_label}" src="img/svg/{$action.button}.svg" /></a>
    {else}                {* HTTP *} <a class="icon" href="index.php?collection_id={$ci.c_id_b64e}&amp;item={$item}&amp;action={$action.button}&amp;r={$user.role_encode}&amp;document_id={$d_info.document_id}#{$d_info.document_id}">                                                                                                                                                                              <img  class="icon" title="{$action.button_label}" src="img/svg/{$action.button}.svg" /></a>
    {/if}
  {/if}
{/foreach}