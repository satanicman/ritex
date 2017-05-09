<?php

include_once(dirname(__FILE__) . '/BlockCMSModel.php');
class BlockCmsOverride extends BlockCms
{
	public function hookDisplayTopMenu()
    {
        if (!($block_activation = Configuration::get('FOOTER_BLOCK_ACTIVATION')))
            return;

        if (!$this->isCached('blockcms.tpl', $this->getCacheId(BlockCMSModelOverride::FOOTER)))
        {
            $display_poweredby = Configuration::get('FOOTER_POWEREDBY');
            $this->smarty->assign(
                array(
                    'block' => 2,
                    'contact_url' => 'contact',
                    'cmslinks' => BlockCMSModelOverride::getCMSTitlesFooter(),
                    'display_stores_footer' => Configuration::get('PS_STORES_DISPLAY_FOOTER'),
                    'display_poweredby' => ((int)$display_poweredby === 1 || $display_poweredby === false),
                    'footer_text' => Configuration::get('FOOTER_CMS_TEXT_'.(int)$this->context->language->id),
                    'show_price_drop' => Configuration::get('FOOTER_PRICE-DROP'),
                    'show_new_products' => Configuration::get('FOOTER_NEW-PRODUCTS'),
                    'show_best_sales' => Configuration::get('FOOTER_BEST-SALES'),
                    'show_contact' => Configuration::get('FOOTER_CONTACT'),
                    'show_sitemap' => Configuration::get('FOOTER_SITEMAP')
                )
            );
        }
        return $this->display(__FILE__, 'blockcms.tpl', $this->getCacheId(BlockCMSModelOverride::FOOTER));
    }

	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'blockcms.css', 'all');
	}

	public function hookLeftColumn()
	{
		return $this->displayBlockCMS(BlockCMSModelOverride::LEFT_COLUMN);
	}

	public function hookRightColumn()
	{
		return $this->displayBlockCMS(BlockCMSModelOverride::RIGHT_COLUMN);
	}

	public function hookFooter()
	{
		if (!($block_activation = Configuration::get('FOOTER_BLOCK_ACTIVATION')))
			return;

		if (!$this->isCached('blockcms.tpl', $this->getCacheId(BlockCMSModelOverride::FOOTER)))
		{
			$display_poweredby = Configuration::get('FOOTER_POWEREDBY');
			$this->smarty->assign(
				array(
					'block' => 0,
					'contact_url' => 'contact',
					'cmslinks' => BlockCMSModelOverride::getCMSTitlesFooter(),
					'display_stores_footer' => Configuration::get('PS_STORES_DISPLAY_FOOTER'),
					'display_poweredby' => ((int)$display_poweredby === 1 || $display_poweredby === false),
					'footer_text' => Configuration::get('FOOTER_CMS_TEXT_'.(int)$this->context->language->id),
					'show_price_drop' => Configuration::get('FOOTER_PRICE-DROP'),
					'show_new_products' => Configuration::get('FOOTER_NEW-PRODUCTS'),
					'show_best_sales' => Configuration::get('FOOTER_BEST-SALES'),
					'show_contact' => Configuration::get('FOOTER_CONTACT'),
					'show_sitemap' => Configuration::get('FOOTER_SITEMAP')
				)
			);
		}
		return $this->display(__FILE__, 'blockcms.tpl', $this->getCacheId(BlockCMSModelOverride::FOOTER));
	}
}
