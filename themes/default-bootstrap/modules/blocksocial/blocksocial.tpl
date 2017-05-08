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
<section id="social_block" class="footer-bottom__col social-block col-sm-3 col-xs-12">
	<ul class="social-block__list">
        {if isset($facebook_url) && $facebook_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_facebook" href="{$facebook_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='Facebook' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($twitter_url) && $twitter_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_twitter" href="{$twitter_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='Twitter' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($rss_url) && $rss_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_rss" href="{$rss_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='RSS' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($youtube_url) && $youtube_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_youtube" href="{$youtube_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='Youtube' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($google_plus_url) && $google_plus_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_google-plus" href="{$google_plus_url|escape:html:'UTF-8'}" rel="publisher">
					<span class="social-block__text">{l s='Google Plus' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($pinterest_url) && $pinterest_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_pinterest" href="{$pinterest_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='Pinterest' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($vimeo_url) && $vimeo_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_vimeo" href="{$vimeo_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='Vimeo' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
        {if isset($instagram_url) && $instagram_url != ''}
			<li class="social-block__item">
				<a class="_blank social-block__link social-block__link_instagram" href="{$instagram_url|escape:html:'UTF-8'}">
					<span class="social-block__text">{l s='Instagram' mod='blocksocial'}</span>
				</a>
			</li>
        {/if}
	</ul>
	{*<h4>{l s='Follow us' mod='blocksocial'}</h4>*}
</section>