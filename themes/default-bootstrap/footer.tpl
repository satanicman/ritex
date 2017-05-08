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
{if !isset($content_only) || !$content_only}
					</div><!-- #center_column -->
					{if isset($right_column_size) && !empty($right_column_size)}
						<div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
					{/if}
					</div><!-- .row -->
				</div><!-- #columns -->
			</div><!-- .columns-container -->
			{if isset($HOOK_FOOTER)}
				<!-- Footer -->
				<footer class="footer">
					<div class="footer__top">
                        <div class="container">
                            <div class="row">
                                <div class="footer__logo col-sm-2">
                                    <a href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
                                        <img class="logo img-responsive" src="{$logo_url}" alt="{$shop_name|escape:'html':'UTF-8'}"{if isset($logo_image_width) && $logo_image_width} width="{$logo_image_width}"{/if}{if isset($logo_image_height) && $logo_image_height} height="{$logo_image_height}"{/if}/>
                                    </a>
                                </div>
                                {$HOOK_FOOTER}
                            </div>
                        </div>
					</div>
                    <div class="footer__bottom">
                        <div class="footer-bottom container">
                            <div class="row footer-bottom__row">
                                {capture name='displayFooterBottom'}{hook h='displayFooterBottom'}{/capture}
                                {if $smarty.capture.displayFooterBottom}
                                    {$smarty.capture.displayFooterBottom}
                                {/if}
                                <div class="footer-bottom__col col-sm-7 col-xs-12">
                                    <p class="copyright">{l s='Все права защищены © 2017'}</p>
                                </div>
                                <div class="footer-bottom__col footer-bottom__col_btn col-sm-2 col-xs-12">
                                    <a href="{$link->getPageLink('contact', true)|escape:'html':'UTF-8'}" title="{l s='Написать нам'}" class="pull-right btn btn_orange">{l s='Написать нам'}</a>
                                </div>
                            </div>
                        </div>
                    </div>
				</footer><!-- #footer -->
			{/if}
		</div><!-- #page -->
{/if}
{include file="$tpl_dir./global.tpl"}
	</body>
</html>