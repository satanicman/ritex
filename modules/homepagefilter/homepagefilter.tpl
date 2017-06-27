{if isset($categories) && $categories}
    <div class="podbor">
        <h3 class="home-h3">Подбор запчастей</h3>
        <select class="podbor active" id="homepagefilter_category">
            <option value="">Выберите категорию</option>
            {foreach from=$categories item=child}
                {include file="$branche_tpl_path" node=$child depth=0}
            {/foreach}
        </select>
        {if $features|@count > 0}
            {foreach from=$features item=feature}
                {if $feature.values|@count <= 0}{continue}{/if}
                <select class="podbor active homepagefilter_feature">
                    <option value="">{$feature.name}</option>
                    {foreach from=$feature.values item=value}
                        <option value="{$value.feature_url}-{$value.url_name}">{$value.value}</option>
                    {/foreach}
                </select>
            {/foreach}
        {/if}
    </div>

{/if}