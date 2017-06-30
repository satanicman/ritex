{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
<div class="index__homecategories home_categories">
   {* <h2>{l s='Categories' mod='homecategories'}</h2>*}
    {if isset($categories) AND $categories}
        <div class="homecategories">
            <ul class="homecategories__list clearfix">
                {foreach from=$categories item='category' name=homecategories}
                    <li class="homecategories__item col-sm-3 col-xs-12">
                        <div class="homecategories__item-container">
                            <a href="{$link->getCategoryLink($category.id_category, $category.link_rewrite)|escape:'html':'UTF-8'}" class="homecategories__link" title="{$category.name|escape:'html':'UTF-8'}">
                                {if $category.id_image}
                                    <img class="homecategories__img" src="{$img_cat_dir}{$category.id_image}_thumb.jpg" alt="{$category.name|escape:'html':'UTF-8'}"/>
                                {else}
                                    <img class="homecategories__img img-responsive" src="{$img_cat_dir}{$lang_iso}-default-medium_default.jpg" alt="{$category.name|escape:'html':'UTF-8'}"/>
                                {/if}
                            </a>
                            {if isset($category.children) && $category.children}
                                <div class="homecategories-child homecategories__child">
                                    <ul class="homecategories-child__list">
                                        {foreach from=$category.children item='subcategory' name='homecategories-child'}
                                            <li class="homecategories-child__item">
                                                <a href="{$link->getCategoryLink($subcategory.id_category, $subcategory.link_rewrite)|escape:'html':'UTF-8'}" class="homecategories-child__link" title="{$subcategory.name|escape:'html':'UTF-8'}">{$subcategory.name|escape:'html':'UTF-8'}</a>
                                            </li>
                                        {/foreach}
                                    </ul>
                                </div>
                            {/if}
                        </div>
                    </li>
                {/foreach}
            </ul>
        </div>
    {else}
        <p>{l s='No categories' mod='homecategories'}</p>
    {/if}
    <div class="cr"></div>
</div>
<!-- /MODULE Home categories -->