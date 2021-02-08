{assign var="a_info" value=$ACTION_INFO.$action }
<input type="submit" 	name="{$a_info.button}" value="{$a_info.button_label|escape}" {if $disabled}disabled="yes"{/if} >
