{*
* 2007-2016 PrestaShop
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
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $block == 1}
	<!-- Block CMS module -->
	{foreach from=$cms_titles key=cms_key item=cms_title}
		<section id="informations_block_left_{$cms_key}" class="block informations_block_left">
			<p class="title_block">
				<a href="{$cms_title.category_link|escape:'html':'UTF-8'}">
					{if !empty($cms_title.name)}{$cms_title.name}{else}{$cms_title.category_name}{/if}
				</a>
			</p>
			<div class="block_content list-block">
				<ul>
					{foreach from=$cms_title.categories item=cms_page}
						{if isset($cms_page.link)}
							<li class="bullet">
								<a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.name|escape:'html':'UTF-8'}">
									{$cms_page.name|escape:'html':'UTF-8'}
								</a>
							</li>
						{/if}
					{/foreach}
					{foreach from=$cms_title.cms item=cms_page}
						{if isset($cms_page.link)}
							<li>
								<a href="{$cms_page.link|escape:'html':'UTF-8'}" title="{$cms_page.meta_title|escape:'html':'UTF-8'}">
									{$cms_page.meta_title|escape:'html':'UTF-8'}
								</a>
							</li>
						{/if}
					{/foreach}
					{if $cms_title.display_store}
						<li>
							<a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}">
								{l s='Our stores' mod='blockcms'}
							</a>
						</li>
					{/if}
				</ul>
			</div>
		</section>
	{/foreach}
	<!-- /Block CMS module -->
{elseif $block == 2}
	<ul class="menu__list col-md-11">
		{foreach from=$cmslinks item=cmslink}
			{if $cmslink.meta_title != ''}
				<li class="menu__item">
					<a href="{$cmslink.link|escape:'html':'UTF-8'}" title="{$cmslink.meta_title|escape:'html':'UTF-8'}" class="menu__link">
						{$cmslink.meta_title|escape:'html':'UTF-8'}
					</a>
				</li>
			{/if}
		{/foreach}
	</ul>
{else}
	<!-- Block CMS module footer -->
	<section class="footer-block col-lg-offset-1 col-xs-12 col-md-4 footer-menu footer__menu" id="block_various_links_footer">
		<h4 class="footer-menu__title footer__title">{l s='Меню' mod='blockcms'}</h4>
		<ul class="footer-menu__list clearfix toggle-footer">
            {if isset($show_price_drop) && $show_price_drop && !$PS_CATALOG_MODE}
				<li class="footer-menu__item">
					<a href="{$link->getPageLink('prices-drop')|escape:'html':'UTF-8'}" title="{l s='Specials' mod='blockcms'}" class="footer-menu__link">
                        {l s='Specials' mod='blockcms'}
					</a>
				</li>
            {/if}
            {if isset($show_new_products) && $show_new_products}
				<li class="footer-menu__item">
					<a href="{$link->getPageLink('new-products')|escape:'html':'UTF-8'}" title="{l s='New products' mod='blockcms'}" class="footer-menu__link">
                        {l s='New products' mod='blockcms'}
					</a>
				</li>
            {/if}
            {if isset($show_best_sales) && $show_best_sales && !$PS_CATALOG_MODE}
				<li class="footer-menu__item">
					<a href="{$link->getPageLink('best-sales')|escape:'html':'UTF-8'}" title="{l s='Top sellers' mod='blockcms'}" class="footer-menu__link">
                        {l s='Top sellers' mod='blockcms'}
					</a>
				</li>
            {/if}
            {if isset($display_stores_footer) && $display_stores_footer}
				<li class="footer-menu__item">
					<a href="{$link->getPageLink('stores')|escape:'html':'UTF-8'}" title="{l s='Our stores' mod='blockcms'}" class="footer-menu__link">
                        {l s='Our stores' mod='blockcms'}
					</a>
				</li>
            {/if}
            {if isset($show_contact) && $show_contact}
				<li class="footer-menu__item">
					<a href="{$link->getPageLink($contact_url, true)|escape:'html':'UTF-8'}" title="{l s='Contact us' mod='blockcms'}" class="footer-menu__link">
                        {l s='Contact us' mod='blockcms'}
					</a>
				</li>
            {/if}
            {foreach from=$cmslinks item=cmslink}
                {if $cmslink.meta_title != ''}
					<li class="footer-menu__item">
						<a href="{$cmslink.link|escape:'html':'UTF-8'}" title="{$cmslink.meta_title|escape:'html':'UTF-8'}" class="footer-menu__link">
                            {$cmslink.meta_title|escape:'html':'UTF-8'}
						</a>
					</li>
                {/if}
            {/foreach}
            {if isset($show_sitemap) && $show_sitemap}
				<li class="footer-menu__item">
					<a href="{$link->getPageLink('sitemap')|escape:'html':'UTF-8'}" title="{l s='Sitemap' mod='blockcms'}" class="footer-menu__link">
                        {l s='Sitemap' mod='blockcms'}
					</a>
				</li>
            {/if}
		</ul>
        {*{$footer_text}*}
	</section>
	{if $display_poweredby}
	<section class="bottom-footer col-xs-12">
		<div>
			{l s='[1] %3$s %2$s - Ecommerce software by %1$s [/1]' mod='blockcms' sprintf=['PrestaShop™', 'Y'|date, '©'] tags=['<a class="_blank" href="http://www.prestashop.com">'] nocache}
		</div>
	</section>
	{/if}
	<!-- /Block CMS module footer -->
{/if}
