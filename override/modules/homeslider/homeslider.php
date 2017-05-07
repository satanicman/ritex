<?php

class HomeSliderOverride extends HomeSlider
{
	public function hookdisplayHeader($params)
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;
		$this->context->controller->addCSS($this->_path.'homeslider.css');
		$this->context->controller->addJS($this->_path.'js/homeslider.js');
		$this->context->controller->addJqueryPlugin(array('slick'));

		$config = $this->getConfigFieldsValues();
		$slider = array(
			'width' => $config['HOMESLIDER_WIDTH'],
			'speed' => $config['HOMESLIDER_SPEED'],
			'pause' => $config['HOMESLIDER_PAUSE'],
			'loop' => (bool)$config['HOMESLIDER_LOOP'],
		);

		$this->smarty->assign('homeslider', $slider);
		return $this->display(__FILE__, 'header.tpl');
	}

	public function hookdisplayTop($params)
	{
		return $this->hookdisplayTopColumn($params);
	}

	public function hookdisplayTopColumn($params)
	{
		if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index')
			return;

		if (!$this->_prepareHook())
			return false;

		return $this->display(__FILE__, 'homeslider.tpl', $this->getCacheId());
	}

	public function hookDisplayHome()
	{
		if (!$this->_prepareHook())
			return false;

		return $this->display(__FILE__, 'homeslider.tpl', $this->getCacheId());
	}
}
