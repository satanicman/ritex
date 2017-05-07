<?php
class BlockCartOverride extends BlockCart
{
	public function hookRightColumn($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		// @todo this variable seems not used
		$this->smarty->assign(array(
			'order_page' => (strpos($_SERVER['PHP_SELF'], 'order') !== false),
			'blockcart_top' => (isset($params['blockcart_top']) && $params['blockcart_top']) ? true : false,
		));
		$this->assignContentVars($params);
		return $this->display(__FILE__, 'blockcart.tpl');
	}

	public function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}

	public function hookAjaxCall($params)
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		$this->assignContentVars($params);
		$res = Tools::jsonDecode($this->display(__FILE__, 'blockcart-json.tpl'), true);

		if (is_array($res) && ($id_product = Tools::getValue('id_product')) && Configuration::get('PS_BLOCK_CART_SHOW_CROSSSELLING'))
		{
			$this->smarty->assign('orderProducts', OrderDetail::getCrossSells($id_product, $this->context->language->id,
				Configuration::get('PS_BLOCK_CART_XSELL_LIMIT')));
			$res['crossSelling'] = $this->display(__FILE__, 'crossselling.tpl');
		}

		$res = Tools::jsonEncode($res);
		return $res;
	}

	public function hookActionCartListOverride($params)
	{
		if (!Configuration::get('PS_BLOCK_CART_AJAX'))
			return;

		$this->assignContentVars(array('cookie' => $this->context->cookie, 'cart' => $this->context->cart));
		$params['json'] = $this->display(__FILE__, 'blockcart-json.tpl');
	}

	public function hookHeader()
	{
		if (Configuration::get('PS_CATALOG_MODE'))
			return;

		$this->context->controller->addCSS(($this->_path).'blockcart.css', 'all');
		if ((int)(Configuration::get('PS_BLOCK_CART_AJAX')))
		{
			$this->context->controller->addJS(($this->_path).'ajax-cart.js');
			$this->context->controller->addJqueryPlugin(array('scrollTo', 'serialScroll', 'bxslider'));
		}
	}

	public function hookDisplayTopMenu($params)
    {
        return $this->hookTop($params);
    }

	public function hookTop($params)
	{
		$params['blockcart_top'] = true;
		return $this->hookRightColumn($params);
	}

	public function hookDisplayNav($params)
	{
		$params['blockcart_top'] = true;
		return $this->hookTop($params);
	}

	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'switch',
						'label' => $this->l('Ajax cart'),
						'name' => 'PS_BLOCK_CART_AJAX',
						'is_bool' => true,
						'desc' => $this->l('Activate Ajax mode for the cart (compatible with the default theme).'),
						'values' => array(
								array(
									'id' => 'active_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id' => 'active_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'switch',
						'label' => $this->l('Show cross-selling'),
						'name' => 'PS_BLOCK_CART_SHOW_CROSSSELLING',
						'is_bool' => true,
						'desc' => $this->l('Activate cross-selling display for the cart.'),
						'values' => array(
								array(
									'id' => 'active_on',
									'value' => 1,
									'label' => $this->l('Enabled')
								),
								array(
									'id' => 'active_off',
									'value' => 0,
									'label' => $this->l('Disabled')
								)
							),
						),
					array(
						'type' => 'text',
						'label' => $this->l('Products to display in cross-selling'),
						'name' => 'PS_BLOCK_CART_XSELL_LIMIT',
						'class' => 'fixed-width-xs',
						'desc' => $this->l('Define the number of products to be displayed in the cross-selling block.')
					),
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitBlockCart';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab
		.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'PS_BLOCK_CART_AJAX' => (bool)Tools::getValue('PS_BLOCK_CART_AJAX', Configuration::get('PS_BLOCK_CART_AJAX')),
			'PS_BLOCK_CART_SHOW_CROSSSELLING' => (bool)Tools::getValue('PS_BLOCK_CART_SHOW_CROSSSELLING', Configuration::get('PS_BLOCK_CART_SHOW_CROSSSELLING')),
			'PS_BLOCK_CART_XSELL_LIMIT' => (int)Tools::getValue('PS_BLOCK_CART_XSELL_LIMIT', Configuration::get('PS_BLOCK_CART_XSELL_LIMIT'))
		);
	}
}
