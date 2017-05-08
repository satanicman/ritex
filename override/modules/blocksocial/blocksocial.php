<?php

class blocksocialOverride extends blocksocial
{
	public function hookDisplayFooterBottom()
    {
        return parent::hookDisplayFooter();
    }
}
