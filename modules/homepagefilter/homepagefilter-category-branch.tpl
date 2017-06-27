<option value="{$node.link}">{str_repeat('&nbsp;',4*$depth)}{$node.name}</option>
{if $node.children|@count > 0}
    {assign var=depth value=$depth+1}
    {foreach from=$node.children item=child}
        {include file="$branche_tpl_path" node=$child depth=$depth}
    {/foreach}
{/if}