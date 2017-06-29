{if isset($category_url) && $category_url}
    <div class="tires-calc">
        <input type="hidden" name="tire-calc-category-url" value="{$category_url}" class="tire-calc__category" id="tire-calc-category-url">
        <h3 class="tires-calc__title"><span
                    class="tires-calc__title-text">{l s='Подбор шин по параметрам' mod='homepagefilter'}</span></h3>
        <div class="tires-calc__row clearfix">
            <div class="container">
                <div class="row">
                    {if $features|@count > 0}
                        {foreach from=$features item=feature name=features}
                            {if $feature.values|@count <= 0}{continue}{/if}
                            {if $smarty.foreach.features.index < 3}
                                <div class="tires-calc__col tires-calc-col">
                                    <div class="tires-calc-col__top tires-calc-col__top_transparent"></div>
                                    <div class="tires-calc-col__header">
                                        <p>{$feature.name}</p>
                                    </div>
                                    <ul class="tires-calc-col__list">
                                        {foreach from=$feature.values item=value name=value}
                                            <li class="tires-calc-col__item">
                                                <input type="radio" name="feature_{$value.id_feature}"
                                                       id="feature_{$value.id_feature}_{$value.id_feature_value}"
                                                       class="not_uniform tires-calc-col__input" value="{$value.feature_url}-{$value.url_name}">
                                                <label for="feature_{$value.id_feature}_{$value.id_feature_value}"
                                                       class="tires-calc-col__label"> <span
                                                        class="tires-calc-col__icon">{$value.image}</span>
                                                    {$value.value}</label>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {else}
                                {if $smarty.foreach.features.index == 3}
                                    <div class="tires-calc__col tires-calc-col tires-calc__col_last">
                                        <div class="tires-calc-col__top tires-calc-col__top_transparent"></div>
                                        <div class="tires-calc-col__header">
                                            <p>{l s='Выбрать параметр' mod='homepagefilter'}</p>
                                        </div>
                                        <ul class="tires-calc-col__list">
                                {/if}
                                            <li class="tires-calc-col__item">
                                                <select name="width" id="width" class="tires-calc-col__select form-control">
                                                    <option value="">{$feature.name}</option>
                                                    {foreach from=$feature.values item=value}
                                                        <option value="{$value.feature_url}-{$value.url_name}">{$value.value}</option>
                                                    {/foreach}
                                                </select>
                                            </li>
                                {if $smarty.foreach.features.index == $features|@count - 1}
                                        </ul>
                                    </div>
                                {/if}
                            {/if}
                        {/foreach}
                    {/if}
                </div>
            </div>
        </div>
    </div>
{/if}
