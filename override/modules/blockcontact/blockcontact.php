<?php

class BlockcontactOverride extends Blockcontact
{
    public function hookDisplayFooter($params)
    {
        return $this->hookDisplayNav($params);
    }

    public function hookDisplayNav($params)
	{
		$params['blockcontact_tpl'] = 'nav';
		return $this->hookDisplayRightColumn($params);
	}
}
