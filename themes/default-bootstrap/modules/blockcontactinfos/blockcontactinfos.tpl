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

<!-- MODULE Block contact infos -->
<section id="block_contact_infos" class="col-xs-12 col-sm-3 contact-information footer__contact-information">
	<ul class="contact-information__list">
		{if $blockcontactinfos_address != ''}
			<li class="contact-information__item">
				{$blockcontactinfos_address|escape:'html':'UTF-8'}
			</li>
		{/if}
		{if $blockcontactinfos_phone != ''}
			<li class="contact-information__item">
				<i class="icon-phone"></i>{l s='Call us now:' mod='blockcontactinfos'}
				<span>{$blockcontactinfos_phone|escape:'html':'UTF-8'}</span>
			</li>
		{/if}
		{if $blockcontactinfos_email != ''}
			<li class="contact-information__item contact-information__item_blue">
				{l s='Email:' mod='blockcontactinfos'} {mailto address=$blockcontactinfos_email|escape:'html':'UTF-8' encode="hex"}
			</li>
		{/if}
	</ul>
</section>
<!-- /MODULE Block contact infos -->
