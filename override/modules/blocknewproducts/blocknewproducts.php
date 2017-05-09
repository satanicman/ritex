<?php

class BlockNewProductsOverride extends BlockNewProducts
{
    public function hookHeader($params)
    {
        parent::hookHeader($params);
        $this->context->controller->addJqueryPlugin(array('slick'));
    }
	public function hookdisplayHomeTabContent($params)
	{
		if (!$this->isCached('blocknewproducts_home.tpl', $this->getCacheId('blocknewproducts-home')))
		{
			$this->smarty->assign(array(
				'new_products' => $this->getNewProducts(),
				'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
			));
		}

		if (BlockNewProducts::$cache_new_products === false)
			return false;

		return $this->display(__FILE__, 'blocknewproducts_home.tpl', $this->getCacheId('blocknewproducts-home'));
	}
}
