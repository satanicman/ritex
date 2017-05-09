<?php
class BlockcmsinfoOverride extends Blockcmsinfo
{
	public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path.'blockcmsinfo.css');
    }

	public function hookHome($params)
	{
		$this->context->controller->addCSS($this->_path.'style.css', 'all');
		if (!$this->isCached('blockcmsinfo.tpl', $this->getCacheId()))
		{
			$infos = $this->getInfos($this->context->language->id, $this->context->shop->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}

		return $this->display(__FILE__, 'blockcmsinfo.tpl', $this->getCacheId());
	}
}
