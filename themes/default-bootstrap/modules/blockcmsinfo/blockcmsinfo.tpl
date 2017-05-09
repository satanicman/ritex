{if $infos|@count > 0}
    <div class="blockcmsinfo index__blockcmsinfo">
        {foreach from=$infos item=info}
            {$info.text}
        {/foreach}
    </div>
{/if}