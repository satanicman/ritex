{if $infos|@count > 0}
    <div class="row">
        <div class="blockcmsinfo">
            {foreach from=$infos item=info}
                {$info.text}
            {/foreach}
        </div>
    </div>
{/if}